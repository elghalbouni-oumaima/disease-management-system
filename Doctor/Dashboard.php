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
            <!--<input type="hidden" name="patient_id" value="<?php echo htmlspecialchars($_GET['patientidDash'] ?? ''); ?>">-->
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

$stmt=$conn->prepare("select count(*) as total_nbPa from patient  ");
$stmt->execute();

// Récupérer le résultat
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$nbTotalPat=$row['total_nbPa'];

$stmt=$conn->prepare("select count(*) as total_nb from patient where doctor_id=? ");
$stmt->bind_param("s",$_SESSION['username']);
$stmt->execute();

// Récupérer le résultat
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$nbTotalPatdoctor=$row['total_nb'];
//calcul % nbTotalPatdoctor
$p1=$nbTotalPatdoctor*100/$nbTotalPat;
$nbpxstrock1=(100-$p1)*226/100;

$stmt=$conn->prepare("select count(*) as female from patient where Gender='female' and doctor_id=?");
$stmt->bind_param("s",$_SESSION['username']);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();
$nbfemale=$row['female'];
//calcul % nbTotalPatdoctor
$p2=$nbfemale*100/$nbTotalPatdoctor;
$nbpxstrock2=(100-$p2)*226/100;


$stmt=$conn->prepare("select count(*) as male from patient where Gender='male' and doctor_id=?");
$stmt->bind_param("s",$_SESSION['username']);
$stmt->execute();

// Récupérer le résultat
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$nbmale=$row['male'];
//calcul % nbTotalPatdoctor
$p3=$nbmale*100/$nbTotalPatdoctor;
$nbpxstrock3=(100-$p3)*226/100;

$stmt=$conn->prepare("select count(*) as older from patient where TIMESTAMPDIFF(YEAR, birthdate, CURDATE())>18 and doctor_id=?;");
$stmt->bind_param("s",$_SESSION['username']);
$stmt->execute();

// Récupérer le résultat
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$nbolder=$row['older'];
//calcul % nbTotalPatdoctor
$p4=$nbolder*100/$nbTotalPatdoctor;
$nbpxstrock4=(100-$p4)*226/100;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <title>DOC</title>
</head>
<body>
<h1>Dashboard</h1>
    <div class="insight">
        <div class="card-container">
        <i style="  background-color: #4468f8;" class='bx bx-plus-medical'></i>
        <div class="inf-card">
                    <div>
                        <h3>Total Patient</h3>
                        <h1><?php echo $nbTotalPatdoctor?></h1>
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
            <div class="elm-container"  >
                <h3><strong style="font-weight: 900;font-size: 20px;margin-top:20px">Undiagnosed Patients List</strong></h3>
                <div class="doctor-table"  style="width:500px;margin-top:10px">
                    <table>
                        <thead>
                            <tr style="text-align:center;background-color:#1cc88a">
                                <th>Id</th>
                                <th>Full name</th>
                                <th>Gender</th>
                                <th>Birth date</th>
                                <th>Action</th>  
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
                                    die( "Error") ;
                                }
                                $result = $stmt->get_result();
                                foreach ($result as $row){
                                    ?>
                                    <tr >
                                        <?php
                                        foreach ($row as $itm){
                                            if (!$row){
                                                $itm='none';
                                            }
                                            ?>
                                            <td style="padding:1.4rem 3rem "> <?php echo $itm?></td>
                                            <?php
                                        }
                                        
                                        ?>
                                        <td>
                                        <button  type="button" class="btn btn-primary"
                                                data-toggle="modal"
                                                data-target="#add_diagnosis"
                                                data-patient="<?php echo $row['id']; ?>">
                                                Add Diagnosis
                                        </button>
                                            
                                        </td>
                                    </tr>
                                    <?php
                                }
                                
                            ?>

                        </tbody>
                    </table>
                </div>
                
            </div>
            <div class="elm-container">
                <div class="elm-head">
                    <h3><strong>Latest Added Patient</strong></h3>
                    <div class="more-detail">
                        <a href="patientconsult.php">See all </a>
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
                            $stmt=$conn->prepare("select Gender,concat(Last_name,' ',First_name) as full_name,email,registration_day  from patient where doctor_id=? limit 4");
                            $stmt->bind_param("s",$_SESSION['username']);
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
            <div class="table-contaner" >
                <h2 style="font-size:25px">Most Recent Diagnoses and Treatments per Patient</h2>

                    <!-- Le div.table_body doit contenir directement le <table> pour que le scroll agisse sur le contenu du tableau.--->
                    <!-- ET DONC IL NE FAUT PAS METTRE table_body DS LA BALISSE TABLE MAIS DS DIV-->
                <div  class="table_body" style="width: 550px; max-width:84% ;max-height: 90%;">
                    <table>
                        <thead>
                            <tr style="text-align:center;background-color:#1cc88a">
                                <th>Id</th>
                                <th>Full name</th>
                                <th>diagnosis ID</th>
                                <th>diagnosis</th>
                                <th>Date of diagnosis </th>
                                <th>Treatments</th>
                                <th>Action</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            
                            <?php
                                $sql="select id ,Full_name,diagnosis_id,diagnosis_name,diagnosis_date,treatment from ( select  p.id,concat(p.Last_name,' ',p.First_name) as Full_name,d.diagnosis_id,d.diagnosis_name, d.diagnosis_date,t.treatment,
                                                    ROW_NUMBER() OVER (PARTITION BY p.id ORDER BY d.diagnosis_date DESC) as row_num
                                                    from patient as p
                                                    left join diagnosis as d on  p.id=d.patient_id
                                                    left join (select patient_id, diagnosis_id,group_concat(concat(treatment_name ,' - ',treatment_duration,' days') separator ' / ') as treatment from treatments group by patient_id,diagnosis_id 
                                                    ) as t on t.diagnosis_id= d.diagnosis_id
                                                        where  p.doctor_id=? and d.diagnosis_name is not null  ) as tab where row_num=1 order by diagnosis_date desc";
                                $stmt=$conn->prepare($sql);
                                
                                $stmt->bind_param("s",$_SESSION['username']);
                                if(!$stmt->execute()){
                                    die( "Error") ;
                                }
                                $result = $stmt->get_result();
                                foreach ($result as $itm){
                                    ?>
                                    <tr>
                                        <?php
                                        foreach ($itm as $row){
                                            if (!$row){
                                                $row='none';
                                            }
                                            ?>
                                            <td> <?php echo $row?></td>
                                            <?php
                                        }
                                        ?>
                                        <td>
                                            <button type="button" class="btn btn-primary"
                                                    data-toggle="modal"
                                                    data-target="#add_treatment"
                                                    data-patient="<?php echo $itm['id']; ?>"
                                                    data-diagid="<?php echo $itm['diagnosis_id']; ?>">
                                                    Add Treatment
                                            </button>
                                        
                                        </td>
                                        
                                    </tr>
                                    <?php
                                }
                                
                                ?>

                        </tbody>
                    </table>
                </div>
                
            </div>

            <!-- statics--> 
            <div class="statics-container" >
                <div class="static-head">
                    <h6>Access Statistics</h6>
                    <form method="post">
                            <button onclick="openMatplotlib()" name="show_graph" >
                            <img src="../images/statics icon.png" alt="Icon" width="30" height="40"> 
                            </button>
                    </form>
                </div>
                
                <div>
                <!-- Afficher le graphique--> 
                    <img src="images/nbConnexion.png" alt="Graphique" width="400" >
                </div>
            </div>
        </div>
        
        
        <!-- END TABLES -->

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

    function openMatplotlib(){
        <?php
        if(isset($_POST['show_graph'])){
            $param = 1; 
            exec("python3 ../python/nbConnexion.py $param 2>&1", $output, $result);
        }
        ?>
    }
}
</script>
</body>
</html>


<?php
$param ="";
exec("python3 ../python/nbConnexion.py 2>&1", $output, $result);
foreach ($output as $line) {
    echo htmlspecialchars($line) . "<br>";
}


// Vérifier si ça a marché
if ($result !== 0) {
    echo "<p>Erreur lors de la génération du graphique :</p>";
    echo "<pre>" . implode("\n", $output) . "</pre>";
} 
?> 


