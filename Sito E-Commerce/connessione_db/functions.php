<?php
function logError(Exception $ex): void
{
    error_log($ex->getMessage() . ' -- ' . date('Y-m-d H:i:s') . "\n", 3, '../log/error.log');  // appende il messaggio in un file di destinazione
    header('Location:../redirect/error_connection.html');  //per non avere problemi di scrittura doppia
}
