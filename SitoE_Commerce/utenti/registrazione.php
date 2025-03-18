<?php
session_start();
// Configurazione del DB
$config = require '../connessione_db/db_config.php';
require '../connessione_db/DB_Connect.php';
require_once '../connessione_db/functions.php';

// Connessione al DB
$db = DataBase_Connect::getDB($config);

// Verifica se il form è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Prendi i dati dal form
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash della password
    $hash = password_hash($password, PASSWORD_DEFAULT);

    try
    {
        // Verifica se lo username esiste già nel database
        $query_check = "SELECT COUNT(*) FROM e_commerce.utenti WHERE username = :username";
        $stmt_check = $db->prepare($query_check);
        $stmt_check->bindParam(':username', $username);
        $stmt_check->execute();

        // Controlla il numero di risultati
        $existingUser = $stmt_check->fetchColumn();

        if ($existingUser > 0)
        {
            header("Location: ../redirect/error_username.pages" );
        }
        else
        {
            // Query SQL per inserire i dati nel database
            $query = "INSERT INTO e_commerce.utenti (nome, cognome, email, username, password) 
                      VALUES (:nome, :cognome, :email, :username, :password)";

            // Prepara la query
            $stmt = $db->prepare($query);

            // Lega i parametri
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':cognome', $cognome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hash);

            // Esegui la query
            $stmt->execute();

            // Reindirizza alla pagina principale
            header("Location: ../pages/index.php");
        }
    }
    catch (PDOException $e)
    {
        logError($e);
    }
}
?>
