<?php
// Global headers
header('Access-Control-Allow-Origin: https://alsawafm.emf-informatique.ch');
header('Access-Control-Allow-Credentials: true');
//Check if a session has been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include DB managers
include_once('workers/DBNoteManager.php');
include_once('workers/DBUserManager.php');

// Instantiate classes
$noteBD = new DBNoteManager();
$user = new DBUserManager();

// Route based on HTTP method
if (isset($_SERVER['REQUEST_METHOD'])) {
    switch ($_SERVER['REQUEST_METHOD']) {
        
        // -------------------- GET --------------------
        case 'GET':
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'getCategories':
                        echo $noteBD->getCategories();
                        break;
                    case 'getNote':
                        if (isset($_GET['pk_note'])) {
                            echo $noteBD->getSingleNote($_GET['pk_note']);
                        } else {
                            echo '<response><error>Missing pk_note</error></response>';
                        }
                        break;
                    case 'getInfos':
                        echo $user->getUserInfo();
                        break;
                    case 'isAuthenticated':
                        echo $user->isAuthenticated();
                        break;
                    default:
                        echo $noteBD->getInXML(); // Default to fetching all notes
                        break;
                }
            } else {
                echo $noteBD->getInXML(); // No action = fetch all notes
            }
            exit;

        // -------------------- POST --------------------
        case 'POST':
            if (isset($_POST['action'])) {
                switch ($_POST['action']) {
                    case 'connect':
                        echo $user->login($_POST['email'], $_POST['password']);
                        break;
                    case 'disconnect':
                        echo $user->logout();
                        break;
                    case 'incrementLike':
                        if (isset($_POST['pk_note'])) {
                            header("Content-Type: text/xml");
                            echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
                            echo "<response>";
                            echo $noteBD->incrementLike($_POST['pk_note']) ? "<status>success</status>" : "<status>error</status>";
                            echo "</response>";
                        } else {
                            echo "<response><error>Missing pk_note</error></response>";
                        }
                        break;
                    default:
                        // Handle new note creation if all params are present
                        if (isset($_POST['title'], $_POST['message'], $_POST['date'], $_POST['time'], $_POST['fk_category'])) {
                            header("Content-Type: text/xml");
                            echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
                            echo "<response><status>success</status>";
                            $pk_note = $noteBD->addNote($_POST['title'], $_POST['message'], $_POST['date'], $_POST['time'], $_POST['fk_category']);
                            echo "<pk_note>$pk_note</pk_note></response>";
                        } else {
                            echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?><response><error>Missing parameters</error></response>";
                        }
                        break;
                }
            }
            exit;

        // -------------------- PUT --------------------
        case 'PUT':
            parse_str(file_get_contents("php://input"), $vars);
            if (isset($vars['title'], $vars['message'], $vars['date'], $vars['fk_category'], $vars['fk_admin'], $vars['pk_note'])) {
                echo $noteBD->updateNote($vars['title'], $vars['message'], $vars['date'], $vars['time'], $vars['fk_category'], $vars['pk_note'], $vars['fk_admin']);
            } else {
                echo 'Paramètre pk_note, title, message, date, fk_category ou fk_admin manquant';
            }
            exit;

        // -------------------- DELETE --------------------
        case 'DELETE':
            header("Content-Type: text/xml");
            parse_str(file_get_contents("php://input"), $vars);
            echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
            if (!empty($vars['titels'])) {
                $titlesArray = explode(',', $vars['titels']);
                $result = $noteBD->deleteNote($titlesArray);
                echo "<response><status>$result</status></response>";
            } else {
                echo "<response><error>Paramètre titels manquant</error></response>";
            }
            exit;
    }
}
