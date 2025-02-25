<?php
/**
 * Category Bean class
 */
class Category {
  // Properties
  private $type;
  private $category_name;
  private $description;

  /**
   * Constructor to initialize the category
   * @param string $type
   * @param string $category_name
   * @param string $description
   */
  public function __construct($type = '', $category_name = '', $description = '') {
    $this->type = $type;
    $this->category_name = $category_name;
    $this->description = $description;
  }

  /**
   * Setter for the category type
   * @param string $type
   */
  public function setType($type) {
    $this->type = $type;
  }

  /**
   * Setter for the category name
   * @param string $category_name
   */
  public function setCategoryName($category_name) {
    $this->category_name = $category_name;
  }

  /**
   * Setter for the category description
   * @param string $description
   */
  public function setDescription($description) {
    $this->description = $description;
  }

  /**
   * Getter for the category type
   * @return string
   */
  public function getType() {
    return $this->type;
  }

  /**
   * Getter for the category name
   * @return string
   */
  public function getCategoryName() {
    return $this->category_name;
  }

  /**
   * Getter for the category description
   * @return string
   */
  public function getDescription() {
    return $this->description;
  }

  /**
   * Returns the category in text format (type + name + description)
   * @return string
   */
  public function toString() {
    return "Type: " . $this->type . "\nCategory Name: " . $this->category_name . "\nDescription: " . $this->description;
  }
}
?>
