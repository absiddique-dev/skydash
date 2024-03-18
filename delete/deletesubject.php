<?php

require("../includes/conn.php");

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if(isset($data['id'])){
$id = $data['id'];
$query = "DELETE FROM subjects WHERE id = '$id'";
$run = mysqli_query($conn, $query);

if($run){
    echo "Subject has been deleted";
}else {
    echo "Something went wrong";
}
}