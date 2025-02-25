<?php
$name=$_POST['name'];
$note=$_POST['note'];

$host="localhost";
$dbusername="root";
$dbpass='';
$dbname="test";

$conn = new mysqli($host,$dbusername,$dbpass,$dbname,3307);


if($conn->connect_error){
    die('connection failed:'.$conn->connect_error);
}
else{
    $stmt=$conn->prepare("insert into notes(name,note) values(?,?)");
    $stmt->bind_param("ss",$name,$note);
    $stmt->execute();
    echo "registration successfully";
    $stmt->close();
    $conn->close();
}
