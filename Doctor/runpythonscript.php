<?php
$param = 1; // Toujours échapper les entrées utilisateurs pour éviter les injections shell
exec("python3 generate_plot.py $param 2>&1", $output, $result);


// Vérifier si ça a marché
if ($result === 0) {
    echo "<p>Graphique généré avec succès.</p>";
} else {
    echo "<p>Erreur lors de la génération du graphique :</p>";
    echo "<pre>" . implode("\n", $output) . "</pre>";
}
?>

<!-- Afficher le graphique -->
<img src="images/graph.png?<?php echo time(); ?>" alt="Graphique" width="500">