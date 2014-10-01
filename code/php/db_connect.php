<?php
// Open a connection to the database 
// (display an error if the connection fails) 
 $conn = mysqli_connect('localhost', 'root', 'Ahsh4Nae') or die(mysqli_error($conn)); 
mysqli_select_db($conn, 'fb') or die(mysqli_error($conn)); 
?>