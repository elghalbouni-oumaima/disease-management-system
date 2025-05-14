<?php require_once  "../Login/controllerUserData.php";?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/profile.css">

    <title>Document</title>
</head>
<body>
<?php 
$sql="select username,
             password,
             role,
            full_name,
            gender,
            CIN,
            specialization,
            contact_info,
            email,

            Degree,
            Years_of_Experience,
            License_Issue_Date,
            License_Expiry_Date,
            Medical_License_Number,
            professional_memberships1,
            License_Issuing_Authority,
            professional_memberships2,
            working_hours,
            Official_Working_Hours,
            Weekly_Days_Off
            from doctor where  username=? ";
$stmt=$conn->prepare($sql);
$stmt->bind_param("s",$_GET['username']);
try{
    $stmt->execute();
    $res=$stmt->get_result();
    $row=$res->fetch_assoc();
    ?>
                            
                                <h1 style="margin:30px;"><center style="color:#44b0f8;" >Doctor Profile</center></h1>
                                <div  class="profile-container" >
                                    
                                    
                                    <div class="personelinf-container">

                                        <div class="inf-container">
                                            <h2 style="white-space: nowrap;">Personal Information</h2>
                                            <span style="color:#4468f8;"> Full Name : </span>
                                            <span style="color:#4468f8;"> Gender :</span>
                                            <span style="color:#4468f8;"> CIN : </span>
                                            <span style="color:#4468f8;"> Phone Number : </span>
                                            <span style="color:#4468f8;"> Email : </span>
                                            <span style="color:#4468f8;"> Username :</span>

                                        </div>
                                        <div class="inf-container">
                                            <span> <?php echo $row['full_name']?></span>
                                            <span><?php echo $row['gender']?></span>
                                            <span> <?php echo $row['CIN']?></span>
                                            <span> <?php if($row['contact_info']){echo $row['contact_info'];} else echo 'None'; ?></span>
                                            <span>  <a href="mailto:<?php echo $row['email']?>"><?php if($row['email']){echo $row['email'];} else{ echo 'None';} ?></a> </span>
                                            <span style="width:20px" id="username">  <?php echo $row['username']?></span>
                                        </div>
                                    </div>
                                    <div class="img-profile" style="width:300px;height:300px" >
                                    
                                        <img style="width:200px" src="../images/profile doctor photo.png" alt="profile image">
                                        <span > <?php echo $row['full_name']?> </span>
                                        <a href="mailto:<?php echo $row['email']?>"  > <?php echo $row['email']?></a>
                                    </div>
                                </div>
                                    
                                <div  class="profile-container"  style="margin-top:20px;">
                                    <div class="inf-container"  style="margin-left:20px;">
                                    <h2 style="white-space: nowrap;">Professional Information</h2>
                                                <span style="color:#4468f8;font-weight:400"> Specialization : </span>
                                                <span style="color:#4468f8;font-weight:400"> Professional memberships 1:</span>
                                                <span style="color:#4468f8;font-weight:400"> Professional emberships 2 : </span>
                                                <span style="color:#4468f8;font-weight:400"> Medical license number : </span>
                                                <span style="color:#4468f8;font-weight:400"> License issuing authority : </span>
                                                <span style="color:#4468f8;font-weight:400"> Degree : </span>
                                                <span style="color:#4468f8;"> License issue date : </span>
                                                <span style="color:#4468f8;"> License expiry date :</span>
                                                <span style="color:#4468f8;"> Weekly days off : </span>
                                                <span style="color:#4468f8;"> Official working hours : </span>
                                                <span style="color:#4468f8;"> Years of experience  : </span>

                                                

                                    </div>
                                    <div class="inf-container">
                                                <span> <?php echo $row['specialization']?></span>
                                                <span><?php  if($row['professional_memberships1']){echo $row['professional_memberships1'];} else echo$row['chronic_diseases'];?></span>
                                                <span> <?php if( $row['professional_memberships2']) echo $row['professional_memberships2'];else echo 'None';?></span>
                                                <span>  <?php if( $row['Medical_License_Number']) echo $row['Medical_License_Number'];else echo 'None';?> </span>
                                                <span> <?php if( $row['License_Issuing_Authority']) echo $row['License_Issuing_Authority']; else echo 'None';?></span>
                                                <span> <?php if( $row['Degree']) echo $row['Degree']; else echo 'None';?></span>
                                                <span>  <?php echo $row['License_Issue_Date']?></span>
                                                <span>  <?php echo $row['License_Expiry_Date']?></span>
                                                <span>  <?php echo $row['Weekly_Days_Off']?></span>
                                                <span>  <?php echo $row['Official_Working_Hours']?></span>
                                                <span>  <?php echo $row['Years_of_Experience']?> Year</span>
                                                

                                    </div>
                                </div>

                            
                            
                                    
    <?php
} catch( Exception  $ex){
echo $ex;
}
?>
<script>
     window.onload = function() {
        window.print();
        setTimeout(() => window.close(), 100);
     }
</script>
        
</body>
</html>