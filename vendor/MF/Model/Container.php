<?php 

namespace MF\Model;
use App\Connection;

class Container{
    public static function getModel($model){
        
        $class = "\\App\\Models\\".ucfirst($model);

        // retornar o modelo olicitado ja istanciado, inclusive com a conexao estabelecida
        $conn  = Connection::getDb();
        return new $class($conn);
    }
}

?>