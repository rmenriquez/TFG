<?php
/**
 * Created by PhpStorm.
 * User: RaquelMarcos
 * Date: 28/12/2018
 * Time: 13:44
 */

class Staff
{

    /**
     * The id of the staff
     * @var null
     */
    private $id_staff;

    /**
     * The name of the staff
     * @var null
     */
    private $name;

    /**
     * The surnames of the staff
     * @var null
     */
    private $surnames;

    /**
     * The birthdate of the staff
     * @var null
     */
    private $birthdate;

    /**
     * The email of the staff
     * @var null
     */
    private $email;

    /**
     * The restaurant where the staff will work
     * @var null
     */
    private $restaurant;

    public function __construct($id_staff=NULL, $name=NULL, $surnames=NULL, $birthdate=NULL, $email=NULL, $restaurant=NULL)
    {
        $this->id_staff = $id_staff;
        $this->name = $name;
        $this->surnames = $surnames;
        $this->birthdate = $birthdate;
        $this->email = $email;
        $this->restaurant = $restaurant;
    }

    /**
     * Gets the Id of this staff (DNI)
     * @return string The id of this staff (DNI)
     */
    public function getIdStaff()
    {
        return $this->id_staff;
    }

    /**
     * Sets the id for this staff
     * @param string $id_staff
     */
    public function setIdStaff($id_staff)
    {
        $this->id_staff = $id_staff;
    }

    /**
     * Gets the name of this staff
     * @return string The name of this staff
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name for this staff
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Gets the surnames of this staff
     * @return string
     */
    public function getSurnames()
    {
        return $this->surnames;
    }

    /**
     * Sets the surnames for this staff
     * @param string $surnames
     */
    public function setSurnames($surnames)
    {
        $this->surnames = $surnames;
    }

    /**
     * Gets the birthdate of this staff
     * @return date
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Sets the birthdate for this staff
     * @param date $birthdate
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
    }

    /**
     * Sets the email of this staff
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the email for this staff
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Gets the restaurant where this staff works
     * @return null
     */
    public function getRestaurant()
    {
        return $this->restaurant;
    }

    /**
     * Sets the restaurant where this staff
     * @param null $restaurant
     */
    public function setRestaurant($restaurant)
    {
        $this->restaurant = $restaurant;
    }


}