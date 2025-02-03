<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	if($_POST['action'] == "connect")
	{

		// doit controller que le password donné (en post) est bien 'emf'
		// si c'est ok => enregistrement de "emf" dans une variable de session et écrire '<result>true</result>'
		// si c'est faux => effacer la variable de session et écrire '<result>false</result>'
		
		//echo '<result>false</result>';

		  // Check if the posted password is "emf"
		  if ($_POST['password'] == 'emf') {
            // Store "emf" in the session variable 'logged'
            $_SESSION['logged'] = 'emf';
            echo '<result>true</result>';
        } else {
            // If the password is wrong, clear the session variable and return false
            unset($_SESSION['logged']);
            echo '<result>false</result>';
        }



	}

	if($_POST['action'] == "disconnect")
	{
		// effacer la variable de session et écrire <result>true</result>
		unset($_SESSION['logged']);
		session_destroy(); 
		echo '<result>true</result>';
	}
}

if ($_SERVER['REQUEST_METHOD'] == 'GET'){
	if($_GET['action'] == "getInfos")
	{
		//session_start();
		// tout d'abord contrôler que la variable de session 'logged' contient bien "emf"
		// si oui => retourner <users><user><name>Victor Legros</name><salaire>9876</salaire></user><user><name>Marinette Lachance</name><salaire>7540</salaire></user><user><name>Gustave Latuile</name><salaire>4369</salaire></user><user><name>Basile Ledisciple</name><salaire>2384</salaire></user></users>
		// si non => écrire <message>DROITS INSUFFISANTS</message>
		
		//echo '<message>DROITS INSUFFISANTS</message>';




		if (isset($_SESSION['logged']) && $_SESSION['logged'] == 'emf') {
            // If the user is logged in, return the list of users and salaries
            echo '<users>';
            echo '<user><name>Victor Legros</name><salaire>9876</salaire></user>';
            echo '<user><name>Marinette Lachance</name><salaire>7540</salaire></user>';
            echo '<user><name>Gustave Latuile</name><salaire>4369</salaire></user>';
            echo '<user><name>Basile Ledisciple</name><salaire>2384</salaire></user>';
            echo '</users>';
        } else {
            // If the user is not logged in, return the error message
            echo '<message>DROITS INSUFFISANTS</message>';
        }
	}
}

?>