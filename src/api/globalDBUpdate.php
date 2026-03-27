<?php 

require("../mainFunctions.php");

$newOffersCount = globalDBUpdate();

header('Content-Type: application/json');
echo json_encode(["inserted" => $newOffersCount]);

?>