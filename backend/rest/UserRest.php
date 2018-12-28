<?php

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/UserMapper.php");
require_once(__DIR__."/../model/NoteMapper.php");
require_once(__DIR__."/BaseRest.php");

/**
* Class UserRest
*
* It contains operations for adding and check users credentials.
* Methods gives responses following Restful standards. Methods of this class
* are intended to be mapped as callbacks using the URIDispatcher class.
*
*/
class UserRest extends BaseRest {
	private $userMapper;
    private $noteMapper;

	public function __construct() {
		parent::__construct();

		$this->userMapper = new UserMapper();
		$this->noteMapper = new NoteMapper();
	}

    public function getUsers($noteid) {
        $currentUser = parent::authenticateUser();

        $note = $this->noteMapper->findById($noteid);
        if ($note->getAuthor()->getUsername() != $currentUser->getUsername()) {
            header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
            echo("You are not the authorized for this");
            return;
        }

        $users = $this->userMapper->findAll($currentUser,$noteid);


        // json_encode Note objects.
        // since Note objects have private fields, the PHP json_encode will not
        // encode them, so we will create an intermediate array using getters and
        // encode it finally
        $users_array = array();
        foreach($users as $user) {
            array_push($users_array, array(
                "id" => $user->getId(),
                "username" => $user->getUsername()
            ));
        }

        header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
        header('Content-Type: application/json');
        echo(json_encode($users_array));
    }

	public function postUser($data) {
		$user = new User($data->username,NULL,$data->name,$data->surname, $data->password);
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
		if ($currentLogged->getUsername() != $username) {
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
->map("GET","/users/$1", array($userRest,"getUsers"));
