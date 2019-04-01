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

    private $FoodMapper;
    private $AllergenMapper;
    private $UserMapper;

    public function __construct()
    {
        parent::__construct();

        $this->FoodMapper = new FoodMapper();
        $this->AllergenMapper = new AllergenMapper();
        $this->UserMapper = new UserMapper();
    }

    //Funciona a la perfección
    public function getFoods(){
        $currentUser = parent::authenticateUser();
        $foods = $this->FoodMapper->findAll($currentUser->getIdUser());

        // json_encode Note objects.
        // since Note objects have private fields, the PHP json_encode will not
        // encode them, so we will create an intermediate array using getters and
        // encode it finally
        $foods_array = array();
        foreach($foods as $food) {
            echo ($food->getIdFood());
            $allergen = $this->FoodMapper->getFoodAllergens($food->getIdFood());
            array_push($foods_array, array(
                "id_food" => $food->getIdFood(),
                "title" => $food->getTitle(),
                "description" => $food->getDescription(),
                "image" => $food->getImage(),
                "restaurant" => $currentUser->getIdUser(),
                "price" => $food->getPrice(),
                "allergens" => $allergen
            ));
        }

        header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
        header('Content-Type: application/json');
       echo(json_encode($foods_array));
    }

    public function createFood($data){
        $currentUser = parent::authenticateUser();
        $food = new Food();
        $allergens = array();

        if(isset($data->title) && isset($data->description)){
            $food->setTitle($data->title);
            $food->setDescription($data->description);
            $food->setImage($data->image);
            $food->setRestaurant($currentUser->getIdUser());
            $food->setPrice($data->price);
            $allergens = $data->allergens;
        }

        try{
            //Validate Post object
            $food->checkIsValidForCreate();

            //Save the Post object into database
            if($this->FoodMapper->foodExists($currentUser->getIdUser(), $data->title) == false){
                echo "Food doesnt exist \n";
                $foodId = $this->FoodMapper->save($food);

                //Save the allergens of food
                $this->AllergenMapper->addAllergenToFood($allergens, $foodId);

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
                    "allergens"=> $allergens
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
        $food = $this->FoodMapper->findById($foodId);
        if($food == NULL){
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            echo("Food with id ".$foodId." not found");
        }
        if($food->getRestaurant() != $currentUser->getIdUser()){
            header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
            echo("You are not the authorized user for view this food");
            return;
        }

        $allergen = $this->FoodMapper->getFoodAllergens($foodId);

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
        $food = $this->FoodMapper->findById($foodId);
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
            // validate Note object
            $food->checkIsValidForUpdate(); // if it fails, ValidationException
            $this->FoodMapper->update($food);
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
        $food = $this->FoodMapper->findById($foodId);

        if($food == NULL){
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            echo("Food with id ".$foodId." not found");
            return;
        }

        if($food->getRestaurant() != $currentUser->getIdUser()){
            header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
            echo("you are not the author of this food");
            return;
        }

        $this->FoodMapper->delete($foodId);

        header($_SERVER['SERVER_PROTOCOL'].' 204 No Content');
    }


}

// URI-MAPPING for this Rest endpoint
$foodRest = new FoodRest();
URIDispatcher::getInstance()
    ->map("GET","/food", array($foodRest, "getFoods"))
    ->map("GET", "/food/$1", array($foodRest, "viewFood"))
    ->map("POST", "/food", array($foodRest, "createFood"))
    ->map("PUT", "/food/$1", array($foodRest, "updateFood"))
    ->map("DELETE", "food/$1", array($foodRest, "deleteFood"))
    ;