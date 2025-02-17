<?php 
	include_once('workers/DBNoteManager.php');
    
    if (isset($_SERVER['REQUEST_METHOD']))
	{
		switch ($_SERVER['REQUEST_METHOD'])
		{
			case 'GET':
				
					$noteBD = new DBNoteManager();
					echo $noteBD->GetInXML();
				break;
			case 'POST':
				if (isset($_POST['titel']) and isset($_POST['message'])and isset($_POST['date'])and isset($_POST['fk_category']))
				{
					$noteBD = new DBNoteManager();
					echo $noteBD->Add(titel: $_POST['titel'], message: $_POST['message'],date:  $_POST['date'],fk_category:  $_POST['fk_category']);
				}
				else{
					echo 'Paramètre titel, message, date ou fk_category manquant';
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
				parse_str(file_get_contents("php://input"), $vars);
				if (isset($vars['titel']))
				{
					$noteBD = new DBNoteManager();
					echo $noteBD->Delete(titel: $vars['titel']);
				}
				else{
					echo 'Paramètre titel manquant';
				}
				break;
		}
	}
?>