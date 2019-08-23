<?php
/**
 * Created by PhpStorm.
 * User: RaquelMarcos
 * Date: 02/01/2019
 * Time: 18:26
 */

require_once ("../core/PDOConnection.php");

class EventMapper
{
    /**
     * Reference to the PDO connection
     * @var PDO
     */
    private $db;

    public function __construct()
    {
        $this->db = PDOConnection::getInstance();
    }

    /**
     * Retrieves all events for that restaurant
     * @param $restaurant
     * @return array $events array of events
     */
    public function findAll($restaurant){
        $stmt = $this->db->prepare("SELECT id_event, type, name, moment, date, guests, children, sweet_table, 
                                    observations, restaurant, phone, price FROM event WHERE restaurant =? ORDER BY date");
        $stmt->execute(array($restaurant));

        $events_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $events = array();

        foreach ($events_db as $event){
            array_push($events, new Event($event["id_event"], $event["type"], $event["name"], $event["moment"], $event["date"],
                $event["guests"], $event["children"], $event["sweet_table"], $event["observations"],
                $event["restaurant"], $event["phone"], $event["price"]));
        }

        return $events;
    }

    /**
     * Retrieves all events with name like $name
     * @param $name The name
     * @return array $events array of events
     */
    public function findByName($name){
        $stmt = $this->db->prepare("SELECT * FROM event WHERE name LIKE %?%");
        $stmt->execute(array($name));

        $events_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $events = array();

        foreach($events_db as $event){
            array_push($events, new Event($event["id_event"], $event["type"], $event["name"],$event["moment"], $event["date"],
                $event["guests"], $event["children"], $event["sweet_table"], $event["observations"],
                $event["restaurant"], $event["phone"],$event["price"] ));
        }

        return $events;
    }

    /**
     * Retrieves all events with date like $date
     * @param $date The date
     * @return array $events array of events
     */
    public function findByDate($date){
        $stmt = $this->db->prepare("SELECT * FROM event WHERE date =?");
        $stmt->execute(array($date));

        $events_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $events = array();

        foreach($events_db as $event){
            array_push($events, new Event($event["id_event"], $event["type"], $event["name"],$event["moment"], $event["date"],
                $event["guests"], $event["children"], $event["sweet_table"], $event["observations"],
                $event["restaurant"], $event["phone"],$event["price"] ));
        }

        return $events;
    }

    /**
     * Retrieves all events with type like $type
     * @param $type The type
     * @return array $events array of events
     */
    public function findByType($type){
        $stmt = $this->db->prepare("SELECT * FROM event WHERE type =?");
        $stmt->execute(array($type));

        $events_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $events = array();

        foreach($events_db as $event){
            array_push($events, new Event($event["id_event"], $event["type"], $event["name"],$event["moment"], $event["date"],
                $event["guests"], $event["children"], $event["sweet_table"], $event["observations"],
                $event["restaurant"], $event["phone"],$event["price"] ));
        }

        return $events;
    }


    public function findById($idEvent){

       $stmt = $this->db->prepare("SELECT e.id_event, e.type, e.name, e.moment, e.date, e.guests, e.children, e.sweet_table, e.observations, e.phone, e.restaurant, e.price FROM event e JOIN user u ON e.restaurant = u.id_user WHERE e.id_event = :i");

        $stmt->bindParam(":i", $idEvent);

        $stmt->execute();
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if($event != null) {
            return new Event(
                $event["id_event"],
                $event["type"],
                $event["name"],
                $event["moment"],
                $event["date"],
                $event["guests"],
                $event["children"],
                $event["sweet_table"],
                $event["observations"],
                $event["restaurant"],
                $event["phone"],
                $event["price"]);
        } else {
            return NULL;
        }
    }


    /**
     * Saves an Event into the database
     * @param Event $event
     */
    public function save(Event $event){

        $stmt = $this->db->prepare("INSERT INTO event(id_event, type, name, moment, date, guests, children,
                                      sweet_table, observations, restaurant, phone, price) VALUES (0,?,?,?,?,?,?,?,?,?,?,?)");

        $stmt->execute(array($event->getType(), $event->getName(), $event->getMoment(), $event->getDate(),
            $event->getGuests(), $event->getChildren(), $event->getSweetTable(), $event->getObservations(),
            $event->getRestaurant(), $event->getPhone(), $event->getPrice()));

        $getId = $this->db->prepare("select last_insert_id()");
        $getId->execute();

        $id = $getId->fetch(PDO::FETCH_ASSOC);

        return $id["last_insert_id()"];

    }

    /**
     * Updates an Event in the database
     * @param Event $event
     */
    public function update(Event $event){
        $stmt = $this->db->prepare("UPDATE event set type=?, name=?, moment=?, date=?, guests=?, children=?, 
                                        sweet_table=?, observations=?, restaurant=?, phone=?, price=? WHERE id_event=?");
        $stmt->execute(array($event->getType(), $event->getName(), $event->getMoment(), $event->getDate(), $event->getGuests(), $event->getChildren(),
            $event->getSweetTable(), $event->getObservations(), $event->getRestaurant(), $event->getPhone(),$event->getPrice(),$event->getIdEvent()));

    }

    /**
     * Deletes an Event into the database
     * @param Event $event
     */
    public function delete(Event $event){
        $stmt = $this->db->prepare("DELETE FROM event WHERE id_event = ?");
        $stmt->execute(array($event->getIdEvent()));
    }

    /**
     * Retrieves all staff for that $event
     * @param Event $event
     */
    public function getAllstaffEvent($event, $restaurant){
        $stmt = $this->db->prepare("SELECT id_staff, name, surnames FROM staff
                                        WHERE restaurant = ? AND id_staff IN (SELECT staff FROM staff_event WHERE event =?)");
        $stmt->execute(array($restaurant,$event));
        $staffs_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $staffs_db;
    }

    /**
     * Sets staff into $event
     * @param Event $event
     * @param $id_staff
     */
    public function setStaffEvent($staffEvent){

        $rowsSQL = array();

        $toBind = array();

        $columnNames = array_keys($staffEvent[0]);

        foreach($staffEvent as $arrayIndex => $row){
            $params = array();
            foreach($row as $columnName => $columnValue){
                $param = ":" . $columnName . $arrayIndex;
                $params[] = $param;
                $toBind[$param] = $columnValue;
            }
            $rowsSQL[] = "(" . implode(", ", $params) . ")";
        }

        $sql = "INSERT INTO `staff_event` (" . implode(", ", $columnNames) . ") VALUES " . implode(", ", $rowsSQL);

        $stmt = $this->db->prepare($sql);
        //Bind our values.
        foreach($toBind as $param => $val){
            $stmt->bindValue($param, $val);
        }

        //Execute our statement (i.e. insert the data).
        $stmt->execute();
    }

    /**
     * @param $event
     * @return array
     */
    public function AllFoodEvent($event){
        $stmt = $this->db->prepare("SELECT id_food, title, description, image, food.restaurant, food.price 
                                        FROM food_event, food WHERE food_event.food = food.id_food AND food_event.event =?");
        $stmt->execute(array($event));
        $foodsEvent_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $events = array();
        //var_dump($foodsEvent_db);

        foreach ($foodsEvent_db as $foodEvent) {
            array_push($events, array("food" => new Food($foodEvent["id_food"], $foodEvent["title"], $foodEvent["description"],
                $foodEvent["image"], $foodEvent["restaurant"], $foodEvent["price"])));

        }

        return $foodsEvent_db;
    }

    /**
     * @param $id_food
     * @param $id_event
     */
    public function setFoodEvent($arrayFoods){
        $rowsSQL = array();

        $toBind = array();

        $columnNames = array_keys($arrayFoods[0]);

        foreach($arrayFoods as $arrayIndex => $row){
            $params = array();
            foreach($row as $columnName => $columnValue){
                $param = ":" . $columnName . $arrayIndex;
                $params[] = $param;
                $toBind[$param] = $columnValue;
            }
            $rowsSQL[] = "(" . implode(", ", $params) . ")";
        }

        $sql = "INSERT INTO `food_event` (" . implode(", ", $columnNames) . ") VALUES " . implode(", ", $rowsSQL);

        $stmt = $this->db->prepare($sql);
        //Bind our values.
        foreach($toBind as $param => $val){
            $stmt->bindValue($param, $val);
        }
        //print_r($sql);
        //print_r($toBind);
        //Execute our statement (i.e. insert the data).
        $stmt->execute();
    }

    /**
     * Checks if the event exists for the restaurant logged in
     * @param $event
     */
    public function eventExists(Event $event){
        //echo $event->getType();
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM event WHERE type = ? AND name = ? AND date = ? AND observations = ? AND guests = ?");
        $stmt->execute(array($event->getType(), $event->getName(), $event->getDate(), $event->getObservations(), $event->getGuests()));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if($data['count'] == 0){
            $exists = false;
        }else{
            $exists = true;
        }
        return $exists;
    }


    /***
     * Gets from the DB the maximum id of saved event
     */
    public function getMaximumId($restaurant){
        $stmt = $this->db->prepare("SELECT MAX(id_event) as max_id FROM event WHERE restaurant = ?");
        $stmt->execute(array($restaurant));
        $max = $stmt->fetch(PDO::FETCH_ASSOC);
        $aux = $max['max_id'];
        return $aux;
    }

    /**
     * Checks if the event already has the given foods
     * @param $foodsEvent
     * @return bool
     */
    public function existFoodsInEvent($foodsEvent){
        $rowsSQL = array();

        $toBind = array();

        $idsFood = array();
        foreach ($foodsEvent as $foodEvent){
            array_push($idsFood, $foodEvent["food"]);
        }

        $params = array();
        foreach($foodsEvent as $row => $value){

            $param = ":".$row;

            $params[] = $param;

            $toBind[$param] = $value["food"];
        }
        $rowsSQL[] = implode(" OR ", $params);

        $sql = "SELECT count(*) as count from `food_event` WHERE event = " . $foodsEvent[0]["event"] . " AND food = (" . implode(" OR ", $rowsSQL).")";

        //print_r($sql);
        $stmt = $this->db->prepare($sql);
        //Bind our values.
        foreach($toBind as $param => $val){
            $stmt->bindValue($param, $val);
        }
        //print_r($sql);
        //print_r($toBind);
        //Execute our statement (i.e. insert the data).
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        //var_dump($data);
        if($data['count'] == 0){
            $exists = false;
        }else{
            $exists = true;
        }

        return $exists;
    }

    /**
     * Checks if the event already has the given staff
     * @param $staffEvent
     * @return bool
     */
    public function existStaffInEvent($staffEvent){
        //var_dump($staffEvent);
        $rowsSQL = array();

        $toBind = array();

        $idsStaff = array();
        foreach ($staffEvent as $personEvent){
            array_push($idsStaff, $personEvent["staff"]);
        }

        $params = array();
        foreach($staffEvent as $row => $value){

            $param = ":".$row;

            $params[] = $param;

            $toBind[$param] = $value["staff"];
        }
        $rowsSQL[] = implode(" OR ", $params);

        $sql = "SELECT count(*) as count from `staff_event` WHERE event = " . $staffEvent[0]["event"] . " AND staff = (" . implode(" OR ", $rowsSQL).")";

        //print_r($sql);
        $stmt = $this->db->prepare($sql);
        //Bind our values.
        foreach($toBind as $param => $val){
            $stmt->bindValue($param, $val);
        }
        //print_r($sql);
        //print_r($toBind);
        //Execute our statement (i.e. insert the data).
        echo $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        //var_dump($data);
        if($data['count'] == 0){
            $exists = false;
        }else{
            $exists = true;
        }

        return $exists;
    }

    /**
     * Deletes foods from the given event
     * @param $event
     * @param $data
     */
    public function deleteFoodsFromEvent($event, $data){
        $rowsSQL = array();

        $toBind = array();

        foreach($data as $arrayIndex => $row){
            $params = array();
            foreach($row as $columnName => $columnValue){
                $param = ":" . $columnName . $arrayIndex;
                $params[] = $param;
                $toBind[$param] = $columnValue;
            }
            $rowsSQL[] = "(" . implode(", ", $params) . ")";
        }

        $sql = "DELETE FROM `food_event` WHERE `event` =". $event ." AND food IN (" . implode(", ", $rowsSQL) . ")";

        $stmt = $this->db->prepare($sql);
        //Bind our values.
        foreach($toBind as $param => $val){
            $stmt->bindValue($param, $val);
        }
        //Execute our statement (i.e. insert the data).
        $stmt->execute();
    }

    /**
     * Updates the food from the given event
     * @param $toUpdate
     */
    public function updateFoodsFromEvent($toUpdate){

       //echo "\ntoUpdate\n";
       //print_r($toUpdate);


        // $rowsToInsert es $allergens_food
        $rowsSQL = array();

        //Will contain the values that we need to bind.
        $toBind = array();

        //Get a list of column names to use in the SQL statement.
        $columnNames = array_keys($toUpdate[0]);
        //var_dump($columnNames);

        //Loop through our $data array.
        foreach($toUpdate as $arrayIndex => $row){
            $params = array();
            foreach($row as $columnName => $columnValue){
                $param = ":" . $columnName . $arrayIndex;
                $params[] = $param;
                $toBind[$param] = $columnValue;
            }
            $rowsSQL[] = "(" . implode(", ", $params) . ")";
        }
        //print_r($rowsSQL);
        //
        //Construct our SQL statement
        $sql = "INSERT INTO `food_event` (food,event) VALUES " .
            implode(", ", $rowsSQL) .
            " ON DUPLICATE KEY UPDATE food=VALUES(food), event=VALUES(event)";

        //echo $sql;
        //Prepare our PDO statement.
        $stmt = $this->db->prepare($sql);
        //Bind our values.
        foreach($toBind as $param => $val){
            $stmt->bindValue($param, $val);
        }

        //Execute our statement (i.e. insert the data).
        $stmt->execute();


    }

    /**
     * Deletes staff from the given event
     * @param $event
     * @param $data
     */
    public function deleteStaffFromEvent($event, $data){
        echo '\ndata en eventMapper\n';
        var_dump($data);
        $rowsSQL = array();

        $toBind = array();

        foreach($data as $arrayIndex => $row){
            $params = array();
            foreach($row as $columnName => $columnValue){
                $param = ":" . $columnName . $arrayIndex;
                $params[] = $param;
                $toBind[$param] = $columnValue;
            }
            $rowsSQL[] = "(" . implode(", ", $params) . ")";
        }

        $sql = "DELETE FROM `staff_event` WHERE `event` =". $event ." AND staff IN (" . implode(", ", $rowsSQL) . ")";

        $stmt = $this->db->prepare($sql);
        //Bind our values.
        foreach($toBind as $param => $val){
            $stmt->bindValue($param, $val);
        }
        //Execute our statement (i.e. insert the data).
        $stmt->execute();
    }
}