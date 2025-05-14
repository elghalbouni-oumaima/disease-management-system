<?php require_once 'Home.php';?>
<style>
    /* .profile-container{
        display: flex;
        align-items: flex-end;
        gap:80px;
        font-family: Nunito, -apple-system, BlinkMacSystemFont, 
    "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, 
    "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
    }
    .img-profile{
        display: flex;
        flex-direction:column;
        align-items: center;
        font-family: cursive;
        gap:2px;
        box-shadow:0 5px 10px rgba(0,0,0,0.1);
        border-radius:50%;
        width:350px;
        height: 350px;
        margin-left: 40px;
        margin-top: 10px;
    }
    .img-profile img {
        border-radius:50%;
        width: 250px;
       
       
    }
    .img-profile a{
        white-space: nowrap;
    }
    .personelinf-container{
        display: flex;
        gap:10px;
        height: 300px;
        align-items: flex-end;
        padding-bottom: 10px;
    }
   
    .inf-container{
        display: flex;
        flex-direction:column;
    }
    .inf-container span,.img-profile  span{
        font-size:21px;
        font-family: Nunito, -apple-system, BlinkMacSystemFont, 
    "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, 
    "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        white-space: nowrap;
    }

    .inf-container span{
        padding: 6px;
    }
    #editinfbtn{
        background-color: #1cc88a;
        margin: 1rem 4rem 1rem 4rem;
        padding:7px 30px; 
        color:white;
        font-weight:400;
        border:none;
        font-size: 20px;
        border-radius:25px;
    }
    #editinfbtn:hover{
        background-color:rgb(21, 171, 116);
    }
    #addtreabtn:hover{
        background-color: #1cc88a !important;
    }
    #adddiagbtn:hover{
        background-color:rgb(76, 76, 255) !important;

    }
    #addsymbtn:hover{
        background-color: #ef5350 !important;

    }
.cadre{
   
    margin: 80px 30px;
    padding-left: 30px;
    box-shadow:0 5px 10px rgba(0,0,0,0.1);
    
}*/
</style> 
<head>
<link rel="stylesheet" href="../css/profile.css">

</head>
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
$stmt->bind_param("s",$_SESSION['username']);
try{
    $stmt->execute();
    $res=$stmt->get_result();
    $row=$res->fetch_assoc();
    ?>
                            <div class="cadre">
                                <button onclick="generatePDF('<?php echo $_SESSION['username'];?>','../Admin/doctorprofile_topdf.php','username')" class="pdfbtn">Download PDF</button>
                                <h1><center style="color:#44b0f8;">My Profile</center></h1>
                                <div  class="profile-container" >
                                    <div class="img-profile">
                                    
                                        <img src="../images/profile doctor photo.png" alt="profile image">
                                        <span style="margin-left: 40px;"> <?php echo $row['full_name']?> </span>
                                        <a href="mailto:<?php echo $row['email']?>"  style="margin-left: 40px;"> <?php echo $row['email']?></a>
                                    </div>
                                    
                                    <div class="personelinf-container">
                                        <div class="inf-container">
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
                                </div>
                                    
                                <div  class="profile-container"  style="margin-top:20px;">
                                    <div class="inf-container"  style="margin-left:20px;">
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
                                <div  class="profile-container"  style="margin-top:20px;">
                                     <div class="inf-container">
                                               

                                     </div>
                                     <div class="inf-container">
                                                
                                     </div>
                                </div>

                                
                            </div>
                                    
    <?php
} catch( Exception  $ex){

}
?>
</main>
</div>
<script src="../js/script.js"></script> 
</html>
</body>
