<?php
require 'db.php';

$id = $_GET['id'];
$query = "SELECT name, ingredients, instructions FROM recipes WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($name, $ingredients, $instructions);
$stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Recipe</title>
    <style>
        /* Copy the same styles from add_recipe.php for consistency */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label, textarea, input[type="text"], input[type="submit"] {
            margin-bottom: 10px;
        }
        textarea {
            height: 100px;
        }
        input[type="submit"] {
            padding: 10px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #4cae4c;
        }
        a {
            display: block;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Recipe</h1>
        <form action="edit_recipe_action.php" method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
            <label for="ingredients">Ingredients:</label>
            <textarea id="ingredients" name="ingredients" required><?php echo $ingredients; ?></textarea>
            <label for="instructions">Instructions:</label>
            <textarea id="instructions" name="instructions" required><?php echo $instructions; ?></textarea>
            <input type="submit" value="Update Recipe">
        </form>
        <a href="index.php">Back to Recipe List</a>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
