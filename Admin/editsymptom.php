<?php include "Home.php"?>
    <div class="edit-contaner" >
        <h2>Edit Symptom</h2>
        <form action="" method="post" style=" display: flex;flex-direction: column;">
            <input type="hidden" name="patientId" value="<?php echo $_GET['patientId']?>">

            <label>Symptom ID :</label>
            <input type="number" name="symp_id" id="" value="<?php echo $_GET['symptom_id']?>" readonly>
            <?php
                        $sql="select symptom_name,severity,start_date from diseasemanagement.symptoms where symptom_id=?";
                        $stmt=$conn->prepare($sql);
                        
                        $stmt->bind_param("i",$_GET['symptom_id']);
                        if(!$stmt->execute()){
                            die( "Error") ;
                        }
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc(); // <-- important : récupérer les données ici
                        
            ?>
            <label>Symptom :</label>
            <input type="text" name="symptom" value="<?php echo $row['symptom_name']?>" required>
                    

            <label >Severity : </label>
            <input type="text" name="severity" value="<?php echo $row['severity']?>" required >
                    
                    
            <label>Start date :</label>
            <input type="date" name="date_start" value="<?php echo $row['start_date']?>" required>  
            <div class="modal-footer">
                <button  type="reset" style="background-color: red;">CANCEL</button>
                <button type="submit"  name="editSymptome" style="background-color: #44b0f8;">UPDATE</button>
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


