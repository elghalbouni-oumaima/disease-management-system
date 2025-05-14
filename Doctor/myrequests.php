<?php 
require_once 'Home.php';
?>
<head>
    <style>

        table{
            margin-left: 20px;
            margin-top: 20px;
            width:90%;
        }
        tbody tr:hover{
            background: transparent !important;
            
        }
        tbody td{
            padding: 10px 30px;
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
        background-color:rgb(60, 243, 176);
    }
    .validate-btn:hover{
        background-color:#7986cb;
    }
    .title{
        margin: 30px 20px;
    }
    .title i{
        color:green;
    }
    #status{
        border-radius:25px;
        padding: 4px 6px;
        display: flex;
        justify-content:center;
    }
    </style>
</head>
<h2 class="title">My Requests</h2>
<?php
$sql = "select id,created_at,subject,description,status from support_requests where username=?";

$stmt=$conn->prepare($sql);
$stmt->bind_param("s",$_SESSION['username']);
if($stmt->execute()){

    $res=$stmt->get_result();
    $nb_rows = $res->num_rows;
    
    ?>
    <h3 style=" margin: 40px  0px 10px 20px;font-size:20px" ><i class='bx bxs-check-circle' style="color:green;"></i> Total number of items found <?php echo  $nb_rows;?></h3>

    <table>
        <thead>
            <th style="border-right:1px solid black">Request ID</th>
            <th>Date of request</th>
            <th>Subject</th>
            <th>Description</th>
            <th>Status</th>
        </thead>
        <tbody>
            <?php
            if($res){
                foreach($res as $row){
                        ?>
                    <tr>
                        <td  style="border-right:1px solid black"><?php echo $row['id']?></td>
                        <td><?php echo $row['created_at'];?></td>
                        <td><?php echo $row['subject'];?></td>
                        <td><?php if( $row['description']) echo $row['description']; else echo '-'?></td>
                        <td >
                            <?php 
                            if(strtolower($row['status'])) {
                                ?>
                                 <span id="status" style="background-color: #f5b041  ;"><?php echo $row['status'];?></span>
                                <?php
                            }
                            else {
                                ?>
                                 <span id="status" style="background-color: rgb(29, 233, 158)   ;"><?php echo $row['status'];?></span>
                                <?php
                            }
                            
                            ?>
                           
                        </td>
                    </tr>
        <?php
                }
            }
            ?>
        </tbody>
    </table>
    </main>
    <?php
}
require_once '../Admin/footer.html'

?>
</div>
<script>
document.querySelectorAll('tr').forEach(row => {
    const specializationSelect = row.querySelector('.choixSp');
    const doctorSelect = row.querySelector('.choixnewdoctor');

    if (specializationSelect && doctorSelect) {
        specializationSelect.addEventListener('change', function () {
            const specialization = this.value;
            console.log(specialization);

            fetch('get_doctors_by_specialization.php?specialization=' + encodeURIComponent(specialization))
                .then(response => response.text())
                .then(data => {
                    doctorSelect.innerHTML = data;
                    console.log("data :",data);
                })
                .catch(error => console.error('Error:', error));
        });
       
    }
});
</script>

</body>
</html>