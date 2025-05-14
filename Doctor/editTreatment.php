<?php include "Home.php"?>
    <div class="edit-contaner" >
        <h2>Edit Treatment</h2>
        <form action="" method="post" style=" display: flex;flex-direction: column;">
            <label>Treatment ID :</label>
            <input type="number" name="treatment_id" id="" value="<?php echo $_GET['treatment_id']?>" readonly>
            <?php
                        $sql="select treatment_name,medication_name,dosage,treatment_duration,treatment_notes from treatments where treatment_id=? ";
                        $stmt=$conn->prepare($sql);
                        
                        $stmt->bind_param("i",$_GET['treatment_id']);
                        if(!$stmt->execute()){
                            die( "Error") ;
                        }
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc(); // <-- important : récupérer les données ici
                        
            ?>
            <label>Treatment :</label>
            <input type="text" name="treatment" value="<?php echo $row['treatment_name']?>" required>
                    

            <label >Medication : </label>
            <input type="text" name="medication" value="<?php echo $row['medication_name']?>" required >
             
            <label >Dosage : </label>
            <input type="text" name="dosage" value="<?php echo $row['dosage']?>" required >
             
                    
            <label>Treatment duration :</label>
            <input type="number" name="treatment_duration" value="<?php echo $row['treatment_duration']?>" required> 

            <label >Treatment note : </label>
            <input type="text" name="treatment_note" value="<?php echo $row['treatment_notes']?>" required >
             
            <div class="modal-footer">
                <button  type="reset" style="background-color: red;">CANCEL</button>
                <button type="submit"  name="edittreatment" style="background-color: #44b0f8;">UPDATE</button>
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


