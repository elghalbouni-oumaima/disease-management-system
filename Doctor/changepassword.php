<?php require_once "Home.php"?>    
<div class="edit-contaner" >
        <h2>Password change</h2>
        <form action="" method="post" style=" display: flex;flex-direction: column;">
            <label>Old password :</label>
            <input type="text" name="oldpassword" required>
            
            <label>New password :</label>
            <input type="text" name="newpassword" required>
            
            
            <label > Confirm new password : </label>
            <input type="text" name="cpassword"  required>
                    
                    
            <div class="modal-footer">
                <button type="reset" style="background-color: red;">CANCEL</button>
                <button type="submit"  name="modifypassword" style="background-color: #44b0f8;">Change password</button>
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
                        ?>
                    </div>
            <?php
                }
                else{
                    if( isset($_SESSION['info'])){
                    ?>
                    <div style="color:rgb(255, 255, 255) ; background-color:#1cc88a;font-size:14px ;font-weight:600;margin:20px;padding:15px 60px;width:max-content;">
                        <?php
                        
                         echo $_SESSION['info'];
                         // Supprimer la variable de session 'info'
                         unset($_SESSION['info']);
                        ?>
                    </div>
            <?php
                    }

                }
            ?>  
</div>
 
</body>
</html>