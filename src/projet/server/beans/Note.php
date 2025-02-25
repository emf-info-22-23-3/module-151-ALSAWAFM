<?php
/**
 * Note Bean class
 */
class Note {
  // Properties
  private $titre;
  private $message;
  private $date;
  private $time;
  private $likes;
  private $fk_category;
  private $fk_admin;

  /**
   * Constructor to initialize the note
   * @param string $titre
   * @param string $message
   * @param string $date
   * @param string $time
   * @param int $likes
   * @param int $fk_category
   * @param int $fk_admin
   */
  public function __construct($titre = '', $message = '', $date = '', $time = '', $likes = 0, $fk_category = 0, $fk_admin = 0) {
    $this->titre = $titre;
    $this->message = $message;
    $this->date = $date;
    $this->time = $time;
    $this->likes = $likes;
    $this->fk_category = $fk_category;
    $this->fk_admin = $fk_admin;
  }

  /**
   * Setter for the title
   * @param string $titre
   */
  public function setTitre($titre) {
    $this->titre = $titre;
  }

  /**
   * Setter for the message
   * @param string $message
   */
  public function setMessage($message) {
    $this->message = $message;
  }

  /**
   * Setter for the date
   * @param string $date
   */
  public function setDate($date) {
    $this->date = $date;
  }

  /**
   * Setter for the time
   * @param string $time
   */
  public function setTime($time) {
    $this->time = $time;
  }

  /**
   * Setter for the number of likes
   * @param int $likes
   */
  public function setLikes($likes) {
    $this->likes = $likes;
  }

  /**
   * Setter for the category ID
   * @param int $fk_category
   */
  public function setFkCategory($fk_category) {
    $this->fk_category = $fk_category;
  }

  /**
   * Setter for the admin ID
   * @param int $fk_admin
   */
  public function setFkAdmin($fk_admin) {
    $this->fk_admin = $fk_admin;
  }

  /**
   * Getter for the title
   * @return string
   */
  public function getTitre() {
    return $this->titre;
  }

  /**
   * Getter for the message
   * @return string
   */
  public function getMessage() {
    return $this->message;
  }

  /**
   * Getter for the date
   * @return string
   */
  public function getDate() {
    return $this->date;
  }

  /**
   * Getter for the time
   * @return string
   */
  public function getTime() {
    return $this->time;
  }

  /**
   * Getter for the number of likes
   * @return int
   */
  public function getLikes() {
    return $this->likes;
  }

  /**
   * Getter for the category ID
   * @return int
   */
  public function getFkCategory() {
    return $this->fk_category;
  }

  /**
   * Getter for the admin ID
   * @return int
   */
  public function getFkAdmin() {
    return $this->fk_admin;
  }

  /**
   * Returns the note in text format (title + message + additional info)
   * @return string
   */
  public function toString() {
    return "Title: " . $this->titre . "\nMessage: " . $this->message . "\nDate: " . $this->date . "\nTime: " . $this->time . "\nLikes: " . $this->likes . "\nCategory ID: " . $this->fk_category . "\nAdmin ID: " . $this->fk_admin;
  }
}
?>
