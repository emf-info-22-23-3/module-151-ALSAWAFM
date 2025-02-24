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
	 * @param $title La langue à laquelle rechercher les députés
	 * @return liste de députés au format XML
	 */
	public function GetInXML()
	{
		// Remove the condition on 'title' to get all records
		$query = connexion::getInstance()->SelectQuery("SELECT * FROM t_note", []);
		
		// Start the XML response with <notes>
		$result = '<notes>';
		
		// Loop through the result and construct XML for each note
		foreach ($query as $data) {
			$result .= '<note>';
			$result .= '<pk_note>' . $data['pk_note'] . '</pk_note>';
			$result .= '<title>' . htmlspecialchars($data['title'], ENT_QUOTES, 'UTF-8') . '</title>';
			$result .= '<message>' . htmlspecialchars($data['message'], ENT_NOQUOTES, 'UTF-8') . '</message>';  // Don't escape message content
			$result .= '<date>' . $data['date'] . '</date>';
			$result .= '<time>' . $data['time'] . '</time>';
			$result .= '<likes>' . $data['likes'] . '</likes>'; // Add likes count
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
	public function Add($title, $message,$date, $time, $fk_category )
	{
		$query = "INSERT INTO t_note (title, message , date,time,fk_category) values(:title, :message, :date,:time, :fk_category)";
		$params = array('title' => $title, 'message' => $message, 'date' => $date, 'time' => $time, 'fk_category' => $fk_category);
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
	public function Update($title, $message,$date,$time, $fk_category,$pk_note )
	{
		$query = "UPDATE t_note set title = :title, message = :message, date = :date,  time = :time, fk_category = :fk_category where pk_note = :pk_note";
		$params = array('title' => $title, 'message' => $message,'date' => $date,'time' => $time,'fk_category' => $fk_category, 'pk_note' => $pk_note);
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
    $query = "DELETE FROM t_note WHERE title IN ($placeholders)";

    file_put_contents("delete_log.txt", "Query: $query\nParams: " . print_r($titels, true) . "\n", FILE_APPEND);

    $res = connexion::getInstance()->ExecuteQuery($query, $titels);
    
    return $res > 0 ? 'True' : 'False';
}


public function GetSingleNote($pk_note)
{
    $query = connexion::getInstance()->SelectQuery("SELECT * FROM t_note WHERE pk_note = ?", [$pk_note]);

    if (empty($query)) {
        return "<note><error>Note not found</error></note>";
    }

    // Ensure that the data is escaped when returning to prevent XSS
    $result = '<note>';
    $result .= '<pk_note>' . $query[0]['pk_note'] . '</pk_note>';
    $result .= '<title>' . htmlspecialchars($query[0]['title'], ENT_QUOTES, 'UTF-8') . '</title>';
    $result .= '<message>' . htmlspecialchars($query[0]['message'], ENT_NOQUOTES, 'UTF-8') . '</message>'; // Don't escape message content
    $result .= '<fk_category>' . $query[0]['fk_category'] . '</fk_category>';
    $result .= '</note>';

    return $result;
}


public function IncrementLike($pk_note)
{
    // Query to increment the like count for the note
    $query = "UPDATE t_note SET likes = likes + 1 WHERE pk_note = :pk_note";
    $params = array('pk_note' => $pk_note);
    $res = connexion::getInstance()->ExecuteQuery($query, $params);
    return $res > 0;
}


}
?>