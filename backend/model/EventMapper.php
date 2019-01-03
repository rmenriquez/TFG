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
        $stmt = $this->db->prepare("SELECT id_event, type, name, date, guests, children, own_sweet_table, 
                                observations, restaurant, phone, price FROM event WHERE restaurant =?");
        $stmt->execute(array($restaurant));

        $events_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $events = array();

        foreach ($events_db as $event){
            array_push($events, new Event($event["id_event"], $event["type"], $event["name"], $event["date"],
                    $event["guests"], $event["children"], $event["own_sweet_table"], $event["observations"],
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
                        $event["guests"], $event["children"], $event["own_sweet_table"], $event["observations"],
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
                $event["guests"], $event["children"], $event["own_sweet_table"], $event["observations"],
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
                $event["guests"], $event["children"], $event["own_sweet_table"], $event["observations"],
                $event["restaurant"], $event["phone"],$event["price"] ));
        }

        return $events;
    }

    /**
     * Saves an Event into the database
     * @param Event $event
     */
    public function save(Event $event){
        $stmt = $this->db->prepare("INSERT INTO event(id_event, type, name, date, guests, children, 
                                  own_sweet_table, observations, restaurant, phone, price) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute(array($event->getIdEvent(), $event->getType(), $event->getName(), $event->getDate(),
                        $event->getGests(), $event->getChildren(), $event->getOwnSweetTable(), $event->getObservations(),
                        $event->getRestaurant(), $event->getPhone(), $event->getPrice()));
    }

    /**
     * Updates an Event in the database
     * @param Event $event
     */
    public function update(Event $event){
        $stmt = $this->db->prepare("UPDATE event set type=?, name=?, date=?, guests=?, children=?, 
                                    own_sweet_table=?, observations=?, restaurant=?, phone=?, price=? WHERE id_event=?");
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
}