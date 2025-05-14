<?php
require_once  "../Login/controllerUserData.php";
$stmt=$conn->prepare("select count(*) as nboftreatperdiag from treatments t
where t.patient_id =?
group by t.patient_id, t.diagnosis_id");
$stmt->bind_param("s",$_GET['patientId']);
$stmt->execute();
$result= $stmt->get_result();
$nboftreatperdiag=[];
$i=0;
while ($row = $result->fetch_assoc()) {
   $nboftreatperdiag[$i]=$row['nboftreatperdiag'];
   $i++;
}



//////////////////////////////////////
$stmt=$conn->prepare("select  count(distinct t.diagnosis_id) as nbofdiagnosisperpatient from treatments t
where t.patient_id=?
group by t.patient_id");
$stmt->bind_param("s",$_GET['patientId']);
$stmt->execute();
$result= $stmt->get_result();
$nbofdiagnosisperpatient=[];
$i=0;
while ($row = $result->fetch_assoc()) {
   $nbofdiagnosisperpatient[$i]=$row['nbofdiagnosisperpatient'];
   
   $i++;
}
$rowspanperpatient=[];
$index=0;
$s=0;
$j=0;
foreach($nbofdiagnosisperpatient as $itm ){
  
   $i=0;
   while($i<$itm){
       $s+=$nboftreatperdiag[$index];
       $index++;
       $i++;
   }
  
   $rowspanperpatient[$j]=$s;
   $j++;
   $s=0;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/stylepatientfile.css">
    <style>
    .profile-container{
        display: flex;
        align-items: center;
        gap:80px;
       
        margin-bottom: 30px;
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
    }
    .img-profile img {
        border-radius:50%;
        width: 200px;
        margin-left: 40px;
        margin-top: 10px;
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
    .personelinf-container div:first-child span:hover{
        text-decoration: underline;
    }
    .inf-container{
        display: flex;
        flex-direction:column;
    }
    .inf-container span,.img-profile  span{
        font-size:16px;
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
    .pdfbtn{
    border: none;
    background-color:  #f5b041  ;
    padding: 5px;
    margin-top: 20px;
    color: white;
    border-radius: 5px;
    width: max-content;
    margin-left: 5px;
}
.pdfbtn:hover{
    background-color: red;
}
</style>
</head>
<body  style="display:flex; justify-content:center;flex-direction:column;
    align-items:center;font-family: Arial, sans-serif;">
    

        <section class="personnel-inf" style="width:auto;border:none;box-shadow:none;">
            <section class="table_title">
                <h1 > Personnel Information</h1>
            </section>
           
            <section >
                    
                        <?php
                            $sql="select concat(Last_name,' ',First_name) as Full_name,Gender,birthdate,address ,contact_phone,email ,registration_day,
                            height_cm, weight_kg, smoking_alcohol, blood_type, bmi, physical_condition, chronic_diseases, medical_history,
                            surgical_history, drug_allergies  from patient where id=?";
                            $stmt=$conn->prepare($sql);
                            
                            $stmt->bind_param("s",$_GET['patientId']);
                            if(!$stmt->execute()){
                                die( "Error") ;
                            }
                            $result = $stmt->get_result();
                            $row = $result->fetch_assoc();
                           
                        ?>
                                <div  class="profile-container" >
                                    <div class="img-profile">
                                        <div>
                                            <span ><strong style="font-size:18px;text-decoration:underline;margin-left: 40px;">Registration Date :</strong>   <?php echo $row['registration_day']?></span>
                                        </div>
                                    
                                        <img src="../images/profile_image.png" alt="profile image">
                                        <span style="margin-left: 40px;"> <?php echo $row['Full_name']?> </span>
                                        <a href="mailto:<?php echo $row['email']?>"  style="margin-left: 40px;"> <?php echo $row['email']?></a>
                                    </div>
                                    
                                    <div class="personelinf-container">
                                        <div class="inf-container">
                                            <span style="color:#4468f8;"> Full Name : </span>
                                            <span style="color:#4468f8;"> Gender :</span>
                                            <span style="color:#4468f8;"> Birth Date : </span>
                                            <span style="color:#4468f8;"> Phone Number : </span>
                                            <span style="color:#4468f8;"> Email : </span>
                                            <span style="color:#4468f8;"> Address :</span>
                                        </div>
                                        <div class="inf-container">
                                            <span> <?php echo $row['Full_name']?></span>
                                            <span><?php echo $row['Gender']?></span>
                                            <span> <?php echo $row['birthdate']?></span>
                                            <span> <?php if($row['contact_phone']){echo $row['contact_phone'];} else echo 'None'; ?></span>
                                            <span>  <a href="mailto:<?php echo $row['email']?>"><?php if($row['email']){echo $row['email'];} else{ echo 'None';} ?></a> </span>
                                            <span id="address">  <?php echo $row['address']?></span>
                                        </div>
                                    </div>
                                </div>
                                <section class="table_title">
                                    <h1 > Basic Medical Information </h1>
                                </section> 
                                <div  class="profile-container"  style="margin-top:20px;">
                                    <div class="inf-container"  style="margin-left:20px;">
                                                <span style="color:#4468f8;font-weight:400"> Physical Condition : </span>
                                                <span style="color:#4468f8;font-weight:400"> Chronic Diseases :</span>
                                                <span style="color:#4468f8;font-weight:400"> Medical History : </span>
                                                <span style="color:#4468f8;font-weight:400"> Surgical History : </span>
                                                <span style="color:#4468f8;font-weight:400"> Drug Allergies : </span>
                                                

                                    </div>
                                    <div class="inf-container">
                                                <span> <?php echo $row['physical_condition']?></span>
                                                <span><?php  if($row['chronic_diseases']){echo $row['chronic_diseases'];} else echo$row['chronic_diseases'];?></span>
                                                <span> <?php if( $row['medical_history']) echo $row['medical_history'];else echo 'None';?></span>
                                                <span>  <?php if( $row['surgical_history']) echo $row['surgical_history'];else echo 'None';?> </span>
                                                <span> <?php if( $row['drug_allergies']) echo $row['drug_allergies']; else echo 'None';?></span>
                                                

                                     </div>
                                     <div class="inf-container">
                                                <span style="color:#4468f8;"> Smoking Alcohol : </span>
                                                <span style="color:#4468f8;"> Blood Type :</span>
                                                <span style="color:#4468f8;"> BMI : </span>
                                                <span style="color:#4468f8;"> Height (Cm) : </span>
                                                <span style="color:#4468f8;"> Weight (kg) : </span>

                                     </div>
                                     <div class="inf-container">
                                                <span>  <?php echo $row['smoking_alcohol']?></span>
                                                <span>  <?php echo $row['blood_type']?></span>
                                                <span>  <?php if( $row['bmi']) echo $row['bmi']; else echo 'None';?></span>
                                                <span>  <?php if( $row['height_cm']) echo $row['height_cm']; else echo 'None';?></span>
                                                <span>  <?php if( $row['weight_kg']) echo $row['weight_kg'];else echo 'None';?></span>
                                     </div>

                                </div>
                                    

            </section>
           
            
        </section>

        <h2 style="color: #4468f8;margin-top: 40px;">Patients Treatments Summary</h2>
<table id="pdftable"  style="
    
    border-collapse: collapse;
    margin-top: 5px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    text-align: center;" 
    border="2" >
            <thead>
                <tr id="icon_arrow" style="
            background-color: #4468f8;
            color: white;
            height: 50px;
            font-size:16px">
                    <th style="padding:10px">Patients</th>
                    <th  style="padding:10px">Diagnosis</th>
                    <th  style="padding:10px">Treatments</th>
                    <th  style="padding:10px">Medication </th>
                    <th  style="padding:10px">Dosage </th>
                    <th  style="padding:10px">Treatments duration</th>
                    <th  style="padding:10px">Treatments note </th>
                   
                                
                </tr>
            </thead>
            <tbody>
            
                <?php
                                                         
                    $stmt=$conn->prepare("select p.id as patientid, concat(p.Last_name,' ',p.First_name) as fullname from patient p
                    where p.id=? group by p.id");
                    $stmt->bind_param("s",$_GET['patientId']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                   
                    ?>
                    <?php
                                                         
                    $i=0;
                    $j=0;
                    foreach($result as $row ){
                        
                        $index1=0;
                        $stmtdiag=$conn->prepare("select  d.diagnosis_name as diagnosis_name,d.diagnosis_date as diagdate,d.diagnosis_id as diagid from  diagnosis d where d.patient_id=? group by d.diagnosis_id");
                        $stmtdiag->bind_param("s",$row['patientid']);
                        $stmtdiag->execute();
                        $resultdiag = $stmtdiag->get_result();
                                                                     
                        foreach($resultdiag as $rowdiag ){
                        $index2=0;
                        $stmtTreat=$conn->prepare("select t.treatment_id as treatment_id,
                        t.treatment_name,
                        t.medication_name,
                        t.dosage,
                        concat(t.treatment_duration,' ','Days'),
                        t.treatment_notes 
                        from treatments t
                        where t.diagnosis_id =?");
                        $stmtTreat->bind_param("s",$rowdiag['diagid']);
                        $stmtTreat->execute();
                        $resultTreat = $stmtTreat->get_result();
                        if($index1==0){
                        ?>
                        <tr style="border-bottom: 1px solid #ddd; background-color: #f9f9f9;">
                        <td rowspan="<?php echo $rowspanperpatient[$i];?>" style="padding:20px;"> <?php echo $row['fullname'];?></td>
                        <?php
                        $index1=1;
                        }
                                             
                        foreach($resultTreat as $rowTreat ){
                        if($index2==0){
                        ?>
                        <td rowspan="<?php echo $nboftreatperdiag[$j];?>" style="padding:20px;"> <?php echo $rowdiag['diagnosis_name']. '<br>' .$rowdiag['diagdate'];?></td>
                                                                                     
                        <?php
                        $j++;
                        $index2=1;
                        }
                        else{
                    ?>
                    </tr>
                    <tr style="border-bottom: 1px solid #ddd; background-color: #f9f9f9;padding:40px">
                    <?php
                    }
                    foreach($rowTreat as $columnName => $value){
                        if ($columnName != 'treatment_id') {
                        ?>
                        <td style="padding:18px"><?php echo $value?></td>
                        <?php
                         }
                    }
                                                                             
                    ?>
                    
                    </tr>
                    <?php
                    }
                }
                $i++;
                }
                                             
                                                             
                ?> 
            </tbody>
</table> 
<script>
    window.onload = function() {
        window.print();
        setTimeout(() => window.close(), 100);
    }
</script>
</body>
</html>