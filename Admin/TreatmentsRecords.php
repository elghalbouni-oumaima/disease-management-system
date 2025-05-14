<?php include "Home.php";?>
<!--  delete_warning Modal -->
<div class="modal fade" id="deleteTreat_warning" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                
                <input type="hidden" name="treatment_id" id="modaltreat">    
                <button type="submit" class="btn btn-primary" name="delete_treat" style="background-color:red;">Continue</button>
            </form>
        </div>  
      
     
    </div>
  </div>
</div>

<!-- delete_warning Modal End-->


<h1>Consult Treatments Records</h1>
<link rel="stylesheet" href="../css/showtable.css">
<style>
    .btn-primary:hover{
        border:#4468f8;
    }
</style>
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
    <button onclick="openTkinterTreatment()" class="btn btn-primary" style="font-size:20px; white-space: nowrap;background:none;border:none;color:#4468f8"><strong style="font-size:22px;">+ </strong> New Treatment</button>

</div>
<div class="consult_patient"  id="Treatments_table"> 
    <h2 class="table_title"> Treatments Records</h2>
    <div  class="table_body">
        <table>
            <thead>
                <tr style="text-align:center;">
                    <th>Diagnosis ID <span class="icon-arrow">&uarr;</span></th>
                    <th>ID <span class="icon-arrow">&uarr;</span></th>
                    <th>Treatments <span class="icon-arrow">&uarr;</span></th>
                    <th>Medication <span class="icon-arrow">&uarr;</span></th>
                    <th>Dosage <span class="icon-arrow">&uarr;</span></th>
                    <th>Treatments duration <span class="icon-arrow">&uarr;</span></th>
                    <th>Treatments note <span class="icon-arrow">&uarr;</span></th>
                    <th>Actions</th>
                        
                </tr>
            </thead>
            <tbody>
                    
            <?php
                $sql="select diagnosis_id, treatment_id,treatment_name,medication_name,dosage,treatment_duration,treatment_notes from treatments";
                $stmt=$conn->prepare($sql);
               
                if(!$stmt->execute()){
                        die( "Error") ;
                }
                $result1 = $stmt->get_result();
                foreach ($result1 as $row){
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
                                    <form action="editTreatment.php" method="get">
                                        <input type="hidden" name="treatment_id" value=<?php echo $row['treatment_id']; ?>>
                                        <button type="submit" name="edit_treat" style="background-color:  #52be80" class="btn">Edit</button>
                                    </form>
                                    
                                        <button  style="background-color:  #e74c3c;color:black;outline:none "
                                            type="button" 
                                            class="btn btn-primary" 
                                            data-toggle="modal" 
                                            data-target="#deleteTreat_warning" 
                                            data-treatid=<?php echo $row['treatment_id']; ?>>
                                            Delete
                                        </button> 
                                   
                                </td>
                                
                            </tr>
                            
                            <?php
                        }
                
                    ?>
                </tbody>
            </table>
        </div>
            
</div>
</main>
<script src="../js/script.js"></script>

<script>
    function openTkinterTreatment() {
    fetch('http://127.0.0.1:5000/run-addTratment?var=<?php echo $_SESSION['username']; ?>')
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
    

