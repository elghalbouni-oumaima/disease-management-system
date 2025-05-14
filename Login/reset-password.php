<?php 
require_once  "controllerUserData.php";
$token=$_GET['token'];

$stmt=$conn->prepare("select reset_token_expires_date from doctor where username=?");
$stmt->bind_param('s',$_SESSION['username']);
if(!$stmt->execute()){
    die ("error");
}
$result=$stmt->get_result();
$row=$result->fetch_assoc();
if($row['reset_token_expires_date']<=date("Y-m-d H:i:s",time())){
    header('location : notfound.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="fpassword.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Document</title>
</head>
<body>
    <div class="container">
        <span class="rotate-container"></span>
        <form action="" method="post" class="verifyemail-Form">
            <h2>Reset Password</h2>
            <div class="input-box">
                <label for="token" class ="tokencode">Token</label>
                <input type="text" value="<?php echo $token; ?>"  style="padding-right:25px;" readonly>
                <i class='bx bxs-user-circle' style="color: red;"></i>
            </div>
            <div class="input-box">
                <input type="password" name="newpassword" id="newpassword"  autocomplete=""  required>
                <label for="newpassword">Create new password</label>
                <i class='bx bxs-lock-alt'></i>
            </div>

            <div class="input-box">
                <input type="password" name="cpassword" id="cpassword"  autocomplete=""  required>
                <label for="cpassword">Confirm your password</label>
                <i class='bx bxs-lock-alt'></i>
            </div>
            
            <button type="submit" name="changepassword">Change</button>
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
                else{
            ?>
                    <div style="color:#ec7063 ;margin:20px;">
                        <?php
                            foreach($errors as $error){
                                echo $error;
                            }
                            $errors=[];
                        ?>

                    </div>
                <?php
                }
                ?> 
        </form>
    </div>
    
</body>
</html>