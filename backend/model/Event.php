<?php
/**
 * Created by PhpStorm.
 * User: RaquelMarcos
 * Date: 28/12/2018
 * Time: 17:08
 */

class Event
{

    /**
     * The id of the event
     * @var int
     */
    private $id_event;

    /**
     * The type of the event: wedding, christening, communion or others
     * @var string
     */
    private $type;

    /**
     * The name of the event
     * @var string
     */
    private $name;

    /**
     * The date of the event
     * @var Date
     */
    private $date;

    /**
     * The number of the event's guests
     * @var null
     */
    private $gests;

    /**
     * The number of the event's children
     * @var null
     */
    private $children;

    /**
     * If the sweet table is by restaurant (false) or by external part (true)
     * @var boolean
     */
    private $own_sweet_table;

    /**
     * Observations for the event
     * @var string
     */
    private $observations;

    /**
     * Code of the event's restaurant
     * @var int
     */
    private $restaurant;

    /**
     * Event organizer phone
     * @var int
     */
    private $phone;

    /**
     * Total price of the event
     * @var double
     */
    private $price;

    public function __construct($id_event=NULL, $type=NULL, $name=NULL, $date=NULL, $guests=NULL, $children=NULL, $own_sweet_table=NULL, $observations=NULL, $restaurant=NULL, $phone=NULL, $price=NULL)
    {
        $this->id_event = $id_event;
        $this->type = $type;
        $this->name = $name;
        $this->date = $date;
        $this->gests = $guests;
        $this->children = $children;
        $this->own_sweet_table = $own_sweet_table;
        $this->observations = $observations;
        $this->restaurant = $restaurant;
        $this->phone = $phone;
        $this->price = $price;

    }

    /**
     * Gets the id of the event
     * @return int The id event
     */
    public function getIdEvent()
    {
        return $this->id_event;
    }

    /**
     * Sets the id for the event
     * @param int $id_event
     */
    public function setIdEvent($id_event)
    {
        $this->id_event = $id_event;
    }

    /**
     * Gets the type of the event
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the type for the event
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Gets the name of the event
     * @return null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name for the event
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Gets the date of the event
     * @return date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Sets the date for the event
     * @param date $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Gets the number of the guests of the event
     * @return int
     */
    public function getGests()
    {
        return $this->gests;
    }

    /**
     * Sets the number of the guests for the event
     * @param int $gests
     */
    public function setGests($gests)
    {
        $this->gests = $gests;
    }

    /**
     * Gets the number of children in the guests of the event
     * @return int
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Sets the number of the children in the guests of the event
     * @param int $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

    /**
     * Gets the sweet table is by restaurant (false) or by external part (true)
     * @return boolean
     */
    public function getOwnSweetTable()
    {
        return $this->own_sweet_table;
    }

    /**
     * Sets that the sweet table is by restaurant (false) or by external part (true)
     * @param boolean $own_sweet_table
     */
    public function setOwnSweetTable($own_sweet_table)
    {
        $this->own_sweet_table = $own_sweet_table;
    }

    /**
     * Gets the observations of the event
     * @return string
     */
    public function getObservations()
    {
        return $this->observations;
    }

    /**
     * Sets the observations for the event
     * @param string $observations
     */
    public function setObservations($observations)
    {
        $this->observations = $observations;
    }

    /**
     * Gets the code of the restaurant where the event will be
     * @return int
     */
    public function getRestaurant()
    {
        return $this->restaurant;
    }

    /**
     * Sets the code of the restaurat where the event will be
     * @param int $restaurant
     */
    public function setRestaurant($restaurant)
    {
        $this->restaurant = $restaurant;
    }

    /**
     * Gets the event organizer phone
     * @return int
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Sets the event organizer phone
     * @param int $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * Gets the total price of the event
     * @return double
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Sets the total price for the event
     * @param double $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }


}