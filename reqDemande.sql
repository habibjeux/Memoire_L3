// Nombre d heure fait pour chaque module
SELECT M.idModule, M.libelleModule, SUM(C.nbHeure) AS nbHeureFait FROM module M, cours C WHERE M.idModule = C.idModule AND C.estFait = 1 GROUP BY C.idModule;

// Nombre d heure non fait pour chaque module
SELECT M.idModule, M.libelleModule, SUM(C.nbHeure) AS nbHeureFait FROM module M, cours C WHERE M.idModule = C.idModule AND C.estFait = 0 GROUP BY C.idModule;

//Nombre d heure restant ou en excès pour chaque module
SELECT M.idModule, M.libelleModule, MA.nbHeureModule - SUM(C.nbHeure) AS RestantExces FROM module M,
`module-annee` MA, `ue-annee` UA, Cours C WHERE M.idModule = MA.idModule AND MA.annee = C.annee AND 
M.idModule = C.idModule AND C.estFait = '1' AND C.idClasse = ? AND C.annee = ? AND UA.codeUE = MA.codeUE 
AND UA.codeSem = ? AND UA.annee = MA.annee GROUP BY M.idModule;


//Les modules terminés et ayant fait un examen
SELECT idModule, libelleModule FROM module WHERE idModule in (SELECT E.idModule FROM enseigner E, Cours C
WHERE E.idModule = C.idModule AND E.dateFin IS NOT NULL AND E.examenFait = 1 GROUP BY E.idModule);

//Les modules terminés et examen pas encore fait
SELECT idModule, libelleModule FROM module WHERE idModule in (SELECT E.idModule FROM enseigner E, Cours C
WHERE E.idModule = C.idModule AND E.dateFin IS NOT NULL AND E.examenFait = 0 GROUP BY E.idModule);

//Les modules qui n ont pas encore débuté
SELECT idModule, libelleModule FROM enseigner WHERE dateDebut IS NULL;

//Le nombre de séance faite pour chaque module
SELECT M.idModule, M.libelleModule, COUNT(C.estFait) AS nbSeanceFaite FROM module M, cours C WHERE M.idModule = C.idModule AND C.estFait = 1 GROUP BY C.idModule;


// Nombre de module enseigné par prof 
SELECT M.libelleModule, COUNT(E.matricule) AS moduleProf FROM `enseigner` E, module M, `module-annee` MA,
`ue-annee` UA WHERE E.idModule = M.idModule AND E.annee = '2021-2022' AND E.idClasse = 'L1' AND MA.idModule = E.idModule
AND MA.annee = E.annee AND MA.codeUE = UA.codeUE AND UA.annee = MA.annee AND UA.codeSem = '2' GROUP BY M.idModule;


//derniers cours
SELECT EN.matricule, EN.prenom, EN.nom, C.dateCours, C.nbHeure, C.estFait, M.libelleModule, C.idClasse FROM enseigner E, 
cours C, module M, enseignant EN, `module-annee` MA, `ue-annee` UA WHERE E.matricule = C.matricule AND E.idModule = C.idModule 
AND E.idClasse = C.idClasse AND E.annee = C.annee AND C.annee = '2021-2022' AND M.idModule = C.idModule AND E.matricule = EN.matricule
AND MA.idModule = M.idModule AND MA.annee = E.annee AND MA.codeUE = UA.codeUE AND MA.annee = UA.annee AND C.idClasse = 'L3' AND 
UA.codeSem = '1' ORDER BY C.dateCours;


