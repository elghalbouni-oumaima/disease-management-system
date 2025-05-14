<?php require_once "controllerUserData.php"; 
require_once('test.php');?>
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
                <img class="avatar" src="../images/undraw_young-man-avatar_wgbd.svg" alt="">
                <h2>Welcome</h2>
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
                    <i class='bx bxs-lock-alt' ></i>
                    </div>
                    <div>
                        <h5>Password</h5>
                        <input type="password" class="input"  name="password"  required id="password">
                    </div>
                </div>
                <div style="display:flex; justify-content: space-between;">

                    <a href=""> <input type="checkbox" id="showpassword"> Show Password</a>
                    <a href="emailVerification.php">Forget Password</a>
                </div>
                <input type="submit" class="btn" value="Login"  name="login">
                <?php
                    if(count($errors) > 0){
                ?>
                        <div style="color:  #ec7063 ;font-size:16px ;margin:20px;">
                            <?php
                            foreach($errors as $showerror){
                                echo $showerror;
                            }
                            $errors=[];
                
                            ?>
                        </div>
                <?php
                    }
                ?>  
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