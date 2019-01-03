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
            $errors["title"] = "title is mandatory";
        }
        if (strlen(trim($this->description)) == 0 ) {
            $errors["description"] = "content is mandatory";
        }
        //Como comprobar que sea el restaurante logueado
        if ($this->restaurant == NULL ) {
            $errors["restaurant"] = "restaurant is mandatory";
        }

        if (sizeof($errors) > 0){
            throw new ValidationException($errors, "note is not valid");
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
