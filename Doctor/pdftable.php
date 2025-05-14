<?php
require_once  "../Login/controllerUserData.php";
$stmt=$conn->prepare("select count(*) as nboftreatperdiag from treatments t
where t.patient_id in(select id from patient where doctor_id=?)
group by t.patient_id, t.diagnosis_id");
$stmt->bind_param("s",$_SESSION['username']);
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
where t.patient_id in(select id from patient where doctor_id=?)
group by t.patient_id");
$stmt->bind_param("s",$_SESSION['username']);
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
    <title>PDFrecords</title>
</head>
<body style="display:flex; justify-content:center;flex-direction:column;
    align-items:center;font-family: Arial, sans-serif;">
  <h1 style="color: #1cc88a;">Patients Treatments Summary</h1>
<table id="pdftable"  style="
    
    border-collapse: collapse;
    margin-top: 30px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    text-align: center;" 
    border="2" >
            <thead>
                <tr id="icon_arrow" style="
            background-color: #1cc88a;
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
                    where  doctor_id=? and p.id in(select patient_id from treatments) group by p.id");
                    $stmt->bind_param("s",$_SESSION['username']);
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
<div style="text-align: left; margin-top: 20px; font-size: 16px;width:80%">
    <strong>Date: </strong><?php echo date('d/m/Y'); ?>
</div> 
<script>
    window.onload = function() {
        window.print();
        setTimeout(() => window.close(), 100);
    }
</script>
        
</body>
</html>

            