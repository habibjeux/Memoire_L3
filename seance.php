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
    
    $req1 = "SELECT DISTINCT UE.codeUE, UE.nomUE FROM `enseigner` E, `module-annee` MA, `ue-annee` UA, UE WHERE E.idModule = MA.idModule AND E.annee = MA.annee AND E.annee = ? AND E.idClasse = ? AND MA.codeUE = UA.codeUE AND MA.annee = UA.annee AND UA.codeUE = UE.codeUE";
    $stmt1 = $bdd->prepare($req1);
    $stmt1->execute(array($annee, $idClasse));

    $req2 = "SELECT * FROM enseignant";
    $stmt2 = $bdd->prepare($req2);
    
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="jquery.min.js"></script>
    <title>Ajouter Séance</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/seance.css">
    <link rel="stylesheet" href="bootstrap-icons/bootstrap-icons.css">
</head>
<body>
    <a href="deconnexion.php" id="deconnexion" class="btn btn-danger">Déconnexion</a>
    <div class="contSe">
        <p id="iajoutseance" class="bg-dark text-light">Ajouter</p>
        <p id="iajoutseance2" class="bg-dark text-light">Séance</p>
    </div>
    <div class="col-lg-4 col-md-6 contenu">
        <form action="" method="POST" class="formSeance">
            <div class="form-outline mb-4">
            <select id="sem" name="sem" class="form-select" required onchange="getModule(this.value);">
                <option value="">Selectionnez un Semestre</option>
                <option value="1">Semestre 1</option>
                <option value="2">Semestre 2</option>
            </select>
            </div>
            <div class="form-outline mb-4">
                <select id="module" name="module" required class="form-select" onchange="getProf(this.value);">
                    <option value="">Choisissez un module</option>
                </select>
            </div>
            <div class="form-outline mb-4">
                <select id="prof" name="prof" required class="form-select">
                    <option value="">Choisissez un professeur</option>
                </select>
            </div>
            <div class="form-outline mb-2">
                <label for="date" class="form-label">Date de la Séance</label>
                <input type="date" class="form-control" name ="date" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="form-outline mb-4">
                <label for="nbHeure" class="form-label">Nombre Heure</label>
                <input type="number" name="nbHeure" class="form-control" min="1" max="4" required>
            </div>
            <div class="formButtons">
                <button  type="submit" class="btn btn-primary" name="fait">Fait</button>
                <button type="submit" class="btn btn-danger" name="nonfait">Non Fait</button>
            </div> 
            <?php
    
                if(isset($_POST['fait'])) {
                    $reqT = "SELECT * FROM enseigner WHERE idModule = ? AND idClasse = ? AND annee = ? AND dateDebut IS NULL";
                    $result = $bdd->prepare($reqT);
                    $result->execute(array($_POST['module'], $idClasse, $annee));
                    if($result->rowCount() > 0) {
                        $req3 = "UPDATE enseigner SET dateDebut = ? WHERE idModule = ? AND idClasse = ? AND annee = ?";
                        $stmt3 = $bdd->prepare($req3);
                        $stmt3->execute(array($_POST['date'], $_POST['module'], $idClasse, $annee));
                    } 
                    $req = "INSERT INTO cours VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $bdd->prepare($req);
                    $stmt->execute(array(NULL, $_POST['date'], $_POST['nbHeure'], '1', $_POST['prof'], $_POST['module'], $idClasse, $annee));
                    if($stmt) 
                        echo "Insertion réussi";
                    else
                        "Erreur lors de l'insertion";
                }
                else if(isset($_POST['nonfait'])) {
                    $reqT = "SELECT * FROM enseigner WHERE idModule = ? AND idClasse = ? AND annee = ? AND dateDebut IS NULL";
                    $result = $bdd->prepare($reqT);
                    $result->execute(array($_POST['module'], $idClasse, $annee));
                    if($result->rowCount() > 0) {
                        $req3 = "UPDATE enseigner SET dateDebut = ? WHERE idModule = ? AND idClasse = ? AND annee = ?";
                        $stmt3 = $bdd->prepare($req3);
                        $stmt3->execute(array($_POST['date'], $_POST['module'], $idClasse, $annee));
                    } 
                    $req = "INSERT INTO cours VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $bdd->prepare($req);
                    $stmt->execute(array(NULL, $_POST['date'], $_POST['nbHeure'], '0', $_POST['prof'], $_POST['module'], $idClasse, $annee));
                    if($stmt) 
                        echo "Insertion réussi";
                    else
                        "Erreur lors de l'insertion";
                }
            ?>
        </form>
    </div>
    <div class="addModule">
    <button class="btn btn-light bi bi-plus-circle" id="btnAdd" data-bs-toggle="modal" data-bs-target="#AddModal"></button>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="AddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Formulaire d'ajout d'un module</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="libelleModule" class="form-label">Libelle Module</label>
                        <input type="text" class="form-control" name="libelleModule">
                        <div class="form-text"></div>
                    </div>
                    <div class="mb-3">
                        <label for="codeUE" class="form-label">Nom UE</label>
                        <select id="codeUE" name="codeUE" class="form-select">
                            <?php while($row = $stmt1->fetch()) : ?>
                                <option value="<?php echo $row['codeUE']; ?>"><?php echo $row['nomUE']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="matricule" class="form-label">Professeur</label>
                        <select id="matricule" name="matricule" class="form-select">
                            <?php $stmt2->execute(); while($row = $stmt2->fetch()) : ?>
                                <option value="<?php echo $row['matricule']; ?>"><?php echo $row['matricule'].'-'.$row['prenom'].' '.$row['nom']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nbHeureModule" class="form-label">Nombre Heure Module</label>
                        <input type="number" min="1" max="100" class="form-control" name="nbHeureModule">
                        <div class="form-text"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" name="submitAdd" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
            </div>
        
        </div>
    </div>
    </div>
    <?php
        if(isset($_POST['submitAdd'])) {
            $matricule = $_POST['matricule'];
            $libelleModule = $_POST['libelleModule'];
            $codeUE = $_POST['codeUE'];
            $nbHeureModule = $_POST['nbHeureModule'];
            $req8 = "SELECT idModule FROM module WHERE idModule LIKE '$codeUE%' ORDER BY idModule DESC LIMIT 1";
            $stmt8 = $bdd->prepare($req8);
            $stmt8->execute();
            $idModule = $stmt8->fetch()['idModule']+1;
            $req9 = "INSERT INTO module VALUES (?, ?)";
            $stmt9 = $bdd->prepare($req9);
            $stmt9->execute(array($idModule, $libelleModule));    
            if($stmt9) {
                $req10 = "INSERT INTO `module-annee`(idModule, codeUE, annee, nbHeureModule)
                VALUES (?, ?, ?, ?)";
                $stmt10 = $bdd->prepare($req10);
                $stmt10->execute(array($idModule, $codeUE, $_SESSION['annee'], $nbHeureModule));
                if($stmt10) {
                    $req11 = "INSERT INTO enseigner(matricule, idModule, idClasse, annee, examenFait, dateDebut, dateFin)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt11 = $bdd->prepare($req11);
                    $stmt11->execute(array($matricule, $idModule, $idClasse, $_SESSION['annee'], '0', NULL, NULL));
                    if($stmt11) {
                        ?>
                        <script>
                            window.location.href= "seance.php";
                        <?php
                    }
                }
            }  
        }
    ?>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        let form = document.getElementsByTagName("form")[0];
        let btnAdd = document.getElementById("btnAdd");

        form.addEventListener('submit', (e) => {
           
        });
        
        btnAdd.addEventListener('mouseover', (e) => {
            btnAdd.innerHTML = "Ajouter Module ?";
        });
        btnAdd.addEventListener('mouseout', (e) => {
            btnAdd.innerHTML = "";
        });

        function getModule(val) {
            $.ajax({
                type: "POST",
                url: 'getModule.php',
                data : 'codeSem='+val,
                success : function(data) {
                    $("#module").html(data);
                }
            })
        }
        function getProf(val) {
            $.ajax({
                type: "POST",
                url: 'getProf.php',
                data : 'idModule='+val,
                success : function(data) {
                    $("#prof").html(data);
                }
            })
        }
        
    </script>
<!-- JavaScript Bundle with Popper -->
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>