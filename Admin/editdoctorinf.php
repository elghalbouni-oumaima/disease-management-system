<?php include "Home.php"?>
    <div class="edit-contaner" >
        <h1>Edit Doctor Information</h1>
        <form action="" method="post" style=" display: flex;flex-direction: column;">
            <?php
            $sql="select 
            role,
            SUBSTRING_INDEX(full_name, ' ', 1) as Last_name, SUBSTRING_INDEX(full_name, ' ', -1) as First_name,
            gender,
            CIN,
            specialization,
            contact_info,
            email,
            Degree,
            Years_of_Experience,
            License_Issue_Date,
            License_Expiry_Date,
            Medical_License_Number,
            professional_memberships1,
            License_Issuing_Authority,
            professional_memberships2,
            working_hours,
            Official_Working_Hours,
            Weekly_Days_Off
            from doctor where  username=? and status='actif'";

            $stmt=$conn->prepare($sql);
            $stmt->bind_param("i",$_GET['username']);
            if(!$stmt->execute()){
                die( "Error") ;
            }
            $result = $stmt->get_result();
            $row = $result->fetch_assoc(); // <-- important : récupérer les données ici
                        
            ?>
            <h2> Personnel Information :</h2>
            <input type="hidden" name="username" value="<?php echo $_GET['username']?>" required>
                    
            <label>Role  :</label>
            <select name="role" id="">
                <?php
                    $role=['Admin','Non admin'];
                    foreach($role as $itm){
                        if($itm ==  $row['role']){
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
                    
            <label>Last Name  :</label>
            <input type="text" name="Last_name" value="<?php echo $row['Last_name']?>" required>
                    
            <label>First Name  :</label>
            <input type="text" name="First_name" value="<?php echo $row['First_name']?>" required>
                    
            <label>CIN  :</label>
            <input type="text" name="CIN" value="<?php echo $row['CIN']?>" required>
                    

            <label >Gender : </label>
            <select name="gender" id="">
                <?php
                    $options = ["Female", "Male","Prefer not to say"];
                    foreach($options as $itm){
                        if($itm ==  $row['gender']){
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
             
                   
            <label>Phone Number :</label>
            <input type="tel" name="contact_phone" value="<?php if($row['contact_info']){echo $row['contact_info'];} else echo 'None'; ?>" required> 
            <label >Email : </label>
            <input type="email" name="email" value="<?php if($row['email']){echo $row['email'];} else{ echo 'None';} ?>" required >
             
            
            
            <h2>Professional Information :</h2>

            <label>Specialization :</label>
            <input type="text" name="specialization" value="<?php echo $row['specialization']?>">
                    

            <label >Degree : </label>
            <select name="Degree">
                <?php
                    $options =["Consultant", "Specialist", "General Practitioner"];
                    foreach($options as $itm){
                        if($itm ==  $row['Degree']){
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
             
            <label >Years Of Experience : </label>
            <input type="number" name="Years_of_Experience" value="<?php if( $row['Years_of_Experience']) echo $row['Years_of_Experience'];else echo 'None';?>"  >
             
                    
            <label>License Issue Date :</label>
            <input type="date" name="License_Issue_Date" value=" <?php if( $row['License_Issue_Date']) echo $row['License_Issue_Date'];else echo 'None';?>" > 

            <label>License Expiry Date :</label>
            <input type="date" name="License_Expiry_Date" value="<?php if( $row['License_Expiry_Date']) echo $row['License_Expiry_Date']; else echo 'None';?>" > 

            <label >Medical License Number : </label>
            <select name="Medical_License_Number" id="">
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
             
            <label >Professional Memberships 1  : </label>
            <select name="professional_memberships1">
                <?php
                    $options =["None", "ADA", "RC", "BMA", "CMA"];
                    foreach($options as $itm){
                        if($itm ==  $row['professional_memberships1']){
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
            
            <label >Professional Memberships 2  : </label>
            <select name="professional_memberships2">
                <?php
                    $options =["None", "ADA", "RC", "BMA", "CMA"];
                    foreach($options as $itm){
                        if($itm ==  $row['professional_memberships2']){
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

            <label >Working Hours : </label>
            <input type="number" name="working_hours" value="<?php echo $row['working_hours']?>"> 
            <label >Official Working Hours : </label>
            <select name="Official_Working_Hours">
                <?php
                    $options =["8AM - 4PM", "9AM - 5PM", "10AM - 6PM"];
                    foreach($options as $itm){
                        if($itm ==  $row['Official_Working_Hours']){
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
            <div>
                <label >Weekly Days Off : </label> 
                <div style=" display: flex;
                        align-items: center;
                        justify-content: space-between;gap: 8px;">
                    <?php
                    $i=0;
                    $options =['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                    foreach($options as $itm){
                        ?>
                            <input type="radio" name="<?php echo "radio". $i?>" id="<?php echo "radio". $i?>" value="<?php  echo $itm;?>"> <?php  echo $itm;?>
                            
                        <?php
                       
                        $i++;
                    }
                ?>
                </div>                   
            </div>
            
            <input type="text"  name="Weekly_Days_Off" id="Weekly_Days_Off" value="<?php echo $row['Weekly_Days_Off'] ?>">       

                       
            <div class="modal-footer">
                <button  type="reset" style="background-color: red;">CANCEL</button>
                <button type="submit"  name="editdoctorlInf" style="background-color: #44b0f8;" onclick="getweeksday()">UPDATE</button>
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
<script>

var radio0=document.getElementById('radio0'),
radio1=document.getElementById('radio1'),
radio2=document.getElementById('radio2'),
radio3=document.getElementById('radio3'),
radio4=document.getElementById('radio4'),
radio5=document.getElementById('radio5'),
radio6=document.getElementById('radio6'),
input=document.getElementById('Weekly_Days_Off');
console.log(input);
function ischecked(obj){
	if(obj.checked){
		input.value =input.value + obj.value + ',';
	}
}
function getweeksday(){
    input.value='';
	ischecked(radio0);
	ischecked(radio1);
	ischecked(radio3);
	ischecked(radio4);
	ischecked(radio5);
	ischecked(radio6);
}

</script>
</body>
</html>


