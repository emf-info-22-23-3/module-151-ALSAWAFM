<?php
// /s/Player.php
class Joueur {
    public $id;
    public $name;
    public $points;
    public $team_id;

    public function __construct($id, $name, $points, $team_id) {
        $this->id = $id;
        $this->name = $name;
        $this->points = $points;
        $this->team_id = $team_id;
    }
}
?>