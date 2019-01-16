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
        foreach ($events as $event){
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

            header($_SERVER['SERVER_PROTOCOL'].'200 Ok');
            header('Content-Type: application/json');
            echo(json_encode($events_array));
        }
    }

    public function createEvent($data){
        $currentUser = parent::authenticateUser();
        $event = new Event();

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

        try{
            //Validate Post object
            $event->checkIsValidForCreate();

        }catch (ValidationException $e){

        }
    }



}