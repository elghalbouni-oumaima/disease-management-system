
<?php
    include "Home.php";
?>
<head>
<link rel="stylesheet" href="../css/showtable.css">

     <!-- DataTables CSS -->
     <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</head>
<style>
    .consult_patient .table_body tr td{
        padding: 20px;
    }
    .consult_patient .table_body{
        max-width:100% !important;
        max-height: 900px !important;
        margin-bottom: 100px;
        
        height: auto;
    }
    .dataTables_wrapper .dataTables_filter input{
        width:300px;
        border-radius:25px;
        padding: 8px;
        transition:.2s ease-in-out;
        margin: 10px;
    }
    .dataTables_wrapper .dataTables_filter input:hover{
        width:320px;
        border-radius:25px;
        padding: 8px;
        border-color:#4468f8;
       
    }
    
</style>
<h1>Consult Patient Records</h1>

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
            
            
        </div>
        <button onclick="openTkinterWindow()" class="btn btn-primary" style="font-size:20px;white-space: nowrap;background:#4468f8;border:none;color:white;"><strong style="font-size:22px;">+ </strong>New Patient</button>

    </div>
<div  class="consult_patient"> 
    

    <h2 class="table_title" style="margin-bottom:20px;">Latest Diagnosis and Treatments per Patient</h2>
    <div  class="table_body">
        <table id='Treatments_table' style="width:100% !important" >
            <thead>
                <tr style="text-align:center;">
                    <th>Id</th>
                    <th>Full name</th>
                    <th>Gender</th>
                    <th>Birth date</th>
                    <th>diagnosis</th>
                    <th>Date of diagnosis </th>
                    <th>Treatments</th>
                    <th>Action</th>
                        
                </tr>
            </thead>
            <tbody>
                    
                    <?php
                        $sql="select id ,Full_name,Gender,birthdate,diagnosis_name,diagnosis_date,treatment from ( select p.id,concat(p.Last_name,' ',p.First_name) as Full_name,p.Gender,p.birthdate,d.diagnosis_name, d.diagnosis_date,t.treatment,
                                            ROW_NUMBER() OVER (PARTITION BY p.id ORDER BY d.diagnosis_date DESC) as row_num
                                            from patient as p
                                            left join diagnosis as d on  p.id=d.patient_id
                                            left join (select patient_id, diagnosis_id,group_concat(concat(treatment_name ,' - ',treatment_duration,' days') separator ' / ') as treatment from treatments group by patient_id,diagnosis_id 
                                            ) as t on t.diagnosis_id= d.diagnosis_id)
                                            as tab where row_num=1 order by diagnosis_date desc ";
                        $stmt=$conn->prepare($sql);
                        if(!$stmt->execute()){
                            die( "Error") ;
                        }
                        $result = $stmt->get_result();
                        foreach ($result as $itm){
                            ?>
                            <tr>
                            <?php
                            foreach ($itm as $row){
                                if (!$row){
                                    $row='none';
                                }
                                ?>
                                <td style="text-align:center;"> <?php echo $row?></td>
                                <?php
                            }
                            ?>
                            <td>
                               <form action="patientfile.php" method="get">
                                <input type="hidden" name="patientId" value=<?php echo $itm['id'];?>>
                                <button type="submit" class="seepatient-btn">See more</button>
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
    function openTkinterWindow() {
    fetch('http://127.0.0.1:5000/run-tkinter?var=<?php echo $_SESSION['username']; ?>')
    .then(response => response.text())
    .then(data => console.log(data))
    .catch(error => console.error('Error:', error));
    }

var tr_elm=document.querySelectorAll('tr');
tr_elm.forEach(tr => {
    tr.addEventListener('mousemove',function(){
        let btn=tr.querySelector('.seepatient-btn');
        if(btn){
            btn.style.backgroundColor ='#1cc88a';
        }
     });
})
tr_elm.forEach(tr => {
    tr.addEventListener('mouseout',function(){
        let btn=tr.querySelector('.seepatient-btn');
        if(btn){
            btn.style.backgroundColor ='red';
        }
     });
})

$(document).ready(function() {
    $('#Treatments_table').DataTable({
        "order": [],
        "pageLength": 10
    });
});
</script>
  
   
 
</body>
</html> 
    

