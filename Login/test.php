<?php
$conn=require __DIR__ . '/database.php';
$sql="select password,username from doctor where password not like '$2%'";
$stmt =$conn->prepare($sql);
if(!$stmt->execute()){
die( "error");
}
$doctor=[];
$result = $stmt->get_result();
if($result){
    while ($row = $result->fetch_assoc()){
        $doctor[]=$row;
    }
    $stmt->close();
    $update_stmt =$conn->prepare('update doctor set password =? where username=?');
    $passwords=[];
    foreach ($doctor as $doc) {
        $passwords_hache=password_hash( $doc['password'],PASSWORD_BCRYPT);
        $update_stmt->bind_param('ss', $passwords_hache,$doc['username']);
        if(!$update_stmt->execute()){
        die( "error");
        }
    }
}

$update_stmt->close();
$conn->close();
