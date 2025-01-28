<?php 

// Connexion à la base de données avec PDO
$bdd = new PDO('mysql:host=localhost;dbname=sys', 'root', 'Pa$$w0rd');

// Préparez la requête SQL pour récupérer les titres des jeux vidéo
$reponse = $bdd->query('SELECT titre FROM jeux_video');

// Parcourez les résultats de la requête et affichez chaque titre
while ($donnees = $reponse->fetch()) {
    echo $donnees['titre'] . '<br>';  // Affiche chaque titre suivi d'un saut de ligne
}

// Ferme le curseur pour libérer la connexion
$reponse->closeCursor(); 

?>
