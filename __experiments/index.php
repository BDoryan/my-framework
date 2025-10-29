<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Démo AJAX PHP / jQuery</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/app.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        #menu a { margin-right: 15px; text-decoration: none; color: blue; }
        #menu a:hover { text-decoration: underline; }
        #content { margin-top: 30px; border-top: 1px solid #ccc; padding-top: 20px; }
    </style>
</head>
<body>

<h1>Démo : Navigation sans rechargement</h1>

<div id="menu">
    <a href="../templates/screens/accueil.php">Accueil</a>
    <a href="../templates/screens/profil.php">Profil</a>
</div>

<div id="content">
    <?php include 'accueil.php'; ?>
</div>

</body>
</html>
