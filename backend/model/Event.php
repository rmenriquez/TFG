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
     * @var varchar
     */
    private $type;

    /**
     * The name of the event
     * @var varchar
     */
    private $name;

    /**
     * The date of the event
     * @var date
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
     * @var varchar
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
     * @param null $id_event
     */
    public function setIdEvent($id_event)
    {
        $this->id_event = $id_event;
    }

    /**
     * @return null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param null $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param null $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return null
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param null $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return null
     */
    public function getGests()
    {
        return $this->gests;
    }

    /**
     * @param null $gests
     */
    public function setGests($gests)
    {
        $this->gests = $gests;
    }

    /**
     * @return null
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param null $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

    /**
     * @return null
     */
    public function getOwnSweetTable()
    {
        return $this->own_sweet_table;
    }

    /**
     * @param null $own_sweet_table
     */
    public function setOwnSweetTable($own_sweet_table)
    {
        $this->own_sweet_table = $own_sweet_table;
    }

    /**
     * @return null
     */
    public function getObservations()
    {
        return $this->observations;
    }

    /**
     * @param null $observations
     */
    public function setObservations($observations)
    {
        $this->observations = $observations;
    }

    /**
     * @return null
     */
    public function getRestaurant()
    {
        return $this->restaurant;
    }

    /**
     * @param null $restaurant
     */
    public function setRestaurant($restaurant)
    {
        $this->restaurant = $restaurant;
    }

    /**
     * @return null
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param null $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return null
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param null $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }


}