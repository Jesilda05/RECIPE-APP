<!DOCTYPE html>
<html>
<head>
    <title>Recipe List</title>
    <link rel="manifest" href="manifest.json">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        a {
            display: inline-block;
            margin: 20px 0;
            padding: 10px;
            background-color: #5cb85c;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        .actions {
            display: flex;
            justify-content: space-around;
        }

        #installBtn {
            display: none;
            margin: 20px 0;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Recipe List</h1>
        <a href="add_recipe.php">Add Recipe</a>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Ingredients</th>
                    <th>Instructions</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="recipe-list">
                <!-- Recipes will be dynamically added here -->
            </tbody>
        </table>
        <button id="installBtn">Install App</button>
    </div>
    <script>
        let deferredPrompt;

        // Unregister existing service workers
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.getRegistrations().then(function(registrations) {
                for (let registration of registrations) {
                    registration.unregister();
                }
            });
        }

        // Register new service worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/recipe_new/service-worker.js').then(function(registration) {
                    console.log('ServiceWorker registration successful with scope: ', registration.scope);
                }, function(error) {
                    console.log('ServiceWorker registration failed: ', error);
                });
            });
        }

        // Listen for the beforeinstallprompt event
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            const installBtn = document.getElementById('installBtn');
            installBtn.style.display = 'block';

            installBtn.addEventListener('click', () => {
                installBtn.style.display = 'none';
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the install prompt');
                    } else {
                        console.log('User dismissed the install prompt');
                    }
                    deferredPrompt = null;
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            fetchRecipes();
        });

        function fetchRecipes() {
            fetch('/recipe_new/fetch_recipes.php')
                .then(response => response.json())
                .then(data => {
                    const recipeList = document.getElementById('recipe-list');
                    if (recipeList) {
                        recipeList.innerHTML = '';
                        data.forEach(recipe => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${recipe.name}</td>
                                <td>${recipe.ingredients}</td>
                                <td>${recipe.instructions}</td>
                                <td class="actions">
                                    <a href="edit_recipe.php?id=${recipe.id}">Edit</a>
                                    <a href="delete_recipe.php?id=${recipe.id}" onclick="return confirm('Are you sure you want to delete this recipe?');">Delete</a>
                                </td>
                            `;
                            recipeList.appendChild(row);
                        });
                    } else {
                        console.error('Recipe list container not found');
                    }
                })
                .catch(error => {
                    console.error('Error fetching recipes:', error);
                });
        }
    </script>
</body>
</html>
