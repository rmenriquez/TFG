<?php
/**
 * Created by PhpStorm.
 * User: RaquelMarcos
 * Date: 26/12/2018
 * Time: 11:18
 */

class Allergen
{

    /**
     * The id of the allergen
     * @var int
     */
    private $id_allergen;

    /**
     * The name and description of the allergen
     * @var string
     */
    private $name_allergen;

    /**
     * Allergen constructor.
     * @param int $id_allergen
     * @param string $name_allergen
     */
    public function __construct($id_allergen=NULL, $name_allergen=NULL)
    {
        $this->id_allergen = $id_allergen;
        $this->name_allergen = $name_allergen;
    }

    /**
     * @return int
     */
    public function getIdAllergen()
    {
        return $this->id_allergen;
    }

    /**
     * @param int $id_allergen
     */
    public function setIdAllergen($id_allergen)
    {
        $this->id_allergen = $id_allergen;
    }

    /**
     * @return string
     */
    public function getNameAllergen()
    {
        return $this->name_allergen;
    }

    /**
     * @param string $name_allergen
     */
    public function setNameAllergen($name_allergen)
    {
        $this->name_allergen = $name_allergen;
    }


}