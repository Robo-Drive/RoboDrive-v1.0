<?php 
namespace app\database;

use Exception;
use PDO;

use app\models\Usuario;

class ConnectionFactory
{
    private static ?PDO $connection = null;

    public static function getConnection(){

        if(self::$connection == null)
        {
            try
            {
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME ;
                self::$connection = self::createConnection($dsn);
                self::folders();
            }
            catch(Exception $e)
            {
                print("Não foi possível acessar o banco:  " . DB_NAME);
                print("<br>O erro foi: ". $e->getMessage());
                print("<br>Tentando inicializar o banco de dados...");

                $dsn = "mysql:host=" . DB_HOST;
                self::$connection = self::createConnection($dsn);

                $databaseInit = new DatabaseInitializer();
                $databaseInit->init(self::$connection);
            
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME ;
                self::$connection = self::createConnection($dsn);
            }
        }

        return self::$connection;
        
    }
    public static function folders()
    {
        $sql = "SELECT * FROM usuario";
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME ;
        $stmt = self::createConnection($dsn)->prepare($sql);
        $stmt->execute();
        $usuarios = Usuario::map($stmt->fetchAll());

        foreach($usuarios as $u)
        {
            if(!file_exists(STORE_PATH."/user-".$u->getId()))
            {
                mkdir(STORE_PATH."/user-".$u->getId(),0777,true);
            }
            if(!file_exists(STORE_PATH."/user-".$u->getId()."/img"))
            {
                mkdir(STORE_PATH."/user-".$u->getId()."/img",0777,true);
            }
            if(!file_exists(STORE_PATH."/user-".$u->getId()."/projects"))
            {
                mkdir(STORE_PATH."/user-".$u->getId()."/projects",0777,true);
            }
        }
    }
    private static function createConnection(string $dsn)
    {
        $connection = new PDO($dsn, DB_USER, DB_PASS);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $connection;
    }
}
