<?php 
require_once  "Login/controllerUserData.php";
if (!isset($_SESSION['username'])) {

    header("Location: Login/login.php"); // Redirige si non connecté
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/stylehome.css">
    <link rel="stylesheet" href="css/styledashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="css/stylepatientfile.css">
    <link rel="stylesheet" href="css/styleedit.css">


    <script>
        function openTkinterWindow() {
            fetch('http://127.0.0.1:5000/run-tkinter?var=<?php echo $_SESSION['username']; ?>')
            .then(response => response.text())
            .then(data => console.log(data))
            .catch(error => console.error('Error:', error));
        }
    </script>
</head>
<body style="display:flex;">
<!-- sidebar -->
<nav>
    <ul class="sidebar-container">
        <img src="images/doctorimg.png" alt="" width="100" height="110">
        <hr class="menu-devider">
        <li class="menu-elm">
            <a href="Dashboard.php" class="home-elm ">
                <i class='bx bxs-home'></i>
                <span >Dashboard</span>
            </a>
        </li>
        <hr class="menu-devider">
        <!-- Patient -->
        <div>Patient</div>
        <li class="menu-elm">
            <a href="" onclick="openTkinterWindow()" class="elm">
                    <i class='bx bx-user-plus'></i>
                    <span>Add patient</span>
            </a>
        </li>
        <li class="menu-elm">
            <a href="Deletepatient.php" class="elm">
                    <i class='bx bxs-user-minus'></i>
                    <span>Delete patient</span>
            </a>
        </li>   
        <li class="menu-elm">
            <a href="patientconsult.php" class="elm">
                    <i class='bx bxs-folder' ></i>
                    <span>Consult the patient's file</span>
            </a>
        </li>
            
        <hr class="menu-devider">
        <!-- Statistics -->
        <div>Statistics</div>
        <li class="menu-elm">
            <a href="#" class="elm">
                <i class='bx bxs-user-minus'></i>
                <span>Average age of patient</span>
            </a>
        </li>
        
        <li class="menu-elm">
            <a href="" class="elm">
                <i class='bx bxs-folder' ></i>
                <span>Case evaluation</span>
            </a>
        </li>
        <li class="menu-elm">
            <a href="" class="elm">
                <i class='bx bxs-folder' ></i>
                <span>Healing rate</span>
            </a>
        </li>
          
        <hr class="menu-devider">
        <!-- Other -->
        <div>Other</div>
        
    </ul>
 
</nav>
<!-- end sidebar -->
<!-- topbar-->
<main class="dash-container">
    <header class="topbar">

    <form action="" method="post" >
        <div  class="search-bar">
            <input type="number" name="patient_id" placeholder="Search by id">
            <div  class="btn">
                <button  type="submit" name="search_id">
                    <img src="images/search_buttom.png" alt="Icon" width="27" height="27"> 
                </button>
            </div>
        
        </div>
        
    </form>


    <div  class="menuderaulement" >
        <li>
                <a href="" >
                    <span><?php echo $_SESSION['name'];?></span> 
                    <i class='bx bxs-cog'></i>
                </a>
                <ul class="sous-menu">
                    <li>
                        <a href="changepassword.php" style="display: flex; align-items: center;gap:10px">
                            <i class='bx bxs-cog' ></i>
                            <span style="font-size: 14px;">Change password</span>
                        </a>
                    </li>
                    <li>
                        <a href="logout.php"  style="display: flex; align-items: center;gap:10px">
                            <i class='bx bx-log-out'></i>
                            <span style="font-size: 14px;">Log out</span>
                        </a>
                    </li>

                </ul>
        </li> 
        </div>

    </header>
    <h2>Edit Diagnostic</h2>
    <div class="edit-contaner" >
        <form action="" method="post" style=" display: flex;flex-direction: column;">
            <input type="hidden" name="patientId" value="<?php echo $_GET['patientId']?>">
            <label>Diagnosis ID :</label>
            <input type="number" name="diag_id" id="" value="<?php echo $_GET['diagnosis_id']?>" readonly>
            <?php
                        $sql="select diagnosis_name,diagnosis_date,diagnosis_notes from diagnosis where diagnosis_id=?";
                        $stmt=$conn->prepare($sql);
                        
                        $stmt->bind_param("i",$_GET['diagnosis_id']);
                        if(!$stmt->execute()){
                            die( "Error") ;
                        }
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc(); // <-- important : récupérer les données ici
                        
            ?>
            <label>Diagnosis :</label>
            <input type="text" name="diagnosis_name" value="<?php echo $row['diagnosis_name']?>" required>
            
            <label>Date of diagnosis:</label>
            <input type="date" name="diagnosis_date" value="<?php echo $row['diagnosis_date']?>" required>  

            <label >Diagnosis notes : </label>
            <input type="text" name="diagnosis_notes" value="<?php echo $row['diagnosis_notes']?>"  >
                    
                    
            <div class="modal-footer">
                <button  type="reset" style="background-color: red;">CANCEL</button>
                <button type="submit"  name="editdiagnosis" style="background-color: #44b0f8;">UPDATE</button>
            </div>              
        </form>
        <?php
        if(isset($_SESSION['info'])){
            ?>
            <div style="color:rgb(255, 255, 255) ; background-color:#1cc88a;font-size:14px ;font-weight:600;margin:20px;padding:15px 60px;width:max-content;"> <?php echo $_SESSION['info'];unset($_SESSION['info']); ?></div>
            <?php
            
        }
        ?>
    </div>
</main>

</body>
</html>