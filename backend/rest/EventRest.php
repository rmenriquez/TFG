<?php
/**
 * Created by PhpStorm.
 * User: RaquelMarcos
 * Date: 16/01/2019
 * Time: 12:50
 */

require_once (__DIR__."/../model/User.php");
require_once (__DIR__."/../model/UserMapper.php");

require_once (__DIR__."/../model/Event.php");
require_once (__DIR__."/../model/EventMapper.php");

require_once (__DIR__."/../model/Food.php");
require_once (__DIR__."/../model/FoodMapper.php");

require_once (__DIR__."/../model/Staff.php");
require_once (__DIR__."/../model/StaffMapper.php");

class EventRest extends BaseRest
{
    private $eventMapper;

    public function __construct()
    {
        parent::__construct();

        $this->eventMapper = new EventMapper();
    }

    public function getEvents(){
        $currentUser = parent::authenticateUser();

        $events = $this->eventMapper->findAll($currentUser->getIdUser());
        $events_array = array();
        foreach ($events as $event) {
            array_push($events_array, array(
                "id_event" => $event->getIdEvent(),
                "type" => $event->getType(),
                "name" => $event->getName(),
                "date" => $event->getDate(),
                "guests" => $event->getGuests(),
                "children" => $event->getChildren(),
                "sweet_table" => $event->getSweetTable(),
                "observations" => $event->getObservations(),
                "restaurant" => $currentUser->getIdUser(),
                "phone" => $event->getPhone(),
                "price" => $event->getPrice()
            ));
        }

            header($_SERVER['SERVER_PROTOCOL'].'200 Ok');
            header('Content-Type: application/json');
            echo(json_encode($events_array));

    }

    public function createEvent($data){
        $currentUser = parent::authenticateUser();
        $event = new Event();

        echo("var_dump de data en createEvent \n");
        echo($data);

        if(isset($data->type) && isset($data->name) && isset($data->date)
        && isset($type->guests) && isset($type->children) && isset($type->sweet_table)
        && isset($data->observations) && isset($data->phone)){
            $event->setType($data->type);
            $event->setName($data->name);
            $event->setDate($data->date);
            $event->setGuests($data->guests);
            $event->setChildren($data->children);
            $event->setSweetTable($data->sweet_table);
            $event->setObservations($data->observations);
            $event->setRestaurant($currentUser->getIdUser());
            $event->setPhone($data->phone);
            $event->setPrice($data->price);
        }

        echo("\n Estoy en EventRest dentro de create \n");
        var_dump($event);
        try{
            //Validate Post object
            $event->checkIsValidForCreate();

            $eventId = $this->eventMapper->save($event);

            header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
            header('Location: '.$_SERVER['REQUEST_URI']."/".$eventId);
            header('Content-Type: application/json');
            echo(json_encode(array(
                "id_event"=>$eventId,
                "type"=>$event->getType(),
                "name"=>$event->getName(),
                "date"=>$event->getDate(),
                "guests"=>$event->getGuests(),
                "children"=>$event->getChildren(),
                "sweet_table"=>$event->getSweetTable(),
                "observations"=>$event->getObservations(),
                "restaurant"=>$event->getRestaurant(),
                "phone"=>$event->getPhone(),
                "price"=>$event->getPrice()
            )));

        }catch (ValidationException $e){
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            header('Content-Type: application/json');
            echo(json_encode($e->getErrors()));
        }
    }


    public function viewEvent($eventId){
        $currentUser = parent::authenticateUser();

        $event = $this->eventMapper->findById($eventId);

        if($event == NULL){
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            echo("Event with id ".$eventId." not found");
        }
        if($event->getRestaurant() != $currentUser->getIdUser()){
            header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
            echo("You are not the authorized user for view this note");
            return;
        }

        $event_array = array(
            "id_event"=>$event->getIdEvent(),
            "type"=>$event->getType(),
            "name"=>$event->getName(),
            "date"=>$event->getDate(),
            "guests"=>$event->getGuests(),
            "children"=>$event->getChildren(),
            "sweet_table"=>$event->getSweetTable(),
            "observations"=>$event->getObservations(),
            "restaurant"=>$event->getRestaurant(),
            "phone"=>$event->getPhone(),
            "price"=>$event->getPrice()
        );

        header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
        header('Content-Type: application/json');
        echo(json_encode($event_array));
    }


    public function updateEvent($eventId, $data){
        $currentUser = parent::authenticateUser();

        $event = $this->eventMapper->findById($eventId);
       if ($event == NULL){
           header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
           echo("Event with id ".$eventId." not found");
       }

       if ($event->getRestaurant() != $currentUser->getIdUser()){
           header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
           echo("You are not the authorized user for edit this event");
           return;
       }
       $event->setType($data->type);
        $event->setName($data->name);
        $event->setDate($data->date);
        $event->setGuests($data->guests);
        $event->setChildren($data->children);
        $event->setSweetTable($data->sweet_table);
        $event->setObservations($data->observations);
        $event->setPhone($data->phone);
        $event->setPrice($data->price);

        try{
            //For Update
            $event->checkIsValidForUpdate();
            $this->eventMapper->update($event);
            header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
        }catch (ValidationException $e){
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            header('Content-Type: application/json');
            echo(json_encode($e->getErrors()));
        }
    }


    public function deleteEvent($eventId){
        $currentUser = parent::authenticateUser();
        $event = $this->eventMapper->findById($eventId);

        if($event == NULL){
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            echo("Event with id ".$eventId." not found");
            return;
        }
        if($event->getRestaurant() != $currentUser->getIdUser()){
            header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
            echo("you are not the author of this event");
            return;
        }


        $this->eventMapper->delete($event);

        header($_SERVER['SERVER_PROTOCOL'].' 204 No Content');
    }



}

// URI-MAPPING for this Rest endpoint
$eventRest = new EventRest();
URIDispatcher::getInstance()
    ->map("GET", "/event", array($eventRest,"getEvents"))
    ->map("GET", "/event/$1", array($eventRest, "viewEvent"))
    ->map("POST", "/event", array($eventRest, "createEvent"))
    ->map("PUT", "/event/$1", array($eventRest, "updateEvent"))
    ->map("DELETE", "/event/$1", array($eventRest, "deleteEvent"));
