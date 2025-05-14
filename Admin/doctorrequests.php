
<?php 
require_once 'Home.php'; 
?>
<head>
    <style>

        table{
            margin-left: 10px;
            margin-top: 20px;
           overflow: hidden;
        }
        tbody tr:hover{
            background: transparent !important;
            
        }
        tbody td{
            padding: 10px 5px;
            border-bottom:1px solid black;
            text-align: center;
           
           
        }
        tbody  span{
            display: flex;
        }
        thead{
            background-color:  #eeeeee ;
            
        }
        thead th{
           text-align: center;
           color:black !important;
           position: static !important;
        }
        tbody select{
             background:  #7986cb  ;
             border:none;
             color:white;
             padding: 2px;
             margin-left: 5px;
        }
        tbody button{
            border:none;
            border-radius:25px;
            padding: 5px 10px;
            color: white;
            
        }
        .seepatient-btn{
        background-color: red;
        padding: 4px 6px ;
    }
    .seepatient-btn:hover{
        background-color: #1cc88a;
    }
    .validate-btn{
        background-color:rgb(75, 60, 243);
    }
    .validate-btn:hover{
        background-color:red;
    }
    .title{
        margin: 30px 20px;
    }
    .title i{
        color:green;
    }
    </style>

     <!-- DataTables CSS -->
     <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</head>
<h2 class="title">Doctor's Requests</h2>
<?php
$sql = "select  d.username,d.full_name,d.specialization,count(r.id ) as rowspan from doctor d,support_requests r 
where d.username=r.username
group by d.username,d.full_name,d.specialization;";

$stmt=$conn->prepare($sql);
if($stmt->execute()){

    $res=$stmt->get_result();
    $nb_rows = $res->Dnum_rows;
    
    ?>
   
    <h3 style=" margin: 40px  0px 20px 20px;font-size:20px" ><i class='bx bxs-check-circle' style="color:green;"></i> Total number of items found <?php echo  $nb_rows;?></h3>
       
        
        <table id='request_table'>
            <thead>
                <th style="border-right:1px solid black">Sent By</th>
                <th>Request Number</th>
                <th>Request Date</th>
                <th>Subject</th>
                <th>Description</th>
                <th>Validate</th>
            </thead>
            <tbody>
                <?php
                if($res){
                    foreach($res as $row){
                        if($row['rowspan']>0){
                            ?>
                            <tr>
                                <td rowspan= <?php echo $row['rowspan']?> style="border-right:1px solid black">
                                    <img class="avatar" src="../images/undraw_young-man-avatar_wgbd.svg" alt="" width="40">
                                    <span><strong>Username : </strong> <?php echo $row['username'];?></span><br>
                                    <span><strong>Full name : </strong> Dr.<?php echo $row['full_name'];?> </span><br>
                                    <span><strong>Specialization : </strong><?php echo $row['specialization'];?></span>
                                </td>
                                <?php
                                    $j=0;
                                    $sql ="select id,created_at,description,subject,status from support_requests where username=?";
                                    $requeststmt=$conn->prepare($sql);
                                    $requeststmt->bind_param("s",$row['username'],);
                                    if($requeststmt->execute()){
                                        $requestres=$requeststmt->get_result();
                                        foreach($requestres as $rowrequest){
                                            if($j!=0){
                                                ?>
                                                </tr>
                                                <tr>
                                                <?php
                                            }
                                            ?>
                                            <td><?php echo $rowrequest['id']?></td>
                                            <td><?php echo $rowrequest['created_at']?></td>
                                            <td style="white-space: normal;"><?php echo $rowrequest['subject']?></td>
                                            <td style="white-space: normal;"><?php  if($rowrequest['description']) echo $rowrequest['description'];else echo 'No description'?></td>

                                            <td>
                                                <?php 
                                                if($rowrequest['status']== "Pending"){
                                                    ?>
                                                    <form method="post" style="width:100%;text-align:center;">
                                                        <input type="hidden" name="requestid" value="<?php echo $rowrequest['id']?>">
                                                        <button  type="submit"  name="validaterequests"  class="validate-btn" onclick="marckasresolved()">Mark as Resolved</button>                                               
                                                    </form>
                                                    <?php
                                                }
                                                else{
                                                    ?>
                                                    <button  type="button" style=" background-color:rgb(60, 243, 176);" >Resolved</button>                                                
                                                <?php
                                                }
                                                ?>
                                                
                                            </td>

                                            </tr>

                                            <?php
                                            $j++;
                                        }
                                        
                                    }
                                    
                                
                                ?>
                        
                        
                            <?php
                        }
                    }
                }
                    
                ?>
                
            </tbody>
        </table>
        <?php
    }

    ?>
    </main>
    </div>
    
<script>


function marckasresolved(){
    requestbtn = document.querySelector('.validate-btn');
    console.log(requestbtn);
    if (requestbtn) {
    requestbtn.style.backgroundColor = 'rgb(60, 243, 176)';
    requestbtn.textContent = 'Resolved';
    }
}



$(document).ready(function() {
    $('#request_table').DataTable({
        "order": [],
        "pageLength": 10
    });
});
</script>

</body>
</html>