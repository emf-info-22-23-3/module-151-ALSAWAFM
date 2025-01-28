// /server/config/Database.php
class Database {
    private $host = 'database';  // Use 'database' as the hostname if using Docker
    private $db_name = 'hockey_stats';
    private $username = 'root';
    private $password = '';  // Empty password unless you set one
    public $conn;

    public function __construct() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db_name", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }
    }
}
