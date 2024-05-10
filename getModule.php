<?php
 session_start();
 $idClasse = $_SESSION['idClasse'];
 $annee = $_SESSION['annee'];
if(!empty($_POST['codeSem'])) {
    try {
        $bdd = new PDO("mysql:hostname=localhost;dbname=SuiviCours", "root", "");
        $req = "SELECT * FROM `ue-annee` UA, `module-annee` MA, Module M, Enseigner E WHERE UA.codeSem = ? AND UA.codeUE = MA.codeUE AND UA.annee = MA.annee AND MA.annee = ? AND M.idModule = MA.idModule AND E.idClasse = ? AND E.idModule = M.idModule AND E.annee = MA.annee AND E.dateFin IS NULL";
        $stmt = $bdd->prepare($req);
        $stmt->execute(array($_POST['codeSem'], $annee, $idClasse));
        echo "<option value=''>Choisissez un module</option>";
        while ($row = $stmt->fetch()) {
            echo '<option value='.$row['idModule'].'>'.$row['libelleModule'].'</option>';
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
    
?>