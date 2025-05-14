<head>
    <style>
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
<?php
require_once 'Home.php';
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


// Admin Patient
$stmt=$conn->prepare("select count(*) as total_nb from patient where doctor_id=? ");
$stmt->bind_param("s",$_SESSION['username']);
$stmt->execute();

?>

<?php
        if(isset($_POST['genderperyear'])){
            $param = 1; 
            exec("python3 ../python/genderperyear.py $param 2>&1", $output, $result);
            $param ="";
            unset($_POST['genderperyear']);
            // Redirige vers la même page sans POST
            // header("Location: " . $_SERVER['PHP_SELF']);
            // exit;

        }
        if(isset($_POST['phisicalconditionperpatient'])){
            $param = 1; 
            exec("python3 ../python/phisicalconditionperpatient.py $param 2>&1", $output, $result);
            $param ="";
            unset($_POST['phisicalconditionperpatient']);
            // Redirige vers la même page sans POST
            // header("Location: " . $_SERVER['PHP_SELF']);
            // exit;

        }
        if(isset($_POST['nbpatientperages'])){
            $param = 1; 
            exec("python3 ../python/nbpatientperages.py $param 2>&1", $output, $result);
            $param ="";

            unset($_POST['nbpatientperages']);


        }
        if(isset($_POST['nbpatientperbloodtype'])){
            $param = 1; 
            exec("python3 ../python/nbpatientperbloodtype.py $param 2>&1", $output, $result);
            $param ="";

            unset($_POST['nbpatientperbloodtype']);

        }
        if(isset($_POST['nbdiagnosispermois'])){
            $param = 1; 
            exec("python3 ../python/nbdiagnosispermois.py $param 2>&1", $output, $result);
            $param ="";

            unset($_POST['nbdiagnosispermois']);

        }
        if(isset($_POST['nbpatientperdiagnosis'])){
            $param = 1; 
            exec("python3 ../python/nbpatientperdiagnosis.py $param 2>&1", $output, $result);
            $param ="";

            unset($_POST['nbpatientperdiagnosis']);

        }
        if(isset($_POST['traitementperdiagnoses'])){
            $param = 1; 
            exec("python3 ../python/traitementperdiagnoses.py $param 2>&1", $output, $result);
            $param ="";

            unset($_POST['traitementperdiagnoses']);

        }
        $param ="";
?>


<div style="margin-top:20px" id="patientstatistics">
    <h1>Patient Statistics</h1>
    <button onclick="generatePDF()" class="pdfbtn">Download PDF</button>
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
                <h6><strong>Number of Female and Male Patients per Year</strong></h6>
                <form method="post">
                        <button type="submit" name="genderperyear" >
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
                        <button type="submit" name="phisicalconditionperpatient" >
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
                        <button type="submit" name="nbpatientperages" >
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
                        <button type="submit" name="nbpatientperbloodtype" >
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
                        <button type="submit" name="nbdiagnosispermois" >
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
                        <button tye="submit" name="nbpatientperdiagnosis" >
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
                        <button type="submit" name="traitementperdiagnoses" >
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
</main>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    async function generatePDF() {
        const { jsPDF } = window.jspdf;
        const element = document.getElementById("patientstatistics");

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

        pdf.save("patient_statistics.pdf");
    }
</script>


</body>
</html>