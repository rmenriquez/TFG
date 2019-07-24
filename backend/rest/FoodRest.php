<?php
/**
 * Created by PhpStorm.
 * User: RaquelMarcos
 * Date: 09/01/2019
 * Time: 13:22
 */

require_once (__DIR__."/../model/User.php");
require_once (__DIR__."/../model/UserMapper.php");

require_once (__DIR__."/../model/Food.php");
require_once (__DIR__."/../model/FoodMapper.php");

require_once (__DIR__."/../model/Allergen.php");
require_once (__DIR__."/../model/AllergenMapper.php");

require_once (__DIR__."/BaseRest.php");

class FoodRest extends BaseRest{

    private $foodMapper;
    private $allergenMapper;
    private $userMapper;

    public function __construct()
    {
        parent::__construct();

        $this->foodMapper = new FoodMapper();
        $this->allergenMapper = new AllergenMapper();
        $this->userMapper = new UserMapper();
    }

    //Funciona a la perfección
    public function getFoods(){
        $currentUser = parent::authenticateUser();
        $foods = $this->foodMapper->findAll($currentUser->getIdUser());

        // json_encode Note objects.
        // since Note objects have private fields, the PHP json_encode will not
        // encode them, so we will create an intermediate array using getters and
        // encode it finally
        $foods_array = array();
        foreach($foods as $food) {
            //echo ($food->getIdFood());
            //$allergen = $this->foodMapper->getFoodAllergens($food->getIdFood());
            array_push($foods_array, array(
                "id_food" => $food->getIdFood(),
                "title" => $food->getTitle(),
                //"description" => $food->getDescription(),
                "price" => $food->getPrice(),
                "image" => $food->getImage()
            ));
        }

        header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
        header('Content-Type: application/json');
       echo(json_encode($foods_array));
    }

    public function createFood($data){
        $currentUser = parent::authenticateUser();
        $food = new Food();

        if(isset($data->title) && isset($data->description) && isset($data->price)){
            $food->setTitle($data->title);
            $food->setDescription($data->description);
            $food->setImage($data->image);
            $food->setRestaurant($currentUser->getIdUser());
            $food->setPrice($data->price);
        }

        try{
            //Validate Post object
            $food->checkIsValidForCreate();

            //Save the Post object into database
            if($this->foodMapper->foodExists($currentUser->getIdUser(), $data->title) == false){
                $foodId = $this->foodMapper->save($food);


                //response OK. Also send post in content
                header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
                header('Location: '.$_SERVER['REQUEST_URI']."/".$foodId);
                header('Content-Type: application/json');
                echo(json_encode(array(
                    "id_food"=>$foodId,
                    "title"=>$food->getTitle(),
                    "description" => $food->getDescription(),
                    "image" => $food->getImage(),
                    "restaurant" => $food->getRestaurant(),
                    "price" => $food->getPrice(),
                )));
            }else{
                header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
                header('Content-Type: application/json');
                throw new Exception('The food already exists.');
            }
        } catch (ValidationException $e){
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            header('Content-Type: application/json');

            echo(json_encode($e->getErrors()));
        }
    }

    //Funciona a la perfección
    public function viewFood($foodId){
        $currentUser = parent::authenticateUser();
        //find the Food object in the database
        $food = $this->foodMapper->findById($foodId);
        if($food == NULL){
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            echo("Food with id ".$foodId." not found");
        }
        if($food->getRestaurant() != $currentUser->getIdUser()){
            header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
            echo("You are not the authorized user for view this food");
            return;
        }

        $allergen = $this->foodMapper->getFoodAllergens($foodId);

        $food_array = array(
            "id_food" => $foodId,
            "title" => $food->getTitle(),
            "description" => $food->getDescription(),
            "image" => $food->getImage(),
            "restaurant" => $food->getRestaurant(),
            "price" => $food->getPrice(),
            "allergens" => $allergen
        );

        header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
        header('Content-Type: application/json');
        echo(json_encode($food_array));
    }

    //Funciona a la perfección
    public function updateFood($foodId, $data){
        $currentUser = parent::authenticateUser();
        //find the Food object in the database
        $food = $this->foodMapper->findById($foodId);
        if($food == NULL){
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            echo("Food with id ".$foodId." not found");
        }
        //Check if the Food author is the currentUser (in Session)
        if($food->getRestaurant() != $currentUser->getIdUser()){
            header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
            echo("You are not the authorized user for view this note");
            return;
        }
        $food->setTitle($data->title);
        $food->setDescription($data->description);
        $food->setImage($data->image);
        $food->setPrice($data->price);

        try{
            // validate Food object
            $food->checkIsValidForUpdate(); // if it fails, ValidationException
            $this->foodMapper->update($food);
            header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
        }catch (ValidationException $e){
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            header('Content-Type: application/json');
            echo(json_encode($e->getErrors()));
        }
    }

    //Funciona a la perfección
    public function deleteFood($foodId){
        $currentUser = parent::authenticateUser();
        $food = $this->foodMapper->findById($foodId);

        if($food == NULL){
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            echo("Food with id ".$foodId." not found");
            return;
        }

        if($food->getRestaurant() != $currentUser->getIdUser()){
            header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
            echo("You are not the author of this food");
            return;
        }

        $this->foodMapper->delete($food);

        header($_SERVER['SERVER_PROTOCOL'].' 204 No Content');
    }

    public function setFoodAllergens($foodId, $data){
        $currentUser = parent::authenticateUser();
        $food = $this->foodMapper->findById($foodId);

        if($food == NULL){
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            echo("Food with id ".$foodId." not found");
            return;
        }

        if($food->getRestaurant() != $currentUser->getIdUser()){
            header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
            echo("You are not the author of this food");
            return;
        }

        //var_dump($data);
        $allergens_food_array = array();
        //echo "allergens\n";
        //$allergens = explode( ',', $data->allergens);
        $allergens = array();

        for($i =0; $i< count($data->allergens); $i++){
            $allergens[$i] = intval($data->allergens[$i]);
        }
        //var_dump($allergens);

        //echo "enable?\n";
        //$allergens_enabled = explode( ',', $data->enabled);
        /*$allergens_enabled = array();
        for($i =0; $i< count($data->enabled); $i++){
            $allergens_enabled[$i] = intval($data->enabled[$i]);
        }*/
        //var_dump($allergens_enabled);

        /* && $allergens_enabled!=null*/
        if($allergens!=null){
            $i=0;
            foreach ($data->allergens as $allergen){
                array_push($allergens_food_array,
                    array('id_food'=>$foodId,
                        'id_allergen'=>$allergen)
                );
                $i++;
            }
            try{
                //Envía id_food, id_allergen
                $this->allergenMapper->addAllergenToFood($allergens_food_array);

                //response OK. Also send post in content
                header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
                //header('Location: '.$_SERVER['REQUEST_URI']."/".$foodId);
                header('Content-Type: application/json');
            }catch (ValidationException $e){
                header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
                header('Content-Type: application/json');
                echo(json_encode($e->getErrors()));
            }
        }else{
            header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
            return;
        }
    }

    public function updateFoodsAllergen($food_id,$data){

        $toAdd = array();
        $toDelete = array();


        foreach($data->toAdd as $allergenAdd){
            array_push($toAdd, array(
                "id_food" => $food_id,
                "id_allergen" => $allergenAdd
            ));
        }

        foreach($data->toDelete as $allergenDelete){
            array_push($toDelete, array(
                "id_allergen" => $allergenDelete
            ));
        }

        try{
            if($toAdd != null && $this->allergenMapper->existAllergensInFood($toAdd) == false){
                try{
                    $this->allergenMapper->addAllergenToFood($toAdd);
                    header($_SERVER['SERVER_PROTOCOL'].' 200 Created');
                    header('Content-Type: application/json');
                }catch (ValidationException $e){
                    header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
                    header('Content-Type: application/json');
                    echo(json_encode($e->getErrors()));
                }
            }
            if($data->toDelete != null){
                try{
                    $this->allergenMapper->deleteFoodAllergens($food_id,$toDelete);
                    header($_SERVER['SERVER_PROTOCOL'].' 204 No Content');
                }catch (ValidationException $e){
                    header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
                    header('Content-Type: application/json');
                    echo(json_encode($e->getErrors()));
                }
            }
        }catch(ValidationException $e){
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            header('Content-Type: application/json');
            echo(json_encode($e->getErrors()));
        }
    }


}

// URI-MAPPING for this Rest endpoint
$foodRest = new FoodRest();
URIDispatcher::getInstance()
    ->map("GET","/food", array($foodRest, "getFoods"))
    ->map("GET", "/food/$1", array($foodRest, "viewFood"))
    ->map("POST", "/food", array($foodRest, "createFood"))
    ->map("PUT", "/food/$1", array($foodRest, "updateFood"))
    ->map("POST", "/food/$1/allergen", array($foodRest, "setFoodAllergens"))
    ->map("PUT", "/food/$1/allergen", array($foodRest, "updateFoodsAllergen"))
    ->map("DELETE", "/food/$1", array($foodRest, "deleteFood"));