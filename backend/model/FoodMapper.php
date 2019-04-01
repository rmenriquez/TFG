<?php
/**
 * Created by PhpStorm.
 * User: RaquelMarcos
 * Date: 24/12/2018
 * Time: 12:16
 */

require_once ("../core/PDOConnection.php");

class FoodMapper
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
     * Retrieves all foods
     */
    public function findAll($restaurant){
        $stmt = $this->db->prepare("SELECT id_food, title, description, image, price
                                                FROM food, user WHERE restaurant = ? AND id_user = restaurant");
        $stmt->execute(array($restaurant));
        print_r($stmt);
        $foods_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

        print_r($foods_db);

        $foods = array();

        foreach ($foods_db as $food){
            array_push($foods, new Food($food["id_food"], $food["title"], $food["description"]));
        }
        return $foods;
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
            array_push($allergens, $allergen["name_allergen"]);
        }

        return $allergens;
    }

    /**
     * @param $title
     * @return Food|null
     */
    public function findByTitle($title){
        $stmt = $this->db->prepare("SELECT id_food, title, description, image, price, restaurant 
                                                FROM food, user WHERE food.restaurant = user.id_user 
                                                AND title LIKE '%?%'");
        $stmt->execute(array($title));
        $food = $stmt->fetch(PDO::FETCH_ASSOC);

        if($food != null){
            return new Food(
                $food["id_food"],
                $food["title"],
                $food["description"],
                $food["image"],
                $food["price"],
                $food["restaurant"]
            );
        }else{
            return NULL;
        }
    }

    /**
     * Saves a Food into the database
     *
     * @param Food $food
     */
    public function save(Food $food){
        $stmt = $this->db->prepare("INSERT INTO food(id_food, title, description, image, price, restaurant)
                                      values (0,?,?,?,?, ?)");
        $stmt->execute(array($food->getTitle(), $food->getDescription(),$food->getImage(), $food->getPrice(), $food->getRestaurant()));
    }

    /**
     * Updates a Food in the database
     *
     * @param Food $food
     */
    public function update(Food $food){
        $stmt = $this->db->prepare("UPDATE food set title=?, description=?, image=?, price=? WHERE id_food=?");
        $stmt->execute(array($food->getTitle(), $food->getDescription(), $food->getImage(), $food->getPrice(), $food->getIdFood()));
    }

    /**
     * Deletes a Food into the database
     *
     * @param Food $food
     */
    public function delete(Food $food){
        $stmt = $this->db->prepare("DELETE FROM food WHERE id_food = ?");
        $stmt->execute(array($food->getIdFood()));
    }


    public function findById($idFood){
        $stmt = $this->db->prepare("SELECT food.id_food, food.title, food.description, food.image, 
                                      food.restaurant, food.price 
                                      FROM food, user 
                                      WHERE food.id_food =? AND food.restaurant = user.id_user");
        $stmt->execute(array($idFood));
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if($event != null) {
            return new Food(
                $event["id_food"],
                $event["title"],
                $event["description"],
                $event["image"],
                $event["restaurant"],
                $event["price"]);
        } else {
            return NULL;
        }
    }

    /**
     * Check if the food exists for the restaurant logged in
     * @param $restaurant
     * @param $foodName
     */
    public function foodExists($restaurant, $foodName){
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM food WHERE title = ? AND restaurant = ?");
        $stmt->execute(array($foodName, $restaurant));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "\nprint_r de data[count]:\n";
        print_r($data['count']);
        echo "\n";


        if($data['count'] == 0){
            $exists = false;
            echo "exists data == 0 =>";
            print_r($exists);
            echo "\n";
        }else{
            echo "exists data else ";
            $exists = true;
            print_r($exists);
            echo "\n";
        }
        echo "\nprint_r de exists\n";
        print_r($exists);
        echo "\n";
        return $exists;
    }

}