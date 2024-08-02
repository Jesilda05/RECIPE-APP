<?php
require 'db.php';

$query = "SELECT id, name, ingredients, instructions FROM recipes";
$result = $conn->query($query);

$recipes = array();
while ($row = $result->fetch_assoc()) {
    $recipes[] = $row;
}

header('Content-Type: application/json');
echo json_encode($recipes);

$conn->close();
?>
