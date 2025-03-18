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

        header("Location: ../html/index.php");
    }
    catch (PDOException $e)
    {
        // Gestione degli errori
        echo "Errore: " . $e->getMessage();
    }
}
?>
