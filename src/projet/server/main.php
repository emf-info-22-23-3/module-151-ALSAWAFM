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
				if (isset($_POST['title']) and isset($_POST['message'])and isset($_POST['date'])and isset($_POST['time'])and isset($_POST['fk_category']))
				{
					$noteBD = new DBNoteManager();
					header("Content-Type: text/xml");
					echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
					echo "<response>";
					echo "<status>success</status>";
					echo "<pk_note>" . $noteBD->Add($_POST['title'], $_POST['message'], $_POST['date'], $_POST['time'], $_POST['fk_category']) . "</pk_note>";
					echo "</response>";
									}
				else{
					echo 'Paramètre title, message, date, time ou fk_category manquant';
				}
				break;
			case 'PUT':
				parse_str(file_get_contents("php://input"), $vars);
				if (isset($vars['title']) and isset($vars['message'])and isset($vars['date'])and isset($vars['fk_category']))
				{
					$noteBD = new DBNoteManager();
					echo $noteBD->Update($vars['title'], $vars['message'], $vars['date'], $vars['fk_category'], pk_note: $vars['pk_note']);
				}
				else{
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
	}}
?>