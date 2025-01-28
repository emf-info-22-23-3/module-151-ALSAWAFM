// /server/joueurs.php
require_once 'controllers/Ctrl.php';

$ctrl = new Ctrl();

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'equipe') {
        echo $ctrl->getTeamsXml();  // Fetch and display teams as XML
    }
    if ($_GET['action'] == 'joueur' && isset($_GET['equipeId'])) {
        echo $ctrl->getPlayersXml($_GET['equipeId']);  // Fetch players by team ID and display as XML
    }
}
