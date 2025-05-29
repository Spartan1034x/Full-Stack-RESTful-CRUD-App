<?PHP

class DirtBike implements JsonSerializable
{
    //Fields
    private $dbID;
    private $make;
    private $model;
    private $year;
    private $size;
    private $price;

    //Accessors
    public function getDBID() {
        return $this->dbID;
    }
    public function getMake() {
        return $this->make;
    }
    public function getModel() {
        return $this->model;
    }
    public function getYear() {
        return $this->year;
    }
    public function getSize() {
        return $this->size;
    }
    public function getPrice() {
        return $this->price;
    }

    //Constructor
    public function __construct($dbID, $make, $model, $year, $size, $price) {
        //Validate data sent throw exception if not
        if ($dbID <= 0) {
            throw new InvalidArgumentException("Invalid dbID, must be greater than 0");
        }
        if ($year <= 1950) {
            throw new InvalidArgumentException("Invalid year, must be greater than 1950");
        }
        if ($size < 49) {
            throw new InvalidArgumentException("Invalid size, must be 49 or greater");
        }
        if ($price < 0) {
            throw new InvalidArgumentException("Invalid price, must be greater than 0");
        }
        $this->dbID = $dbID;
        $this->make = $make;
        $this->model = $model;
        $this->year = $year;
        $this->size = $size;
        $this->price = $price;
    }

    //Implementation
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }

}//End DB class
?>