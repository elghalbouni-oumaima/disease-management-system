<?php 
require_once 'Home.php';
?>
<head>
    <style>

        table{
            margin-left: 10px;
            margin-top: 20px;
        }
        tbody tr:hover{
            background: transparent !important;
            
        }
        tbody td{
            padding: 10px 25px;
            border-bottom:1px solid black;
            text-align: left;
           
        }
        tbody  span{
            display: flex;
        }
        thead{
            background-color:  #eeeeee ;
            
        }
        thead th{
           text-align: center;
           color:black !important;
           position: static !important;
        }
        tbody select{
             background:  #7986cb  ;
             border:none;
             color:white;
             padding: 2px;
             margin-left: 5px;
        }
        tbody button{
            border:none;
            border-radius:25px;
            padding: 5px 10px;
            color: white;
            
        }
        .seepatient-btn{
        background-color: red;
        padding: 4px 6px ;
    }
    .seepatient-btn:hover{
        background-color: #1cc88a;
    }
    .validate-btn{
        background-color:rgb(60, 243, 176);
    }
    .validate-btn:hover{
        background-color:#7986cb;
    }
    .title{
        margin: 30px 20px;
    }
    .title i{
        color:green;
    }
    </style>
</head>
<h2 class="title">Assign New Doctors to Patients of a Removed Doctor</h2>
<?php
$sql = "select  d.username,d.full_name,d.specialization,d.doctor_removed_at,count(p.id ) as rowspan from doctor d,patient p 
where d.username=p.doctor_id and d.status = 'inactif'
group by d.username,d.full_name,d.specialization order by doctor_removed_at;";

$stmt=$conn->prepare($sql);
if($stmt->execute()){

    $res=$stmt->get_result();
    $nb_rows = $res->num_rows;
    
    ?>
    <h3 style=" margin: 40px  0px 10px 20px;font-size:20px" ><i class='bx bxs-check-circle' style="color:green;"></i> Total number of items found <?php echo  $nb_rows;?></h3>

    <table>
        <thead>
            <th style="border-right:1px solid black">Deleted Doctor</th>
            <th>Patients</th>
            <th>New Doctor</th>
            <th>Validate</th>
        </thead>
        <tbody>
            <?php
            if($res){
                foreach($res as $row){
                    if($row['rowspan']>0){
                        ?>
                        <tr>
                            <td rowspan= <?php echo $row['rowspan']?> style="border-right:1px solid black">
                                <div style="width:100%;text-align:center;margin-bottom:15px">
                                    <img class="avatar" src="../images/undraw_young-man-avatar_wgbd.svg" alt="" width="40" >
                                </div>
                                <span><strong>Username : </strong> <?php echo $row['username'];?></span><br>
                                <span><strong>Full name : </strong> Dr.<?php echo $row['full_name'];?> </span><br>
                                <span><strong>Specialization : </strong><?php echo $row['specialization'];?></span>
                                <span><strong>Deletion date : </strong><?php  if($row['doctor_removed_at']) echo $row['doctor_removed_at'];else echo 'Not available'?></span>
                            </td>
                            <?php
                                $j=0;
                                $sql ="select id,concat(Last_name,' ',First_name) as Full_name from patient where doctor_id=?";
                                $patientstmt=$conn->prepare($sql);
                                $patientstmt->bind_param("s",$row['username']);
                                if($patientstmt->execute()){
                                    $patientres=$patientstmt->get_result();
                                    foreach($patientres as $rowpatient){
                                        if($j!=0){
                                            ?>
                                             </tr>
                                             <tr>
                                            <?php
                                        }
                                        ?>
                                        <td>
                                            <span><strong>Patient ID : </strong><?php echo $rowpatient['id'];?></span><br>
                                            <span>Full name : <?php echo $rowpatient['Full_name'];?></span><br>
                                            <form action="patientfile.php" method="get" style="width:100%;text-align:end">
                                                <input type="hidden" name="patientId" value=<?php echo $rowpatient['id'];?>>
                                                <button type="submit" class="seepatient-btn">See more</button>
                                            </form>
                                        </td>
                                        <td>
                                            <form method="post" style="width:100%;text-align:center">
                                                <input type="hidden" name="id" value="<?php echo $rowpatient['id'];?>">
                                                <span>
                                                    Specialization : 
                                                    <select name="specialization" class="choixSp" >
                                                        <?php
                                                        $stmtspecia=$conn->prepare("select distinct specialization from doctor");
                                                        if($stmtspecia->execute()){
                                                            $speciaresult=$stmtspecia->get_result();
                                                            foreach($speciaresult as $choice){
                                                                if($choice['specialization'] == $row['specialization']){
                                                                ?>
                                                                    <option value="<?php echo $row['specialization'];?>" style="color:black"  selected><?php echo $row['specialization'];?></option>
                                                                <?php
                                                                }
                                                                else{
                                                                ?>
                                                                    <option value="<?php echo $choice['specialization'] ?>" style="color:black"><?php echo $choice['specialization'] ?></option>
                                                                <?php
                                                                }
                                                                
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </span> <br>
                                                <span>
                                                    Doctor :     
                                                    <select name="Doctor_usernameSp" class="choixnewdoctor">
                                                        <?php
                                                        $stmtnewdoctor=$conn->prepare("select distinct username from doctor where specialization=? and status='actif' order by CAST(SUBSTRING(username, 2) AS UNSIGNED)");
                                                        $stmtnewdoctor->bind_param("s",$row['specialization']);
                                                        if($stmtnewdoctor->execute()){
                                                            $newdoctorresult=$stmtnewdoctor->get_result();
                                                            foreach($newdoctorresult as $newdoctor){
                                                                ?>
                                                                    <option value="<?php echo  $newdoctor['username'] ?>" style="color:black"><?php echo  $newdoctor['username'] ?></option>
                                                                <?php
                                                                
                                                                
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </span>
                                                <td style="padding: 10px 50px;">
                                                <button  type="submit"  name="validatebewdoctor"  class="validate-btn">Validate</button>
                                                </td>                                                
                                            </form>
                                        </td>

                                        </tr>

                                        <?php
                                        $j++;
                                    }
                                    
                                }
                                
                            
                            ?>
                       
                       
                        <?php
                    }
                }
            }
                   
            ?>
            
        </tbody>
    </table>
    <?php
}

?>
<script>
document.querySelectorAll('tr').forEach(row => {
    const specializationSelect = row.querySelector('.choixSp');
    const doctorSelect = row.querySelector('.choixnewdoctor');

    if (specializationSelect && doctorSelect) {
        specializationSelect.addEventListener('change', function () {
            const specialization = this.value;
            console.log(specialization);

            fetch('get_doctors_by_specialization.php?specialization=' + encodeURIComponent(specialization))
                .then(response => response.text())
                .then(data => {
                    doctorSelect.innerHTML = data;
                    console.log("data :",data);
                })
                .catch(error => console.error('Error:', error));
        });
       
    }
});
</script>

</body>
</html>