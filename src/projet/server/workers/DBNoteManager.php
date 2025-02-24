<?php
include_once('connexion.php');

/**
 * Classe DBNoteManager
 *
 * Gère les notes de manière sécurisée en utilisant des requêtes préparées.
 */
class DBNoteManager
{
    /**
     * Retourne la liste des notes au format XML.
     * Sécurise les sorties en échappant les données.
     */
    public function GetInXML()
    {
        $query = connexion::getInstance()->SelectQuery("SELECT * FROM t_note", []);

        $result = '<notes>';
        foreach ($query as $data) {
            $result .= '<note>';
            $result .= '<pk_note>' . htmlspecialchars($data['pk_note'], ENT_QUOTES, 'UTF-8') . '</pk_note>';
            $result .= '<title>' . htmlspecialchars($data['title'], ENT_QUOTES, 'UTF-8') . '</title>';
            $result .= '<message>' . htmlspecialchars($data['message'], ENT_NOQUOTES, 'UTF-8') . '</message>';
            $result .= '<date>' . htmlspecialchars($data['date'], ENT_QUOTES, 'UTF-8') . '</date>';
            $result .= '<time>' . htmlspecialchars($data['time'], ENT_QUOTES, 'UTF-8') . '</time>';
            $result .= '<likes>' . intval($data['likes']) . '</likes>';
            $result .= '</note>';
        }
        $result .= '</notes>';

        return $result;
    }

    /**
     * Retourne toutes les catégories en XML.
     */
    public function GetCategories()
    {
        $query = connexion::getInstance()->SelectQuery("SELECT pk_category, category_name FROM t_category", []);

        $result = '<categories>';
        foreach ($query as $data) {
            $result .= '<category>';
            $result .= '<pk_category>' . htmlspecialchars($data['pk_category'], ENT_QUOTES, 'UTF-8') . '</pk_category>';
            $result .= '<category_name>' . htmlspecialchars($data['category_name'], ENT_QUOTES, 'UTF-8') . '</category_name>';
            $result .= '</category>';
        }
        $result .= '</categories>';

        return $result;
    }

    /**
     * Ajoute une note dans la base de données.
     */
    public function Add($title, $message, $date, $time, $fk_category)
    {
        $query = "INSERT INTO t_note (title, message, date, time, fk_category) 
                  VALUES (:title, :message, :date, :time, :fk_category)";
        $params = [
            'title' => $title,
            'message' => $message,
            'date' => $date,
            'time' => $time,
            'fk_category' => $fk_category
        ];

        connexion::getInstance()->ExecuteQuery($query, $params);
        return connexion::getInstance()->GetLastId('t_note');
    }

    /**
     * Modifie une note existante de manière sécurisée.
     */
    public function Update($title, $message, $date, $time, $fk_category, $pk_note, $fk_admin)
{
    $query = "UPDATE t_note 
              SET title = :title, message = :message, date = :date, time = :time, fk_category = :fk_category, fk_admin = :fk_admin  
              WHERE pk_note = :pk_note";
    $params = [
        'title' => $title,
        'message' => $message,
        'date' => $date,
        'time' => $time,
        'fk_category' => $fk_category,
        'pk_note' => $pk_note,
		'fk_admin' => $fk_admin

    ];

    $res = connexion::getInstance()->ExecuteQuery($query, $params);

    header("Content-Type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
    echo "<response>";
    echo "<result>" . ($res > 0 ? "true" : "false") . "</result>";
    echo "</response>";


	
}


    /**
     * Supprime une ou plusieurs notes en fonction des titres.
     */
    public function Delete($titles)
    {
        if (empty($titles)) {
            return 'False';
        }

        $placeholders = implode(',', array_fill(0, count($titles), '?'));
        $query = "DELETE FROM t_note WHERE title IN ($placeholders)";

        file_put_contents("delete_log.txt", "Query: $query\nParams: " . print_r($titles, true) . "\n", FILE_APPEND);

        $res = connexion::getInstance()->ExecuteQuery($query, $titles);
        return $res > 0 ? 'True' : 'False';
    }

    /**
     * Récupère une note spécifique de manière sécurisée.
     */
    public function GetSingleNote($pk_note)
    {
        $query = connexion::getInstance()->SelectQuery("SELECT * FROM t_note WHERE pk_note = ?", [$pk_note]);

        if (empty($query)) {
            return "<note><error>Note not found</error></note>";
        }

        $data = $query[0];
        $result = '<note>';
        $result .= '<pk_note>' . htmlspecialchars($data['pk_note'], ENT_QUOTES, 'UTF-8') . '</pk_note>';
        $result .= '<title>' . htmlspecialchars($data['title'], ENT_QUOTES, 'UTF-8') . '</title>';
        $result .= '<message>' . htmlspecialchars($data['message'], ENT_NOQUOTES, 'UTF-8') . '</message>';
        $result .= '<fk_category>' . htmlspecialchars($data['fk_category'], ENT_QUOTES, 'UTF-8') . '</fk_category>';
        $result .= '</note>';

        return $result;
    }

    /**
     * Incrémente les "likes" pour une note donnée.
     */
    public function IncrementLike($pk_note)
    {
        $query = "UPDATE t_note SET likes = likes + 1 WHERE pk_note = :pk_note";
        $params = ['pk_note' => $pk_note];

        $res = connexion::getInstance()->ExecuteQuery($query, $params);
        return $res > 0;
    }
}
?>
