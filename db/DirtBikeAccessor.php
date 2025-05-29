<?PHP
require_once dirname(__DIR__, 1) . '/entity/DirtBike.php';

class DirtBikeAccessor {
    private $getAllStatementString = "select * from dirtbikes";
    private $getByIDStatementString = "select * from dirtbikes where db_id = :dbID";
    private $deleteStatementString = "delete from dirtbikes where db_id = :dbID";
    private $addStatementString = "INSERT INTO dirtbikes (make, model, man_year, size_cc, price) 
                                    VALUES (:make, :model, :year, :cc, :price)";
    private $updateStatementString = "UPDATE dirtbikes 
                                    SET make = :make, model = :model, man_year = :year, size_cc = :cc, price = :price
                                    WHERE db_id = :id;";

    private $getAllStatement = null;
    private $getByIDStatement = null;
    private $deleteStatement = null; 
    private $addStatement = null;
    private $updateStatement = null;
    
    //
    //Constructor creates instance of accessor with database connection sent in
    public function __construct($conn)
    {
        if (is_null($conn)) {
            throw new Exception("no connection");
        }

        $this->getAllStatement = $conn->prepare($this->getAllStatementString);
        if (is_null($this->getAllStatement)) {
            throw new Exception("bad statement: '" . $this->getAllStatementString . "'");
        }

        $this->getByIDStatement = $conn->prepare($this->getByIDStatementString);
        if (is_null($this->getByIDStatement)) {
            throw new Exception("bad statement: '" . $this->getByIDStatementString . "'");
        }

        $this->deleteStatement = $conn->prepare($this->deleteStatementString);
        if (is_null($this->deleteStatement)) {
            throw new Exception("bad statement: '" . $this->deleteStatementString . "'");
        }

        $this->addStatement = $conn->prepare($this->addStatementString);
        if (is_null($this->addStatement)) {
            throw new Exception("bad statement: '" . $this->addStatementString . "'");
        }

        $this->updateStatement = $conn->prepare($this->updateStatementString);
        if (is_null($this->updateStatement)) {
            throw new Exception("bad statement: '" . $this->updateStatementString . "'");
        }
    }

    //
    //Returns array of dirtbike objects
    public function getAllItems()
    {
        $results = [];

        try {
            $this->getAllStatement->execute();
            $dbresults = $this->getAllStatement->fetchAll(PDO::FETCH_ASSOC);

            foreach ($dbresults as $r) {
                $dbID = $r['db_id'];
                $make = $r['make'];
                $model = $r['model'];
                $year = $r['man_year'];
                $size = $r['size_cc'];
                $price = $r['price'];
                $obj = new DirtBike($dbID, $make, $model, $year, $size, $price);
                array_push($results, $obj);
            }
        } catch (PDOException $e) {
            $results = [];
        } finally {
            if (!is_null($this->getAllStatement)) {
                $this->getAllStatement->closeCursor();
            }
        }

        return $results;
    }

    //
    //Gets the dirtbike with the specified id sent in, returns db object or null
    public function getItemByID($id)
    {
        $result = null;

        try {
            $this->getByIDStatement->bindParam(":dbID", $id);
            $this->getByIDStatement->execute();
            $dbresults = $this->getByIDStatement->fetch(PDO::FETCH_ASSOC);

            if ($dbresults) {
                $dbID = $dbresults['db_id'];
                $make = $dbresults['make'];
                $model = $dbresults['model'];
                $year = $dbresults['man_year'];
                $size = $dbresults['size_cc'];
                $price = $dbresults['price'];
                $result = new DirtBike($dbID, $make, $model, $year, $size, $price);
            }
        } catch (PDOException $e) {
            $result = null;
        } finally {
            if (!is_null($this->getByIDStatement)) {
                $this->getByIDStatement->closeCursor();
            }
        }

        return $result;
    }

    //
    //returns true if item exists, null if not
    public function itemExists($item)
    {
        return $this->getItemByID($item->getDBID()) !== null;
    }

    //
    //Deletes item of object sent in, returns true if deleted false if not
    public function deleteItem($item)
    {
        if (!$this->itemExists($item)) {
            return false;
        }

        $success = false;
        $itemID = $item->getDBID(); //$item is of type DirtBike, Call the get ID method from that class

        try {
            $this->deleteStatement->bindParam(":dbID", $itemID); //Bind param to statement
            $success = $this->deleteStatement->execute(); //Returns true if ran
            $success = $success && $this->deleteStatement->rowCount() === 1; //Returns true if ran and return count is 1
        } catch (PDOException $e) {
            $success = false;
        } finally {
            if (!is_null($this->deleteStatement)) {
                $this->deleteStatement->closeCursor();
            }
        }
        return $success;
    }

    //
    //Adds item of object sent in, returns true if added false if not
    public function addItem($item) {
        $success = false;
        try {
            $make = $item->getMake();
            $model = $item->getModel();
            $year = $item->getYear();
            $cc = $item->getSize();
            $price = $item->getPrice();

            $this->addStatement->bindParam(":make", $make); // Bind param to statement
            $this->addStatement->bindParam(":model", $model); // Bind param to statement
            $this->addStatement->bindParam(":year", $year); // Bind param to statement
            $this->addStatement->bindParam(":cc", $cc); // Bind param to statement
            $this->addStatement->bindParam(":price", $price); // Bind param to statement
            $success = $this->addStatement->execute();
            $success = $success && $this->addStatement->rowCount() == 1;

        } catch (PDOException $e) {
            $success = false;
        } finally {
            if (!is_null($this->addStatement)) {
                $this->addStatement->closeCursor();
            }
        }

        return $success;
    }

    //
    //updates item of object sent in, returns true if updated false if not
    public function updateItem($item) {
        //If item does not exists return false
        if (!$this->itemExists($item)) {
            return false;
        }

        $success = false;

        try {
        $itemID = $item->getDBID();
        $make = $item->getMake();
        $model = $item->getModel();
        $year = $item->getYear();
        $cc = $item->getSize();
        $price = $item->getPrice();

        $this->updateStatement->bindParam(":id", $itemID);
        $this->updateStatement->bindParam(":make", $make);
        $this->updateStatement->bindParam(":model", $model);
        $this->updateStatement->bindParam(":year", $year);
        $this->updateStatement->bindParam(":cc", $cc);
        $this->updateStatement->bindParam(":price", $price);

        $success = $this->updateStatement->execute();
        $success = $success && $this->updateStatement->rowCount() >= 0;

        } catch (PDOException $e) {
            $success = false;
        } finally {
            if (!is_null($this->updateStatement)) {
                $this->updateStatement->closeCursor();
            }
        }

        return $success;
    }
} //End class
?>