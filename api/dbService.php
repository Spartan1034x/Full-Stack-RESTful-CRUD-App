<?PHP
require_once dirname(__DIR__, 1) . '/db/ConnectionManager.php';
require_once dirname(__DIR__, 1) . '/db/DirtBikeAccessor.php';
require_once dirname(__DIR__, 1) . '/entity/DirtBike.php';
require_once dirname(__DIR__, 1) . '/utils/DatabaseConstants.php';
require_once dirname(__DIR__, 1) . '/utils/ChromePhp.php';

$method = $_SERVER['REQUEST_METHOD'];

try {
    $cm = new ConnectionManager(DatabaseConstants::$MYSQL_CONNECTION_STRING, DatabaseConstants::$MYSQL_USERNAME, DatabaseConstants::$MYSQL_PASSWORD);
    $conn = $cm->getConnection();
    $dba = new DirtBikeAccessor($conn);

    if ($method === "GET") {
        doGet($dba);
    } else if ($method === "DELETE") {
        doDelete($dba);
    } else if ($method === "POST") {
        doPost($dba);
    } else if ($method === "PUT") {
        doPut($dba);
    } else {
        sendResponse(405, null, "THIS METHOD IS NOT ALLOWED");
    }
} catch (Exception $e) {
    sendResponse(500, null, "ERROR " . $e->getMessage());
    //sendResponse(500, null, "ERROR " . "Could not connect to database!");
} finally {
    if (!is_null($cm)) {
        $cm->closeConnection();
    }
}

// Received HTTP Req from front end
// Calls Accessor to query db to get all dbs
// Returns dbs in json format
function doGet($dba)
{
    // individual
    if (isset($_GET['db_id'])) {
        sendResponse(405, null, "individual GETs not allowed");
    }
    // collection
    else {
        try {
            $items = $dba->getAllItems();
            sendResponse(200, $items, null);
        } catch (Exception $e) {
            sendResponse(500, null, "could not retrieve data");
        }
    }
}

// CRUD Delete
function doDelete($dba)
{
     // individual
    if (isset($_GET['db_id'])) {
        try {
            $id = intval($_GET['db_id']);
            $obj = new DirtBike($id, "Renegade", "Honda", 2006, 800, 9000.00);
            $success = $dba->deleteItem($obj);
            if ($success) {
                sendResponse(200, 1, null);
            } else {
                sendResponse(404, null, "item does not exist");
            }
        } catch (Exception $e) {
            sendResponse(500, null, "could not proceed - server error");
        }
    }
    // collection
    else {
        sendResponse(405, null, "bulk DELETEs are not allowed");
    }
}

// CRUD Update
function doPut($dba)
{
    // individual
    if (isset($_GET['db_id'])) {
        try {
            // get all the data sent from the client
            $payload = file_get_contents('php://input');
            $data = json_decode($payload, true);
            $id = $data["id"];
            $make = $data["make"];
            $model = $data["model"];
            $year = $data["year"];
            $size = $data["size"];
            $price = $data["price"];
            // create an object - catch constructor exception
            try {
                $db = new DirtBike($id, $make, $model, $year, $size, $price);
                //    attempt to insert the item
                //    if true is returned by the dba, send 201
                //    if false is reutrn by dba, send 409
                $success = $dba->updateItem($db);
                if ($success) {
                    sendResponse(201, 1, null);
                }
                else 
                    sendResponse(409, null, "Could not insert into db");
            }
            // if constructor throws an exception, return 400
            catch (InvalidArgumentException $e) {
                sendResponse(400, null, $e->getMessage());
            }
            // send 500 if server error
        } catch (Exception $e) {
            sendResponse(500, null, "could not proceed - server error");
        }
    }
    // collection
    else {
        sendResponse(405, null, "bulk INSERTs are not allowed");
    }
}

// CRUD Create
function doPost($dba) {
    // individual
    if (isset($_GET['db_id'])) {
        try {
            // get all the data sent from the client
            $payload = file_get_contents('php://input');
            $data = json_decode($payload, true);
            $id = $data["id"];
            $make = $data["make"];
            $model = $data["model"];
            $year = $data["year"];
            $size = $data["size"];
            $price = $data["price"];
            // create an object - catch constructor exception
            try {
                $db = new DirtBike($id, $make, $model, $year, $size, $price);
                //    attempt to insert the item
                //    if true is returned by the dba, send 201
                //    if false is reutrn by dba, send 409
                $success = $dba->addItem($db);
                if ($success) {
                    sendResponse(201, 1, null);
                }
                else 
                    sendResponse(409, null, "Item already exists, cannot insert");
            }
            // if constructor throws an exception, return 400
            catch (InvalidArgumentException $e) {
                sendResponse(400, null, $e->getMessage());
            }
            // send 500 if server error
        } catch (Exception $e) {
            sendResponse(500, null, "could not proceed - server error");
        }
    }
    // collection
    else {
        sendResponse(405, null, "bulk CREATEs are not allowed");
    }

}


function sendResponse($statusCode, $data, $error)
{
    header("Content-Type: application/json");
    http_response_code($statusCode);
    $resp = ['data' => $data, 'error' => $error];
    echo json_encode($resp, JSON_NUMERIC_CHECK);
}
?>