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
        $currentUser = parent::authenticateUser();
        $allergens = $this->AllergenMapper->findAll();
    }

}