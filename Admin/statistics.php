<head>
    <style>
        .search-box {
  position: relative;
  width: 360px;
  margin: 0 40px;
}

.search-box input {
  width: 100%;
  padding: 10px 35px 10px 15px;
  border: 2px solid #ccc;
  border-radius: 25px;
  font-size: 16px;
 
}

.search-box input:focus {
  outline: none;
  border-color: #3498db;
  box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
}
.search-box input:hover {
box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
}


.search-box button {
  position: absolute;
  right: 20px;
  top: 50%;
  transform: translateY(-50%);
  color: #999;
  font-size: 22px;
  background: transparent;
  border:none
}

    </style>
</head>
<?php require_once 'Home.php';
$stmt=$conn->prepare("select count(*) as total_nbPa from doctor  ");
$stmt->execute();

// Récupérer le résultat
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$nbTotaldoctor=$row['total_nbPa'];

// calcul nb Admin
$stmt=$conn->prepare("select count(*) as total_nbadmin from doctor where role='Admin' ");
$stmt->execute();

// Récupérer le résultat
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$nbTotalAdmin=$row['total_nbadmin'];
//calcul % nbTotaldoctor
$p1=$nbTotalAdmin*100/$nbTotaldoctor;
$nbpxstrock1=(100-$p1)*226/100;

$stmt=$conn->prepare("select count(*) as female from doctor where Gender='female' ");
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();
$nbfemale=$row['female'];
//calcul % nbTotalFemale
$p2=$nbfemale*100/$nbTotaldoctor;
$nbpxstrock2=(100-$p2)*226/100;


$stmt=$conn->prepare("select count(*) as male from doctor where Gender='male'");
$stmt->execute();

// Récupérer le résultat
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$nbmale=$row['male'];
//calcul % nbTotaldoctor
$p3=$nbmale*100/$nbTotaldoctor;
$nbpxstrock3=(100-$p3)*226/100;

$stmt=$conn->prepare("select count(*) as total_nbNadmin from doctor where role='Non admin'");
$stmt->execute();

// Récupérer le résultat
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$nbolder=$row['total_nbNadmin'];
//calcul % nbTotalPatientOver18
$p4=$nbolder*100/$nbTotaldoctor;
$nbpxstrock4=(100-$p4)*226/100;



$stmt=$conn->prepare("select count(*) as older from patient where TIMESTAMPDIFF(YEAR, birthdate, CURDATE())>18");
$stmt->execute();

// Récupérer le résultat
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$nbolderdoctor=$row['older'];
//calcul % nbolderadmin
$p2_admin=$nbolderdoctor*100/$nbTotaldoctor;

#####################################
#################################################
$stmt=$conn->prepare("select count(*) as total_nbPa from patient  ");
$stmt->execute();

// Récupérer le résultat
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$nbTotalPat=$row['total_nbPa'];

// calcul smokers
$stmt=$conn->prepare("select count(*) as smokers from patient where smoking_alcohol='Yes' or  smoking_alcohol='Rarely' ");
$stmt->execute();

// Récupérer le résultat
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$smokers=$row['smokers'];
//calcul % smokers
$p5=$smokers*100/$nbTotalPat;


$stmt=$conn->prepare("select count(*) as withsurgery from patient where surgical_history is not null ");
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();
$nbwithsurgery=$row['withsurgery'];
//calcul % nbTotalFemale
$p6=$nbwithsurgery*100/$nbTotalPat;


$stmt=$conn->prepare("select count(*) as drugallergies from patient where drug_allergies!='No Drug Allergy' ");
$stmt->execute();

// Récupérer le résultat
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$nbdrugallergies=$row['drugallergies'];
//calcul % nbTotalMale
$p7=$nbdrugallergies*100/$nbTotalPat;

$stmt=$conn->prepare("select count(*) as chronicdiseases from patient where chronic_diseases!='No Chronic Disease' ");
$stmt->execute();

// Récupérer le résultat
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$nbchronicdiseases=$row['chronicdiseases'];
//calcul % nbTotalPatientOver18
$p8=$nbchronicdiseases*100/$nbTotalPat;
$nbpxstrock4=(100-$p4)*226/100;


// Admin Patient
$stmt=$conn->prepare("select count(*) as total_nb from patient where doctor_id=? ");
$stmt->bind_param("s",$_SESSION['username']);
$stmt->execute();

?>

<div class="insight">
    <div class="card-container">
        <div style="display:flex;justify-content:space-between;">
            <i style="  background-color: #4468f8;" class='bx bx-plus-medical'></i>
            <i style="  color: #4468f8;"  class='bx bx-bar-chart'></i>
        </div>
        
        <div class="inf-card">
            <div>
                <h3>Total Administrators</h3>
                <h1><?php echo $nbTotalAdmin?></h1>
            </div>
            <div class="circle">
                <text x="43" y="50" text-anchor="middle" font-size="16" fill="#4468f8" font-weight="800" text-anchor="middle"> <?php echo number_format($p1, 1)?>% </text>
            </div>
        </div>
        
        
    </div>
    <div class="card-container">
        <div  style="display:flex;justify-content:space-between;">
            <i style="  background-color:  #f06292 ;" class='bx bx-female' ></i>
            <i style="  color: #f06292;"  class='bx bx-bar-chart'></i>

        </div>
        
        <div class="inf-card">
            <div>
                <h3>Female Doctors</h3>
                <h1><?php echo $nbfemale?></h1>
            </div>
            <div class="circle">
                <text x="43" y="50" text-anchor="middle" font-size="16" fill="#f06292" font-weight="800" text-anchor="middle"><?php  echo number_format($p2, 1)?>%</text>
            </div>
        </div>
        
    </div>
    <div class="card-container">
        <div  style="display:flex;justify-content:space-between;">
            <i style="  background-color: #44b0f8;"class='bx bx-male' ></i>
            <i style="  color: #44b0f8;"  class='bx bx-bar-chart'></i>
        </div>
        
        <div class="inf-card">
            <div>
                <h3>Male Doctors</h3>
                <h1><?php echo $nbmale;?></h1>
            </div>
            <div class="circle">
                <text x="43" y="50" text-anchor="middle" font-size="16" fill="#44b0f8" font-weight="800" text-anchor="middle"><?php echo number_format($p3, 1)?>%</text>
            </div>
        </div>
        
        
    </div>
    <div class="card-container">
        <div  style="display:flex;justify-content:space-between;">
            <i style="  background-color: #ef5350 ;"class='bx bxs-group'></i>
            <i style="  color: #ef5350;"  class='bx bx-bar-chart'></i>
        </div>
        <div class="inf-card">
            <div>
                <h3>Total Doctors</h3>
                <h1><?php echo $nbolder;?></h1>
            </div>
            <div class="circle">
                <text x="43" y="50" text-anchor="middle" font-size="16" font-weight="800" fill="#ef5350" ><?php echo number_format($p4, 1); ?>%</text>

            </div>
        </div>
        
    </div>
</div>

<div>
<h1>Doctor Activity Report</h1>
    <div style="display:flex;justify-content:space-between;">
        <button onclick="generatePDF('<?php echo $_SESSION['username'];?>','../Admin/doctorprofile_topdf.php','username')" class="pdfbtn">Download PDF</button>
      
        <div class="search-box">
            <form action="#" method="get">
                <input  type="search"  id="searchbar" placeholder="Search..." name="doctorstatistics"/>
                <button type="submit"><i class='bx bx-search' style=" color:black;"></i></button>
            </form>
            
        </div>
    </div>
    <div class="statics-table"  style="margin-top:40px;" >
                                
                
        <!--genderperyear-->
        <div class="statics-container" >
            <div class="static-head" >
                <h6><strong>Distribution of Doctors by Specialization</strong></h6>
                <form method="post">
                        <button onclick="openMatplotlib()" name="nbdoctorperspecialite" >
                        <img src="../images/statics icon.png" alt="Icon" width="30" height="40"> 
                        </button>
                </form>
            </div>
            
            <div style="background:red;"> 
            <!-- Afficher le graphique de nbConnexion-->
                <img src="images/nbdoctorperspecialite.png" alt="Graphique" style="width:500px;" >
            </div>
        </div>

            <!--  statics -->
        <div class="statics-container" >
            <div class="static-head" style="align-items: center;margin-top:25px">
                <h6><strong>Access Statistics</strong></h6>
                <form method="post">
                        <button onclick="openMatplotlib()" name="graph_nbConnexion" >
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
    <div class="statics-table" style="margin-top:20px;">
                                
                
        <!--genderperyear-->
        <div class="statics-container" >
            <div class="static-head" >
                <h6><strong>Top 20 Doctors by Number of Patients (Last 2 Years)</strong></h6>
                <form method="post">
                        <button onclick="openMatplotlib()" name="nbpatientperdoctor" >
                        <img src="../images/statics icon.png" alt="Icon" width="30" height="40"> 
                        </button>
                </form>
            </div>
            
            <div style="background:red;"> 
            <!-- Afficher le graphique de nbConnexion-->
                <img src="images/nbpatientperdoctor.png" alt="Graphique" style="width:980px;height:550px" >
            </div>
        </div>

    

    </div>  
          
<div>
    <h1>Patient Statistics</h1>
    <div class="insight">
        <div class="card-container">
            <div style="display:flex;justify-content:space-between;">
                <i style="  color: #4468f8;"  class='bx bx-bar-chart'></i>
            </div>
            
            <div class="inf-card">
                <div>
                    <h3>Total Smokers (Patients)</h3>
                    <h1><?php echo $smokers?></h1>
                </div>
                <div class="circle">
                    <text x="43" y="50" text-anchor="middle" font-size="16" fill="#4468f8" font-weight="800" text-anchor="middle"> <?php echo number_format($p5, 1)?>% </text>
                </div>
            </div>
            
            
        </div>
        <div class="card-container">
            <div  style="display:flex;justify-content:space-between;">
                <i style="  color: #f06292;"  class='bx bx-bar-chart'></i>

            </div>
            
            <div class="inf-card">
                <div>
                    <h3>Total Patients with Surgery</h3>
                    <h1><?php echo $nbwithsurgery?></h1>
                </div>
                <div class="circle">
                    <text x="43" y="50" text-anchor="middle" font-size="16" fill="#f06292" font-weight="800" text-anchor="middle"><?php  echo number_format($p6, 1)?>%</text>
                </div>
            </div>
            
        </div>
        <div class="card-container">
            <div  style="display:flex;justify-content:space-between;">
                <i style="  color: #44b0f8;"  class='bx bx-bar-chart'></i>
            </div>
            
            <div class="inf-card">
                <div>
                    <h3>Total Patients with Drug Allergies</h3>
                    <h1><?php echo $nbdrugallergies;?></h1>
                </div>
                <div class="circle">
                    <text x="43" y="50" text-anchor="middle" font-size="16" fill="#44b0f8" font-weight="800" text-anchor="middle"><?php echo number_format($p7, 1)?>%</text>
                </div>
            </div>
            
            
        </div>
        <div class="card-container">
            <div  style="display:flex;justify-content:space-between;">
                <i style="  color: #ef5350;"  class='bx bx-bar-chart'></i>
            </div>
            <div class="inf-card">
                <div>
                    <h3>Total Patients with Chronic Diseases</h3>
                    <h1><?php echo $nbchronicdiseases;?></h1>
                </div>
                <div class="circle">
                    <text x="43" y="50" text-anchor="middle" font-size="16" font-weight="800" fill="#ef5350" ><?php echo number_format($p8, 1); ?>%</text>

                </div>
            </div>
            
        </div>
    </div>



    <div class="statics-table"  style="margin-top:40px;" >
        <div class="statics-container" >
            <div class="static-head" >
                <h6><strong>Distribution of Doctors by Specialization</strong></h6>
                <form method="post">
                        <button onclick="openMatplotlib()" name="genderperyear" >
                        <img src="../images/statics icon.png" alt="Icon" width="30" height="40"> 
                        </button>
                </form>
            </div>
            
            <div style="background:red;"> 
                <img src="images/genderperyear.png" alt="Graphique" style="width:500px;" >
            </div>
        </div>

        <div class="statics-container" >
            <div class="static-head" style="align-items: center;margin-top:25px">
                <h6><strong>Distribution of Patient by Physical Condition</strong></h6>
                <form method="post">
                        <button onclick="openMatplotlib()" name="phisicalconditionperpatient" >
                        <img src="../images/statics icon.png" alt="Icon" width="30" height="40"> 
                        </button>
                </form>
            </div>
            
            <div>
                <img src="images/phisicalconditionperpatient.png" alt="Graphique" style="width:100%;"  >
            </div>
        </div>
    </div>
    
    
    <div class="statics-table" style="margin-top:20px;">
        <div class="statics-container" >
            <div class="static-head" >
                <h6><strong>Distribution of Patients by Age Group</strong></h6>
                <form method="post">
                        <button onclick="openMatplotlib()" name="nbpatientperages" >
                        <img src="../images/statics icon.png" alt="Icon" width="30" height="40"> 
                        </button>
                </form>
            </div>
            
            <div style="background:red;"> 
                <img src="images/nbpatientperages.png" alt="Graphique" style="width:100%;" >
            </div>
        </div>
        <div class="statics-container" >
            <div class="static-head" >
                <h6><strong>Distribution of Patients by Blood Type</strong></h6>
                <form method="post">
                        <button onclick="openMatplotlib()" name="nbpatientperbloodtype" >
                        <img src="../images/statics icon.png" alt="Icon" width="30" height="40"> 
                        </button>
                </form>
            </div>
            
            <div style="background:red;"> 
                <img src="images/nbpatientperbloodtype.png" alt="Graphique" style="width:100%;" >
            </div>
        </div>
    </div>  

    <div class="statics-table" style="margin-top:20px;">
        <div class="statics-container" >
            <div class="static-head" >
                <h6><strong>Monthly Distribution of Diagnoses:</strong></h6>
                <form method="post">
                        <button onclick="openMatplotlib()" name="nbdiagnosispermois" >
                        <img src="../images/statics icon.png" alt="Icon" width="30" height="40"> 
                        </button>
                </form>
            </div>
            
            <div > 
                <img src="images/nbdiagnosispermois.png" alt="Graphique" style="width:100%;" >
            </div>
        </div>
        <div class="statics-container" >
            <div class="static-head" >
                <h6><strong>Top 7 Most Common Diagnoses Among Patients</strong></h6>
                <form method="post">
                        <button onclick="openMatplotlib()" name="nbpatientperdiagnosis" >
                        <img src="../images/statics icon.png" alt="Icon" width="30" height="40"> 
                        </button>
                </form>
            </div>
            
            <div style="background:red;"> 
                <img src="images/nbpatientperdiagnosis.png" alt="Graphique" style="width:100%;" >
            </div>
        </div>
        
    </div>  
    <div class="statics-table" style="margin-top:20px;">
        <div class="statics-container" >
            <div class="static-head" >
                <h6><strong>Most Frequently Used Treatment for Each Diagnosis</strong></h6>
                <form method="post">
                        <button onclick="openMatplotlib()" name="traitementperdiagnoses" >
                        <img src="../images/statics icon.png" alt="Icon" width="30" height="40"> 
                        </button>
                </form>
            </div>
            
            <div > 
                <img src="images/traitementperdiagnoses.png" alt="Graphique" style="width:60rem;height:30rem" >
            </div>
        </div>
    </div>
    
</div>

        
</div>
</main>
</div>
<script>
     function openMatplotlib(){
        <?php
        if(isset($_POST['graph_nbConnexion'])){
            $param = 1; 
            exec("python3 ../python/nbConnexion.py $param 2>&1", $output, $result);
            $param ="";
            unset($_POST['graph_nbConnexion']);
            // Redirige vers la même page sans POST
            // header("Location: " . $_SERVER['PHP_SELF']);
            // exit;

        }
        if(isset($_POST['nbdoctorperspecialite'])){
            $param = 1; 
            exec("python3 ../python/nbdoctorperspecialite.py $param 2>&1", $output, $result);
            $param ="";
            unset($_POST['nbdoctorperspecialite']);
            // Redirige vers la même page sans POST
            // header("Location: " . $_SERVER['PHP_SELF']);
            // exit;

        }
        if(isset($_POST['nbpatientperdoctor'])){
            $param = 1; 
            exec("python3 ../python/nbpatientperdoctor.py $param 2>&1", $output, $result);
            $param ="";

            unset($_POST['nbpatientperdoctor']);

            // Redirige vers la même page sans POST
            // header("Location: " . $_SERVER['PHP_SELF']);
            // exit;
        }
        ?>
    }

</script>
</body>
</html>