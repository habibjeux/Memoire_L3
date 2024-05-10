<?php 
    session_start();
    if(isset($_SESSION['idClasse'])) {
        $idClasse = $_SESSION['idClasse'];
        $annee = $_SESSION['annee'];
    }
    else {
        header('Location: index.php'); 
    }
    $bdd = new PDO("mysql:hostname=localhost;dbname=SuiviCours", "root", "");
    
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="jquery.min.js"></script>
    <title>Gestion module</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/seance.css">
</head>
<body>
    <a href="deconnexion.php" id="deconnexion" class="btn btn-danger">Déconnexion</a>
    <div class="col-lg-4 col-md-6 contenu">
        <h1>Gestion Module</h1>
    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
    
</body>
</html>