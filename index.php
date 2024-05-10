<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <title>Authentification</title>
</head>
<?php 
    session_start();
    $bdd = new PDO("mysql:hostname=localhost;dbname=SuiviCours", "root", "");
    $req = "SELECT * FROM annee";
    $stmt = $bdd->prepare($req);
    $stmt->execute();
?>
<body>
    <div class="col-lg-8 col-md-10 contenu">
        <img id="logo" src="images/authentif.svg">
        <form action="" method="POST" class="needs-validation">
            <div class="form-group form-outline mb-4 was-validated">
                <select id="annee" name="annee" required class="form-select">
                    <option value="">Choisissez une année</option>
                    <?php while($row = $stmt->fetch()) : ?>
                        <option value="<?php echo $row['annee']; ?>"><?php echo $row['annee']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group form-outline mb-4 was-validated">
                <input class="form-control" type="email" name="email" placeholder="xxxxxxx@xyz.com" required>
            </div>
            <div class="form-group form-outline mb-4 was-validated">
                <input class="form-control" type="password" name="password" placeholder="password" required>
            </div>
            <button id="btnLogin" type="submit" name="submit" class="btn btn-primary">Se connecter</button>
            <?php 
            if(isset($_POST['submit'])) {
                $email = $_POST['email'];
                $password = $_POST['password'];
                $annee = $_POST['annee'];
                try {
                    $bdd = new PDO("mysql:hostname=localhost;dbname=SuiviCours", "root", "");
                    $req = "SELECT RC.idClasse, RC.annee FROM `resped-classe` RC, enseignant E WHERE RC.matricule = E.matricule AND email = ? AND password = ? AND RC.annee = ?";
                    $stmt = $bdd->prepare($req);
                    $stmt->execute(array($email, $password, $annee));
                    if($stmt->rowCount() > 0) 
                    {
                        $row = $stmt->fetch();
                        $_SESSION['idClasse'] = $row['idClasse'];
                        $_SESSION['annee'] = $annee;
                        header("Location: seance.php");

                    } 
                    else {
                        $req = "SELECT RC.NCE, RC.idClasse, RC.annee FROM `resad-classe` RC, etudiant E WHERE RC.NCE = E.NCE AND email = ? AND password = ? AND annee = ?";
                        $stmt = $bdd->prepare($req);
                        $stmt->execute(array($email, $password, $annee));
                        if($stmt->rowCount() > 0) {
                            $row = $stmt->fetch();
                            $_SESSION['idClasse'] = $row['idClasse'];
                            $_SESSION['annee'] = $annee;
                            header("Location: seance.php");
                        }
                        else
                            echo "Identifiants incorrect";
                    };
                } catch (PDOException $e) {
                    echo "Erreur : " . $e->getMessage();
                }
            }
        ?>
        </form>
    </div>
    
    
</body>
</html>