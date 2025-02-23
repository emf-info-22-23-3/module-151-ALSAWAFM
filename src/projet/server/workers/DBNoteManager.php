<?php
include_once('connexion.php');

/**
 * Classe deputesBDManager
 *
 * Cette classe permet la gestion des députés dans la base de données dans l'exercice de debbugage
 *
 * @version 1.0
 * @author Alsawaf Mazen <mazen.alsawaf@edufr.ch>
 * @project Exercice 11 - debuggage
 */
class DBNoteManager
{
	/**
	 * Retourne la liste des députés d'une langue au format XML
	 *
	 * @param $titel La langue à laquelle rechercher les députés
	 * @return liste de députés au format XML
	 */
	public function GetInXML()
	{
		// Remove the condition on 'titel' to get all records
		$query = connexion::getInstance()->SelectQuery("SELECT * FROM t_note", []);
	
		// Start the XML response with <notes>
		$result = '<notes>';
	
		// Loop through the result and construct XML for each note
		foreach ($query as $data) {
			$result .= '<note>';
			$result .= '<pk_note>' . $data['pk_note'] . '</pk_note>';
			$result .= '<titel>' . $data['titel'] . '</titel>';
			$result .= '<message>' . $data['message'] . '</message>';
			$result .= '<date>' . $data['date'] . '</date>';
			$result .= '<time>' . $data['time'] . '</time>';
			$result .= '</note>';
		}
	
		// Close the <notes> tag and return the XML
		$result .= '</notes>';
	
		return $result;
	}


	/**
 * Retrieve all categories from the database.
 * @return XML list of categories.
 */
public function GetCategories()
{
    $query = connexion::getInstance()->SelectQuery("SELECT pk_category, category_name FROM t_category", []);
    
    $result = '<categories>';
    foreach ($query as $data) {
        $result .= '<category>';
        $result .= '<pk_category>' . $data['pk_category'] . '</pk_category>'; // Fix XML field names
        $result .= '<category_name>' . $data['category_name'] . '</category_name>';
        $result .= '</category>';
    }
    $result .= '</categories>';

    return $result;
}

	

	/**
	 * Ajoute un député à la liste des députés
	 *
	 * @param $nom Le nom du député 
	 * @param $langue La langue du député
	 * @return la pk du député ajouté
	 */
	public function Add($titel, $message,$date, $time, $fk_category )
	{
		$query = "INSERT INTO t_note (titel, message , date,time,fk_category) values(:titel, :message, :date,:time, :fk_category)";
		$params = array('titel' => $titel, 'message' => $message, 'date' => $date, 'time' => $time, 'fk_category' => $fk_category);
		$res = connexion::getInstance()->ExecuteQuery($query, $params);
		return connexion::getInstance()->GetLastId('t_note');
	}

	/**
	 * Modifie un député 
	 *
	 * @param $pkDepute La PK du député à modifier
	 * @param $nom Le nom du député 
	 * @param $langue La langue du député
	 * @return 'True' si la modification a bien eu lieu, 'False' sinon
	 */
	public function Update($titel, $message,$date, $fk_category,$pk_note )
	{
		$query = "UPDATE t_note set titel = :titel, message = :message, date = :date, fk_category = :fk_category where pk_note = :pk_note";
		$params = array('titel' => $titel, 'message' => $message,'date' => $date,'fk_category' => $fk_category, 'pk_note' => $pk_note);
		$res = connexion::getInstance()->ExecuteQuery($query, $params);
		if ($res > 0) {
			return 'True';
		} else {
			return 'False';
		}
	}

	/**
	 * Supprime un député 
	 *
	 * @param $pkDepute La PK du député à supprimer
	 * @return 'True' si la suppression a bien eu lieu, 'False' sinon
	 */
	public function Delete($titels)
	{
		$placeholders = implode(',', array_fill(0, count($titels), '?')); 
    $query = "DELETE FROM t_note WHERE titel IN ($placeholders)";
    $res = connexion::getInstance()->ExecuteQuery($query, $titels);
    return $res > 0 ? 'True' : 'False';
	}

}
?>