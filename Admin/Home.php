<?php 
require_once  "../Login/controllerUserData.php";
if (!isset($_SESSION['username'])) {
    header("Location: ../Login/login.php"); // Redirige si non connecté
    exit();
}
$stmt=$conn->prepare("select count(*) from notifications");
$stmt->execute();
$res=$stmt->get_result();
$row=$res->fetch_row();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../css/stylehome.css">
    <link rel="stylesheet" href="../css/styledashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/stylepatientfile.css">
    <link rel="stylesheet" href="../css/styleedit.css">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const notif=document.getElementById('notif');
            const acount=document.getElementById('acount');
            
            var notifclass = document.querySelector(".notif-container .notif-box");
            var acountclass = document.querySelector('.acount-container .acount-options');
            acount.addEventListener('change',function(){
                if(acount.checked){
                    notif.checked=false;
                    acountclass.style.opacity="1";
                    acountclass.style.transform="scale(1)";
                    notifclass.style.opacity="0";
                    notifclass.style.transform="scale(0.8)";
                }
                else{
                    acountclass.style.opacity="0";
                    acountclass.style.transform="scale(0.8)";
               
                }
            })
            notif.addEventListener('change',function(){
                if(notif.checked){
                    acount.checked=false;
                    notifclass.style.opacity="1";
                    notifclass.style.transform="scale(1)";
                    acountclass.style.opacity="0";
                    acountclass.style.transform="scale(0.8)";
                }
                else{
                    notifclass.style.opacity="0";
                    notifclass.style.transform="scale(0.8)";
               
                }
            })
            let sidebar_container = document.querySelector('.sidebar-container'),
            sidebarelm = document.querySelectorAll('.sidebar-container .menu-elm .elm '),
            sidebartitle = document.querySelectorAll('.elm span'),
            sidebarhome = document.querySelector('.home-elm'),
            sidebarhomespan = sidebarhome.querySelector('span'),
            sidebarhomei = sidebarhome.querySelector('i'),
            divelm = document.querySelectorAll('.sidebar-container >div');
            var sidebaretoggle =document.getElementById('sidebaretoggle');
            console.log(sidebarhomespan);
            // 🔁 Restaurer l’état depuis localStorage
            if (localStorage.getItem('sidebarState') === 'collapsed') {
                collapseSidebar();
            } else {
                expandSidebar();
            }
            sidebaretoggle.addEventListener('click',function(){
         
                console.log(divelm);
                if ( sidebar_container.style.width == "7rem"){
                    expandSidebar();
                    localStorage.setItem('sidebarState', 'expanded');
                }
                else{
                    collapseSidebar();
                    localStorage.setItem('sidebarState', 'collapsed');
                    
                }
                

            });
            
            function expandSidebar(){
                sidebar_container.style.width = "";
                sidebarhome.style.flexDirection = "row";
                sidebarhome.style.whiteSpace = "nowrap";
                sidebarhomespan.style.display = "block";
                sidebarhomei.style.fontSize = "";
                console.log(sidebarhomespan.style.dispaly);
                    sidebartitle.forEach(element => {
                        element.style.fontSize = "";
                        
                    });
                    sidebarelm.forEach(element => {
                        element.style.flexDirection="row";
                        element.style.whiteSpace="nowrap";
                        
                    });
                    divelm.forEach(element => {
                        element.style.fontSize="";
                        element.style.color= "";
                        element.style.textAlign="";
                    });

            }
            function collapseSidebar(){
                sidebar_container.style.width = "7rem";
                sidebarhomespan.style.display = "none";
                sidebarhomei.style.fontSize = "2rem";
                console.log(sidebarhomespan.style.dispaly);
                sidebarhome.style.flexDirection = "column";
                sidebarhome.style.whiteSpace = "normal";
                sidebartitle.forEach(element => {
                        element.style.fontSize = ".65rem";
                        
                    });
                    sidebarelm.forEach(element => {
                        element.style.flexDirection="column";
                        element.style.whiteSpace="normal";
                    });
                    divelm.forEach(element => {
                        element.style.fontSize=".80rem";
                        element.style.color= "rgb(72 65 65 / 47%)";
                        element.style.textAlign="center";
                    });

            }
        });
    </script>
</head>
<body style="display:flex;">
<!-- sidebar -->
<nav >
    <ul class="sidebar-container" >
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
            <a href="patientrecords.php" class="elm">
                <div style="color:white;">
                    <ion-icon name="list"></ion-icon>
                </div>
                
                <span>Patient List</span>
            </a>
        </li>
        <li class="menu-elm">
            <a href="change_deleteddoctor.php" class="elm">
                <div style="color:white;">
                     <ion-icon name="list"></ion-icon>
                </div>
                   
                    <span>Patient List (Deleted Doctor)</span>
            </a>
        </li>
        <li class="menu-elm">
            <a href="diagnosisRedcords.php"  class="elm">
                <div style="color:white;">
                    <ion-icon name="medkit"></ion-icon>
                </div>
                
                <span>Diagnosis Records</span>
            </a>
        </li>
        <li class="menu-elm">
            <a href="TreatmentsRecords.php" class="elm">
                <div style="color:white;">
                    <ion-icon name="document-text"></ion-icon>
                </div>
                
                <span>Treatments Record</span>
            </a>
        </li>   
        
            
        <hr class="menu-devider">
        <!-- Patient -->
        <div>Doctor</div>
        <li class="menu-elm">
            <a href="doctorrecords.php" class="elm">
                <div style="color:white;">
                    <iconify-icon icon="fontisto:doctor"></iconify-icon>
                </div>
                
                <span>Doctor Records</span>
            </a>
        </li>
        <li class="menu-elm">
            <a href="doctorrequests.php"  class="elm">
                <div style="color:white;">
                    <ion-icon name="document-text"></ion-icon>
                </div>
                
                <span>Doctor Requests</span>
            </a>
        </li>
         
        
            
        <hr class="menu-devider">
        <!-- Statistics -->
        <div>Statistics</div>
        <li class="menu-elm">
            <a href="doctorstatistics.php" class="elm">
                <div style="color:white;">
                    <ion-icon name="stats-chart"></ion-icon>
                </div>
                
                <span>Doctor Statistics</span>
            </a>
          
        </li>
        <li class="menu-elm">
            <a href="patienttatistics.php" class="elm">
                <div style="color:white;">
                    <ion-icon name="stats-chart"></ion-icon>
                </div>
                
                <span>Patients Statistics</span>
            </a>
          
        </li>
        
          
        <hr class="menu-devider">
        <!-- Other -->
        <div>Other</div>
        <li class="menu-elm">
            <a href="profile.php" class="elm">
                <div style="color:white;">
                    <iconify-icon icon="fontisto:doctor"></iconify-icon>
                </div>
                
                <span>My Profile</span>
            </a>
        </li>
        <li class="menu-elm">
            <a href="changepassword.php" class="elm">
                <div style="color:white;">
                    <ion-icon name="help-circle"></ion-icon>
                </div>
                
                <span>Change Passsword</span>
            </a>
        </li>
        <div style="width:100%;display:flex;justify-content:center;margin-top:20px;">
            <div class="sidebar-toggle">
                <button id="sidebaretoggle">
                    <i class='bx bx-chevron-left'></i>
                </button>
            </div>
        </div>
        
        
    </ul>

    
 
</nav>
<!-- end sidebar -->
 
<!-- topbar-->
<main class="dash-container">
<header class="topbar">

    <form action="" method="post" >
        <div  class="search-bar">
            <input type="search" name="patient_id" placeholder="Search by username or id">
            <div  class="btn">
                <button  type="submit" name="search_id">
                    <img src="../images/search_buttom.png" alt="Icon" width="27" height="27"> 
                </button>
            </div>
           
        </div>
        
    </form>
   <div style="display:flex;align-items: center;gap:35px">
        <div class="notif-container" >
            <label class="notif-icon" for="notif">
                <i class='bx bxs-bell' style="color:black;"></i>
            </label>
            <div class="notif-count">
                +<?php echo $row[0]?>
            </div>
            <input type="checkbox"  id="notif" style="display:none">
            <div class="notif-box" >
                <label for="notif" class="notiflabel">notifications</label>
                <label for="notif"  class="href-notif">
                    <a href="#" style="color: green;"><i class='bx bxs-chevrons-right' style="color: green;"></i> Lire les notification</a>
                </label>
            </div>
        </div>
        <div class="acount-container">
            <label for="acount" class="acount-btn"><?php echo $_SESSION['name'];?>  <i class='bx bxs-cog' style="color:#839192;"></i></label>
            <input type="checkbox"  id="acount">
            <div class="acount-options" >
                <label for="acount">
                    <a href="profile.php">
                    <i class='bx bxs-user-circle'  style="color:#839192;"></i>
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

   </div>
    
   <!-- <div  class="menuderaulement" >
        <li>
                <a href="" >
                    <span><?php echo $_SESSION['name'];?></span> 
                    <i class='bx bxs-cog' style="color:#839192"></i>
                </a>
                <ul class="sous-menu">
                    <li>
                        <a href="changepassword.php" style="display: flex; align-items: center;gap:10px">
                            <i class='bx bxs-cog' style="color:black"></i>
                            <span style="font-size: 14px;">Change password</span>
                        </a>
                    </li>
                    <li>
                        <a href="../Login/login.php"  style="display: flex; align-items: center;gap:10px">
                            <i class='bx bx-log-out' style="color:black"></i>
                            <span style="font-size: 14px;">Log out</span>
                        </a>
                    </li>

                </ul>
        </li> 
    </div>-->
   
</header>
