<head>
    <style>
        .search-box {
  position: relative;
  width: 360px;
  margin-right: 40px;
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
.pdfbtn{
    border: none;
    background-color:  #f5b041  ;
    padding: 5px;
    margin-top: 20px;
    color: white;
    border-radius: 5px;
    width: max-content;
    margin-left: 20px;
    margin-bottom: 40px;
}
.pdfbtn:hover{
    background-color: red;
}
</style>
</head>
<?php 
require_once 'Home.php';
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
?>

<?php
        if(isset($_POST['graph_nbConnexion'])){
            $param = 1; 
            exec("python3 ../python/nbConnexion.py $param 2>&1", $output, $result);
            $param ="";
            unset($_POST['graph_nbConnexion']);
           
        }
        if(isset($_POST['nbdoctorperspecialite'])){
            $param = 1; 
            exec("python3 ../python/nbdoctorperspecialite.py $param 2>&1", $output, $result);
            $param ="";
            unset($_POST['nbdoctorperspecialite']);
            
        }
        if(isset($_POST['nbpatientperdoctor'])){
            $param = 1; 
            exec("python3 ../python/nbpatientperdoctor.py $param 2>&1", $output, $result);
            $param ="";

            unset($_POST['nbpatientperdoctor']);

           
        }
?>



<div id="doctor_statistics">
<h1 style="margin-bottom: 40px;margin-left: 20px;">Doctor Activity Report</h1>
<button onclick="generatePDF()" class="pdfbtn">Download PDF</button>

<div class="insight" >
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

    
    <div style="display:flex;justify-content:flex-end;margin-top:60px;margin-bottom: 70px;">
        <div class="search-box">
            <form action="statisticsspecificdoctor.php" method="get">
                <input  type="search"  id="searchbar" placeholder="Search..." name="doctorstatistics"/>
                <button type="submit" name="doctorstatisticsbtn"><i class='bx bx-search' style=" color:black;"></i></button>
            </form>
            
        </div>
    </div>
    <div class="statics-table"  >
                                
                
        <!--genderperyear-->
        <div class="statics-container" >
            <div class="static-head" >
                <h6><strong>Distribution of Doctors by Specialization</strong></h6>
                <form method="post">
                        <button type="submit" name="nbdoctorperspecialite" >
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
            <div class="static-head" style="align-items: center;margin-top:0px">
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
    <div class="statics-table" style="margin-top:70px;">
                                
                
        <!--genderperyear-->
        <div class="statics-container" >
            <div class="static-head" >
                <h6><strong>Top 20 Doctors by Number of Patients (Last 2 Years)</strong></h6>
                <form method="post">
                        <button type="submit" name="nbpatientperdoctor" >
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
</div>
</main>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
    

    async function generatePDF() {
        const { jsPDF } = window.jspdf;
        const element = document.getElementById("doctor_statistics");

        // Use html2canvas to render the DOM element
        const canvas = await html2canvas(element, {
            scale: 2, // for better quality
            useCORS: true // if there are cross-origin images
        });

        const imgData = canvas.toDataURL("image/png");
        const pdf = new jsPDF("p", "mm", "a4");

        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

        let position = 0;
        if (pdfHeight > pdf.internal.pageSize.getHeight()) {
            // If content is taller than one page, split it
            const totalPages = Math.ceil(pdfHeight / pdf.internal.pageSize.getHeight());
            for (let i = 0; i < totalPages; i++) {
                const sliceHeight = pdf.internal.pageSize.getHeight();
                pdf.addImage(imgData, "PNG", 0, -i * sliceHeight, pdfWidth, pdfHeight);
                if (i < totalPages - 1) pdf.addPage();
            }
        } else {
            pdf.addImage(imgData, "PNG", 0, 0, pdfWidth, pdfHeight);
        }

        pdf.save("doctor_statistics.pdf");
    }
</script>
</body>
</html>