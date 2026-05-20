
<?php

session_start();
require("../mainFunctions.php");

// check si user_id correspond bien au propriétaire de l'expression
$match = checkMatchBetweenKeyAndUserId($_POST['id']);
if(!$match) {
    // si pas le cas => error mauvais owner
    echo "wrongUser";
} else {
    // sinon exécute effacement et renvoie résultat
    $erasingResult = eraseKeyFromDB($_POST['id']);
    if($erasingResult) {
        echo "success";
    } else {
        echo "error";
    }
}

?>
