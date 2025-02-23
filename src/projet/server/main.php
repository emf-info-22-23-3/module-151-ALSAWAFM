<?php 
	include_once('workers/DBNoteManager.php');
    
    if (isset($_SERVER['REQUEST_METHOD']))
	{
		switch ($_SERVER['REQUEST_METHOD'])
		{
			case 'GET':
				if (isset($_GET['action']) && $_GET['action'] == 'getCategories') {
					$noteBD = new DBNoteManager();
					echo $noteBD->GetCategories();
					exit; // Ensure the script stops execution
				} else {
					$noteBD = new DBNoteManager();
					echo $noteBD->GetInXML();
					exit;
				}
				break;
			case 'POST':
				if (isset($_POST['titel']) and isset($_POST['message'])and isset($_POST['date'])and isset($_POST['time'])and isset($_POST['fk_category']))
				{
					$noteBD = new DBNoteManager();
					header("Content-Type: text/xml");
					echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
					echo "<response>";
					echo "<status>success</status>";
					echo "<pk_note>" . $noteBD->Add($_POST['titel'], $_POST['message'], $_POST['date'], $_POST['time'], $_POST['fk_category']) . "</pk_note>";
					echo "</response>";
									}
				else{
					echo 'Paramètre titel, message, date, time ou fk_category manquant';
				}
				break;
			case 'PUT':
				parse_str(file_get_contents("php://input"), $vars);
				if (isset($vars['titel']) and isset($vars['message'])and isset($vars['date'])and isset($vars['fk_category']))
				{
					$noteBD = new DBNoteManager();
					echo $noteBD->Update($vars['titel'], $vars['message'], $vars['date'], $vars['fk_category'], pk_note: $vars['pk_note']);
				}
				else{
					echo 'Paramètre pk_note, titel, message, date ou fk_category manquant';
				}
				break;
			case 'DELETE':
				case 'DELETE':
					header("Content-Type: text/xml");
					parse_str(file_get_contents("php://input"), $vars);
					echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
					if (isset($vars['titel'])) {
						$noteBD = new DBNoteManager();
						$result = $noteBD->Delete(titel: $vars['titel']);
						echo "<response>";
						echo "<status>" . $result . "</status>";
						echo "</response>";
					} else {
						echo "<response><error>Paramètre titel manquant</error></response>";
					}
					break;	
	}}
?>