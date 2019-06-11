<?php
/**
 * Created by PhpStorm.
 * User: RaquelMarcos
 * Date: 05/06/2019
 * Time: 19:29
 */

require_once (__DIR__."/../model/User.php");
require_once (__DIR__."/../model/UserMapper.php");

require_once (__DIR__."/../model/Staff.php");
require_once (__DIR__."/../model/StaffMapper.php");

require_once (__DIR__."/BaseRest.php");

class StaffRest extends BaseRest{

    private $userMapper;
    private $staffMapper;

    public function __construct()
    {
        parent::__construct();

        $this->userMapper = new UserMapper();
        $this->staffMapper = new StaffMapper();
    }

    public function getStaff(){
        $currentUser = parent::authenticateUser();
        $staff = $this->staffMapper->findAll($currentUser->getIdUser());

        $staff_array = array();
        foreach ($staff as $person){
            array_push($staff_array, array(
                "id_staff" => $person->getIdStaff(),
                "name" => $person->getName(),
                "surnames" => $person->getSurnames(),
                "birthdate" => $person->getBirthdate(),
                "email" => $person->getEmail(),
                "restaurant" => $person->getRestaurant()
            ));
        }
        header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
        header('Content-Type: application/json');
        echo(json_encode($staff_array));
    }

    public function viewPerson($staffId){
        $currentUser = parent::authenticateUser();

        $staff = $this->staffMapper->findById($staffId);

        if($staff == NULL){
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            echo("Staff with id ".$staff." not found");
        }
        if($staff->getRestaurant() != $currentUser->getIdUser()){
            header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
            echo("You are not the authorized user for view this staff");
            return;
        }

        $staff_array = array(
            "id_staff" => $staffId,
            "name" => $staff->getName(),
            "surnames" => $staff->getSurnames(),
            "birthdate" => $staff->getBirthdate(),
            "email" => $staff->getEmail(),
            "restaurant" => $staff->getRestaurant(),
        );

        header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
        header('Content-Type: application/json');
        echo(json_encode($staff_array));
    }

    public function createStaff($data){
        $currentUser = parent::authenticateUser();
        $staff = new Staff();

        if(isset($data->id_staff) && isset($data->name) &&
        isset($data->surnames) && isset($data->email)){
            $staff->setIdStaff($data->id_staff);
            $staff->setName($data->name);
            $staff->setSurnames($data->surnames);
            $staff->setEmail($data->email);
            $staff->setBirthdate($data->birthdate);
            $staff->setRestaurant($currentUser->getIdUser());
        }

        try{
            $staff->checkIsValidForCreate();

            if($this->staffMapper->staffExists($currentUser->getIdUser(),$data->id_staff) == false){
                $staffId = $this->staffMapper->save($staff);

                header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
                header('Location: '.$_SERVER['REQUEST_URI']."/".$staffId);
                header('Content-Type: application/json');
                echo(json_encode(array(
                    "id_staff"=>$staff->getIdStaff(),
                    "name" => $staff->getName(),
                    "surnames" => $staff->getSurnames(),
                    "birthdate" => $staff->getBirthdate(),
                    "email" => $staff->getEmail(),
                    "restaurant" => $staff->getRestaurant(),
                )));
            }else{
                header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
                header('Content-Type: application/json');
                throw new Exception('The staff already exists.');
            }
        }catch (ValidationException $e){
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            header('Content-Type: application/json');

            echo(json_encode($e->getErrors()));
        }

    }

    public function updatePerson($id_staff, $data){
        $currentUser = parent::authenticateUser();

        $staff = $this->staffMapper->findById($id_staff);

        if($staff == NULL){
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            echo("Staff with id ".$id_staff." not found");
            return;
        }
        if($staff->getRestaurant() != $currentUser->getIdUser()){
            header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
            echo("you are not the restaurant of this staff");
            return;
        }

        $staff->setName($data->name);
        $staff->setSurnames($data->surnames);
        $staff->setBirthdate($data->birthdate);
        $staff->setEmail($data->email);

        try{
            $staff->checkIsValidForUpdate();
            $this->staffMapper->update($staff);
            header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
        }catch(ValidationException $e){
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            header('Content-Type: application/json');
            echo(json_encode($e->getErrors()));

        }
    }

    public function deletePerson($id_staff){
        $currentUser = parent::authenticateUser();

        $staff = $this->staffMapper->findById($id_staff);

        if($staff == NULL){
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            echo("Staff with id ".$id_staff." not found");
            return;
        }
        if($staff->getRestaurant() != $currentUser->getIdUser()){
            header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
            echo("you are not the restaurant of this staff");
            return;
        }

        $this->staffMapper->delete($staff);
        header($_SERVER['SERVER_PROTOCOL'].' 204 No Content');
    }
}

// URI-MAPPING for this Rest endpoint
$staffRest = new StaffRest();
URIDispatcher::getInstance()
    ->map("GET","/staff", array($staffRest, "getStaff"))
    ->map("GET", "/staff/$1", array($staffRest, "viewPerson"))
    ->map("POST", "/staff", array($staffRest, "createStaff"))
    ->map("PUT", "/staff/$1", array($staffRest, "updatePerson"))
    ->map("DELETE", "/staff/$1", array($staffRest, "deletePerson"));