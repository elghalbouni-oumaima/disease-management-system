<!-- <//?php require_once "controllerUserData.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/fpassword.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Document</title>
</head>
<body>
    <div class="container">
        <span class="rotate-container"></span>
        <form action="" method="post" class="verifyemail-Form">
            <//?php
                if(count($errors) == 0 and isset( $_SESSION['info'])){
            ?>
                    <div style="color: red;margin:20px;">
                        <//?php
                            echo  $_SESSION['info'];
                            // Delete the session variable
                            unset($_SESSION['info']);
                        ?>

                    </div>
            <//?php
                 }
            ?>
            <h2>Email Verification</h2>
            
            <div class="input-box">
                <input type="text" name="username" id="username"  autocomplete=""  required>
                <label for="username">Username</label>
                <i class='bx bxs-user-circle'></i>
            </div>

            <div class="input-box">
                <input type="email" name="email" id="email"  autocomplete=""  required>
                <label for="email">Email address</label>
                <i class='bx bxs-envelope'></i>
            </div>
            
            <button type="submit" name="verify">Continue</button>
           
        </form>
    </div>
    
</body>
</html> -->

<?php require_once "controllerUserData.php"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  	<link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <img class="wave" src="../images/wave.png" alt="">
    <div class="container">
        <div class="img">
            <img src="../images/undraw_medicine_hqqg.svg" alt="">
        </div>
        <div class="login-container">
            
            <form action=""  method="post">
                 <?php
                    if(count($errors) == 0 and isset( $_SESSION['info'])){
                    ?>
                            <div style="color: red;margin:20px;">
                                <?php
                                    echo  $_SESSION['info'];
                                    // Delete the session variable
                                    unset($_SESSION['info']);
                                ?>

                            </div>
                    <?php
                        }
                    ?>
                <img class="avatar" src="../images/undraw_young-man-avatar_wgbd.svg" alt="">
                <h3 style="white-space:nowrap;font-size:30px">Email Verification</h3>
                <div class="input-div ">
                    <div class="i">
                    <i class='bx bxs-user-circle'></i>
                    </div>
                    <div>
                        <h5>Username</h5>
                        <input type="text" class="input" name="username" required>
                    </div>
                </div>
                <div class="input-div ">
                    <div class="i">
                    <i class='bx bxs-envelope'></i>
                    </div>
                    <div>
                        <h5>Email address</h5>
                        <input type="email" class="input"  name="email"  required id="password">
                    </div>
                </div>
                <div style="display:flex; justify-content: space-between;">

                    <a href=""> <input type="checkbox" id="showpassword"> Show Password</a>
                    <a href="emailVerification.php">Forget Password</a>
                </div>
                <input type="submit" class="btn" value="Continue"  name="verify">
               
            </form>
            
        </div>
    </div>
    <script src="../js/main.js"></script>
    <script>
        const checkboxpw=document.getElementById('showpassword');
        console.log(checkboxpw);
        const pw=document.getElementById('password');
        console.log(pw);
        checkboxpw.addEventListener('change',function (){
        if(checkboxpw.checked){
            console.log("hi");
            pw.type="text";
        }
        else{
            pw.type="password";
        }
        });
    </script>
   
</body>
</html>

