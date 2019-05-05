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
        $stmt = $this->db->prepare("SELECT id_event, type, name, date, guests, children, sweet_table, 
                                observations, restaurant, phone, price FROM event WHERE restaurant =?");
        $stmt->execute(array($restaurant));

        $events_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $events = array();

        foreach ($events_db as $event){
            array_push($events, new Event($event["id_event"], $event["type"], $event["name"], $event["date"],
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
            array_push($events, new Event($event["id_event"], $event["type"], $event["name"], $event["date"],
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
            array_push($events, new Event($event["id_event"], $event["type"], $event["name"], $event["date"],
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
            array_push($events, new Event($event["id_event"], $event["type"], $event["name"], $event["date"],
                $event["guests"], $event["children"], $event["sweet_table"], $event["observations"],
                $event["restaurant"], $event["phone"],$event["price"] ));
        }

        return $events;
    }


    public function findById($idEvent){
        $stmt = $this->db->prepare("SELECT event.id_event, event.type, event.name, event.date, event.guests, event.children, event.sweet_table, event.observations, event.phone, event.restaurant, event.price
                                    FROM event, user WHERE event.id_event =? AND event.restaurant = user.id_user");
        $stmt->execute(array($idEvent));
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if($event != null) {
            return new Event(
                $event["id_event"],
                $event["type"],
                $event["name"],
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

        $stmt = $this->db->prepare("INSERT INTO event(id_event, type, name, date, guests, children,
                                  sweet_table, observations, restaurant, phone, price) VALUES (0,?,?,?,?,?,?,?,?,?,?)");

        $stmt->execute(array($event->getType(), $event->getName(), $event->getDate(),
                        $event->getGuests(), $event->getChildren(), $event->getSweetTable(), $event->getObservations(),
                        $event->getRestaurant(), $event->getPhone(), $event->getPrice()));


    }

    /**
     * Updates an Event in the database
     * @param Event $event
     */
    public function update(Event $event){
        $stmt = $this->db->prepare("UPDATE event set type=?, name=?, date=?, guests=?, children=?, 
                                    sweet_table=?, observations=?, restaurant=?, phone=?, price=? WHERE id_event=?");
        $stmt->execute(array($event->getType(), $event->getName(), $event->getDate(), $event->getGuests(), $event->getChildren(),
            $event->getSweetTable(), $event->getObservations(), $event->getRestaurant(), $event->getPhone(),$event->getPrice(),$event->getRestaurant()));

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
     * Retrieves all stuff for that $event
     * @param Event $event
     */
    public function getAllStuffEvent(Event $event){
        $stmt = $this->db->prepare("SELECT name, surnames FROM stuff
                                    WHERE id_stuff IN (SELECT stuff FROM stuff_event WHERE event =?)");
        $stmt->execute(array($event->getIdEvent()));
        $stuffs_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stuffs = array();

        foreach ($stuffs_db as $stuff) {
            array_push($stuffs, new Event($stuff["id_event"], $stuff["type"], $stuff["name"],$stuff["date"],
                $stuff["guests"], $stuff["children"], $stuff["sweet_table"], $stuff["observations"], $stuff["restaurant"],
                $stuff["phone"], $stuff["price"]));

        }

        return $stuffs;
    }

    /**
     * Sets stuff into $event
     * @param Event $event
     * @param $id_stuff
     */
    public function setStuffEvent(Event $event, $id_stuff){
        $stmt = $this->db->prepare("INSERT INTO stuff_event(stuff, event) VALUES (?,?)");
        $stmt->execute(array($id_stuff, $event->getIdEvent() ));
    }

    /**
     * @param $event
     * @return array
     */
    public function AllFoodEvent($event){
        $stmt = $this->db->prepare("SELECT id_food, title, description, image, food.restaurant, food.price, clamp 
                                    FROM food_event, food WHERE food_event.food = food.id_food AND food_event.event =?");
        $stmt->execute(array($event));
        $foodsEvent_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $events = array();

        foreach ($foodsEvent_db as $foodEvent) {
            array_push($events, array("food" => new Food($foodEvent["id_food"], $foodEvent["title"], $foodEvent["description"],
                $foodEvent["image"], $foodEvent["restaurant"], $foodEvent["price"]), "clamp" =>$foodEvent["clamp"]));

        }

        return $events;
    }

    /**
     * @param $id_food
     * @param $id_event
     * @param $clamp
     */
    public function setFoodEvent($id_food, $id_event, $clamp){
        $stmt = $this->db->prepare("INSERT INTO food_event (food,event,clamp) values (?,?,?)");
        $stmt->execute(array($id_food,$id_event, $clamp));
    }

    /**
     * Check if the event exists for the restaurant logged in
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
}