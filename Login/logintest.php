<?php require_once "controllerUserData.php"; 
require_once('test.php');?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../css/style.css">
    <title>LoginPage</title>
</head>
<body>
    <div class="container">
        <div class="login-container">
            
            <div class="form-box">
                    
                <h2>Log in</h2>
                
                <form action="" method="post">

                    <div class="input-box">
                        <input type="text" name="username" required>
                        <label for="username">Username</label>
                        <i class='bx bxs-user-circle'></i>
                    </div>
                    
                    <div class="input-box">
                        <input type="password" name="password" required>
                        <label for="password">Password</label>
                        <i class='bx bxs-lock-alt' ></i>
                    </div>

                    <div class="signup-link">
                        <a href="emailVerification.php">Forgot password?</a>
                    </div>

                    <div style="text-align:center">
                        <button type="submit" class="btn" name="login">Log in</button>
                    </div>
                        
                </form>
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
            </div>
            
        </div>
        <div class="projet-name">
            <h2>Disease Management </h2>
            <p>& 
            Medical Statistics</p>
        </div>
        <div class="conatiner-img">
            <img src="../images/Medical_Landing_Page.png" alt="" width="450">
        </div>
    </div>
       
   
</body>
</html>
