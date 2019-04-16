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
        //var_dump($data);
        $currentUser = parent::authenticateUser();
        $allergens_food_array = array();
        //echo "allergens\n";
        $allergens = explode( ',', $data->allergens);
        for($i =0; $i< count($allergens); $i++){
            $allergens[$i] = intval($allergens[$i]);
        }
        //var_dump($allergens);

        //echo "enable?\n";
        $allergens_enabled = explode( ',', $data->enabled);
        for($i =0; $i< count($allergens_enabled); $i++){
            $allergens_enabled[$i] = intval($allergens_enabled[$i]);
        }
        //var_dump($allergens_enabled);

        if(isset($allergens) && isset($allergens_enabled)){
            $i=0;
            foreach ($allergens as $allergen){
                array_push($allergens_food_array,
                    array('id_food'=>$this->FoodMapper->getMaximumId($currentUser->getIdUser()),
                        'id_allergen'=>$allergen, 'enabled'=>$allergens_enabled[$i])
                );
                $i++;
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


    public function updateFoodsAllergen($food_id,$data){
        $currentUser = parent::authenticateUser();
        //var_dump($data);
        $allergens_db = $this->AllergenMapper->getFoodAllergens($food_id);
        $update = array();
        $insert = array();

        $enabled = explode(',', $data->enabled);
        $allergens = explode( ',', $data->allergens);
        for($i =0; $i< count($allergens); $i++){
            $allergens[$i] = intval($allergens[$i]);
        }

        $allergens_array = array();
        for($i=0; $i < count($allergens); $i++){
            array_push($allergens_array, array(
                'id_food'=>$food_id,
                'id_allergen'=>$allergens[$i],
                'enabled'=> $enabled[$i]
            ));
        }
        //echo "Imprimo allergens_array\n";
        //var_dump($allergens_array);

        //echo "Imprimo allergens_bd\n";
        //var_dump($allergens_db);

        if (isset($data->allergens)) {
            foreach ($allergens_db as $allergen){
                foreach ($allergens_array as $toUpdate){
                    if($allergen['id_allergen'] == $toUpdate['id_allergen']
                        && $allergen['enabled'] != $toUpdate['enabled']){
                        //echo "Actualizamos los alergenos\n";
                        array_push($update, array(
                            //'id_food'=> $toUpdate['id_food'],
                            'id_allergen'=> $toUpdate['id_allergen'],
                            'enabled'=>$toUpdate['enabled']));
                    }
                    if($toUpdate['id_allergen'] != $allergen['id_allergen']){
          //              echo "Añadimos los alergenos\n";
                        array_push($insert, array(
                            'id_food'=> $toUpdate['id_food'],
                            'id_allergen'=> $toUpdate['id_allergen'],
                            'enabled'=>$toUpdate['enabled']
                        ));
                    }
                }
            }
        }

        try{
            if(count($update) > 0){
                $this->AllergenMapper->updateFoodAllergens($food_id,$update);
            }
            if(count($insert) >0){
                $this->AllergenMapper->addAllergenToFood($insert);
            }
            //response OK. Also send post in content
            header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
            //header('Location: '.$_SERVER['REQUEST_URI']."/".$foodId);
            header('Content-Type: application/json');
        }catch(ValidationException $e){
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            header('Content-Type: application/json');
            echo(json_encode($e->getErrors()));
        }
    }

}

// URI-MAPPING for this Rest endpoint
$allergenRest = new AllergenRest();
URIDispatcher::getInstance()
    ->map("GET","/allergen", array($allergenRest, "getAllergens"))
    ->map("POST", "/allergen", array($allergenRest, "setAllergenToFood"))
    ->map("PUT", "/allergen/$1", array($allergenRest, "updateFoodsAllergen"))
;