<?php

session_start();

require("../mainFunctions.php");

$result = changeOfferStatus($_POST['id'], $_POST['status']);
echo $result ? "success" : "error";

?>