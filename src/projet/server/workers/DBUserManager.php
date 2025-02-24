<?php
session_start();
include_once('Connexion.php'); // Make sure to include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['action'] == "connect") {
        // Get the email and password from POST
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Create a DB connection instance
        $db = Connexion::getInstance();

        // Prepare the query to check the user
        $query = "SELECT * FROM t_admin WHERE email = :email";
        $params = array(':email' => $email);
        $result = $db->selectSingleQuery($query, $params);

        if ($result) {
            // Check if password is hashed, if not, hash it now and update DB
            if (!password_get_info($result['password'])['algo']) {
                $hashedPassword = password_hash($result['password'], PASSWORD_DEFAULT);
                $updateQuery = "UPDATE t_admin SET password = :password WHERE pk_admin = :pk_admin";
                $db->ExecuteQuery($updateQuery, [':password' => $hashedPassword, ':pk_admin' => $result['pk_admin']]);
            }}
        
        if ($result && password_verify($password, $result['password'])) {  // Assuming password is hashed
            // Store user info in session
            $_SESSION['logged'] = $result['email'];
            echo "<response>
            <result>true</result>
            <pk_admin>{$result['pk_admin']}</pk_admin>
            <email>{$result['email']}</email>
          </response>";  
        } else {
            // Clear session if login failed
            unset($_SESSION['logged']);
            echo '<result>false</result>';
        }
    }

    if ($_POST['action'] == "disconnect") {
        // Clear session and destroy
        unset($_SESSION['logged']);
        session_destroy();
        echo '<result>true</result>';
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ($_GET['action'] == "getInfos") {
        // Check if the session is logged in
        if (isset($_SESSION['logged'])) {
            // You can now use the session variable $_SESSION['logged'] for logged-in user data
            // Query user info or any other data based on session
            echo '<message>Logged in as ' . $_SESSION['logged'] . '</message>';
        } else {
            echo '<message>DROITS INSUFFISANTS</message>';
        }
    }
}
?>