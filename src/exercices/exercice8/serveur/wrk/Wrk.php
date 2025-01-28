<?php
// /server/controllers/Wrk.php
require_once 'config/Database.php';
require_once 'beans/Joueur.php';
require_once 'beans/Equipe.php';

class Wrk {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->conn;
    }

    public function getTeams() {
        $query = "SELECT * FROM t_equipe";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $teams = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $teams[] = new Equipe($row['PK_equipe'], $row['Nom']);
        }
        return $teams;
    }

    public function getPlayersByTeam($teamId) {
        $query = "SELECT * FROM t_joueur WHERE FK_equipe = :teamId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':teamId', $teamId);
        $stmt->execute();
        $players = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $players[] = new Joueur($row['PK_joueur'], $row['Nom'], $row['Points'], $row['FK_equipe']);
        }
        return $players;
    }
}
?>