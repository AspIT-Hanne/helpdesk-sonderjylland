<?php 

function getFeaturesData()
{

    // Output buffer start. Opsamler al output, som phpheader (og connect, som includes i phpheader) måtte generere.
    // Udfordringen er, at når jeg bruger header() i linje 9, så må der ikke have været noget output først, så for at undgå det, 
    // bruger jeg en output buffer, som opsamler det hele og så sletter det i linje 7.
    ob_start();
        include_once realpath(__DIR__ . '/..') . "/includes/phpheader.php";
    ob_end_clean();

    global $dbcon;

    try {
        $table = "systemFeatures";

        $result = $dbcon->getAllData($table);

        return $result;
    } catch (Exception $e) {
        throw new Exception("Der blev ikke fundet nogle data." . $e->getMessage());
    }
}