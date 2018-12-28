<?php
/**
 * Created by PhpStorm.
 * User: RaquelMarcos
 * Date: 28/12/2018
 * Time: 13:44
 */

class Stuff
{

    /**
     * The id of the stuff
     * @var null
     */
    private $id_stuff;

    /**
     * The name of the stuff
     * @var null
     */
    private $name;

    /**
     * The surnames of the stuff
     * @var null
     */
    private $surnames;

    /**
     * The birthdate of the stuff
     * @var null
     */
    private $birthdate;

    /**
     * The email of the stuff
     * @var null
     */
    private $email;

    /**
     * The restaurant where the stuff will work
     * @var null
     */
    private $restaurant;

    public function __construct($id_stuff=NULL, $name=NULL, $surnames=NULL, $birthdate=NULL, $email=NULL, $restaurant=NULL)
    {
        $this->id_stuff = $id_stuff;
        $this->name = $name;
        $this->surnames = $surnames;
        $this->birthdate = $birthdate;
        $this->email = $email;
        $this->restaurant = $restaurant;
    }

    /**
     * Gets the Id of this stuff (DNI)
     * @return string The id of this stuff (DNI)
     */
    public function getIdStuff()
    {
        return $this->id_stuff;
    }

    /**
     * Sets the id for this stuff
     * @param string $id_stuff
     */
    public function setIdStuff($id_stuff)
    {
        $this->id_stuff = $id_stuff;
    }

    /**
     * Gets the name of this stuff
     * @return string The name of this stuff
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name for this stuff
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Gets the surnames of this stuff
     * @return string
     */
    public function getSurnames()
    {
        return $this->surnames;
    }

    /**
     * Sets the surnames for this stuff
     * @param string $surnames
     */
    public function setSurnames($surnames)
    {
        $this->surnames = $surnames;
    }

    /**
     * Gets the birthdate of this stuff
     * @return date
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Sets the birthdate for this stuff
     * @param date $birthdate
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
    }

    /**
     * Sets the email of this stuff
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the email for this stuff
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Gets the restaurant where this stuff works
     * @return null
     */
    public function getRestaurant()
    {
        return $this->restaurant;
    }

    /**
     * Sets the restaurant where this stuff
     * @param null $restaurant
     */
    public function setRestaurant($restaurant)
    {
        $this->restaurant = $restaurant;
    }


}