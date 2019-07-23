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

use PHPMailer\PHPMailer\PHPMailer;
require_once (__DIR__."/../lib/PHPMailer/SMTP.php");
require_once (__DIR__."/../lib/PHPMailer/PHPMailer.php");

//require_once(__DIR__."../core/PHPMailer-master/src/PHPMailer.php");
//require_once(__DIR__."../core/PHPMailer-master/src/SMTP.php");

class EventRest extends BaseRest
{
    private $eventMapper;
    private $userMapper;
    private $foodMapper;
    private $staffMapper;

    public function __construct()
    {
        parent::__construct();

        $this->eventMapper = new EventMapper();
        $this->userMapper = new UserMapper();
        $this->foodMapper = new FoodMapper();
        $this->staffMapper = new StaffMapper();
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

        header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
        header('Content-Type: application/json');
        echo(json_encode($events_array));

    }

    //Funciona a la perfección
    public function createEvent($data){
        $currentUser = parent::authenticateUser();
        $event = new Event();

        if(isset($data->type)){

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

        $food = $this->eventMapper->AllFoodEvent($eventId);
        $staff = $this->eventMapper->getAllstaffEvent($eventId);
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
            "price"=>$event->getPrice(),
            "food"=>$food,
            "staff"=>$staff
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
                    "allergens"=> $this->foodMapper->getFoodAllergens($foodEvent["food"]->getIdFood())));

            }
            header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
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
        //var_dump($data);

        foreach ($data as $food){
            array_push($foodsEvent,array(
                "food"=>$food,
                "event"=>$id_event
            ));
        }

        try{
            if($this->eventMapper->existFoodsInEvent($foodsEvent) == false) {
                $this->eventMapper->setFoodEvent($foodsEvent);
                header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
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

        $toAdd = array();
        $toDelete = array();
        if($data->toAdd != null){
            foreach ($data->toAdd as $foodAdd){
                array_push($toAdd, array(
                    "food" => $foodAdd,
                    "event" => $id_event
                ));
            }
        }
        if($data->toDelete != null){
            foreach ($data->toDelete as $foodDelete){
                array_push($toDelete, array(
                    "food" => $foodDelete
                ));
            }
        }
        try{
            if($toAdd != null && $this->eventMapper->existFoodsInEvent($toAdd) == false){

                try{
                    $this->eventMapper->setFoodEvent($toAdd);
                    header($_SERVER['SERVER_PROTOCOL'].' 200 Created');
                    header('Content-Type: application/json');
                }catch (ValidationException $e){
                    header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
                    header('Content-Type: application/json');
                    echo(json_encode($e->getErrors()));
                }

            }
            if($data->toDelete != null){
                try{
                    $this->eventMapper->deleteFoodsFromEvent($id_event,$toDelete);
                    header($_SERVER['SERVER_PROTOCOL'].' 204 No Content');
                }catch (ValidationException $e){
                    header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
                    header('Content-Type: application/json');
                    echo(json_encode($e->getErrors()));
                }
            }

        }catch(ValidationException $e){
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            header('Content-Type: application/json');
            echo(json_encode($e->getErrors()));
        }

    }

    //Funciona a la perfección
    /**
     * @param $id_event
     * @param $data
     */
    public function setStaffEvent($id_event, $data){
        $currentUser = parent::authenticateUser();
        $staffEvent = array();
        //var_dump($data);
        $arrayStaffEmail = array();
        $emailList = null;
        foreach ($data as $staff){
            //inserta nombre del personal y el mail
            array_push($arrayStaffEmail, array(
                "name" => $this->staffMapper->findById($staff)->getName(),
                "email" => $this->staffMapper->findById($staff)->getEmail()
            ));
            //inserta el personal y el evento
            array_push($staffEvent,array(
                "staff"=>$staff,
                "event"=>$id_event,
                "invited"=>1,
                "rejected"=>null,
                "confirmed"=>null
            ));

        }

        $event = $this->eventMapper->findById($id_event);
        //echo 'staffEvent\n';

        //var_dump($staffEvent);
        //echo 'emailLlist\n';
        //var_dump($emailList);
        try{
            if($this->eventMapper->existStaffInEvent($staffEvent) == false) {
                //echo 'hola';
                $this->eventMapper->setStaffEvent($staffEvent);

                $mail = new PHPMailer();

                $date = $event->getDate();
                $time = strtotime($date);
                $myFormatForView = date("d/m/Y", $time);

                $mail->Subject = 'Evento '. $myFormatForView;

                $body = 'Hola! ¿Podrías venir el ' . $myFormatForView . ' a trabajar al evento '. $event->getType() . ' de ' . $event->getName() .'? </br>
                Te ruego me contestes en cuanto sea posible. Con cualquier duda o contratiempo llámame.';

                $mail->SMTPDebug = 0;

                $mail->isSMTP();

                $mail->Port = 587;

                $mail->SMTPSecure = 'tls';

                $mail->SMTPAuth = true;
                /*Sustituye (ServidorDeCorreoSMTP)  por el host de tu servidor de correo SMTP*/
                $mail->Host = 'smtp.gmail.com';

                /* Sustituye  ( CuentaDeEnvio )  por la cuenta desde la que deseas enviar por ejem. prueba@domitienda.com  */

                //$mail->From = 'rmenriqueztfg@gmail.com';
                $mail->setFrom($currentUser->getEmail(), $currentUser->getUser());

                //$mail->FromName = $currentUser->getName() . ' de '. $currentUser->getUser();

                $mail->MsgHTML($body);

                /* Sustituye  (CuentaDestino )  por la cuenta a la que deseas enviar por ejem. admin@domitienda.com  */
                foreach ($arrayStaffEmail as $email){
                    $mail->AddAddress($email["email"]);
                }

                $mail->AddReplyTo($currentUser->getEmail(),$currentUser->getName());

                $mail->AltBody = 'Hola! ¿Podrías venir el ' . $myFormatForView . ' a trabajar al evento '. $event->getType() . ' de ' . $event->getName() .'? 
                                Te ruego me contestes en cuanto sea posible. Con cualquier duda o contratiempo llámame.';

                /* Sustituye (CuentaDeEnvio )  por la misma cuenta que usaste en la parte superior en este caso  prueba@domitienda.com  y sustituye (ContraseñaDeEnvio)  por la contraseña que tenga dicha cuenta */

                $mail->Username = 'rmenriqueztfg@gmail.com';
                $mail->Password = 'tfgtfg2019';

                if(!$mail->Send()) {
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                } else {
                    echo 'Message sent!';
                }
                header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
                header('Content-Type: application/json');
                //echo(json_encode($foodsEvent));
            }else{
                header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
                header('Content-Type: application/json');
                echo "Some staff already exists";
            }

        }catch (ValidationException $e){
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            header('Content-Type: application/json');
            echo(json_encode($e->getErrors()));
        } catch (Exception $e) {
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            header('Content-Type: application/json');
            echo(json_encode($e->getErrors()));
        }
    }

    public function deleteStaffEvent($id_event, $data){
        $staffEvent = array();
        //var_dump($data);

        foreach ($data as $person){
            array_push($staffEvent,array(
                "staff"=>$person,
                "event"=>$id_event
            ));
        }

        try{

            $this->eventMapper->setFoodEvent($staffEvent);
            header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
            header('Content-Type: application/json');
            //echo(json_encode($foodsEvent));


        }catch (ValidationException $e){
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
    ->map("PUT", "/event/$1/food", array($eventRest, "updateFoodsEvent"))
    ->map("PUT", "/event/$1/staff", array($eventRest, "deleteStaffEvent"))
    ->map("POST", "/event/$1/staff", array($eventRest, "setStaffEvent"));
