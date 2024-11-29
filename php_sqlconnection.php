<?php
$host="localhost";
$userName="root";
$password=null;
$database="databasename";

$conn= new mysqli($host,$userName,$password,$database);

if($conn =>connection_error){
    die("some error ".$conn=>connection_error);
}

echo "connection suscccesfull"

?>
