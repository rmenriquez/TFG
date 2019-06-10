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

require_once (__DIR__."/BaseRest.php");


class EventRest extends BaseRest
{
    private $eventMapper;
    private $userMapper;
    private $foodMapper;

    public function __construct()
    {
        parent::__construct();

        $this->eventMapper = new EventMapper();
        $this->userMapper = new UserMapper();
        $this->foodMapper = new FoodMapper();
    }

    //Funciona a la perfección
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

    //Funciona a la perfección
    public function createEvent($data){
        $currentUser = parent::authenticateUser();
        $event = new Event();

        if(isset($data->type)){
            /*
             *  && isset($data->name) && isset($data->date)
        && isset($type->guests) && isset($type->children)
        && isset($data->observations) && isset($data->phone)
             * */
            //echo "HOLA GILIPOLLAS";
            $event->setType($data->type);
            //echo "Imprimo data->type\n";
            //print_r($event);
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

        try{
            //Validate Post object
            $event->checkIsValidForCreate();

            if($this->eventMapper->eventExists($event) == false){
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
            }else{
                header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
                header('Content-Type: application/json');
                throw new Exception('The event already exists.');
            }


        }catch (ValidationException $e){
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            header('Content-Type: application/json');
            echo(json_encode($e->getErrors()));
        }
    }

//Funciona a la perfección
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

//Funciona a la perfección
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

    //Funciona a la perfección
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

    /**
     * @param $id_event
     */
    public function getFoodsEvent($id_event){
        try{
            $foodsEvent = $this->eventMapper->AllFoodEvent($id_event);
            //print_r($foodsEvent);
            $foodEvent_array = array();
            foreach ($foodsEvent as $foodEvent) {
                array_push($foodEvent_array, array(
                    "id_food"=>$foodEvent["food"]->getIdFood(),
                    "title"=>$foodEvent["food"]->getTitle(),
                    "description"=>$foodEvent["food"]->getDescription(),
                    "image"=>$foodEvent["food"]->getimage(),
                    "restaurant"=>$foodEvent["food"]->getRestaurant(),
                    "price"=>$foodEvent["food"]->getPrice(),
                    "clamp"=>$foodEvent["clamp"],
                    "allergens"=> $this->foodMapper->getFoodAllergens($foodEvent["food"]->getIdFood())));

            }

            header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
            header('Content-Type: application/json');
            echo(json_encode($foodEvent_array));
        }catch (ValidationException $e){
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            header('Content-Type: application/json');
            echo(json_encode($e->getErrors()));
        }

    }

    //Funciona a la perfección
    public function setFoodsEvent($id_event, $data){

        $foodsEvent = array();

        foreach ($data as $food){
            array_push($foodsEvent,array(
                "food"=>$food[0],
                "event"=>$id_event,
                "clamp"=>$food[1]
            ));
        }

        try{
            if($this->eventMapper->existFoodsInEvent($foodsEvent) == false) {
                $this->eventMapper->setFoodEvent($foodsEvent);
                header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
                header('Content-Type: application/json');
                //echo(json_encode($foodsEvent));
            }else{
                header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
                header('Content-Type: application/json');
                echo "Some food already exists";
            }

        }catch (ValidationException $e){
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            header('Content-Type: application/json');
            echo(json_encode($e->getErrors()));
        }
    }

    //Funciona a la perfección
    public function deleteFoodsEvent($id_event, $data){

        try{
            $this->eventMapper->deleteFoodsFromEvent($id_event,$data);

            header($_SERVER['SERVER_PROTOCOL'].' 204 No Content');

        }catch(Exception $e){
            echo "Some food not exist in this event";
        }
    }

    //Funciona a la perfección
    public function updateFoodsEvent($id_event, $data){
        $toUpdate = array();
        foreach ($data as $food){
            array_push($toUpdate, array(
               "food" => $food[0],
               "event" => $id_event,
               "clamp" => $food[1]
            ));
        }
        /*echo "\naux\n";
        print_r($aux);*/

        try{

            $this->eventMapper->updateFoodsFromEvent($toUpdate);
            //response OK. Also send post in content
            header($_SERVER['SERVER_PROTOCOL'].' 200 Created');
            //header('Location: '.$_SERVER['REQUEST_URI']."/".$foodId);
            header('Content-Type: application/json');

        }catch(ValidationException $e){
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            header('Content-Type: application/json');
            echo(json_encode($e->getErrors()));
        }

    }



}

// URI-MAPPING for this Rest endpoint
$eventRest = new EventRest();
URIDispatcher::getInstance()
    ->map("GET", "/event", array($eventRest,"getEvents"))
    ->map("GET", "/event/$1", array($eventRest, "viewEvent"))
    ->map("POST", "/event", array($eventRest, "createEvent"))
    ->map("PUT", "/event/$1", array($eventRest, "updateEvent"))
    ->map("DELETE", "/event/$1", array($eventRest, "deleteEvent"))
    ->map("GET", "/event/$1/food", array($eventRest, "getFoodsEvent"))
    ->map("POST", "/event/$1/food", array($eventRest, "setFoodsEvent"))
    ->map("DELETE", "/event/$1/food", array($eventRest, "deleteFoodsEvent"))
    ->map("PUT", "/event/$1/food", array($eventRest, "updateFoodsEvent"));
