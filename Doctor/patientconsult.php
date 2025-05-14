
    <?php
    include "Home.php";
    ?>
<link rel="stylesheet" href="../css/showtable.css">
<h1 class="table_title">Latest Diagnosis and Treatments per Patient</h1>
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
    <button onclick="openTkinterWindow()" class="btn btn-primary" style="font-size:20px;white-space: nowrap;background:none;border:none;color:#4468f8;"><strong style="font-size:22px;">+ </strong>New Patient</button>

</div>

<div class="treat-container"  id="Treatments_table"> 
    <div  class="table-container">
        <table>
            <thead>
                <tr style="text-align:center;" id="icon_arrow">
                    <th>Id<span class="icon-arrow">&uarr;</span></th>
                    <th>Full name<span class="icon-arrow">&uarr;</span></th>
                    <th>Gender<span class="icon-arrow">&uarr;</span></th>
                    <th>Birth date<span class="icon-arrow">&uarr;</span></th>
                    <th>diagnosis<span class="icon-arrow">&uarr;</span></th>
                    <th>Date of diagnosis <span class="icon-arrow">&uarr;</span></th>
                    <th>Treatments<span class="icon-arrow">&uarr;</span></th>
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
                                            ) as t on t.diagnosis_id= d.diagnosis_id
                                            where p.doctor_id=?) as tab where row_num=1 order by diagnosis_date desc ";
                        $stmt=$conn->prepare($sql);
                        
                        $stmt->bind_param("s",$_SESSION['username']);
                        if(!$stmt->execute()){
                            die( "Error") ;
                        }
                        $result = $stmt->get_result();
                        foreach ($result as $itm){
                            ?>
                            <tr id='tr_elm'>
                            <?php
                            foreach ($itm as $row){
                                if (!$row){
                                    $row='none';
                                }
                                ?>
                                <td> <?php echo $row?></td>
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
    fetch('http://127.0.0.1:5000/run-AddPatient?var=<?php echo $_SESSION['username']; ?>')
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

</script>
   
 
</main>
</html> 
    

