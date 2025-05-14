<?php include "Home.php"?>
    <div class="edit-contaner" >
        <h1>Edit Patient Information</h1>
        <form action="" method="post" style=" display: flex;flex-direction: column;">
            <?php
                        $sql="select  Last_name,First_name,Gender,birthdate,address ,contact_phone,email ,registration_day,
                        height_cm, weight_kg, smoking_alcohol, blood_type, bmi, physical_condition, chronic_diseases, medical_history,
                        surgical_history, drug_allergies  from patient where id=?";

                        $stmt=$conn->prepare($sql);
                        
                        $stmt->bind_param("i",$_GET['patientId']);
                        if(!$stmt->execute()){
                            die( "Error") ;
                        }
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc(); // <-- important : récupérer les données ici
                        
            ?>
            <h2> Personnel Information :</h2>
            <input type="hidden" name="id" value="<?php echo $_GET['patientId']?>" required>
                    
            <label>Last Name  :</label>
            <input type="text" name="Last_name" value="<?php echo $row['Last_name']?>" required>
                    
            <label>First Name  :</label>
            <input type="text" name="First_name" value="<?php echo $row['First_name']?>" required>
                    

            <label >Gender : </label>
            <input type="text" name="gender" value="<?php echo $row['Gender']?>" required >
             
            <label >Birth Date : </label>
            <input type="text" name="birthdate" value="<?php echo $row['birthdate']?>" required >
             
                    
            <label>Phone Number :</label>
            <input type="number" name="contact_phone" value="<?php if($row['contact_phone']){echo $row['contact_phone'];} else echo 'None'; ?>" required> 

            <label >Email : </label>
            <input type="text" name="email" value="<?php if($row['email']){echo $row['email'];} else{ echo 'None';} ?>" required >
             
            <label >Adress : </label>
            <input type="text" name="address" value="<?php if( $row['address']) echo $row['address']; else echo 'None';?>" required >
            
            
            
            
            <h2>Basic Medical Information :</h2>

            <label>Physical Condition :</label>
            <select name="physical_condition" id="">
                <?php
                    $physicalCond_options=['Moderate','Weak','Requires Special Care','Active'];
                    foreach($physicalCond_options as $itm){
                        if($itm ==  $row['physical_condition']){
                            ?>
                        <option value="<?php echo $itm?>" selected><?php echo $itm?></option>
                        <?php
                        }else {
                            ?>
                            <option value="<?php echo $itm?>"><?php echo $itm?></option>
                        <?php
                        }
                    }
                ?>
            </select>
                    

            <label >Chronic Diseases : </label>
            <input type="text" name="chronic_diseases" value="<?php  if($row['chronic_diseases']){echo $row['chronic_diseases'];} else echo$row['chronic_diseases'];?>" required >
             
            <label >Medical History : </label>
            <input type="text" name="birthdmedical_historyate" value="<?php if( $row['medical_history']) echo $row['medical_history'];else echo 'None';?>"  >
             
                    
            <label>Surgical History :</label>
            <input type="text" name="surgical_history" value=" <?php if( $row['surgical_history']) echo $row['surgical_history'];else echo 'None';?>" > 

            <label>Drug Allergies :</label>
            <input type="text" name="drug_allergies" value="<?php if( $row['drug_allergies']) echo $row['drug_allergies']; else echo 'None';?>" required> 

            <label >Smoking Alcohol : </label>
            <select name="smoking_alcohol" id="">
                <?php
                    $smoking_options=["yes","no","rarely"];
                    foreach($smoking_options as $itm){
                        if($itm ==  $row['smoking_alcohol']){
                            ?>
                        <option value="<?php echo $itm?>" selected><?php echo $itm?></option>
                        <?php
                        }else {
                            ?>
                            <option value="<?php echo $itm?>"><?php echo $itm?></option>
                        <?php
                        }
                    }
                ?>
            </select>
             
            <label >Blood Type  : </label>
            <select name="blood_type">
                <?php
                    $blood_types = ["A+", "A-", "B+", "B-", "AB+", "AB-", "O+", "O-"];
                    foreach($blood_types as $itm){
                        if($itm ==  $row['blood_type']){
                            ?>
                        <option value="<?php echo $itm?>" selected><?php echo $itm?></option>
                        <?php
                        }else {
                            ?>
                            <option value="<?php echo $itm?>"><?php echo $itm?></option>
                        <?php
                        }
                    }
                ?>
            </select>
            
            <label >BMI  : </label>
            <input type="nymber" name="bmi" value="<?php if($row['bmi']) echo $row['bmi'];else echo "None";?>" required >
            
            <label >Height (Cm)   : </label>
            <input type="number" name="height_cm" value="<?php if($row['height_cm']) echo $row['height_cm'];else echo "None";?>" required >
            
            <label >Weight (kg)   : </label>
            <input type="number" name="weight_kg" value="<?php if($row['weight_kg']) echo $row['weight_kg'];else echo "None";?>" required >
            

                       
             
            <div class="modal-footer">
                <button  type="reset" style="background-color: red;">CANCEL</button>
                <button type="submit"  name="editpersonnelInf" style="background-color: #44b0f8;">UPDATE</button>
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

</body>
</html>


