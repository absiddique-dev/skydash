<?php

require("../includes/conn.php");

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if(isset($data['id'])){
$id = $data['id'];
$query = "DELETE FROM categories WHERE id = '$id'";
$run = mysqli_query($conn, $query);

if($run){
    echo "Your data has been deleted";
}else {
    echo "Something went wrong";
}
}