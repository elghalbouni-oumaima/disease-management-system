
<!--  delete_warning Modal -->
<div class="modal fade" id="deletepatient_warning" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                
                <input type="hidden" name="id" id="modalpatientid">    
                <button type="submit" class="btn btn-primary" name="delete_patient" style="background-color:red;">Continue</button>
            </form>
        </div>  
      
     
    </div>
  </div>
</div>
<!-- delete_warning Modal End-->


<?php require_once "Home.php"?>
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
.delete-btn{
    background-color:  #e74c3c;
    padding:5px 15px;
    color: white;
}
.delete-btn:hover{
    background-color:rgb(183, 62, 49);
    color: white;
}

.pdf-doctorinf{
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.doctorinf-btn{
background: transparent;
color:black;
border:none;
margin-right: 20px;
}
.doctorinf-btn{
background: transparent;
color:black;
border:none;
margin-right: 20px;
}
.doctorinf-btn i{
font-size: 20px;
color:black;

}

</style>
 <!-- prsonnel inf Start-->
    <div action="" method="post" style="text-align:end;margin-top:20px;margin-right:40px;">
        <button 
            name="delete_patient"  
            class="btn delete-btn"
            type="button" 
            data-toggle="modal" 
            data-target="#deletepatient_warning" 
            data-id=<?php echo $_GET['patientId']; ?>>
            <i class='bx bx-trash'></i> 
            Delete Patient
        </button>
    </div>
    <section class="personnel-inf">
        <div class="pdf-doctorinf">
            <button onclick="generatePDF('<?php echo $_GET['patientId'];?>','patientprofile_topdf.php','patientId')" class="pdfbtn">Download PDF</button>
            <label for="doctorinf_btn" title="Doctor Information" class="doctor-inflabel">
                <button
                    type="button" 
                    data-toggle="modal" 
                    data-target="#doctor_inf"
                    class="doctorinf-btn"
                    >
                    <i class='bx bxs-chevron-down'></i>
                </button>
            </label>

        </div>

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
                                            <span ><strong style="font-size:18px;text-decoration:underline;">Registration Date :</strong>   <?php echo $row['registration_day']?></span>
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
            <form action="editpatientInf.php" method="get" style="width:100%;text-align:center">
                <input type="hidden" name="patientId" value="<?php echo $_GET['patientId']?>">
                <button  type="submit"  id="editinfbtn">Edit</button>
            </form>
            
        </section>
                    <!-- prsonnel inf End-->
                    <!-- Symptom Start-->
        <section class="symptom">
            <section class="table_title">
            
                <h1 > Symptoms</h1>
                <i class='bx bxs-user-circle'></i>
            </section>
        
            <section  class="table_body">
            <table>
                <thead>
                    <tr style="text-align:center;">
                        <th>Id</th>
                        <th>Symptom</th>
                        <th>Severity</th>
                        <th>Start date </th>
                        <th>Edit</th>
                        <th>Delete</th>
                    
                    </tr>
                </thead>
                <tbody>
                    
                    <?php
                        $sql="select symptom_id,symptom_name,severity,start_date from diseasemanagement.symptoms where patient_id=?";
                        $stmt=$conn->prepare($sql);
                        
                        $stmt->bind_param("s",$_GET['patientId']);
                        if(!$stmt->execute()){
                            die( "Error") ;
                        }
                        $result = $stmt->get_result();
                        foreach ($result as $row){
                            ?>
                            <tr>
                            <?php
                            foreach ($row as $itm){
                                if (!$itm){
                                    $itm='none';
                                }
                                ?>
                                <td  style="text-align: center;"> <?php echo $itm?></td>
                                <?php
                            }
                            ?>
                                <td> 
                                    <form action="editsymptom.php" method="get">
                                        <input type="hidden" name="symptom_id" value="<?php echo $row['symptom_id']; ?>">
                                        <input type="hidden" name="patientId" value="<?php echo $_GET['patientId']; ?>">
                                        <button type="submit" name="edit_symptom" style="background-color:  #52be80" class="btn" >
                                            Edit
                                        </button>
                                    </form>
                                    <form action="" method="post">
                                        <input type="hidden" name="symptom_id" value="<?php echo $row['symptom_id']; ?>">
                                        <td><button type="submit " name="delete_symptom" style="background-color:  #e74c3c " class="btn">Delete</button></td>
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                        
                        ?>

                </tbody>
            </table>
            </section>
            
        </section>
        <section >
            <button  type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_symptom" style="background-color: #44b0f8;margin: 1rem 0 0 4rem; padding:8px 30px;font-weight:400;color:white; ">Add Symptom</button>
        </section>
        <!-- Symptom End-->

        <!-- Diagnosis Start-->
         <?php  
                $sql="select diagnosis_id,diagnosis_name,diagnosis_date,diagnosis_notes from diagnosis where patient_id=?";
                $stmt=$conn->prepare($sql);
                        
               $stmt->bind_param("s",$_GET['patientId']);
               if(!$stmt->execute()){
                     die( "Error") ;
               }
            $result = $stmt->get_result();
            
        ?>
        <section class="diagnosis">
            <section class="table_title">
            
                <h1 > Diagnoses</h1>
                <i class='bx bx-line-chart'></i>
            </section>
        
            <section  class="table_body">
            <table>
                <thead>
                    <tr style="text-align:center;">
                        <th>Id</th>
                        <th>Diagnoses</th>
                        <th>Date of diagnoses</th>
                        <th>Diagnoses notes </th>
                        <th>Edit</th>
                        <th>Delete</th>

                    </tr>
                </thead>
                <tbody>
                    
                    <?php
                        foreach ($result as $row){
                            ?>
                            <tr>
                            <?php
                            foreach ($row as $itm){
                                if (!$itm){
                                    $itm='none';
                                }
                                ?>
                                <td  style="text-align: center;"> <?php echo $itm?></td>
                                <?php
                            }
                            ?>
                                <td> 
                                    <form action="editDiagnosis.php" method="get">
                                        <input type="hidden" name="patientId" value=<?php echo $_GET['patientId']; ?>>
                                        <input type="hidden" name="diagnosis_id" value=<?php echo $row['diagnosis_id']; ?>>
                                        <button type="submit" name="edit_diag" style="background-color:  #52be80" class="btn">Edit</button>
                                    </form>
                                    <form action="" method="post">
                                        <input type="hidden" name="diagnosis_id" value=<?php echo $row['diagnosis_id']; ?>>
                                        <td><button type="submit " name="delete_diag" style="background-color:  #e74c3c " class="btn">Delete</button></td>
                                    </form>
                                </td>
                                
                            </tr>
                            
                            <?php
                        }
                
                    ?>
                    

                </tbody>
            </table>
            </section>
            
        </section>
        <section>
                <button  type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_diagnosis" style="background-color: #44b0f8;margin: 1rem 0 0 4rem;padding:8px 30px; color:white;font-weight:400; ">Add Diagnosis</button>
        </section>
        <!-- Diagnosis End-->

        
        <!-- treatments Start-->
        <?php  
                $sql="select diagnosis_id, treatment_id,treatment_name,medication_name,dosage,treatment_duration,treatment_notes from treatments
                    where patient_id=?";
                $stmt=$conn->prepare($sql);
                        
               $stmt->bind_param("s",$_GET['patientId']);
               if(!$stmt->execute()){
                     die( "Error") ;
               }
            $result1 = $stmt->get_result();
            if($result->num_rows){
        ?>
        <section class="treatments">
            <section class="table_title">
            
                <h1 > Treatments</h1>
                <i class='bx bxs-capsule' ></i>
            </section>
        
            <section  class="table_body">
            <table>
                <thead>
                    <tr style="text-align:center;">
                        <th>Diagnosis ID</th>
                        <th>ID </th>
                        <th>Treatment</th>
                        <th>Medication </th>
                        <th>Dosage</th>
                        <th>Treatment duration</th>
                        <th>Treatment note</th>
                        <th>Edit</th>
                        <th>Delete</th>

                    </tr>
                </thead>
                <tbody>
                    
                    <?php
                        foreach ($result1 as $row){
                            ?>
                            <tr>
                            <?php
                            foreach ($row as $itm){
                                if (!$itm){
                                    $itm='none';
                                }
                                ?>
                                <td  style="text-align: center;"> <?php echo $itm?></td>
                                <?php
                            }
                            ?>
                                <td> 
                                    <form action="editTreatment.php" method="get">
                                        <input type="hidden" name="treatment_id" value=<?php echo $row['treatment_id']; ?>>
                                        <button type="submit" name="edit_treat" style="background-color:  #52be80" class="btn">Edit</button>
                                    </form>
                                    <form action="" method="post">
                                        <input type="hidden" name="treatment_id" value=<?php echo $row['treatment_id']; ?>>
                                        <td><button type="submit " name="delete_treat" style="background-color:  #e74c3c " class="btn">Delete</button></td>
                                    </form>
                                </td>
                                
                            </tr>
                            
                            <?php
                        }
                
                    ?>
                    

                </tbody>
            </table>
            </section>
            
        </section>
        <section>
                <button  type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_treatment" style="background-color: #44b0f8;margin: 1rem 0 0 4rem;padding:8px 30px; color:white;font-weight:400; ">Add Treatment</button>
        </section>
        <?php }?>
        <!-- treatments End-->
</main>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
<script>
     $('#deletepatient_warning').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget); // Récupère le bouton qui a déclenché la modale
      var Id = button.data('id');  // Récupère data-diagid 
      console.log("id= ",Id);
      var modal = $(this); // La modale elle-même
      modal.find('#modalpatientid').val(Id); 
    });
    
    function generatePDF(x,file_path,y){
    console.log(y);
    /*toPDF(treatments_table) */
    const url = `${file_path}?${y}=${encodeURIComponent(x)}`;
    const new_window = window.open(url, "_blank");
    if (!new_window) {
        alert("Popup blocked. Please allow popups for this site.");
    }
}
</script>
</body>
</html>
<!-- Symptome Modal -->
<div class="modal fade" id="add_symptom" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Symptom</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="post" style=" display: flex;flex-direction: column;">
            <input type="hidden" name="patientId" value="<?php echo $_GET['patientId']?>" required>

            <label>Symptom :</label>
            <input type="text" name="symptom" required>
                    

            <label >Severity : </label>
            <input type="text" name="severity" required >
                    
                    
            <label>Start date :</label>
            <input type="date" name="date_start"  required>  
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" name="addSymptome">Save changes</button>
            </div>              
        </form>

      </div>
      
    </div>
  </div>
</div>

<!-- Symptome Modal End-->

<!-- Diagnosis Modal -->
<div class="modal fade" id="add_diagnosis" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Diagnosis</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="post" style=" display: flex;flex-direction: column;">
            <input type="hidden" name="patientId" value="<?php echo $_GET['patientId']?>" required>
            <input type="hidden" name="det" value="1">
            <label>Diagnosis :</label>
            <input type="text" name="diagnosis" required>
                    

            <label >Diagnosis notes : </label>
            <input type="text" name="diag_note" value="none" required>
                    
                    
            <label>Date of diagnosis :</label>
            <input type="date" name="diag_date" required>  
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" name="addDiagnosis">Save changes</button>
            </div>              
        </form>

      </div>
      
    </div>
  </div>
</div> 

<!-- Diagnosis Modal End-->


<!-- Treatment Modal -->
<div class="modal fade" id="add_treatment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Treatment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="post" style=" display: flex;flex-direction: column;">
            <input type="hidden" name="patientId" value="<?php echo $_GET['patientId']?>" required>
            <input type="hidden" name="det" value="1">   
                
                Diagnosis ID :<select name="Diagnosis_id" id="">
                    <?php 
                    $stmt = $conn->prepare("select diagnosis_id  from diagnosis where doctor_id=? and patient_id=?");
                    $stmt->bind_param("ss",$_SESSION['username'],$_GET['patientId']);
                    if(!$stmt->execute()){
                        die( "Error") ;
                    }
                    $result = $stmt->get_result();
                    foreach ($result as $row) {
                        echo '<option value="' . htmlspecialchars($row['diagnosis_id']) . '">' . htmlspecialchars($row['diagnosis_id']) . '</option>';
                    }
                    ?>
                </select>
     
                Treatment : <input type="text" name="Treatment" required >

                Medication : <input type="text" name="medication"  value="none" required>

                Dosage : <input type="text" name="dosage"  value="none" required>

                Treatment duration (days) :<input type="number" name="treatment_duration" required>

                Treatment note :<input type="text" name="treatment_note" value="none"  required>
                    
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="addTreatment">Save changes</button>
                </div>          
        </form>
         
      </div>
     
    </div>
  </div>
</div>

<!-- Treatment Modal End-->
 
<!-- Doctor-Info Modal -->
<div class="modal fade" id="doctor_inf" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header" style="background-color:#7d82821f">
            <h5 class="modal-title" id="exampleModalLabel"><strong>Assigned Doctor</strong></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div  style="display:flex;justify-content: space-between;padding:30px">
            <?php
                $sql=" select  full_name ,CIN   ,email ,contact_info ,specialization  from doctor
                    where username=(select doctor_id from patient where id=?)";
                $stmt=$conn->prepare($sql);
                
                $stmt->bind_param("i",$_GET['patientId']);
                if($stmt->execute()){
                    $result=$stmt->get_result();
                    $row = $result->fetch_assoc();
                    ?>
                    <div  style="display:flex;flex-direction:column;font-size:18px ">
                        <label for="" style=""><strong>Full name:</strong></label>
                        <label for=""><strong>CIN:</strong></label>
                        <label for=""><strong>Email: </strong></label>
                        <label for=""><strong>Phone number: </strong></label>
                        <label for=""><strong>Specialization:</strong></label>
                    
                    </div>
                    <div  style="display:flex;flex-direction:column;font-size:18px ">
                        <label for=""><?php echo $row['full_name'];?></label>
                        <label for=""><?php echo $row['CIN'];?></label>
                        <label for=""><a href="mailto:<?php echo $row['email'];?>"><?php echo $row['email'];?></a></label>
                        <label for=""><?php echo $row['contact_info'];?></label>
                        <label for=""><?php echo $row['specialization'];?></label>
                    </div>
                    <?php
                }
                
                ?>
        
        </div>
      
     
    </div>
  </div>
</div>

<!-- Doctor-Info Modal End-->


