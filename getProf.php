<?php
    session_start();
    $idClasse = $_SESSION['idClasse'];
    $annee = $_SESSION['annee'];
    if(!empty($_POST['idModule'])) {
        try {
            $bdd = new PDO("mysql:hostname=localhost;dbname=SuiviCours", "root", "");
            $req = "SELECT * FROM enseignant WHERE matricule in (SELECT matricule FROM enseigner WHERE idModule = ? AND idClasse = ? AND annee = ?)";
            $stmt = $bdd->prepare($req);
            $stmt->execute(array($_POST['idModule'], $idClasse, $annee));
            echo "<option value=''>Choisissez un professeur</option>";
            while ($row = $stmt->fetch()) {
                echo '<option value='.$row['matricule'].'>'.$row['matricule'].'-'.$row['prenom'].' '.$row['nom'].'</option>';
            }
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }
    
?>