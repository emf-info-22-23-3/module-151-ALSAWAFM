<?php
include_once('workers/DBNoteManager.php');

if (isset($_SERVER['REQUEST_METHOD'])) {
	switch ($_SERVER['REQUEST_METHOD']) {
		case 'GET':
			$noteBD = new DBNoteManager();
			if (isset($_GET['action']) && $_GET['action'] == 'getCategories') {
				echo $noteBD->GetCategories();
			} elseif (isset($_GET['action']) && $_GET['action'] == 'getNote' && isset($_GET['pk_note'])) {
				echo $noteBD->GetSingleNote($_GET['pk_note']); // New function to return only one note
			} else {
				echo $noteBD->GetInXML();
			}
			exit;
			break;
		case 'POST':
			// If the action is to increment the like count
			if (isset($_POST['action']) && $_POST['action'] == 'incrementLike' && isset($_POST['pk_note'])) {
				$noteBD = new DBNoteManager();
				header("Content-Type: text/xml");
				echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
				echo "<response>";

				// Call the function to increment the like count
				$result = $noteBD->IncrementLike($_POST['pk_note']);

				if ($result) {
					echo "<status>success</status>";
				} else {
					echo "<status>error</status>";
				}

				echo "</response>";
				exit;
			}
			// If the action is to add a new note
			else if (isset($_POST['title']) && isset($_POST['message']) && isset($_POST['date']) && isset($_POST['time']) && isset($_POST['fk_category'])) {
				$noteBD = new DBNoteManager();
				header("Content-Type: text/xml");
				echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
				echo "<response>";
				echo "<status>success</status>";
				// Add the new note and get its ID
				$pk_note = $noteBD->Add($_POST['title'], $_POST['message'], $_POST['date'], $_POST['time'], $_POST['fk_category']);
				echo "<pk_note>" . $pk_note . "</pk_note>";
				echo "</response>";
				exit;
			} else {
				echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
				echo "<response><error>Missing required parameters</error></response>";
				exit;
			}
			break;
		case 'PUT':
			parse_str(file_get_contents("php://input"), $vars);
			if (isset($vars['title']) and isset($vars['message']) and isset($vars['date']) and isset($vars['fk_category'])) {
				$noteBD = new DBNoteManager();
				echo $noteBD->Update($vars['title'], $vars['message'], $vars['date'], $vars['time'], $vars['fk_category'], $vars['pk_note']);
			} else {
				echo 'Paramètre pk_note, title, message, date ou fk_category manquant';
			}
			break;
		case 'DELETE':
			header("Content-Type: text/xml");
			parse_str(file_get_contents("php://input"), $vars);

			echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";

			if (!empty($vars['titels'])) {
				$noteBD = new DBNoteManager();
				$titlesArray = explode(',', $vars['titels']); // Convert CSV string into an array
				$result = $noteBD->Delete($titlesArray);

				echo "<response>";
				echo "<status>" . $result . "</status>";
				echo "</response>";
			} else {
				echo "<response><error>Paramètre titels manquant</error></response>";
			}
			break;
	}
}
?>