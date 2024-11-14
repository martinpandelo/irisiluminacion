<?php
class DbConnect {

    private $host;
    private $db;
    private $user;
    private $password;
    private $charset;

    public function __construct(){
        include_once dirname(__FILE__) . '/Config.php';

        $this->host = constant('DB_HOST');
        $this->db = constant('DB_NAME');
        $this->user = constant('DB_USERNAME');
        $this->password = constant('DB_PASSWORD');
        $this->charset = constant('CHARSET');
    }


    function connect(){
        try{
            $connection = "mysql:host=" . $this->host . ";dbname=" . $this->db . ";charset=" . $this->charset;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            
            $pdo = new PDO($connection, $this->user, $this->password, $options);
    
            return $pdo;
        }catch(PDOException $e){
            print_r('Error connection: ' . $e->getMessage());
        }
    }
 
}
?>