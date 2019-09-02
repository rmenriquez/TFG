<?php
class Food{

    /**
     * The id of the note
     * @var int
     */
	private $id_food;

    /**
     * The title of the note
     * @var string
     */
	private $title;

    /**
     * The content of the food
     * @var string
     */
	private $description;

    /**
     * The image of the food
     * @var string
     */
	private $image;

    /**
     * The author of the food
     * @var int
     */
	private $restaurant;

    /**
     * The price of the food
     * @var double
     */
    private $price;

    /**
     * Note constructor.
     * @param int $id_food
     * @param string $title
     * @param string $description
     * @param string $image
     * @param int $restaurant
     * @param double $price
     */
    public function __construct($id_food=NULL, $title=NULL, $description=NULL, $image=NULL, $restaurant=NULL, $price=NULL)
    {
        $this->id_food = $id_food;
        $this->title = $title;
        $this->description = $description;
        $this->image = $image;
        $this->restaurant = $restaurant;
        $this->price = $price;
    }

    /**
     * @return int
     */
    public function getIdFood()
    {
        return $this->id_food;
    }

    /**
     * @param int $id_food
     */
    public function setIdFood($id_food)
    {
        $this->id_food = $id_food;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return int
     */
    public function getRestaurant()
    {
        return $this->restaurant;
    }

    /**
     * @param int $restaurant
     */
    public function setRestaurant($restaurant)
    {
        $this->restaurant = $restaurant;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }


    public function checkIsValidForCreate() {
        $errors = array();
        if (strlen(trim($this->title)) == 0 ) {
            $errors["title"] = "Title is mandatory";
        }
        if(strlen($this->title) > 255){
            $errors["title"] = "Title is too large. Máx 255 characters";
        }
        if (strlen(trim($this->description)) == 0 ) {
            $errors["description"] = "Content is mandatory";
        }

        if ($this->restaurant == NULL ) {
            $errors["restaurant"] = "Restaurant is mandatory";
        }
        if($this->price == null || $this->price == 0){
            $errors["price"] = "Price is mandatory";
        }
        if($this->price >= 10000){
            $errors["price"] = "Price is too big. Máx 999,99";
        }

        if (sizeof($errors) > 0){
            throw new ValidationException($errors, "food is not valid");
        }
    }

    /**
     * Checks if the current instance is valid
     * for being updated in the database.
     *
     * @throws ValidationException if the instance is
     * not valid
     *
     * @return void
     */
    public function checkIsValidForUpdate() {
        $errors = array();

        if (!isset($this->id_food)) {
            $errors["id_food"] = "id_food is mandatory";
        }

        try{
            $this->checkIsValidForCreate();
        }catch(ValidationException $ex) {
            foreach ($ex->getErrors() as $key=>$error) {
                $errors[$key] = $error;
            }
        }
        if (sizeof($errors) > 0) {
            throw new ValidationException($errors, "food is not valid");
        }
    }


}
