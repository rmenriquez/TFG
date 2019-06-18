<?php

require_once("../core/ValidationException.php");

class User {

	/**
	* The id of the user
	* @var int
	*/
	private $id_user;

	/**
	* The name of the user
	* @var string
	*/
	private $name;

	/**
	* The user name of the user
	* @var string
	*/
	private $user;

	/**
	* The password of the user
	* @var string
	*/
	private $password;

    /**
     * The number of wedding's clients
     * @var number
     */
    private $n_cli_wedding;

    /**
     * The number of christening's clients
     * @var number
     */
    private $n_cli_christening;

    /**
     * The number of communion's clients
     * @var number
     */
    private $n_cli_communion;

    /**
     * The number of clients of other events
     * @var number
     */
    private $n_cli_others;

    /**
     * The restaurant's email
     * @var string
     */
    private $email;



	/**
	* The constructor
	*
     * @param int $id_user The id of the user
     * @param string $name The name of the user
     *  @param string $user The surname of the user
	* @param string $password The password of the user
	* @param int $n_cli_wedding The number of waiters for every 20 guests
     * @param int $n_cli_christening The number of waiters for every 20 guests
     * @param int $n_cli_communion The number of waiters for every 20 guests
     * @param int $n_cli_others The number of waiters for every 20 guests
	*/
	public function __construct($id_user=NULL,$name=NULL,$user=NULL,$password=NULL, $n_cli_wedding=NULL, $n_cli_christening=NULL, $n_cli_communion=NULL, $n_cli_others=NULL, $email=NULL) {
		$this->id_user=$id_user;
		$this->name=$name;
		$this->user = $user;
		$this->password = $password;
		$this->n_cli_wedding = $n_cli_wedding;
		$this->n_cli_christening = $n_cli_christening;
		$this->n_cli_communion = $n_cli_communion;
		$this->n_cli_others = $n_cli_others;
		$this->email = $email;
	}

	/**
	* Gets the id of this user
	*
	* @return int The id of this user
	*/
	public function getIdUser() {
		return $this->id_user;
	}

    /**
     * Sets the id of this user
     *
     * @param int $id The id of this user
     * @return void
     */
    public function setIdUser($id_user) {
        $this->id_user = $id_user;
    }

	/**
	* Gets the name of this user
	*
	* @return string The name of this user
	*/
	public function getName() {
		return $this->name;
	}

    /**
     * Sets the name of this user
     *
     * @param string $name The name of this user
     * @return void
     */
    public function setName($name) {
        $this->name = $name;
    }

	/**
	* Gets the username of this user
	*
	* @return string The username of this user
	*/
	public function getUser() {
		return $this->user;
	}

    /**
     * Sets the username of this user
     *
     * @param string $username The username of this user
     * @return void
     */
    public function setUser($user) {
        $this->user = $user;
    }

    /**
     * Gets the password of this user
     *
     * @return string The password of this user
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Sets the password of this user
     *
     * @param string $passwd The password of this user
     * @return void
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * Gets the number of waiters for every 20 guests
     * @return number
     */
    public function getNCliWedding()
    {
        return $this->n_cli_wedding;
    }

    /**
     * Sets the number of waiters for every 20 guests
     * @param number $n_cli_wedding
     */
    public function setNCliWedding($n_cli_wedding)
    {
        $this->n_cli_wedding = $n_cli_wedding;
    }

    /**
     * Gets the number of waiters for every 20 guests
     * @return number
     */
    public function getNCliChristening()
    {
        return $this->n_cli_christening;
    }

    /**
     * Sets the number of waiters for every 20 guests
     * @param number $n_cli_christening
     */
    public function setNCliChristening($n_cli_christening)
    {
        $this->n_cli_christening = $n_cli_christening;
    }

    /**
     * Gets the number of waiters for every 20 guests
     * @return number
     */
    public function getNCliCommunion()
    {
        return $this->n_cli_communion;
    }

    /**
     * Sets the number of waiters for every 20 guests
     * @param number $n_cli_communion
     */
    public function setNCliCommunion($n_cli_communion)
    {
        $this->n_cli_communion = $n_cli_communion;
    }

    /**
     * Gets the number of waiters for every 20 guests
     * @return number
     */
    public function getNCliOthers()
    {
        return $this->n_cli_others;
    }

    /**
     * Sets the number of waiters for every 20 guests
     * @param number $n_cli_others
     */
    public function setNCliOthers($n_cli_others)
    {
        $this->n_cli_others = $n_cli_others;
    }

    /**
     * Gets the email of the restaurant
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the email of the restaurant
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }



	/**
	* Checks if the current user instance is valid
	* for being registered in the database
	*
	* @throws ValidationException if the instance is
	* not valid
	*
	* @return void
	*/
	public function checkIsValidForRegister() {
		$errors = array();
		if (strlen($this->name) < 2) {
			$errors["name"] = "Name must be at least 5 characters length";

		}
		if (strlen($this->password) < 5) {
			$errors["password"] = "Password must be at least 5 characters length";
		}
		if (strlen($this->user) < 3){
		    $errors["user"] = "User must be at least 3 characters";
        }
        if (!preg_match('/\d/', $this->n_cli_wedding)) {
            $errors["n_cli_wedding"] = "Number of wedding's waiters must be at least 0";
        }
        if (!preg_match('/\d/',$this->n_cli_christening)) {
            $errors["n_cli_christening"] = "Number of christening's waiters must be at least 0";
        }
        if (!preg_match('/\d/',$this->n_cli_communion)) {
            $errors["n_cli_communion"] = "Number of communion's waiters must be at least 0";
        }
        if (!preg_match('/\d/',$this->n_cli_others)) {
            $errors["n_cli_others"] = "Number of other's waiters must be at least 0";
        }
		if (sizeof($errors)>0){
			throw new ValidationException($errors, "user is not valid");
		}
	}
}
