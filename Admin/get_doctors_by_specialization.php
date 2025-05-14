<?php
require_once '../Login/database.php';
echo "k";
if (isset($_GET['specialization'])){
    $stmtnewdoctor=$conn->prepare("select distinct username from doctor where specialization=? and status='actif' order by CAST(SUBSTRING(username, 2) AS UNSIGNED)");
    $stmtnewdoctor->bind_param("s",$_GET['specialization']);
    echo $_GET['specialization'];
    if ($stmtnewdoctor->execute()) {
        $res = $stmtnewdoctor->get_result();
        while ($row = $res->fetch_assoc()) {
            echo '<option value="' . htmlspecialchars($row['username']) . '" style="color:black">' . htmlspecialchars($row['username']) . '</option>';
        }
    }
}
?>