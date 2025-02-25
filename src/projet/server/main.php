<?php
include_once('workers/DBNoteManager.php'); // Include the DBNoteManager class for managing notes

/**
 * Handles HTTP requests (GET, POST, PUT, DELETE) to manage notes.
 * The script processes incoming requests, interacts with the database, and returns appropriate responses in XML format.
 */
if (isset($_SERVER['REQUEST_METHOD'])) {
    switch ($_SERVER['REQUEST_METHOD']) {
        
        /**
         * Handles GET requests.
         * Retrieves information based on the action parameter (categories, single note, or all notes).
         */
        case 'GET':
            $noteBD = new DBNoteManager();
            
            // If the action is to get categories
            if (isset($_GET['action']) && $_GET['action'] == 'getCategories') {
                echo $noteBD->GetCategories(); // Return categories in XML
            } 
            // If the action is to get a specific note
            elseif (isset($_GET['action']) && $_GET['action'] == 'getNote' && isset($_GET['pk_note'])) {
                echo $noteBD->GetSingleNote($_GET['pk_note']); // Return a specific note by primary key
            } else {
                echo $noteBD->GetInXML(); // Return all notes in XML format
            }
            exit;
            break;

        /**
         * Handles POST requests.
         * Processes actions like incrementing likes or adding a new note.
         */
        case 'POST':
            
            // If the action is to increment the like count for a note
            if (isset($_POST['action']) && $_POST['action'] == 'incrementLike' && isset($_POST['pk_note'])) {
                $noteBD = new DBNoteManager();
                header("Content-Type: text/xml"); // Set response type to XML
                echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
                echo "<response>";

                // Call function to increment the like count and return success or error status
                $result = $noteBD->IncrementLike($_POST['pk_note']);

                if ($result) {
                    echo "<status>success</status>"; // Return success if like incremented
                } else {
                    echo "<status>error</status>"; // Return error if there was an issue
                }

                echo "</response>";
                exit;
            }
            
            // If the action is to add a new note
            else if (isset($_POST['title']) && isset($_POST['message']) && isset($_POST['date']) && isset($_POST['time']) && isset($_POST['fk_category'])) {
                $noteBD = new DBNoteManager();
                header("Content-Type: text/xml"); // Set response type to XML
                echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
                echo "<response>";
                echo "<status>success</status>";

                // Add the new note and return its primary key
                $pk_note = $noteBD->Add($_POST['title'], $_POST['message'], $_POST['date'], $_POST['time'], $_POST['fk_category']);
                echo "<pk_note>" . $pk_note . "</pk_note>";
                echo "</response>";
                exit;
            } else {
                echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
                echo "<response><error>Missing required parameters</error></response>"; // Missing parameters error
                exit;
            }
            break;

        /**
         * Handles PUT requests.
         * Updates an existing note in the database.
         */
        case 'PUT':
            parse_str(file_get_contents("php://input"), $vars); // Parse the PUT request parameters

            // If required parameters are set for updating a note
            if (isset($vars['title']) && isset($vars['message']) && isset($vars['date']) && isset($vars['fk_category']) && isset($vars['fk_admin'])) {
                $noteBD = new DBNoteManager();
                echo $noteBD->Update($vars['title'], $vars['message'], $vars['date'], $vars['time'], $vars['fk_category'], $vars['pk_note'], $vars['fk_admin']);
            } else {
                echo 'Paramètre pk_note, title, message, date, fk_category ou fk_admin manquant'; // Missing required parameters error
            }
            break;

        /**
         * Handles DELETE requests.
         * Deletes one or more notes based on titles.
         */
        case 'DELETE':
            header("Content-Type: text/xml"); // Set response type to XML
            parse_str(file_get_contents("php://input"), $vars); // Parse DELETE request parameters

            echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";

            // If 'titels' parameter is not empty, process the deletion of notes
            if (!empty($vars['titels'])) {
                $noteBD = new DBNoteManager();
                $titlesArray = explode(',', $vars['titels']); // Convert CSV string into an array
                $result = $noteBD->Delete($titlesArray); // Call delete function

                echo "<response>";
                echo "<status>" . $result . "</status>"; // Return success or failure status
                echo "</response>";
            } else {
                echo "<response><error>Paramètre titels manquant</error></response>"; // Missing 'titels' parameter error
            }
            break;
			
    }
}
?>
