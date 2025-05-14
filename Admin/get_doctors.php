<?php
$conn= require "../Login/database.php";
$spec = $_GET['spec']; // On récupère la spécialité depuis l'URL
if($spec!="specialization"){
    $stmt=$conn->prepare("select  username,full_name ,CIN   ,email ,contact_info ,specialization  from doctor where specialization=?");
    $stmt->bind_param("s",$spec);
}
else{
    $stmt=$conn->prepare(" select  username,full_name ,CIN   ,email ,contact_info ,specialization  from doctor");
}

if(!$stmt->execute()){
    die( "Error") ;
}
$result = $stmt->get_result();
// On transforme les résultats en tableau JSON
$doctors = [];
while ($row = $result->fetch_assoc()) {
    $doctors[] = $row;
}

header('Content-Type: application/json');
echo json_encode($doctors);
?>