<?php
// CORS setup should go in the API entry point, not here.
include_once('connexion.php'); 

/**
 * DBUserManager handles login, logout, session, and user info.
 */
class DBUserManager {
    private $db;

    public function __construct() {
        $this->db = Connexion::getInstance();
    }

    public function login($email, $password) {
        $query = "SELECT * FROM t_admin WHERE email = :email";
        $params = array(':email' => $email);
        $result = $this->db->selectSingleQuery($query, $params);

        if ($result && password_verify($password, $result['password'])) {
            $_SESSION['logged'] = $result['email'];
            return "<response>
                        <result>true</result>
                        <pk_admin>{$result['pk_admin']}</pk_admin>
                        <email>{$result['email']}</email>
                    </response>";
        } else {
            unset($_SESSION['logged']);
            return '<result>false</result>';
        }
    }

    public function logout() {
        unset($_SESSION['logged']);
        session_destroy();
        return '<result>true</result>';
    }

    public function getUserInfo() {
        if (isset($_SESSION['logged'])) {
            return '<message>Logged in as ' . $_SESSION['logged'] . '</message>';
        } else {
            return '<message>DROITS INSUFFISANTS</message>';
        }
    }

    public function isAuthenticated() {
        if (isset($_SESSION['logged'])) {
            return "<response><authenticated>true</authenticated></response>";
        } else {
            return "<response><authenticated>false</authenticated></response>";
        }
    }
}
