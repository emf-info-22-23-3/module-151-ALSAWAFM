<?php
session_start();
include_once('Connexion.php'); // Ensure the database connection is included

/**
 * User class to handle login, logout, session management, and fetching user info.
 */
class User {
    
    private $db;

    public function __construct() {
        // Create a DB connection instance
        $this->db = Connexion::getInstance();
    }

    /**
     * Handles the login process.
     * Verifies the user exists, checks the password, and starts a session.
     * @param string $email The email of the user.
     * @param string $password The password of the user.
     * @return string XML response indicating success or failure.
     */
    public function login($email, $password) {
        // Prepare the query to check for the user based on the provided email
        $query = "SELECT * FROM t_admin WHERE email = :email";
        $params = array(':email' => $email);
        $result = $this->db->selectSingleQuery($query, $params);

        if ($result) {
            // Check if password is already hashed, if not, hash it and update the database
            if (!password_get_info($result['password'])['algo']) {
                $hashedPassword = password_hash($result['password'], PASSWORD_DEFAULT);
                // Update the database with the newly hashed password
                $updateQuery = "UPDATE t_admin SET password = :password WHERE pk_admin = :pk_admin";
                $this->db->ExecuteQuery($updateQuery, [':password' => $hashedPassword, ':pk_admin' => $result['pk_admin']]);
            }
        }

        // Check if the password is verified and login is successful
        if ($result && password_verify($password, $result['password'])) {
            // Store user email in session for logged-in state
            $_SESSION['logged'] = $result['email'];
            // Return a response with the login success
            return "<response>
                        <result>true</result>
                        <pk_admin>{$result['pk_admin']}</pk_admin>
                        <email>{$result['email']}</email>
                    </response>";
        } else {
            // If login failed, clear the session and return failure response
            unset($_SESSION['logged']);
            return '<result>false</result>';
        }
    }

    /**
     * Handles the logout process.
     * Clears the session and destroys it to log out the user.
     * @return string XML response indicating success.
     */
    public function logout() {
        // Clear session data and destroy the session
        unset($_SESSION['logged']);
        session_destroy();
        return '<result>true</result>';
    }

    /**
     * Retrieves user information if the user is logged in.
     * @return string XML response with user email or insufficient rights message.
     */
    public function getUserInfo() {
        // Check if the user is logged in
        if (isset($_SESSION['logged'])) {
            // Return the logged-in user's email from the session
            return '<message>Logged in as ' . $_SESSION['logged'] . '</message>';
        } else {
            // If the user is not logged in, return an insufficient rights message
            return '<message>DROITS INSUFFISANTS</message>';
        }
    }

    /**
     * Handles the user action based on the provided POST or GET request.
     */

     public function isAuthenticated() {
        if (isset($_SESSION['logged'])) {
            return "<response><authenticated>true</authenticated></response>";
        } else {
            return "<response><authenticated>false</authenticated></response>";
        }
    }
    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($_POST['action'] == "connect") {
                // Handle login
                $email = $_POST['email'];
                $password = $_POST['password'];
                echo $this->login($email, $password);
            }

            if ($_POST['action'] == "disconnect") {
                // Handle logout
                echo $this->logout();
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if ($_GET['action'] == "getInfos") {
                echo $this->getUserInfo();
            }
            if ($_GET['action'] == "isAuthenticated") {
                echo $this->isAuthenticated();
            }
        }
    }
}

// Instantiate and handle the request
$user = new User();
$user->handleRequest();
?>
