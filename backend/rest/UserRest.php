<?php
/**
 * Created by PhpStorm.
 * User: RaquelMarcos
 * Date: 06/01/2019
 * Time: 19:50
 */

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/UserMapper.php");

require_once(__DIR__."/../model/Event.php");
require_once(__DIR__."/../model/EventMapper.php");

require_once(__DIR__."/BaseRest.php");


class UserRest extends BaseRest
{
    private $userMapper;
    private $eventMapper;

    public function __construct()
    {
        parent::__construct();

        $this->userMapper = new UserMapper();
        $this->eventMapper = new EventMapper();
    }

    public function getUser($eventId){
        $currentUser = parent::authenticateUser();

        $event = $this->eventMapper->findById($eventId);
        if ($event->getRestaurant() != $currentUser->getIdUser()) {
            header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
            echo("You are not the authorized for this");
            return;
        }

        $users = $this->userMapper->findAll($currentUser,$eventId);


        // json_encode Note objects.
        // since Note objects have private fields, the PHP json_encode will not
        // encode them, so we will create an intermediate array using getters and
        // encode it finally
        $users_array = array();
        foreach($users as $user) {
            array_push($users_array, array(
                "id_user" => $user->getIdUser(),
                "user" => $user->getUser(),
                "n_cli_wedding" =>$user-> getNCliWedding(),
                "n_cli_christening" => $user->getNCliChristening(),
                "n_cli_communion" => $user->getNCliCommunion(),
                "n_cli_others" => $user->getNCliOthers()
            ));
        }

        header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
        header('Content-Type: application/json');
        echo(json_encode($users_array));
    }

    public function postUser($data) {
        $user = new User($data->name,NULL,$data->user,$data->password, $data->n_cli_wedding, $data->n_cli_christening, $data->n_cli_communion, $data->n_cli_others);
        try {
            $user->checkIsValidForRegister();

            $this->userMapper->save($user);

            header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
            header("Location: ".$_SERVER['REQUEST_URI']."/".$data->username);
        }catch(ValidationException $e) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo(json_encode($e->getErrors()));
        }
    }

    public function login($username) {
        $currentLogged = parent::authenticateUser();
        if ($currentLogged->getUser() != $username) {
            header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
            echo("You are not authorized to login as anyone but you");
        } else {
            header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
            echo("Hello ".$username);
        }
    }
}

// URI-MAPPING for this Rest endpoint
$userRest = new UserRest();
URIDispatcher::getInstance()
    ->map("GET",	"/user/$1", array($userRest,"login"))
    ->map("POST", "/user", array($userRest,"postUser"))
    ->map("GET","/users/$1", array($userRest,"getUser"));
