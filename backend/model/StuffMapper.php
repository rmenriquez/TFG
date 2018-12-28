<?php
/**
 * Created by PhpStorm.
 * User: RaquelMarcos
 * Date: 28/12/2018
 * Time: 16:10
 */

require_once ("../core/PDOConnection.php");

class StuffMapper
{
    /**
     * Reference to the PDO connection
     * @var PDO
     */
    private $db;

    public function __construct()
    {
        $this->db = PDOConnection::getInstance();
    }

    /**
     * Retrieves all stuff for this restaurant
     * @param $restaurant
     */
    public function findAll($restaurant){
        $stmt = $this->db->prepare("SELECT id_stuff, name, surnames, birthdate, email, restaurant 
        FROM stuff WHERE restaurant = ?");
        $stmt->execute(array($restaurant));

        $stuffs_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stuffs = array();

        foreach ($stuffs_db as $stuff){
            array_push($stuffs, new Stuff($stuff["id_stuff"], $stuff["name"], $stuff["surnames"], $stuff["birthdate"], $stuff["email"], $stuff["restaurant"]));
        }

        return $stuffs;
    }

    /**
     * Retrieves the stuff wich name is like the param
     * @param $name
     * @return null|Stuff
     */
    public function findByName($name){
        $stmt = $this->db->prepare("SELECT * FROM stuff WHERE name LIKE %?%");
        $stmt->execute(array($name));

        $stuff = $stmt->fetch(PDO::FETCH_ASSOC);

        if($stuff != null){
            return new Stuff(
                $stuff["id_stuff"],
                $stuff["name"],
                $stuff["surnames"],
                $stuff["birthdate"],
                $stuff["email"],
                $stuff["restaurant"]
            );
        }else{
            return NULL;
        }
    }

    /**
     * Saves a Stuff into the database
     * @param Stuff $stuff
     */
    public function save(Stuff $stuff){
        $stmt = $this->db->prepare("INSERT INTO stuff(id_stuff, name, surnames, birthdate, email, restaurant)
                                              VALUES(?,?,?,?,?,?)");
        $stmt->execute(array($stuff->getIdStuff(), $stuff->getName(), $stuff->getSurnames(), $stuff->getBirthdate(), $stuff->getEmail(), $stuff->getRestaurant()));
    }

    /**
     * Updates a Stuff in the database
     * @param Stuff $stuff
     */
    public function update(Stuff $stuff){
        $stmt = $this->db->prepare("UPDATE stuff SET name=?, surnames=?, birthdate =?, email=?");
        $stmt->execute(array($stuff->getName(), $stuff->getSurnames(), $stuff->getBirthdate(), $stuff->getEmail()));
    }

    /**
     * Deletes a Stuff into the database
     * @param Stuff $stuff
     */
    public function delete(Stuff $stuff){
        $stmt = $this->db->prepare("DELETE FROM stuff WHERE id_stuff =?");
        $stmt->execute(array($stuff->getIdStuff()));
    }


}