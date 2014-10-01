<?php
	$user_id = '';
    if(isset($_COOKIE["ID_Friendbook"])) 
	
 { 

 	$user_id = $_COOKIE["ID_Friendbook"]; 

 	 	$check = mysqli_query($conn, "SELECT * FROM User_Page WHERE User_ID = '$user_id';")or die(mysql_error()); 
		
		if(!(mysqli_num_rows($check) == 1)){
			header("Location: /home.php");
		}
		mysqli_free_result($check);
 }
	else{
			header("Location: /home.php");
		}
	
	if(isset($_GET['logout'])) {

		setcookie('ID_Friendbook','',time()-3600);

		header('Location: /home.php');

		exit();

		}
?>