<?php 

namespace APP;
use PDOException;

class Connection {
    
    public static function getDb(){
        try {
            
            $conn = new \PDO(
            "mysql:host=localhost;dbname=twitter_clone;charset=utf8",
            "root",
            ""
            );

            return  $conn;

        } catch (\PDOException $e) {
            echo $e;
        }
    }

}


?>