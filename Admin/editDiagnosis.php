<?php include "Home.php"?>

    <div class="edit-contaner" >
    <h2>Edit Diagnostic</h2>
        <form action="" method="post" style=" display: flex;flex-direction: column;">
            <input type="hidden" name="patientId" value="<?php echo $_GET['patientId']?>">
            <label>Diagnosis ID :</label>
            <input type="number" name="diag_id" id="" value="<?php echo $_GET['diagnosis_id']?>" readonly>
            <?php
                        $sql="select diagnosis_name,diagnosis_date,diagnosis_notes from diagnosis where diagnosis_id=?";
                        $stmt=$conn->prepare($sql);
                        
                        $stmt->bind_param("i",$_GET['diagnosis_id']);
                        if(!$stmt->execute()){
                            die( "Error") ;
                        }
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc(); // <-- important : récupérer les données ici
                        
            ?>
            <label>Diagnosis :</label>
            <input type="text" name="diagnosis_name" value="<?php echo $row['diagnosis_name']?>" required>
            
            <label>Date of diagnosis:</label>
            <input type="date" name="diagnosis_date" value="<?php echo $row['diagnosis_date']?>" required>  

            <label >Diagnosis notes : </label>
            <input type="text" name="diagnosis_notes" value="<?php echo $row['diagnosis_notes']?>"  >
                    
                    
            <div class="modal-footer">
                <button  type="reset" style="background-color: red;">CANCEL</button>
                <button type="submit"  name="editdiagnosis" style="background-color: #44b0f8;">UPDATE</button>
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


