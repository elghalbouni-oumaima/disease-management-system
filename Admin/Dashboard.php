<!-- Diagnosis Modal -->
<div class="modal fade" id="add_diagnosis" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
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
            <input type="hidden" name="det" value="0">
            <!-- Ici on va insérer l’ID du patient dynamiquement -->
            <input type="hidden" name="patientId" id="modalPatientId">
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
                <!-- Ici on va insérer l’ID du patient dynamiquement -->
                <input type="hidden" name="det" value="0">
                <input type="hidden" name="patientId" id="modalPatientIdTreatment">  
                
                <!-- Ici on va insérer l’ID du patient dynamiquement -->
                <input type="hidden" name="Diagnosis_id" id="modaldiagId">

                <label >Treatment : </label>
                <input type="text" name="Treatment" required >

                <label>Medication :</label>
                <input type="text" name="medication"  value="none" required>

                <label>Dosage :</label>
                <input type="text" name="dosage"  value="none" required>

                <label>Treatment duration (days) :</label>
                <input type="number" name="treatment_duration" required>

                <label>Treatment note :</label>
                <input type="text" name="treatment_note" value="none"  required>
                    
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="addTreatment">Save changes</button>
                </div>          
        </form>
         
      </div>
     
    </div>
  </div>
</div>
<?php 
require_once "Home.php";
        if(isset($_POST['graph_nbConnexion'])){
            $param = 1; 
            exec("python3 ../python/nbConnexion.py $param 2>&1", $output, $result);
            $param ="";
            // Redirige vers la même page sans POST
            // header("Location: " . $_SERVER['PHP_SELF']);
            // exit;
            unset($_POST['graph_nbConnexion']);

        }
        if(isset($_POST['graph_genderperyear'])){
            $param = 1; 
            exec("python3 ../python/genderperyear.py $param 2>&1", $output, $result);
            $param ="";
            // Redirige vers la même page sans POST
            // header("Location: " . $_SERVER['PHP_SELF']);
            // exit;
            unset($_POST['graph_genderperyear']);
        }

$stmt=$conn->prepare("select count(*) as total_nbPa from patient  ");
$stmt->execute();

// Récupérer le résultat
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$nbTotalPat=$row['total_nbPa'];

//calcul % nbTotalPatient
$p1=100;
$nbpxstrock1=(100-$p1)*226/100;

$stmt=$conn->prepare("select count(*) as female from patient where Gender='female' ");
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();
$nbfemale=$row['female'];
//calcul % nbTotalFemale
$p2=$nbfemale*100/$nbTotalPat;
$nbpxstrock2=(100-$p2)*226/100;


$stmt=$conn->prepare("select count(*) as male from patient where Gender='male'");
$stmt->execute();

// Récupérer le résultat
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$nbmale=$row['male'];
//calcul % nbTotalMale
$p3=$nbmale*100/$nbTotalPat;
$nbpxstrock3=(100-$p3)*226/100;

$stmt=$conn->prepare("select count(*) as older from patient where TIMESTAMPDIFF(YEAR, birthdate, CURDATE())>18;");
$stmt->execute();

// Récupérer le résultat
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$nbolder=$row['older'];
//calcul % nbTotalPatientOver18
$p4=$nbolder*100/$nbTotalPat;
$nbpxstrock4=(100-$p4)*226/100;


// Admin Patient
$stmt=$conn->prepare("select count(*) as total_nb from patient where doctor_id=? ");
$stmt->bind_param("s",$_SESSION['username']);
$stmt->execute();

// Récupérer le résultat
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$nbTotalPatdoctor=$row['total_nb'];
//calcul % nbTotalPatdoctor admin
$p1_admin=$nbTotalPatdoctor*100/$nbTotalPat;

$stmt=$conn->prepare("select count(*) as older from patient where TIMESTAMPDIFF(YEAR, birthdate, CURDATE())>18 and doctor_id=?;");
$stmt->bind_param("s",$_SESSION['username']);
$stmt->execute();

// Récupérer le résultat
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$nbolderdoctor=$row['older'];
//calcul % nbolderadmin
$p2_admin=$nbolderdoctor*100/$nbTotalPatdoctor;
?>


<style>
    .adminpat-title{
        margin-top:40px;
        padding-left:10px;
        width:100%;
        display:flex;
        gap:10px;
        align-items: flex-start;
    }
    .adminpat-title a i{
        font-size:40px;color:black
    }
    
</style>
<h1>Dashboard</h1>
    <div class="insight">
        <div class="card-container">
        <i style="  background-color: #4468f8;" class='bx bx-plus-medical'></i>
        <div class="inf-card">
                    <div>
                        <h3>Total Patient</h3>
                        <h1><?php echo $nbTotalPat?></h1>
                    </div>
                    <div class="circle">
                        <svg >
                            <!-- Cercle principal -->
                            <circle cx="38" cy="38" r="36" stroke="#4468f8" stroke-dashoffset="<?php echo $nbpxstrock1?>"/>/>
                            <!-- Texte centré -->
                            <text x="43" y="50" text-anchor="middle" font-size="16" fill="#4468f8" font-weight="800" text-anchor="middle"> <?php echo number_format($p1, 1)?>% </text>
                        </svg>
                    </div>
                </div>
                
                
            </div>
            <div class="card-container">
                <i style="  background-color:  #f06292 ;" class='bx bx-female' ></i>
                <div class="inf-card">
                    <div>
                        <h3>Females</h3>
                        <h1><?php echo $nbfemale?></h1>
                    </div>
                    <div class="circle">
                        <svg >
                            <!-- Cercle principal -->
                            <circle cx="38" cy="38" r="36" stroke="#f06292" stroke-dashoffset="<?php echo $nbpxstrock2?>"/>/>
                            <!-- Texte centré -->
                            <text x="43" y="50" text-anchor="middle" font-size="16" fill="#f06292" font-weight="800" text-anchor="middle"><?php  echo number_format($p2, 1)?>%</text>
                        </svg>
                    </div>
                </div>
                
            </div>
            <div class="card-container">
                <i style="  background-color: #44b0f8;"class='bx bx-male' ></i>
                <div class="inf-card">
                    <div>
                        <h3>Males</h3>
                        <h1><?php echo $nbmale;?></h1>
                    </div>
                    <div class="circle">
                        <svg >
                            <!-- Cercle principal -->
                            <circle cx="38" cy="38" r="36" stroke="#44b0f8" stroke-dashoffset="<?php echo $nbpxstrock3?>"/>/>
                            <!-- Texte centré -->
                            <text x="43" y="50" text-anchor="middle" font-size="16" fill="#44b0f8" font-weight="800" text-anchor="middle"><?php echo number_format($p3, 1)?>%</text>
                        </svg>
                    </div>
                </div>
                
                
            </div>
            <div class="card-container">
                <i style="  background-color: #ef5350 ;"class='bx bxs-group'></i>
                <div class="inf-card">
                    <div>
                        <h3>Patients over 18</h3>
                        <h1><?php echo $nbolder;?></h1>
                    </div>
                    <div class="circle">
                        <svg >
                            <!-- Cercle principal -->
                            <circle cx="38" cy="38" r="36" stroke="#ef5350" stroke-dashoffset="<?php echo $nbpxstrock4?>"/>
                            <!-- Texte centré -->
                            <text x="43" y="50" text-anchor="middle" font-size="16" font-weight="800" fill="#ef5350" ><?php echo number_format($p4, 1); ?>%</text>
                        </svg>
                    </div>
                </div>
                
            </div>
    </div>


        <!-- TABLES -->
            <div style=" margin-top:40px;margin-left:20px;display:flex; justify-content: flex-start;">
                <button onclick="openTkinterWindow()" class="btn btn-primary" style="font-size:20px;padding:5px 10px 5px 10px">Add Patient</button>
            </div>
            


        
        <div style="display:grid; grid-template-columns: repeat(2,1fr);gap:20px;margin:20px;">
            <!-- recent Doctors-->
            <div class="elm-container">
                <div class="elm-head">
                    <h3><strong>Latest Doctor Added</strong></h3>
                    <div class="more-detail">
                        <a href="doctorrecords.php">See all </a>
                        <i class='bx bx-right-arrow-alt'></i>
                    </div>
                    
                </div>
                
                <div class="doctor-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Full name</th>
                                <th>Email</th>
                                <th>Specialization</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $stmt=$conn->prepare("select full_name,email,specialization from doctor limit 5;");
                                if(!$stmt->execute()){
                                    die("error in doctor qurey execution");
                                }
                                $res=$stmt->get_result();
                                foreach($res as $row ){
                                    
                                    ?>
                                    <tr style="">
                                        <?php
                                        foreach($row as $itm){
                                            if(strpos($itm, '@') !== false){
                                                ?>
                                                <td ><a style="color:black" href="mailto:<?php echo $itm;?>"><?php echo $itm;?></a></td>
                                                <?php
                                                
                                            }
                                    
                                            else{
                                                ?>
                                                <td><?php echo $itm;?></td>
                                                <?php
                                            } 
                                        }
                                        ?>
                                    </tr>
                                    <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div style=" margin-top:30px;margin-left:20px;display:flex; justify-content: flex-start;margin-bottom:10px;width:100%">
                    <button onclick="openAddDoctkinter()" class="btn btn-primary" style="font-size:20px;padding:5px 10px 5px 10px;">Add Doctor</button>
                </div>
            </div>
            
            <div class="elm-container">
                <div class="elm-head">
                    <h3><strong>New Patient</strong></h3>
                    <div class="more-detail">
                        <a href="patientrecords.php">See all </a>
                        <i class='bx bx-right-arrow-alt'></i>
                    </div>
                    
                </div>
                <div class="patient-table">
                    <table >
                        <thead>
                            <tr >
                                <th  style=" text-align: justify; padding: 1rem;">Full name</th>
                                <th  style=" text-align: justify; padding: 1rem;">Registration date</th> 
                            </tr>
                        </thead>
                        <tbody >
                            <?php
                            $stmt=$conn->prepare("select Gender,concat(Last_name,' ',First_name) as full_name,email,registration_day  from patient limit 5");
                            if(! $stmt->execute()){
                                die("error in patient qurey execution");
                            }
                            $res=$stmt->get_result();
                                foreach($res as $row ){
                                    ?>
                                    <tr>
                                        <?php
                                        if($row['Gender']=="Female"){
                                                ?>
                                                <td style=" text-align: justify;"><i class='bx bxs-circle' style="color: #f06292"></i> <?php echo $row['full_name']?></td>
                                                <?php

                                            }
                                        else{
                                                ?>
                                                <td  style=" text-align: justify;">
                                                    <div>
                                                        <i class='bx bxs-circle' style="color: #44b0f8"></i> 
                                                        <?php echo $row['full_name']?>
                                                    </div>
                                                    <div class="email">
                                                        <a href="mailto:"><?php echo $row['email']?> </a>
                                                    </div>
                                                    
                                                </td>
                                                <?php

                                            }
                                            ?>
                                        <td  style=" text-align: center;"><?php echo $row['registration_day']?></td>
                                    </tr>
                                    
                                    <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
            
            <div class="statics-table" >
                                
                
                <!--genderperyear-->
                <div class="statics-container" >
                    <div class="static-head" >
                        <h6><strong>Number of Female and Male Patients per Year</strong></h6>
                        <form method="post">
                                <button type="submit" name="graph_genderperyear" >
                                <img src="../images/statics icon.png" alt="Icon" width="30" height="40"> 
                                </button>
                        </form>
                    </div>
                    
                    <div> 
                    <!-- Afficher le graphique de nbConnexion-->
                        <img src="images/genderperyear.png" alt="Graphique"  width="540">
                    </div>
                </div>

                 <!--  statics -->
                 <div class="statics-container" >
                    <div class="static-head" style="align-items: center;margin-top:25px">
                        <h6><strong>Access Statistics</strong></h6>
                        <form method="post">
                            <button type="submit" name="graph_nbConnexion" >
                                <img src="../images/statics icon.png" alt="Icon" width="30" height="40"> 
                            </button>
                        </form>
                    </div>
                    
                    <div>
                   <!--Afficher le graphique de nbConnexion -->
                        <img src="images/nbConnexion.png" alt="Graphique" width="100%"  >
                    </div>
                </div>

            </div>  
            
        <!-- END TABLES -->


        <!-- ##################################### -->
        <!-- ##################################### -->
        <!-- ##################################### -->
        <div class="adminpat-title">
            <a href="patientrecords.php">
                <i class='bx bx-menu' style=""></i>
            </a>  
            <h2>Your Patients</h2>
        </div>
        <div style="display:grid; grid-template-columns: repeat(2,1fr);gap:20px;margin:40px;">
            <!-- recent Doctors-->
            <div class="elm-container">
                <div class="elm-head">
                    <h3><strong>Undiagnosed Patients List</strong></h3>
                    <div class="more-detail">
                        <a href="patientrecords.php">See all </a>
                        <i class='bx bx-right-arrow-alt'></i>
                    </div>
                    
                </div>
                
                <div class="doctor-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Full name</th>
                                <th>Gender</th>
                                <th>Birth date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $sql="select id ,Full_name,Gender,birthdate from ( select p.id,concat(p.Last_name,' ',p.First_name) as Full_name,p.Gender,p.birthdate,d.diagnosis_name, d.diagnosis_date,t.treatment
                                from patient as p
                                left join diagnosis as d on  p.id=d.patient_id
                                left join (select patient_id, diagnosis_id,group_concat(concat(treatment_name ,' - ',treatment_duration,' days') separator ' / ') as treatment from treatments group by patient_id,diagnosis_id 
                                ) as t on t.diagnosis_id= d.diagnosis_id
                                where p.doctor_id=? and d.diagnosis_name is null ) as tab  "; 
                                $stmt=$conn->prepare($sql);
                                
                                $stmt->bind_param("s",$_SESSION['username']);                               
                                if(!$stmt->execute()){
                                    die("error in doctor qurey execution");
                                }
                                $res=$stmt->get_result();
                                foreach($res as $row ){
                                    
                                    ?>
                                    <tr style="">
                                        <?php
                                        foreach($row as $itm){
                                            if(strpos($itm, '@') !== false){
                                                ?>
                                                <td ><a style="color:black" href="mailto:<?php echo $itm;?>"><?php echo $itm;?></a></td>
                                                <?php
                                                
                                            }
                                    
                                            else{
                                                ?>
                                                <td><?php echo $itm;?></td>
                                                <?php
                                            } 
                                        }
                                        ?>
                                    </tr>
                                    <?php
                                }
                            ?>
                        </tbody>
                    </table>
                    <div style=" margin-top:30px;margin-left:20px;display:flex; justify-content: flex-start;margin-bottom:10px;width:100%">
                    <button onclick="openTkinterWindow()" class="btn btn-primary" style="font-size:18px;padding:5px 10px 5px 10px;">Add Patient</button>
                    </div>
                </div>
            </div>
            
            <div  >
                <div class="card-container" style=" width: 100%;margin-bottom:10px;padding:20px">
                    <div style="display:flex;align-items: center;justify-content: flex-start;">
                        <i style="  background-color: #4468f8;" class='bx bx-plus-medical'></i>
                        <h2>Total Patients </h2>
                    </div>
                    
                    <div class="inf-card">
                        <div>
                            <h3><?php echo $nbTotalPatdoctor;?> patients</h3>
                        </div>
                        <div  style="width:100%;background-color:#7d82821f;text-align:center;">
                            <div style="width:<?php echo  number_format($p1_admin, 1);?>%;background-color:#4468f8;text-align:center;">
                                <span ><?php echo  number_format($p1_admin, 1);?>%</span> 
                            </div>
                           
                        </div>
                    </div>
                    
                </div>
                <div class="card-container" style=" width: 100%;padding:20px">
                    <div style="display:flex;align-items: center;justify-content: flex-start;">
                        <i style="  background-color: #ef5350 ;" class='bx bxs-group'></i>
                        <h2>Patients over 18</h2>
                    </div>
                    
                    <div class="inf-card">
                        <div>
                            <h3><?php echo $nbolderdoctor;?> patients</h3>
                        </div>
                        <div  style="width:100%;background-color:#7d82821f;text-align:center;">
                            <div style="width:<?php echo number_format($p2_admin,1);?>%;background-color:#ef5350;text-align:center;">
                                <span ><?php echo number_format($p2_admin,1);?>%</span> 
                            </div>
                           
                        </div>
                    </div>
                    
                </div>
                
        </div>

        
    </main>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>


<!-- le script personnalisé -->
<script src="../js/modal-handler.js"></script> 
<script >
    function openTkinterWindow() {
    fetch('http://127.0.0.1:5000/run-tkinter?var=<?php echo $_SESSION['username']; ?>')
    .then(response => response.text())
    .then(data => console.log(data))
    .catch(error => console.error('Error:', error));
    }
  
    function openAddDoctkinter() {
        fetch('http://127.0.0.1:5000/run-tkinter?var=<?php echo $_SESSION['username']; ?>')
        .then(response => response.text())
        .then(data => console.log(data))
        .catch(error => console.error('Error:', error));
    }
    

</script>
</body>
</html>


<?php
$output=[];
$result=0;

    $param ="";
    $output_nbConnexion = [];
    $result_nbConnexion = 0;
    $output_genderperyear = [];
    $result_genderperyear = 0;
    exec("python3 ../python/nbConnexion.py 2>&1", $output_nbConnexion, $result_nbConnexion);
    exec("python3 ../python/genderperyear.py 2>&1", $output_genderperyear, $result_genderperyear);
    exec("python3 ../python/nbdoctorperspecialite.py 2>&1", $output_genderperyear, $result_genderperyear);
    exec("python3 ../python/nbpatientperdoctor.py 2>&1", $output_genderperyear, $result_genderperyear);
    
    exec("python3 ../python/nbdiagnosispermois.py 2>&1", $output_genderperyear, $result_genderperyear);
    exec("python3 ../python/nbpatientperages.py 2>&1", $output_genderperyear, $result_genderperyear);
    exec("python3 ../python/nbpatientperbloodtype.py 2>&1", $output_genderperyear, $result_genderperyear);
    exec("python3 ../python/nbpatientperdiagnosis.py 2>&1", $output_genderperyear, $result_genderperyear);
    exec("python3 ../python/phisicalconditionperpatient.py 2>&1", $output_genderperyear, $result_genderperyear);
    exec("python3 ../python/traitementperdiagnoses.py 2>&1", $output_genderperyear, $result_genderperyear);

    // Afficher les résultats séparément
    if ($result_nbConnexion !== 0) {
        echo "<p>Erreur dans nbConnexion.py :</p>";
        echo "<pre>" . implode("\n", $output_nbConnexion) . "</pre>";
    }

    if ($result_genderperyear !== 0) {
        echo "<p>Erreur dans genderperyear.py :</p>";
        echo "<pre>" . implode("\n", $output_genderperyear) . "</pre>";
    }


// Vérifier si ça a marché
if ($result !== 0) {
    echo "<p>Erreur lors de la génération du graphique :</p>";
    echo "<pre>" . implode("\n", $output) . "</pre>";
} 
?> 


