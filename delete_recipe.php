<?php
require 'db.php';

$id = $_GET['id'];

$query = "DELETE FROM recipes WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: index.php?update=1"); // Indicate that an update has occurred
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
