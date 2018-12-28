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
    //Lo óptimo sería buscar solo el nombre del alérgeno
    public function nameAllergenExists($name_allergen) {
        $stmt = $this->db->prepare("SELECT count(name_allergen) FROM allergen where name_allergen LIKE %?%");
        $stmt->execute(array($name_allergen));

        if ($stmt->fetchColumn() > 0) {
            return true;
        }
    }

    //Dado un restaurante, devolver todas las comidas que contengan el alergeno X



}