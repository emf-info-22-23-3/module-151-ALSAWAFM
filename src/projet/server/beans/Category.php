<?php
/**
 * Category Bean class
 */
class Category {
  // Properties
  private $type;
  private $category_name;

  /**
   * Constructor to initialize the category
   * @param string $type
   * @param string $category_name
   */
  public function __construct($type = '', $category_name = '') {
    $this->type = $type;
    $this->category_name = $category_name;
  }

  /**
   * Setter for the category type
   * @param string $type
   */
  public function setType($type) {
    $this->type = $type;
  }

  /**
   * Setter for the category description
   * @param string $category_name
   */
  public function setDescription($category_name) {
    $this->category_name = $category_name;
  }

  /**
   * Getter for the category type
   * @return string
   */
  public function getCategory_name() {
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
   * Returns the category in text format (type + description)
   * @return string
   */
  public function toString() {
    return "Type: " . $this->type . "\nDescription: " . $this->category_name;
  }
}
?>
