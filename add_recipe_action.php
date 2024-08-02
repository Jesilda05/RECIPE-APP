<?php
require 'db.php';

$name = $_POST['name'];
$ingredients = $_POST['ingredients'];
$instructions = $_POST['instructions'];

$query = "INSERT INTO recipes (name, ingredients, instructions) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("sss", $name, $ingredients, $instructions);

if ($stmt->execute()) {
    header("Location: index.php?update=1"); // Indicate that an update has occurred
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
