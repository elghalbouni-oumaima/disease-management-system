
<?php
    include "Home.php";
?>
<style>
.searchbycol{
    display: flex;
    justify-content: flex-end;
    align-items: center;
    width: 100%;
    gap:10px;
    margin-bottom: 10px;
    margin-right: 40px;
    margin-top: 10px;
}


.searchbycol select{
    background: #f8f9fc;
   
    padding: 5px;
    outline:none
   
}
.search-box {
  position: relative;
  width: 360px;
  margin: 0 40px;
}

.search-box input {
  width: 100%;
  padding: 10px 35px 10px 15px;
  border: 2px solid #ccc;
  border-radius: 25px;
  font-size: 16px;
 
}

.search-box input:focus {
  outline: none;
  border-color: #3498db;
  box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
}
.search-box input:hover {
box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
}

.search-box i {
  position: absolute;
  right: 20px;
  top: 50%;
  transform: translateY(-50%);
  color: #999;
  font-size: 22px;
  pointer-events: none;
}
.seepatient-btn{
        background-color: red;
        color: white;
        border-radius: 25px;
        border: none;
        padding: 4px 6px ;
    }
    .seepatient-btn:hover{
        background-color: #1cc88a;
    }
</style>
<h1>Consult Doctor Records</h1>
<div style=" margin-top:40px;;margin-right:20px;display:flex; justify-content: flex-end;">
    <button onclick="openTkinterWindow()" class="btn btn-primary" style="font-size:20px;padding:5px 20px">Add Doctor</button>
</div>
<div class="consult_patient"> 
    <h2 class="table_title"> Doctor Records</h2>
    <div class="searchbycol">
        <select name="tablecolumn" id='column'>
            <option value="0">Select</option>
            <option value="1">Username</option>
            <option value="2">Full name</option>
            <option value="3">CIN</option>
            <option value="4">Email</option>
            <option value="5">Phone number</option>
            <option value="6">Specialization</option>
        </select>
        <div class="search-box">
            <input  type="search"  id="searchbar" placeholder="Search..." />
            <i class='bx bx-search'></i>
        </div>
    </div>
    <div  class="table_body">
        <table id="doctor_table">
            <thead>
                <tr style="text-align:center;">
                    <th>Username</th>
                    <th>Full name</th>
                    <th>CIN</th>
                    <th>Email</th>
                    <th>Phone number</th>
                    <th>
                        <select name="specialization" id="choix" style="background:transparent;border:none;color:white">
                            <option value="specialization" style="color:black"  selected>Specialization</option>
                            <?php
                            $stmt=$conn->prepare("select distinct specialization from doctor");
                            if($stmt->execute()){
                                $result=$stmt->get_result();
                                foreach($result as $row){
                                    ?>
                                        <option value="<?php echo $row['specialization'] ?>" style="color:black"><?php echo $row['specialization'] ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </th>
                    <th>Actions</th>
                        
                </tr>
            </thead>
            <tbody id='tbody'>
                    
                    <?php
                        $sql=" select  username,full_name ,CIN   ,email ,contact_info ,specialization  from doctor";
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
                                if(strpos($itm, '@') !== false){
                                    ?>
                                    <td ><a style="color:black;text-align:center;" href="mailto:<?php echo $itm;?>"><?php echo $itm;?></a></td>
                                    <?php
                                    
                                }
                                else if(strpos($itm, '+') !== false){
                                    ?>
                                    <td ><a style="color:black;text-align:center;" href="tel:<?php echo $itm;?>"><?php echo $itm;?></a></td>
                                    <?php
                                    
                                }
                                else{
                                    ?>
                                    <td style="text-align:center;"> <?php echo $itm?></td>
                                    <?php
                                }
                                
                            }
                            ?>
                            <td>
                               <form action="doctorprofile.php" method="get">
                                <input type="hidden" name="username" value=<?php echo $row['username'];?>>
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
  
<script>
   const table = document.getElementById("doctor_table");
   var specialization=document.getElementById("choix");
  //On dit à JavaScript : "Quand l’utilisateur change de spécialité, exécute ce qui est entre les { ... }.
  specialization.addEventListener("change", () => { 
    // 1. Vider le tableau sauf l'en-tête
  const tbody = table.querySelector("tbody");
  const rows = tbody.querySelectorAll("tr");
  rows.forEach(row => row.remove());
     // Récupère l'index sélectionné
    const choix = specialization.selectedIndex;
    // Récupère la valeur choisie
    const valeur = specialization.value;

    if (valeur !== "") {
    // On envoie la spécialité choisie à PHP
    fetch("get_doctors.php?spec=" + encodeURIComponent(valeur))
        .then(response => response.json()) // PHP renvoie des données JSON
        .then(data => {
            // On ajoute chaque médecin dans une ligne du tableau
            data.forEach(doctor => {
                const row = tbody.insertRow(); // Ajoute une ligne
                row.innerHTML = `
                    <td style="text-align:center;">${doctor.username}</td>
                    <td style="text-align:center;">${doctor.full_name}</td>
                    <td style="text-align:center;">${doctor.CIN}</td>
                    <td><a href="mailto:${doctor.email}" style="color:black;text-align:center;">${doctor.email}</a></td>
                    <td><a href="tel:${doctor.contact_info}" style="color:black;text-align:center;">${doctor.contact_info}</a></td>
                    <td style="text-align:center;">${doctor.specialization}</td>
                    <td style="display:flex;gap:40px">
                    <form action="editdoctorinf.php" method="get">
                        <input type="hidden" name="username" value="${doctor.username}">
                        <button type="submit" name="edit_treat" style="background-color:#52be80;text-align:center;" class="btn">Edit</button>
                    </form>
                    <form action="" method="post">
                        <input type="hidden" name="treatment_id" value="${doctor.username}">
                        <button type="submit" name="delete_treat" style="background-color:#e74c3c;text-align:center;" class="btn">Delete</button>
                    </form>
                    </td>
                `;
            });
        });
    }
});
   
// var searchbar = document.getElementById('searchbar');
// var column= document.getElementById('column');
// var tbody = document.getElementById('tbody');
// var origintable = tbody.innerHTML;
//   function search(){
//     tbody.innerHTML = origintable;
//     let rows = tbody.children;
//     if(searchbar.value.length < 1 || column.value == '0'){
//         return;
//     }
//     let filterrows = '';
//     let colnb= Number(column.value) - 1;
//     let searchtext = searchbar.value.toLowerCase();

//     for(let i =0;i < rows.length; i++){
//         const currentrowtext = rows[i].children[colnb].innerText.toLowerCase();

//         if(currentrowtext.indexOf(searchtext) > -1){
//             filterrows += rows[i].outerHTML;
//         }
//     }
//     tbody.innerHTML=filterrows;

//   }
// searchbar.addEventListener('input',search)


var searchbar = document.getElementById('searchbar');
var column = document.getElementById('column');
var tbody = document.getElementById('tbody') ;
var origintable = tbody.innerHTML;
function search(){
    tbody.innerHTML = origintable;
    let rows = tbody.children;
    let nbcol= Number(column.value)-1;
    if(column.value == '0' || searchbar.value.length < 1 ) {
        attachHoverEffect();
        return;
    }
    let searchtext = searchbar.value.toLowerCase();
    filtertables = '';
    for( let i=0;i<rows.length;i++){
        let currentext = rows[i].children[nbcol].innerText.toLowerCase();
        if(currentext.indexOf(searchtext) > -1){
            filtertables+=rows[i].outerHTML;
        }
    }
    tbody.innerHTML = filtertables;
    attachHoverEffect();
}
searchbar.addEventListener('input',search)
attachHoverEffect();

function attachHoverEffect(){
    var tr_elm=document.querySelectorAll('tr');
tr_elm.forEach(tr => {
    tr.addEventListener('mousemove',function(){
        let btn=tr.querySelector('.seepatient-btn');
        if(btn){
            console.log("hi");
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
}

function openTkinterWindow() {
    fetch('http://127.0.0.1:5000/run-addDoctor?var=<?php echo $_SESSION['username']; ?>')
    .then(response => response.text())
    .then(data => console.log(data))
    .catch(error => console.error('Error:', error));
    }

</script>
 
</body>
</html> 
    

