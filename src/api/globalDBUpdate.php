<?php 

session_start();

require("../mainFunctions.php");

$newOffersCount = globalDBUpdateForUser();

header('Content-Type: application/json');
echo json_encode(["inserted" => $newOffersCount]);

?>