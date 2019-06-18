<?php

require_once("../core/PDOConnection.php");

class UserMapper {

	/**
	* Reference to the PDO connection
	* @var PDO
	*/
	private $db;

	public function __construct() {
		$this->db = PDOConnection::getInstance();
	}

	/**
	* Saves a User into the database
	*
	* @param User $user The user to be saved
	* @throws PDOException if a database error occurs
	* @return void
	*/
	public function save($user) {
		$stmt = $this->db->prepare("INSERT INTO user (id_user,name,user,password,n_cli_wedding,n_cli_christening,n_cli_communion,n_cli_others, email) 
          values (0,?,?,?,?,?,?,?,?)");
		$stmt->execute(array($user->getName(),$user->getUser(),$user->getPassword(), $user->getNCliWedding(), $user->getNCliChristening(), $user->getNCliCommunion(), $user->getNCliOthers(), $user->getEmail()));
	}

    /**
     * Checks if a given username is already in the database
     *
     * @param string $username the username to check
     * @return boolean true if the username exists, false otherwise
     */
    public function usernameExists($user) {
        $stmt = $this->db->prepare("SELECT count(user) FROM user where user=?");
        $stmt->execute(array($user));

        if ($stmt->fetchColumn() > 0) {
            return true;
        }
    }

    /**
	* Checks if a given pair of username/password exists in the database
	*
	* @param string $username the username
	* @param string $passwd the password
	* @return boolean true the username/passwrod exists, false otherwise.
	*/
	public function isValidUser($user, $password) {
		$stmt = $this->db->prepare("SELECT count(user) FROM user where user=? and password=?");
		$stmt->execute(array($user, $password));
		if ($stmt->fetchColumn() > 0) {
			return true;
		}
	}

//Funciona
	public function getIdUserAux($user){
	    $stmt = $this->db->prepare("SELECT id_user FROM user WHERE user.user =?");
	    $stmt->execute(array($user));
	    $id_user = $stmt->fetch(PDO::FETCH_ASSOC);

	    if($id_user != null){
	        return $id_user['id_user'];
        }else{
	        return NULL;
        }

    }

    //Funciona
    /**
     * Returns the complete user for that username
     *
     * @param $user The Username for the current user
     * @return null|User the user complete
     */
    public function getUserComplete($user){
        $stmt = $this->db->prepare("SELECT * FROM user WHERE user.user =?");
        $stmt->execute(array($user));
        $userComplete = $stmt->fetch(PDO::FETCH_ASSOC);
        $userAux = new User();
        if($userComplete != null){
            $userAux->setUser($user);
            $userAux->setIdUser($userComplete['id_user']);
            $userAux->setName($userComplete['name']);
            $userAux->setNCliWedding($userComplete['n_cli_wedding']);
            $userAux->setNCliChristening($userComplete['n_cli_christening']);
            $userAux->setNCliCommunion($userComplete['n_cli_communion']);
            $userAux->setNCliOthers($userComplete['n_cli_others']);
            $userAux->setEmail($userComplete['email']);
            return $userAux;
        }else{
            echo("NO SE LOGRO RECUPERAR EL USUARIO COMPLETO");
            return NULL;
        }
	}

    /**
     * Retrieves the user which id is the same that the given
     * @param $id
     * @return null|User
     */
    public function findById($id){
        $stmt = $this->db->prepare("SELECT * FROM user WHERE id_user = ?");
        $stmt->execute(array($id));

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user != null){
            return new User(
                $user["id_user"],
                $user["name"],
                $user["user"],
                $user["n_cli_wedding"],
                $user["n_cli_christening"],
                $user["n_cli_communion"],
                $user["n_cli_others"],
                $user["email"]
            );
        }else{
            return NULL;
        }
    }

    /*public function findAll(User $user,$note) {
        $stmt = $this->db->prepare("SELECT id_user,name,surname,username FROM user WHERE username!= ? AND id_user NOT IN (SELECT user FROM shared WHERE note = ? )");
        $stmt->execute(array($user->getUsername(),$note));
        $users_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $users = array();

        foreach ($users_db as $user) {
            array_push($users, new User($user["username"], $user["id_user"], $user["name"],$user["surname"]));
        }
        return $users;
    }*/
}
