<?php


namespace Daeme\SRC;

use PDO;
use PDOException;

class Database {
    public static function getConnection(): ?PDO {
        $servername = "localhost";
        $username = "root";
        $password = ""; // Assurez-vous d'utiliser le bon mot de passe pour votre environnement
        $dbname = "ecf";
        $port = 3306;

        try {
            $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname;charset=utf8", $username, $password);
            // Définir le mode d'erreur PDO à exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch(PDOException $e) {
            // En production, il est préférable de ne pas afficher les messages d'erreur détaillés
            // echo "Erreur de connexion: " . $e->getMessage();

            // Log l'erreur sans exposer de détails sensibles
            error_log($e->getMessage());

            // Informer l'utilisateur d'une erreur sans révéler d'informations sensibles
            echo "Une erreur est survenue lors de la connexion à la base de données.";

            return null;
        }
    }

    
}
