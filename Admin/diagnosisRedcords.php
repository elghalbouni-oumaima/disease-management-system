<?php include "Home.php"; ?>
<!--  delete_warning Modal -->
<div class="modal fade" id="delete_warning" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div  style="justify-content: space-between;padding:30px;text-align:center;">
        <i class='bx bx-message-error' style="font-size:6rem;color: #ffe082 ;"></i>
        <h2>Are you sure ?</h2>
        <span>You won't be able to revert this!</span>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <form action="" method="post">
                
                <input type="hidden" name="diagnosis_id" id="modaldiagid">    
                <button type="submit" class="btn btn-primary" name="delete_diag" style="background-color:red;">Continue</button>
            </form>
        </div>  
      
     
    </div>
  </div>
</div>

<!-- delete_warning Modal End-->


<link rel="stylesheet" href="../css/showtable.css">
<style>
    .btn-primary{
        border:#4468f8;
    }
</style>
<h1 style="margin-bottom:20px"> Diagnosis Records</h1>
<div class="table__Header">
    <div class="search-data">
        <div class="export__file">
            <label for="export-file" class="export__file-btn" title="Export File"></label>
            <input type="checkbox"  id="export-file">
            <div class="export__file-options">
                <label> Export As &nbsp;&#10140;</label>
                <label for="export-file" id="toJSON"> <img src="../images/json.png" alt="" > JSON</label>
                <label for="export-file" id="toEXCEL"> <img src="../images/excel.png" alt="" >EXCEL</label>
            </div>
        </div>              
        <div class="input-grp">
            <input type="search" name=""  placeholder="Search Data...">
            <img src="../images/search_buttom.png" alt="Icon" width="27" height="27"> 
        </div>
        
    </div>
    <button onclick="openTkinterdiagnosis()" class="btn btn-primary" style="font-size:20px;white-space: nowrap;background:none;border:none;color:#4468f8;padding:5px"><strong style="font-size:22px;">+ </strong>Add diagnosis</button>

</div>
<div class="consult_patient"  id="Treatments_table"> 
    <h2 class="table_title"> Diagnosis Records</h2>
    <div  class="table_body">
        <table>
            <thead>
                <tr style="text-align:center;">
                    <th>Patients ID<span class="icon-arrow">&uarr;</span></th>
                    <th>Diagnoses ID<span class="icon-arrow">&uarr;</span></th>
                    <th>Diagnoses<span class="icon-arrow">&uarr;</span></th>
                    <th>Date of diagnoses<span class="icon-arrow">&uarr;</span></th>
                    <th>Diagnoses notes <span class="icon-arrow">&uarr;</span></th>
                    <th>Actions</th>
                        
                </tr>
            </thead>
            <tbody>
                    
            <?php
                $sql="select patient_id, diagnosis_id,diagnosis_name,diagnosis_date,diagnosis_notes from diagnosis ";
                $stmt=$conn->prepare($sql);

               if(!$stmt->execute()){
                     die( "Error") ;
               }
                $result = $stmt->get_result();
                foreach ($result as $row){
            ?>
                <tr>
                    <?php
                        foreach ($row as $itm){
                                if (!$itm){
                                    $itm='none';
                                }
                                ?>
                                <td  style="text-align: center;"> <?php echo $itm?></td>
                                <?php
                            }
                            ?>
                                <td style="display:flex;gap:40px"> 
                                    <form action="editDiagnosis.php" method="get">
                                        <input type="hidden" name="patientId" value=<?php echo $row['patient_id']; ?>>
                                        <input type="hidden" name="diagnosis_id" value=<?php echo $row['diagnosis_id']; ?>>
                                        <button type="submit" name="edit_diag" style="background-color:  #52be80" class="btn">Edit</button>
                                    </form>
                                    <form action="" method="post">
                                        <button  style="background-color:  #e74c3c;color:black;outline:none "
                                            type="button" 
                                            class="btn btn-primary" 
                                            data-toggle="modal" 
                                            data-target="#delete_warning" 
                                            data-diagid=<?php echo $row['diagnosis_id']; ?>>
                                            Delete
                                        </button> 
                                    </form>
                                </td>
                                
                </tr>
                            
                            <?php
                        }
                
                    ?>


                </tbody>
            </table>
        </div>
            
</div>
<script src="../js/script.js"></script> 
<script>
    function openTkinterdiagnosis() {
    //const username = encodeURIComponent("<//?php echo $_SESSION['username']; ?>");
    fetch('http://127.0.0.1:5000/run-addDiagnosis?var=<?php echo $_SESSION['username']; ?>')
    .then(response => response.text())
    .then(data => console.log(data))
    .catch(error => console.error('Error:', error));
}
</script>
 <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
<script src="../js/modal-handler.js"></script> 
</body>
</html> 
    

