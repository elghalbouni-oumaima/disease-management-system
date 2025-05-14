<?php 
require_once  "../Login/controllerUserData.php";
if (!isset($_SESSION['username'])) {
    header("Location: ../Login/login.php"); // Redirige si non connecté
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
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../css/stylehome.css">
    <link rel="stylesheet" href="../css/styledashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/styleedit.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</head>
<body style="display:flex;">
<!-- sidebar -->
<nav>
    <ul class="sidebar-container">
        <img src="../images/doctorimg.png" alt="" width="100" height="110">
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
            <a href="patientconsult.php" class="elm">
                <ion-icon name="list-outline"></ion-icon>
                <span>Patient Records</span>
            </a>
        </li>
        <li class="menu-elm">
            <a href="diagnosisRedcords.php"  class="elm">
                <ion-icon name="medkit-outline"></ion-icon>
                <span>Diagnosis Records</span>
            </a>
        </li>
        <li class="menu-elm">
            <a href="TreatmentsRecords.php" class="elm">
                <ion-icon name="document-text-outline"></ion-icon>
                <span>Treatments Records</span>
            </a>
        </li>   
        
            
        <hr class="menu-devider">
        <!-- Statistics -->
        <div>Statistics</div>
        <li class="menu-elm">
            <a href="statistics.php" class="elm">
                <ion-icon name="stats-chart-outline"></ion-icon>
                <span>Statistics</span>
            </a>
        </li>
        
          
        <hr class="menu-devider">
        <!-- Other -->
        <div>Other</div>
        <li class="menu-elm">
            <a href="support&contact.php" class="elm">
                <ion-icon name="help-circle-outline"></ion-icon>
                <span>Support & Contact</span>
            </a>
        </li>
        <li class="menu-elm">
            <a href="myrequests.php" class="elm">
                <ion-icon name="document-text-outline"></ion-icon>
                <span>My Requests</span>
            </a>
        </li>
        
    </ul>
 
</nav>
<!-- end sidebar -->
<!-- topbar-->
<div style="display:flex;flex-direction:column;width:100%">
    <main class="dash-container">
    <header class="topbar">

        <form action="" method="post" >
            <div  class="search-bar">
                <input type="number" name="patient_id" placeholder="Search by id">
                <div  class="btn">
                    <button  type="submit" name="search_id">
                        <img src="../images/search_buttom.png" alt="Icon" width="27" height="27"> 
                    </button>
                </div>
            
            </div>
            
        </form>
        <div class="acount-container">
            <label for="acount" class="acount-btn"><?php echo $_SESSION['name'];?>  <i class='bx bxs-cog' style="color:#839192;"></i></label>
            <input type="checkbox"  id="acount">
            <div class="acount-options">
                <label for="acount">
                    <a href="profile.php">
                    <i class='bx bxs-user-circle'  style="color:#839192;font-size:18px"></i>
                    <span >Profile</span>
                    </a>
                </label>
                <label for="acount">
                    <a href="changepassword.php">
                        <i class='bx bxs-cog'  style="color:#839192;"></i>
                        <span >Change password</span>
                    </a>
                </label>
                <hr class="menu-devider" style="border:1px solid #4d586026;margin:10px 2px 10px 2px">
                <label for="acount">
                    <a href="../Login/login.php" >
                        <i class='bx bx-log-out'  style="color:#839192;"></i>
                        <span >Log out</span>
                    </a>
                </label>
            </div>
        </div>

        <!--<div  class="menuderaulement" >
            <li>
                    <a href="" >
                        <span><?php echo $_SESSION['name'];?></span> 
                        <i class='bx bxs-cog' style="color:#839192;"></i>
                    </a>
                    <ul class="sous-menu">
                        <li>
                            <a href="changepassword.php" style="display: flex; align-items: center;gap:10px">
                                <i class='bx bxs-cog'  style="color:black;"></i>
                                <span style="font-size: 14px;">Change password</span>
                            </a>
                        </li>
                        <li>
                            <a href="../Login/login.php"  style="display: flex; align-items: center;gap:10px">
                                <i class='bx bx-log-out'  style="color:black;"></i>
                                <span style="font-size: 14px;">Log out</span>
                            </a>
                        </li>

                    </ul>
            </li> 
        </div>-->
    
    </header>
