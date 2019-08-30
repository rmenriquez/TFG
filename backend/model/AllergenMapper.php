<?php
/**
 * Created by PhpStorm.
 * User: RaquelMarcos
 * Date: 26/12/2018
 * Time: 11:30
 */

require_once ("../core/PDOConnection.php");

class AllergenMapper
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
     * Retrieves all allergens
     */
    public function findAll(){
        $stmt = $this->db->prepare("SELECT * FROM allergen");
        $stmt->execute();

        $allergens_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $allergens_db;
    }

    /**
     * Saves an Allergen in the database
     *
     * @param Allergen $allergen
     */
    public function save(Allergen $allergen){
        if(!$this->nameAllergenExists($allergen->getNameAllergen())){
            $stmt = $this->db->prepare("INSERT INTO allergen(id_allergen, name_allergen) 
                                              VALUES (0, ?)");
            $stmt->execute(array(0,$allergen->getNameAllergen()));
        }
    }

    /**
     * Updates an Allergen in the database
     *
     * @param Allergen $allergen
     */
    public function update(Allergen $allergen){
        $stmt = $this->db->prepare("UPDATE allergen SET name_allergen=?");
        $stmt->execute(array($allergen->getNameAllergen()));
    }


    public function delete(Allergen $allergen){
        $stmt = $this->db->prepare("DELETE FROM allergen WHERE id_allergen = ?");
        $stmt->execute(array($allergen->getIdAllergen()));
    }


    /**
     * @param $name_allergen
     * @return bool
     */
    public function nameAllergenExists($name_allergen) {
        $stmt = $this->db->prepare("SELECT count(name_allergen) FROM allergen where name_allergen LIKE %?%");
        $stmt->execute(array($name_allergen));

        if ($stmt->fetchColumn() > 0) {
            return true;
        }
    }


    /***
     * Assigns allergens to food
     * @param $allergen id of the allergen's food
     * @param $food id of the food
     */
    public function addAllergenToFood($allergens_food){

        // $rowsToInsert es $allergens_food
        $rowsSQL = array();

        //Will contain the values that we need to bind.
        $toBind = array();

        //Get a list of column names to use in the SQL statement.
        $columnNames = array_keys($allergens_food[0]);

        //Loop through our $data array.
        foreach($allergens_food as $arrayIndex => $row){
            $params = array();
            foreach($row as $columnName => $columnValue){
                $param = ":" . $columnName . $arrayIndex;
                $params[] = $param;
                $toBind[$param] = $columnValue;
            }
            $rowsSQL[] = "(" . implode(", ", $params) . ")";
        }

        //Construct our SQL statement
        $sql = "INSERT INTO `food_allergen` (" . implode(", ", $columnNames) . ") VALUES " . implode(", ", $rowsSQL);

        //Prepare our PDO statement.
        $stmt = $this->db->prepare($sql);
        //Bind our values.
        foreach($toBind as $param => $val){
            $stmt->bindValue($param, $val);
        }
        //Execute our statement (i.e. insert the data).
        $stmt->execute();
    }

    /**
     * Retrieves all allergens of food
     * @param $food
     * @return array of allergens for food
     */
    public function getFoodAllergens($food){
        $stmt = $this->db->prepare("SELECT food_allergen.id_allergen, name_allergen FROM food_allergen, allergen 
                                                WHERE food_allergen.id_food = ? 
                                                AND food_allergen.id_allergen = allergen.id_allergen");
        $stmt->execute(array($food));
        $allergens_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $allergens = array();

        foreach($allergens_db as $allergen){
            array_push($allergens, array("id_allergen"=>$allergen["id_allergen"], "name_allergen"=>$allergen["name_allergen"]));
        }

        return $allergens;
    }

    /***
     * Updates foods allegerns
     * @param $food_id
     * @param $allergens_food
     */
    public function updateFoodAllergens($food_id,$allergens_food){
        // $rowsToInsert es $allergens_food
        $rowsSQL = array();

        //Will contain the values that we need to bind.
        $toBind = array();

        //Get a list of column names to use in the SQL statement.
        $columnNames = array_keys($allergens_food[0]);
        //var_dump($columnNames);

        //Loop through our $data array.
        foreach($allergens_food as $arrayIndex => $row){
            $params = array();
            foreach($row as $columnName => $columnValue){
                $param = ":" . $columnName . $arrayIndex;
                $params[] = $param;
                $toBind[$param] = $columnValue;
            }
            $rowsSQL[] = "($food_id," . implode(", ", $params) . ")";
        }
            //
            //Construct our SQL statement
            $sql = "INSERT INTO `food_allergen` (id_food," . implode(", ", $columnNames) . ") VALUES " .
                implode(", ", $rowsSQL) .
                " ON DUPLICATE KEY UPDATE id_food=VALUES(id_food), id_allergen=VALUES(id_allergen)";

            //Prepare our PDO statement.
            $stmt = $this->db->prepare($sql);
            //Bind our values.
            foreach($toBind as $param => $val){
                $stmt->bindValue($param, $val);
            }

        //Execute our statement (i.e. insert the data).
        $stmt->execute();

    }

    /**
     * Deletes the given allergens from given food
     * @param $food
     * @param $data 
     */
    public function deleteFoodAllergens($food, $data){
        $rowsSQL = array();

        $toBind = array();

        foreach($data as $arrayIndex => $row){
            $params = array();
            foreach($row as $columnName => $columnValue){
                $param = ":" . $columnName . $arrayIndex;
                $params[] = $param;
                $toBind[$param] = $columnValue;
            }
            $rowsSQL[] = "(" . implode(", ", $params) . ")";
        }

        $sql = "DELETE FROM `food_allergen` WHERE `id_food` =". $food ." AND id_allergen IN (" . implode(", ", $rowsSQL) . ")";

        $stmt = $this->db->prepare($sql);
        //Bind our values.
        foreach($toBind as $param => $val){
            $stmt->bindValue($param, $val);
        }
        //Execute our statement (i.e. insert the data).
        $stmt->execute();
    }

    /**
     * Checks if the food already has the given allergens
     * @param $foodsEvent
     * @return bool
     */
    public function existAllergensInFood($allergensFood){
        $rowsSQL = array();

        $toBind = array();

        $idsFood = array();
        foreach ($allergensFood as $allergenFood){
            array_push($idsFood, $allergenFood["id_allergen"]);
        }

        $params = array();
        foreach($allergensFood as $row => $value){

            $param = ":".$row;

            $params[] = $param;

            $toBind[$param] = $value["food"];
        }
        $rowsSQL[] = implode(" OR ", $params);

        $sql = "SELECT count(*) as count from `food_allergen` WHERE id_food = " . $allergensFood[0]["id_food"] . " AND id_allergen = (" . implode(" OR ", $rowsSQL).")";

        //print_r($sql);
        $stmt = $this->db->prepare($sql);
        //Bind our values.
        foreach($toBind as $param => $val){
            $stmt->bindValue($param, $val);
        }

        //Execute our statement (i.e. insert the data).
        echo $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if($data['count'] == 0){
            $exists = false;
        }else{
            $exists = true;
        }

        return $exists;
    }

}