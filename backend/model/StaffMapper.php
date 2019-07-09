<?php
/**
 * Created by PhpStorm.
 * User: RaquelMarcos
 * Date: 28/12/2018
 * Time: 16:10
 */

require_once ("../core/PDOConnection.php");

class StaffMapper
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
     * Retrieves all staff for this restaurant
     * @param $restaurant
     * @return $staffs
     */
    public function findAll($restaurant){
        $stmt = $this->db->prepare("SELECT id_staff, name, surnames, birthdate, email, restaurant 
        FROM staff WHERE restaurant = ?");
        $stmt->execute(array($restaurant));

        $staffs_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $staffs = array();

        foreach ($staffs_db as $staff){
            array_push($staffs, new Staff($staff["id_staff"], $staff["name"], $staff["surnames"], $staff["birthdate"], $staff["email"], $staff["restaurant"]));
        }

        return $staffs;
    }

    /**
     * Retrieves the staff wich name is like the param
     * @param $name
     * @return null|Staff
     */
    public function findByName($name){
        $stmt = $this->db->prepare("SELECT * FROM staff WHERE name LIKE %?%");
        $stmt->execute(array($name));

        $staff = $stmt->fetch(PDO::FETCH_ASSOC);

        if($staff != null){
            return new Staff(
                $staff["id_staff"],
                $staff["name"],
                $staff["surnames"],
                $staff["birthdate"],
                $staff["email"],
                $staff["restaurant"]
            );
        }else{
            return NULL;
        }
    }

    /**
     * Retrieves the staff which id is the same that the given
     * @param $id
     * @return null|Staff
     */
    public function findById($id){
        $stmt = $this->db->prepare("SELECT * FROM staff WHERE id_staff = ?");
        $stmt->execute(array($id));

        $staff = $stmt->fetch(PDO::FETCH_ASSOC);

        if($staff != null){
            return new Staff(
                $staff["id_staff"],
                $staff["name"],
                $staff["surnames"],
                $staff["birthdate"],
                $staff["email"],
                $staff["restaurant"]
            );
        }else{
            return NULL;
        }
    }


    /**
     * Saves a Staff into the database
     * @param Staff $staff
     */
    public function save(Staff $staff){
        $stmt = $this->db->prepare("INSERT INTO staff(id_staff, name, surnames, birthdate, email, restaurant)
                                              VALUES(?,?,?,?,?,?)");
        $stmt->execute(array($staff->getIdStaff(), $staff->getName(), $staff->getSurnames(), $staff->getBirthdate(), $staff->getEmail(), $staff->getRestaurant()));
    }

    /**
     * Updates a Staff in the database
     * @param Staff $staff
     */
    public function update(Staff $staff){
        $stmt = $this->db->prepare("UPDATE staff SET name=?, surnames=?, birthdate =?, email=? WHERE id_staff = ? AND restaurant = ?");
        $stmt->execute(array($staff->getName(), $staff->getSurnames(), $staff->getBirthdate(), $staff->getEmail(), $staff->getIdStaff(), $staff->getRestaurant()));
    }

    /**
     * Deletes a Staff into the database
     * @param Staff $staff
     */
    public function delete(Staff $staff){
        $stmt = $this->db->prepare("DELETE FROM staff WHERE id_staff =?");
        $stmt->execute(array($staff->getIdStaff()));
    }


    /**
     * Check if the staff exists for the restaurant logged in
     * @param $restaurant
     * @param $id_staff
     * @return boolean
     */
    public function staffExists($restaurant, $id_staff){
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM staff WHERE id_staff = ? AND restaurant = ?");
        $stmt->execute(array($id_staff, $restaurant));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if($data['count'] == 0){
            $exists = false;
        }else{
            $exists = true;
        }

        return $exists;
    }
}