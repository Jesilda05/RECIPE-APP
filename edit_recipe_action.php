<?php
require 'db.php';

$id = $_POST['id'];
$name = $_POST['name'];
$ingredients = $_POST['ingredients'];
$instructions = $_POST['instructions'];

$query = "UPDATE recipes SET name = ?, ingredients = ?, instructions = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("sssi", $name, $ingredients, $instructions, $id);

if ($stmt->execute()) {
    header("Location: index.php?update=1"); // Indicate that an update has occurred
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
