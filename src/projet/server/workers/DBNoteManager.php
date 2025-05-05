<?php
include_once('Connexion.php');
include_once('beans/Note.php');
include_once('beans/Category.php');


/**
 * Classe DBNoteManager
 *
 * Gère les notes de manière sécurisée en utilisant des requêtes préparées.
 */
class DBNoteManager
{
    /**
     * Retourne la liste des notes au format XML.
     *
     * Cette méthode exécute une requête pour récupérer toutes les notes et les retourne sous forme d'un fichier XML.
     * 
     * @return string Le contenu XML représentant toutes les notes.
     */
    public function getInXML()
    {
        $query = connexion::getInstance()->SelectQuery("SELECT * FROM t_note", []);

        $result = '<notes>';
        foreach ($query as $data) {
            $result .= '<note>';
            $result .= '<pk_note>' . htmlspecialchars($data['pk_note'], ENT_QUOTES, 'UTF-8') . '</pk_note>';
            $result .= '<title>' . htmlspecialchars($data['title'], ENT_QUOTES, 'UTF-8') . '</title>';
            $result .= '<message>' . htmlspecialchars($data['message'], ENT_QUOTES, 'UTF-8') . '</message>';
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
     *
     * Cette méthode exécute une requête pour récupérer toutes les catégories et les retourne sous forme d'un fichier XML.
     * 
     * @return string Le contenu XML représentant toutes les catégories.
     */
    public function getCategories()
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
     *
     * Cette méthode permet d'ajouter une nouvelle note dans la base de données en utilisant des requêtes préparées.
     * 
     * @param string $title Le titre de la note.
     * @param string $message Le message de la note.
     * @param string $date La date de la note.
     * @param string $time L'heure de la note.
     * @param int $fk_category L'identifiant de la catégorie associée à la note.
     * 
     * @return int L'identifiant de la note ajoutée.
     */
    public function addNote($title, $message, $date, $time, $fk_category)
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
     *
     * Cette méthode met à jour les informations d'une note existante en utilisant des requêtes préparées.
     * 
     * @param string $title Le titre de la note.
     * @param string $message Le message de la note.
     * @param string $date La date de la note.
     * @param string $time L'heure de la note.
     * @param int $fk_category L'identifiant de la catégorie associée à la note.
     * @param int $pk_note L'identifiant de la note à mettre à jour.
     * @param int $fk_admin L'identifiant de l'administrateur modifiant la note.
     * 
     * @return void
     */
    public function updateNote($title, $message, $date, $time, $fk_category, $pk_note, $fk_admin)
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

        return connexion::getInstance()->ExecuteQuery($query, $params); // ✅ return result

    }

    /**
     * Supprime une ou plusieurs notes en fonction des titres.
     *
     * Cette méthode supprime les notes dont les titres sont fournis.
     * 
     * @param array $titles Un tableau de titres de notes à supprimer.
     * 
     * @return string 'True' si les notes ont été supprimées, sinon 'False'.
     */
    public function deleteNote($titles)
    {
        if (empty($titles)) {
            return 'False';
        }

        $placeholders = implode(',', array_fill(0, count($titles), '?'));
        $query = "DELETE FROM t_note WHERE title IN ($placeholders)";

        $res = connexion::getInstance()->ExecuteQuery($query, $titles);
        return $res > 0 ? 'True' : 'False';
    }

    /**
     * Récupère une note spécifique de manière sécurisée.
     *
     * Cette méthode récupère une note à partir de son identifiant unique.
     * 
     * @param int $pk_note L'identifiant unique de la note.
     * 
     * @return string Le contenu XML représentant la note ou une erreur si la note n'est pas trouvée.
     */
    public function getSingleNote($pk_note)
    {
        $query = connexion::getInstance()->SelectQuery("SELECT * FROM t_note WHERE pk_note = ?", [$pk_note]);

        if (empty($query)) {
            return "<note><error>Note not found</error></note>";
        }

        $data = $query[0];
        $result = '<note>';
        $result .= '<pk_note>' . htmlspecialchars($data['pk_note'], ENT_QUOTES, 'UTF-8') . '</pk_note>';
        $result .= '<title>' . htmlspecialchars($data['title'], ENT_QUOTES, 'UTF-8') . '</title>';
        $result .= '<message>' . htmlspecialchars($data['message'], ENT_QUOTES, 'UTF-8') . '</message>';
        $result .= '<fk_category>' . htmlspecialchars($data['fk_category'], ENT_QUOTES, 'UTF-8') . '</fk_category>';
        $result .= '</note>';

        return $result;
    }

    /**
     * Incrémente les "likes" pour une note donnée.
     *
     * Cette méthode incrémente le nombre de "likes" pour une note spécifique.
     * 
     * @param int $pk_note L'identifiant unique de la note.
     * 
     * @return bool Retourne true si le nombre de "likes" a été incrémenté avec succès, sinon false.
     */
    public function incrementLike($pk_note)
    {
        $query = "UPDATE t_note SET likes = likes + 1 WHERE pk_note = :pk_note";
        $params = ['pk_note' => $pk_note];

        $res = connexion::getInstance()->ExecuteQuery($query, $params);
        return $res > 0;
    }
}
?>
