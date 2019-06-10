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

        }catch (ValidationException $e){

        }

    }

    public function updatePerson(){

    }

    public function deletePerson(){

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