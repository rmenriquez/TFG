<?php
/**
 * Created by PhpStorm.
 * User: RaquelMarcos
 * Date: 14/03/2019
 * Time: 10:50
 */

require_once (__DIR__."/../model/User.php");
require_once (__DIR__."/../model/UserMapper.php");

require_once (__DIR__."/../model/Food.php");
require_once (__DIR__."/../model/FoodMapper.php");

require_once (__DIR__."/../model/Allergen.php");
require_once (__DIR__."/../model/AllergenMapper.php");

require_once (__DIR__."/BaseRest.php");


class AllergenRest extends BaseRest
{
    private $AllergenMapper;
    private $FoodMapper;
    private $UserMapper;


    public function __construct(){
        parent::__construct();

        $this->FoodMapper = new FoodMapper();
        $this->UserMapper = new UserMapper();
        $this->AllergenMapper = new AllergenMapper();
    }

    public function getAllergens(){
        $allergens = $this->AllergenMapper->findAll();

        //print_r($allergens);

        $allergens_array = array();
        foreach($allergens as $allergen){
            array_push($allergens_array,array(
                "id_allergen" => $allergen['id_allergen'],
                "name_allergen" => $allergen['name_allergen']
            ));
        }

        header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
        header('Content-Type: application/json');
        echo(json_encode($allergens_array));
    }


    public function setAllergenToFood($data){
        $currentUser = parent::authenticateUser();
        $allergens_food_array = array();
        //var_dump($data->allergens);
        $allergens = explode( ',', $data->allergens);
        for($i =0; $i< count($allergens); $i++){
            $allergens[$i] = intval($allergens[$i]);
        }
        //var_dump($allergens);
        if(isset($allergens)){
            foreach ($allergens as $allergen){
                array_push($allergens_food_array,
                    array('id_food'=>$this->FoodMapper->getMaximumId($currentUser->getIdUser()),
                        'id_allergen'=>$allergen)
                );
            }
        }
        //var_dump($allergens_food_array);
        try{
            //Envía id_food, id_allergen
            $this->AllergenMapper->addAllergenToFood($allergens_food_array);

            //response OK. Also send post in content
            header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
            //header('Location: '.$_SERVER['REQUEST_URI']."/".$foodId);
            header('Content-Type: application/json');
        }catch (ValidationException $e){
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            header('Content-Type: application/json');
            echo(json_encode($e->getErrors()));
        }
    }


    public function updateFoodsAllergen($data){
        $currentUser = parent::authenticateUser();
        $allergens = $this->AllergenMapper->getFoodAllergens($data->id_food);
    }

}

// URI-MAPPING for this Rest endpoint
$allergenRest = new AllergenRest();
URIDispatcher::getInstance()
    ->map("GET","/allergen", array($allergenRest, "getAllergens"))
    ->map("POST", "/allergen", array($allergenRest, "setAllergenToFood"))
;