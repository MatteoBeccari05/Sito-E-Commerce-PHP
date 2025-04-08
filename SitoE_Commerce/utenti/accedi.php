<?php
$config = require '../connessione_db/db_config.php';
require '../connessione_db/DB_Connect.php';
require_once '../connessione_db/functions.php';
$db = DataBase_Connect::getDB($config);

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $username = $_POST['username'];
    $password = $_POST['password'];

    try
    {
        // Prepara la query per cercare l'utente nel database
        $query = "SELECT * FROM e_commerce.utenti WHERE username = :username";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user)
        {
            // Verifica la password
            if (password_verify($password, $user['password']))
            {
                session_start();

                $_SESSION['username'] = $username;
                $_SESSION['nome'] = $user['nome'];
                $_SESSION['cognome'] = $user['cognome'];
                header("Location: ../pages/index.php");
                exit;
            }
            else
            {
                header("Location: ../redirect/error_password.html");
            }
        }
        else
        {
            header("Location: ../redirect/error_password.html");
        }
    }
    catch (PDOException $e)
    {
        logError($e);
    }
}
?>
