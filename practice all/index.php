<?php

// Include the database class
include 'database.php';

// Create a new Database object
$obj = new Database();

// Insert data into the 'students' table
$insertStatus = $obj->insert('students', [
    'student_name' => 'Ram Kumar',
    'age' => 20,
    'city' => 'Goa'
]);

// Check if the insert was successful
if ($insertStatus) {
    echo "Record inserted successfully!";
} else {
    echo "Failed to insert record: ";
    print_r($obj->getResult());
}

?>
