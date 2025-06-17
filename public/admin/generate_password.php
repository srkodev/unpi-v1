<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Générateur de mot de passe hashé</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        form {
            margin: 20px 0;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #666;
        }
        input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .result {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .result pre {
            white-space: pre-wrap;
            word-wrap: break-word;
            margin: 0;
        }
        .copy-btn {
            background-color: #007bff;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        .copy-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Générateur de mot de passe hashé</h1>
        
        <form method="POST">
            <div class="form-group">
                <label for="password">Entrez le mot de passe à hasher :</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Générer le hash</button>
        </form>

        <?php if (isset($hashed_password)): ?>
        <div class="result">
            <h3>Mot de passe hashé :</h3>
            <pre><?php echo htmlspecialchars($hashed_password); ?></pre>
            <button class="copy-btn" onclick="copyToClipboard('<?php echo htmlspecialchars($hashed_password); ?>')">
                Copier le hash
            </button>
        </div>
        <?php endif; ?>
    </div>

    <script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            alert('Hash copié dans le presse-papiers !');
        }).catch(function(err) {
            console.error('Erreur lors de la copie : ', err);
        });
    }
    </script>
</body>
</html> 