<?php include('dbcon1.php'); ?>


<?php

if(isset($_GET['id'])){

    $id = $_GET['id'];

    

    $query = "delete  from `pc_reports` where `id` = '$id' ";

    $result = mysqli_query($connection, $query);

    if(!$result){
        die("query Failed".mysqli_error());
    
    }
    else{
     
            header('location:index1.php?solved_msg=You have successfully solved the record.');

 
}
}

?>