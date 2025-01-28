<?php
// /server/controllers/Ctrl.php
require_once 'wrk/Wrk.php';

class Ctrl {
    private $wrk;

    public function __construct() {
        $this->wrk = new Wrk();
    }

    public function getTeamsXml() {
        $teams = $this->wrk->getTeams();
        $xml = new SimpleXMLElement('<equipes/>');
        foreach ($teams as $team) {
            $teamElement = $xml->addChild('equipe');
            $teamElement->addChild('id', $team->id);
            $teamElement->addChild('nom', $team->name);
        }
        return $xml->asXML();
    }

    public function getPlayersXml($teamId) {
        $players = $this->wrk->getPlayersByTeam($teamId);
        $xml = new SimpleXMLElement('<joueurs/>');
        foreach ($players as $player) {
            $playerElement = $xml->addChild('joueur');
            $playerElement->addChild('id', $player->id);
            $playerElement->addChild('nom', $player->name);
            $playerElement->addChild('points', $player->points);
        }
        return $xml->asXML();
    }
}
?>