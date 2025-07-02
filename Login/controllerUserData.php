<?php 
//⛔⛔⛔⛔⛔⛔⛔⛔⛔⛔⛔⛔⛔⛔⛔⛔⛔⛔
session_start();
$conn= require __DIR__ . "/database.php";
$Username="";
$email="";
$errors=array();

// click login button
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])){
    
        $Username=$_POST['username'];
        $sql="SELECT 
                username, password,full_name,role
            FROM
                doctor
            WHERE
                username = ?  AND status='actif'";
        
        $stmt=$conn->prepare($sql);
        $stmt->bind_param("s",$Username);
        if(!$stmt->execute()){
            die("error!Login");
        }
        $result = $stmt->get_result(); //⛔⛔ Récupérer les résultats ⛔⛔
        if($result->num_rows==0){
            $errors['login']="The username or password invalid!";
        }
        else{
            
            $row=$result->fetch_assoc();
            //echo $_POST['password'] . "   /  " . $row['password'];
            if (password_verify($_POST['password'], $row['password'])){
               
                // Password is correct, proceed with login
                $_SESSION['username']= $Username;
                $_SESSION['name']=$row['full_name'];
                $today=date("Y-m-d");
                $stmtacess=$conn->prepare("insert into acess_dates (access_date) value (?)");
                $stmtacess->bind_param("s",$today);
                if(!$stmtacess->execute()){
                    die("error!acess_dates");
                }
                if($row['role']=="non admin"){
                    header('location: ../Doctor/Dashboard.php');
                }
                else {
                    header('location: ../Admin/Dashboard.php');
                }
                
                exit(); // Good practice to add exit after header redirect
                
            } else {
                $errors['login'] = "Invalidusername or  password ";
            }

        }
    $conn->close();
}
// clich the verify email button
if(isset($_POST['verify'])){

    $email=$_POST["email"];
    $username=$_POST['username'];
    $_SESSION['username']= $username;
    $token=bin2hex(random_bytes(16));
    $token_hash = hash("sha256",$token);
    $expiry=date("Y-m-d H:i:s",time()+60*30);
    if ($conn->connect_error) { 
            // Correction ici
        echo "not connected";
    } 
    // sql statement
    $sql="UPDATE doctor 
     SET 
        reset_token_hash =?,
        reset_token_expires_date=?
    WHERE
        email = ? and username=?";
        
    //prepare sql statement
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("ssss",$token_hash,$expiry,$email,$username);
    if(!$stmt->execute()){
        echo "Error:" ;
    }
    if($conn->affected_rows){
        $mail=require __DIR__ . "/mailer.php";
        $mail->setFrom( $_ENV['SMTP_USERNAME']);
        $mail->addAddress($email);
        $mail->Subject="Password Reset";
        $mail->Body= <<<END
        Click <a href="http://localhost/Gestion%20des%20Maladies%20et%20Statistiques%20M%c3%a9dicales/Login/reset-password.php?token=$token">here</a>
        to reset your password.validate until $expiry.
        END;
        try{
            $mail->send();
            $_SESSION['info']="We've sent a verification code to your email - $email";
            $_SESSION['username']=$username;
        } catch (Exception $e){
            $errors['verifyemail']="message could not be send"  . $mail->ErrorInfo();
           
        }
    }
    else{
        $errors['verifyemail']="no such email exist";
    }
    //close teh declaration & connection
    $stmt->close();
    $conn->close();
}
// click change password buttom
if(isset($_POST['changepassword'])){
    if($_POST['newpassword']==$_POST['cpassword']){
        $password_hache=password_hash( $_POST['newpassword'],PASSWORD_BCRYPT);
        $sql="update doctor set password=? where username =? ";
        $stmt=$conn->prepare($sql);
        $stmt->bind_param("ss",$password_hache, $_SESSION['username']);
        if(!$stmt->execute()){
            echo "Error:" ;
        }
        $_SESSION['info']="Modify password succesfuly.";
    }
    else{
        $errors['password']="not matched confirm password.";
    }
     // Close statement
     $stmt->close();
}

if(isset($_POST['modifypassword'])){
    $sql="select password from doctor where username=?";
    $stmt=$conn->prepare($sql);
                        
    $stmt->bind_param("s",$_SESSION['username']);
    if(!$stmt->execute()){
        die( "Error") ;
    }
    $result = $stmt->get_result();
    $row = $result->fetch_assoc(); // <-- important : récupérer les données ici
    if(!password_verify($_POST['oldpassword'], $row['password'])){
        echo "hu";
        $errors['passwordfalse']="invalid password";
    }

    else{
        if($_POST['newpassword']==$_POST['cpassword']){
            $password_hache=password_hash( $_POST['newpassword'],PASSWORD_BCRYPT);
            $sql="update doctor set password=? where username =? ";
            $stmt=$conn->prepare($sql);
            $stmt->bind_param("ss",$password_hache, $_SESSION['username']);
            if(!$stmt->execute()){
                echo "Error:" ;
            }
            $_SESSION['info']="Modify password succesfuly.";
        }
        else{
            $errors['password']="not matched confirm password.";
        }
         // Close statement
         $stmt->close();
    }                  

   
}




//click th search bar button in patientconsult.php
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_id'])){
    $sql="select role from doctor where username=? ";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("s",$_SESSION['username']);
    if($stmt->execute()){
        if($result=$stmt->get_result()){
            $row = $result->fetch_assoc();
            if( $row && $row['role']=='non admin'){
                $sql="select * from patient where doctor_id=? and id=? ";
                $stmt=$conn->prepare($sql);
                $stmt->bind_param("ss",$_SESSION['username'],$_POST['patient_id']);
                if($stmt->execute()){
                    $result=$stmt->get_result();
                    if($result->num_rows){
                        echo $result->num_rows;
                        $patientid = $_POST['patient_id']; // Assure-toi que la variable est bien définie
                        header("Location:patientfile.php?patientId=" . urlencode($patientid));
                        exit();
                    $stmt->close();
                }
               
                }
            }
            else{
                $sql="select * from doctor where  username=? ";
                $stmt=$conn->prepare($sql);
                $stmt->bind_param("s",$_POST['patient_id']);
                if($stmt->execute()){
                    $result=$stmt->get_result();
                    if($result->num_rows){
                        echo $result->num_rows;
                        $doctorusername = $_POST['patient_id']; // Assure-toi que la variable est bien définie
                        header("Location:doctorprofile.php?username=" . urlencode($doctorusername));
                        $stmt->close();
                        exit();
                    }
                    else{
                        $sql="select * from patient where  id=? ";
                        $stmt=$conn->prepare($sql);
                        $stmt->bind_param("s",$_POST['patient_id']);
                        if($stmt->execute()){
                            $result=$stmt->get_result();
                            if($result->num_rows){
                                echo $result->num_rows;
                                $patientid = $_POST['patient_id']; // Assure-toi que la variable est bien définie
                                header("Location:patientfile.php?patientId=" . urlencode($patientid));
                                exit();
                            }
                        }
                       
                    }
                }
               
            }
            
            
        }
        
        
    }
    // Close statement
    $stmt->close();

}
     

// click the symptom delete button
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_symptom'])){
    $symptomId = $_POST['symptom_id']; // Assign to variable
   
    $sql="DELETE FROM symptoms WHERE symptom_id= ? ";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("i",$symptomId);
    if(!$stmt->execute()){
        die("error! the symptom could not delete it");
    }
    
     // Close statement
     $stmt->close();

}


// click the diagnosis delete button
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_diag'])){
    echo $_POST['delete_diag'];
    $sql="DELETE FROM diagnosis WHERE diagnosis_id= ? ";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("s",$_POST['diagnosis_id']);
    if(!$stmt->execute()){
        die("error! the diagnisis could not delete it");
    }
     // Close statement
     $stmt->close();

}


// click the treatment delete button
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_treat'])){
    $sql="DELETE FROM treatments WHERE treatment_id= ? ";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("s",$_POST['treatment_id']);
    if(!$stmt->execute()){
        die("error! the treatment  could not delete it");
    }
     // Close statement
     $stmt->close();

}

//click add Symptome patientfile.php
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addSymptome'])){
    $sql=" insert into symptoms (patient_id,symptom_name,severity,start_date,registration_date) values (?,?,?,?,?)";
    $stmt=$conn->prepare($sql);
    $today=date("Y-m-d");
    $stmt->bind_param("issss",$_POST['patientId'],$_POST['symptom'],$_POST['severity'],$_POST['date_start'],$today);
    if(!$stmt->execute()){
        die("error! the treatment  could not delete it");
    }
     // Close statement
     $stmt->close();
     $patientid = $_POST['patientId'];
     header("Location:patientfile.php?patientId=" . urlencode($patientid));
     exit();

}

//click  addDiagnosis patientfile.php
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addDiagnosis'])){
    $sql=" insert into diagnosis (patient_id,diagnosis_name,diagnosis_notes,diagnosis_date,doctor_id) values (?,?,?,?,?)";
    echo $_POST['patientId'];
    echo $_POST['patientId'];
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("issss",$_POST['patientId'],$_POST['diagnosis'],$_POST['diag_note'],$_POST['diag_date'],$_SESSION['username']);
    if(!$stmt->execute()){
        die("error! the treatment  could not delete it");
    }
     // Close statement
     $stmt->close();
     if($_POST['det']==1){
        $patientid = $_POST['patientId'];
        header("Location:patientfile.php?patientId=" . urlencode($patientid));
        exit();
    }

}


//click  addTreatment in patientfile.php
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addTreatment'])){
    $sql=" insert into treatments (patient_id,diagnosis_id,treatment_name,medication_name,dosage,treatment_duration,treatment_notes) values (?,?,?,?,?,?,?)";
    
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("iisssss",
    $_POST['patientId'],
     $_POST['Diagnosis_id'],$_POST['Treatment'],
    $_POST['medication'],$_POST['dosage'],
    $_POST['treatment_duration'],$_POST['treatment_note']
    );
          
    if(!$stmt->execute()){
        die("error! the treatment  could not add");
    }
    // Close statement
    $stmt->close();
   
    if($_POST['det']==1){
        $patientid = $_POST['patientId'];
        header("Location:patientfile.php?patientId=" . urlencode($patientid));
        exit();
    }
    
}

// click editpersonnelInf button
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editpersonnelInf'])){
    
    $sql="update patient set 
    Last_name=?,
    First_name=?,
    Gender=?,
    birthdate=?,
    address=?,
    contact_phone=?,
    email=?,
    registration_day=?,
    height_cm=?,
    weight_kg=?,
    smoking_alcohol=?,
    blood_type=?,
    bmi=?,
    physical_condition=?,
    chronic_diseases=?,
    medical_history=?,
    surgical_history=?,
    drug_allergies=?
   where id=?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("sssssssssssssssssss",
    $_POST['Last_name'],
    $_POST['First_name'],
    $_POST['gender'],
    $_POST['birthdate'],
    $_POST['address'],
    $_POST['contact_phone'],
    $_POST['email'],
    $_POST['registration_day'],
    $_POST['height_cm'],
    $_POST['weight_kg'],
    $_POST['smoking_alcohol'],
    $_POST['blood_type'],
    $_POST['bmi'],
    $_POST['physical_condition'],
    $_POST['chronic_diseases'],
    $_POST['medical_history'],
    $_POST['surgical_history'],
    $_POST['drug_allergies'],
    $_POST['id']);
    try{
        $stmt->execute();
    }
    catch (Exception $exp){
        die($exp);
    }
    finally{
        // Close statement
     $stmt->close();
     $_SESSION['info']="Patient information successfully updated";
    }

}
// click editdoctorlInf button
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editdoctorlInf'])){
    
    $sql="update doctor set 
            role=?,
            full_name=?,
            gender=?,
            CIN=?,
            specialization=?,
            contact_info=?,
            email=?,
            Degree=?,
            Years_of_Experience=?,
            License_Issue_Date=?,
            License_Expiry_Date=?,
            Medical_License_Number=?,
            professional_memberships1=?,
            License_Issuing_Authority=?,
            professional_memberships2=?,
            working_hours=?,
            Official_Working_Hours=?,
            Weekly_Days_Off=?
             where  username=? and status='actif'";
    $fullname= $_POST['Last_name']  .' ' . $_POST['First_name'];
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("sssssssssssssssssss",
    $_POST['role'],
    $fullname,
    $_POST['gender'],
    $_POST['CIN'],
    $_POST['specialization'],
    $_POST['contact_info'],
    $_POST['email'],
    $_POST['Degree'],
    $_POST['Years_of_Experience'],
    $_POST['License_Issue_Date'],
    $_POST['License_Expiry_Date'],
    $_POST['Medical_License_Number'],
    $_POST['professional_memberships1'],
    $_POST['License_Issuing_Authority'],
    $_POST['professional_memberships2'],
    $_POST['working_hours'],
    $_POST['Official_Working_Hours'],
    $_POST['Weekly_Days_Off'],
    $_POST['username']);
    try{
        $stmt->execute();
        if($_POST['username'] == $_SESSION['username']){
            $_SESSION['name'] = $fullname;
        }
    }
    catch (Exception $exp){
        die($exp);
    }
    finally{
        // Close statement
     $stmt->close();
     $_SESSION['info']="Doctor information successfully updated";
    }

}

// click editSymptome button 
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editSymptome'])){
    $sql=" update symptoms set symptom_name=?,severity=?,start_date=?,registration_date=? where symptom_id=?";
    $stmt=$conn->prepare($sql);
    $today=date("Y-m-d");
    $stmt->bind_param("sssss",$_POST['symptom'],$_POST['severity'],$_POST['date_start'],$today,$_POST['symp_id']);
    if(!$stmt->execute()){
        die("error! the treatment  could not delete it");
    }
     // Close statement
     $stmt->close();
     $_SESSION['info']="Symptom successfully updated";
    

}



// click edittreatment button 
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edittreatment'])){
   
    $sql=" update treatments set treatment_name=?,medication_name=?,dosage=?,treatment_duration=?,treatment_notes=? where treatment_id=?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("sssss",$_POST['treatment'],$_POST['medication'],$_POST['dosage'],$_POST['treatment_duration'],$_POST['treatment_note'],$_POST['treatment_id']);
    if(!$stmt->execute()){
        die("error! edittreatment");
    }
     // Close statement
     $stmt->close();
     $_SESSION['info']="Treatment successfully updated";
   

}

// click editdiagnosis button 
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editdiagnosis'])){
   
    $sql=" update diagnosis set diagnosis_name=?,diagnosis_date=?,diagnosis_notes=? where diagnosis_id=?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("ssss",$_POST['diagnosis_name'],$_POST['diagnosis_date'],$_POST['diagnosis_notes'],$_POST['diag_id']);
    if(!$stmt->execute()){
        die("error! editdiagnosis");
    }
     // Close statement
     $stmt->close(); 
     $_SESSION['info']="Diagnosis successfully updated";

}
//click delete_Doctor button in Admin.doctorrecords.php
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_Doctor'] )){
    $sql="DELETE FROM doctor WHERE username = ?;";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("s",$_POST['username']);
    $stmt->execute();
}

//click delete_patient button
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_patient'] )){
    $sql="DELETE FROM patient WHERE id = ?;";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("s",$_POST['id']);
    try{
        $stmt->execute();
        header('location: ../Admin/patientrecords.php');
    }
    catch (Exception  $ex){
        echo $ex;
    }
}


// click delete_doctor button

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_doctor'] )){
    $today=date("Y-m-d");
    $sql="update  doctor set status='inactif',doctor_removed_at=? WHERE username = ?;";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("ss",$today,$_POST['username']);
    try{
        $stmt->execute();
        header('location: ../Admin/change_deleteddoctor.php');
    }
    catch (Exception  $ex){
        echo $ex;
    }
}


//click validatebewdoctor button
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['validatebewdoctor'])){
    $stmt = $conn->prepare("update patient set doctor_id=? where id=?");
    $stmt->bind_param("ss",$_POST['Doctor_usernameSp'],$_POST['id']);
    try{
        $stmt->execute();
    }catch(Exception  $ex){
        echo $ex;
    }
}


// Mise à jour du statut si "Resolve" est cliqué
if (isset($_POST['validaterequests'])) {
    $id = $_POST['requestid'];
    
    $stmt = $conn->prepare("UPDATE support_requests SET status = 'Resolved' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows < 0) {
        echo "<p style='color:green;'>Request #$id marked as resolved.</p>";
    }

    $stmt->close();
}

?>


