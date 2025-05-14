<?php
session_start();

// Récupération du paramètre à passer au script Python
$titre = escapeshellarg($_SESSION['username']); // sécurise la valeur passée

// Exécution du script Python avec le paramètre
exec("python3 AddPatient.py $titre 2>&1", $output, $result);

// Vérifier le résultat de l'exécution
if ($result === 0) {
    echo "<p>Graphique généré avec succès.</p>";
} else {
    echo "<p>Erreur lors de la génération du graphique :</p>";
    echo "<pre>" . implode("\n", $output) . "</pre>";
}
?>
