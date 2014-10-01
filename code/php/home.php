<?php
require_once 'db_connect.php';
?>
<!doctype html>

<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" data-useragent="Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Friendbook</title>
    <meta name="description" content="A place for friends." />
    
    <meta name="author" content="Tyler Rockwood" />
	<link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link rel="stylesheet" href="/css/foundation.css" />
    <script src="/js/vendor/modernizr.js"></script>
    <?php
    if(isset($_COOKIE["ID_Friendbook"])) 

 		{ 

 		$user_id = $_COOKIE["ID_Friendbook"]; 

 	 	$check = mysqli_query($conn, "SELECT * FROM User_Page WHERE User_ID = '$user_id';")or die(mysql_error()); 
		
		if((mysqli_num_rows($check) == 1)){
			header("Location: /");
		}
 	}
 	?>
  </head>
  <body>
    

 <!-- Header and Nav -->
<div class="fixed" style="background-color: #3b5998">
 <nav class="top-bar row">
  <div class="large-12 columns">
  <ul class="title-area">
    <!-- Title Area -->
    <li class="name">
      <h1><a href="/"><img src="img/logo.png" alt="Friendbook" width="180" height="38"></a></h1>
    </li>
    <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
    <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
  </ul>

  <section class="top-bar-section" style="background-color: #3b5998">
    <!-- Right Nav Section -->
    <ul class="right" style="background-color: #3b5998">
      <li class="">

    	<div class="row">
    		<form action="" method="post">
    			<div class="large-5 columns">
            		<input type="email" name="loginEmail" placeholder="Email"></input>
        		</div>
        		<div class="large-5 columns">
            		<input type="password" name="loginPassword" placeholder="Password"></input>
        		</div>
        		<div class="large-2 columns">
            		<input class="expand success button" type="submit" name= "LogIn" value="Log In" title="Log In"/>
        		</div>
     		</form>
    	</div>

	  </li>
    </ul>
  </section>
  </div>
</nav>
</div>
  <!-- End Header and Nav -->
	

  <!-- First Band (Image) -->

  <div style="padding-top: 75px" class="row">
    <div class="large-12 columns">
    	<!-- PHP for login -->

	<?php
		//User logining in
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			
			if(isset($_POST['loginEmail']))
			{
				$loginEmail = $_POST['loginEmail'];
				$loginPassword = $_POST['loginPassword'];
				$loginEmail = mysqli_real_escape_string($conn, $loginEmail);
 				$loginPassword = mysqli_real_escape_string($conn, $loginPassword);
				$loginPassword = htmlspecialchars($loginPassword, ENT_QUOTES);
				$userId = mysqli_query($conn, "SELECT User_ID FROM User_Page WHERE Email = '$loginEmail' AND Password = '$loginPassword';");
				
				if(mysqli_num_rows($userId) == 1){
					//Login ok add a cookie
					$hour = time() + 3600; 
					while ($row = mysqli_fetch_array($userId)) { 
						setcookie("ID_Friendbook", $row[0], $hour);
					}
					header("Location: /");
					exit();
				}
				else {
					echo '<div data-alert data-options="animation_speed:500;" style="margin-bottom:25px;" class=" row alert-box warning round"> Wrong email or password.<a href="#" class="close">&times;</a></div>';
				}
				
			}
			//user signing up
			else if(isset($_POST['fName'])) {
				$errors = '';
				$newfname = $_POST['fName'];
				if (empty($newfname)) $errors .= '<li>First name is required</li>'; 
				$newlname = $_POST['lName'];
				if (empty($newlname)) $errors .= '<li>Last name is required</li>'; 
				$newemail = $_POST['email'];
				if (empty($newemail)) $errors .= '<li>Email is required</li>'; 
				$newbday = $_POST['bday'];
				if (empty($newbday)) $errors .= '<li>Birthday is required</li>'; 
				$newgender = $_POST['gender'];
				if (empty($newgender)) $errors .= '<li>Gender is required</li>'; 
				$newpassword = $_POST['password'];
				if (empty($newpassword)) $errors .= '<li>Password is required</li>'; 
				$newconfirm = $_POST['confirm'];
				if (strcmp($newpassword, $newconfirm) != 0) $errors .= '<li>Passwords do not match</li>'; 
				
				if (!empty($errors)) { 
 					echo '<div data-alert data-options="animation_speed:500;" style="margin-bottom:25px;" class=" row alert-box warning round"><ul>' . $errors . '</ul><a href="#" class="close">&times;</a></div>';
				}
				else{
					$newfname = mysqli_real_escape_string($conn, $newfname);
 					$newfname = htmlspecialchars($newfname, ENT_QUOTES);
					$newlname = mysqli_real_escape_string($conn, $newlname);
 					$newlname = htmlspecialchars($newlname, ENT_QUOTES);
					$newemail = mysqli_real_escape_string($conn, $newemail);
 					$newemail = htmlspecialchars($newemail, ENT_QUOTES);
					$newbday = mysqli_real_escape_string($conn, $newbday);
 					$newbday = htmlspecialchars($newbday, ENT_QUOTES);
					$newgender = mysqli_real_escape_string($conn, $newgender);
 					$newgender = htmlspecialchars($newgender, ENT_QUOTES);
					$newpassword = mysqli_real_escape_string($conn, $newpassword);
 					$newpassword = htmlspecialchars($newpassword, ENT_QUOTES);
					//check for if another user already has that email address
					$user_results = mysqli_query($conn, "SELECT Email FROM User_Page WHERE Email = " . $newemail); 
					if ($user_results) {
						if (mysqli_fetch_array($user_results)) {
							echo '<div data-alert data-options="animation_speed:500;" style="margin-bottom:25px;" class=" row alert-box warning round"> That email already has an account <a href="#" class="close">&times;</a></div>';
						}
					}
					else{
					$query = "CALL createUser_Page('". $newfname ."', '". $newlname ."', '". $newbday ."', '". $newemail ."', '". $newpassword ."', '". $newgender ."')";
					$result = mysqli_query($conn, $query);
					$row = mysqli_fetch_array($result); 
 					$status = $row[0]; 
					echo '<div data-alert data-options="animation_speed:500;" style="margin-bottom:25px" class=" row alert-box success round"> Registration Successful! <a href="#" class="close">&times;</a></div>';
					}
							
			}
			}
			
		}
	?>
      <img src="/img/Friendbook Register.jpg">

      <hr>
    </div>
  </div>
  
  <!-- Sign up form -->
  <div class="row">
    <div class="large-6 columns">
    	<form action="" method="post">
    		<fieldset>
    			<legend>Join Friendbook!</legend>
    	
      	
      			<div class="row"><div class="large-12 columns">
      			<input type="text" required placeholder="First Name" name="fName"/></div></div>
      	
      			<div class="row"><div class="large-12 columns">
      			<input type="text" required placeholder="Last Name" name="lName"/></div></div>
      	
      			<div class="row"><div class="large-12 columns">
      			<input type="email" required placeholder="Email"  name="email"/></div></div>
      	
      			<div class="row"><div class="large-7 columns">
      			<input type="date" required placeholder="Birthday" name="bday"/></div>
      	
      			<div class="large-5 columns"><label>Gender</label>
      			<input type="radio"  name="gender" value="Male" id="Male" checked><label for="Male">Male</label>
      			<input type="radio"  name="gender" value="Female" id="Female"><label for="Female">Female</label></div></div>
      	
      			<div class="row"><div class="large-12 columns">
      			<input type="password" required placeholder="Password (Limited to 10 characters)" name="password"/></div></div>
      		
      			<div class="row"><div class="large-12 columns">
      			<input type="password" required placeholder="Confirm Password"  name="confirm"/></div></div>
      	
      			<div class="row"><div class="large-12 columns"><input class="large success button" type="submit" name="SignUp" value="Sign Up" title="Sign Up"/></div></div>
      	
      		</fieldset>
      	</form>
    </div>
    <div class="large-6 columns">
      <!-- No ideas for this section yet -->
    </div>
  </div>



  


  <!-- Footer -->

  	<footer class="row">
    <div class="large-12 columns">
      <hr />
      <div class="row">
        <div class="large-5 columns">
          <p>&copy; CSSE 333 Friendbook Group</p>
        </div>
        <div class="large-7 columns">
          <ul class="inline-list right">
            <li></li>
          </ul>
        </div>
      </div>
    </div>
  </footer>
  	
  	
  	<script src="/js/vendor/jquery.js"></script>
  	<script src="/js/foundation/foundation.js"></script>
    <script src="/js/vendor/fastclick.js"></script>
    <script src="/js/foundation/foundation.topbar.js"></script>
    <script src="/js/foundation/foundation.reveal.js"></script>
    <script src="/js/foundation/foundation.alert.js"></script>
    <script src="/js/foundation/foundation.abide.js"></script>
    <script>
      $(document).foundation();

      var doc = document.documentElement;
      doc.setAttribute('data-useragent', navigator.userAgent);
    </script>
  </body>
</html>