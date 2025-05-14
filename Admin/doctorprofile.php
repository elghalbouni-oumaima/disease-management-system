
<!--  delete_warning Modal -->
<div class="modal fade" id="deletedoctor_warning" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div  style="justify-content: space-between;padding:30px;text-align:center;">
        <i class='bx bx-message-error' style="font-size:6rem;color: #ffe082 ;"></i>
        <h2>Are you sure ?</h2>
        <span>You won't be able to revert this!</span>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <form action="" method="post">
                <input type="hidden" name="username" id="modalusername">    
                <button type="submit" class="btn btn-primary" name="delete_doctor" style="background-color:red;">Continue</button>
            </form>
        </div>  
      
     
    </div>
  </div>
</div>
<!-- delete_warning Modal End-->

<?php require_once 'Home.php';?>
<head>
<link rel="stylesheet" href="../css/showtable.css">
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
$stmt->bind_param("s",$_GET['username']);
try{
    $stmt->execute();
    $res=$stmt->get_result();
    $row=$res->fetch_assoc();
    ?>
                            <div class="cadre">
                                <button onclick="generatePDF('<?php echo $_GET['username'];?>','../Admin/doctorprofile_topdf.php','username')" class="pdfbtn">Download PDF</button>

                                <h1 style="margin:30px;"><center style="color:#44b0f8;" >Doctor Profile</center></h1>
                                <div  class="profile-container" >
                                    <div class="img-profile" >
                                    
                                        <img src="../images/profile doctor photo.png" alt="profile image">
                                        <span > <?php echo $row['full_name']?> </span>
                                        <a href="mailto:<?php echo $row['email']?>"  > <?php echo $row['email']?></a>
                                    </div>
                                    
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
                                <div style="width:100%;justify-content:flex-end;display:flex">
                                    <form action="editdoctorInf.php" method="get">
                                        <input type="hidden" name="username" value="<?php echo $_GET['username']?>">
                                        <button  type="submit"  id="editinfbtn">Edit</button>
                                    </form>
                                    <form action="" method="get" >
                                        <input type="hidden" name="username" value="<?php echo $_GET['username']?>">
                                        <button   id="deletedoctorbtn"type="button" 
                                            data-toggle="modal" 
                                            data-target="#deletedoctor_warning" 
                                            data-username=<?php echo $_GET['username']; ?>>
                                            Delete
                                        </button>
                                    </form>
                                </div> 
                            </div>
                            <h2><center>Patient</center></h2>
                            <div  class="consult_patient"  style="margin-top:20px;">
                            <?php
                                $sql="select id ,Full_name,Gender,birthdate,diagnosis_name,diagnosis_date,treatment from ( select p.id,concat(p.Last_name,' ',p.First_name) as Full_name,p.Gender,p.birthdate,d.diagnosis_name, d.diagnosis_date,t.treatment,
                                                    ROW_NUMBER() OVER (PARTITION BY p.id ORDER BY d.diagnosis_date DESC) as row_num
                                                    from patient as p
                                                    left join diagnosis as d on  p.id=d.patient_id
                                                    left join (select patient_id, diagnosis_id,group_concat(concat(treatment_name ,' - ',treatment_duration,' days') separator ' / ') as treatment from treatments group by patient_id,diagnosis_id 
                                                    ) as t on t.diagnosis_id= d.diagnosis_id where p.doctor_id=?)
                                                    as tab where row_num=1  order by diagnosis_date desc ";
                                $stmt=$conn->prepare($sql);
                                $stmt->bind_param("s",$_GET['username']);
                                if(!$stmt->execute()){
                                    die( "Error") ;
                                }
                                $result = $stmt->get_result();
                                $nb_rows = $res->num_rows;
                                ?>
                                <h3 style=" margin: 40px  0px 20px 20px;font-size:20px" ><i class='bx bxs-check-circle' style="color:green;"></i> Total number of items found <?php echo  $nb_rows;?></h3>
                                <div class="table_body"  >
                                    <table style="width:100% !important"  >
                                        <thead style="background-color:#f8f9fc ;">
                                            <tr style="text-align:center;background-color:#f8f9fc ">
                                                <th style="background-color:#44b0f8 ;">Id</th>
                                                <th style="background-color:#44b0f8 ;">Full name</th>
                                                <th style="background-color:#44b0f8 ;">Gender</th>
                                                <th style="background-color:#44b0f8 ;">Birth date</th>
                                                <th style="background-color:#44b0f8 ;">diagnosis</th>
                                                <th style="background-color:#44b0f8 ;">Date of diagnosis </th>
                                                <th style="background-color:#44b0f8 ;">Treatments</th>
                                                <th style="background-color:#44b0f8 ;">Action</th>
                                                    
                                            </tr>
                                        </thead>
                                        <tbody>
                                                
                                                
                                                    <?php
                                                    foreach ($result as $itm){
                                                        ?>
                                                        <tr>
                                                        <?php
                                                        foreach ($itm as $row){
                                                            if (!$row){
                                                                $row='none';
                                                            }
                                                            ?>
                                                            <td style="text-align:center;"> <?php echo $row?></td>
                                                            <?php
                                                        }
                                                        ?>
                                                        <td>
                                                            <form action="patientfile.php" method="get">
                                                            <input type="hidden" name="patientId" value=<?php echo $itm['id'];?>>
                                                            <button type="submit" class="seepatient-btn">See more</button>
                                                            </form>
                                                        </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    
                                                    ?>

                                            </tbody>
                                        </table>
                                    </div>
                                        
                                </div> 
                                
                               
                            </div>
                                    
    <?php
} catch( Exception  $ex){
echo $ex;
}
?>
<script src="../js/script.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

<script>
    
    var tr_elm=document.querySelectorAll('tr');
tr_elm.forEach(tr => {
    tr.addEventListener('mousemove',function(){
        let btn=tr.querySelector('.seepatient-btn');
        if(btn){
            btn.style.backgroundColor ='#1cc88a';
        }
     });
})
tr_elm.forEach(tr => {
    tr.addEventListener('mouseout',function(){
        let btn=tr.querySelector('.seepatient-btn');
        if(btn){
            btn.style.backgroundColor ='red';
        }
     });
})

$('#deletedoctor_warning').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget); // Récupère le bouton qui a déclenché la modale
      var Id = button.data('username');  // Récupère data-diagid 
      console.log("id= ",Id);
      var modal = $(this); // La modale elle-même
      modal.find('#modalusername').val(Id); 
    });
</script>
</body>
</html> 