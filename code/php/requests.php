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
    <link rel="stylesheet" href="/foundation-icons/foundation-icons.css" />
    <link rel="stylesheet" href="zmultiselect/zurb5-multiselect.css" />
    <script src="/js/vendor/modernizr.js"></script>
    <?php
    	require_once 'check_cookie.php';
		
    ?>
    
  </head>
  <body>
    

 <!-- Header and Nav -->
<?php require_once 'header.php'; ?>
  <!-- End Header and Nav -->
	

  <div style="padding-top: 75px" class="row">



 <!-- Nav Sidebar -->
    <!-- This is source ordered to be pulled to the left on larger screens -->
    <div  class="large-3 columns">
      <div class="panel">
        <a href="#"><img style="margin-bottom: 5px;"  src="/img/Profile Pic Top Left.jpg" /></a>
        <h5><a href="/page.php?ID=<?php echo $user_id; ?>">
			<?php
			//Get user's Name
			
			$username = mysqli_query($conn, "SELECT PageName FROM User_Page WHERE User_ID = '$user_id';"); 
			 while ($row = mysqli_fetch_row($username)) {
        		echo $row[0];
    		}
			 mysqli_free_result($username);
			?>
		</a></h5>
          <div class="section-container vertical-nav" data-section data-options="deep_linking: false; one_up: true">
          <section class="section">
          	<h5 class="title"><a href="/" data-reveal>Home</a></h5>
          	<h5 class="title"><a href="/messages.php">Messages [<?php 
          	$unread = mysqli_query($conn, "SELECT COUNT(unread) as Unread
                FROM Message
                WHERE Message.Receiver_ID = '$user_id' AND Message.unread = 1;"); 
			 while ($row = mysqli_fetch_row($unread)) {
        		echo $row[0];
    		} ?>]</a></h5>
          	<h5 class="title"><a href="#" data-reveal-id="friendModal" data-reveal>Search a Friend</a></h5>
          	<h5 class="title"><a href="/requests.php">Requests [<?php 
          	$unseen = mysqli_query($conn, "SELECT COUNT(*) as toAccept
                FROM temp_request
                WHERE temp_request.Receiver_ID = '$user_id'"); 
			 $row = mysqli_fetch_row($unseen);
        		echo $row[0];
    		 mysqli_free_result($unseen);?>]</a></h5>
    		 <h5 class="title"><a href="/friends.php">View Friends [<?php 
          	$fs = mysqli_query($conn, "SELECT COUNT(PageName) FROM User_Page WHERE User_ID IN (SELECT User2 FROM User_Follows_User WHERE User1 = $user_id AND User2 != $user_id)"); 
			 $row = mysqli_fetch_row($fs);
        		echo $row[0];
    		 mysqli_free_result($fs);?>]</a></h5>
          	<!-- To call a "logout" just create a link with the href="?logout" -->
            <h5 class="title"><a href="?logout">Log Out</a></h5>
          </section>
        </div>
      </div>
    </div>
    <!-- Messages List Feed -->
    <div class="large-9 columns">
    	<?php
    	if($_SERVER['REQUEST_METHOD']=='POST'){
    		if(isset($_POST['denierID'])){
    			$denier = $_POST['denierID'];
				$reqID = $_POST['requesterID'];
				mysqli_query($conn, "DELETE FROM `fb`.`temp_request` WHERE `temp_request`.`Requester_ID` = $reqID AND `temp_request`.`Receiver_ID` = $denier") or die(mysqli_error($conn));
    			echo '<div data-alert data-options="animation_speed:500;" style="margin-bottom:25px" class=" row alert-box alert round"> Request denied! <a href="#" class="close">&times;</a></div>';
    		}
    		elseif(isset($_POST['accepterID'])){
    			$accepter = $_POST['accepterID'];
				$reqID = $_POST['requesterID'];
				mysqli_query($conn, "CALL `Request_Accepted` ($reqID , $accepter);") or die(mysqli_error($conn));
				echo '<div data-alert data-options="animation_speed:500;" style="margin-bottom:25px" class=" row alert-box success round"> Request accepted! <a href="#" class="close">&times;</a></div>';
    		}
    		
    	}   	
    	?>
    <?php 
    
    
    $sql = "SELECT PageName AS RequesterName, User_ID AS RequesterID, (SELECT COUNT(User1) 
       FROM User_Follows_User WHERE User2 IN (SELECT User2 FROM User_Follows_User 
       WHERE User1 = 1 AND User2 != User_ID) AND User1 = User_ID AND User2 != $user_id) AS Mutual_Friends  
       FROM User_Page WHERE User_ID IN (SELECT Requester_ID FROM temp_request WHERE Receiver_ID = $user_id) ORDER BY Mutual_Friends DESC";
    
    	$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
		
		while( $row = mysqli_fetch_array($rs, MYSQL_ASSOC) )
		{
			echo '<div class="row">
      		<div class="large-2 columns small-3"><img src="/img/80x80.jpg" /></div>
      		<div class="large-10 columns">';
			echo '<h4><a href="/page.php?ID=' . $row['RequesterID'] . '">' . $row['RequesterName'] . '</a><span data-tooltip class="has-tip right" title="';
			$whoFriendQuery = "SELECT PageName FROM User_Page WHERE User_ID IN (SELECT (User2) FROM User_Follows_User WHERE User2 IN (SELECT User2 FROM User_Follows_User 
			WHERE User1 = $user_id AND User2 != ". $row['RequesterID'] .") AND User1 = ". $row['RequesterID'] ." AND User2 != $user_id)";
     		$whoFriend = mysqli_query($conn, $whoFriendQuery) or die(mysqli_error($conn));
			$tuple = mysqli_fetch_array($whoFriend, MYSQL_NUM);
			if(!(empty($tuple[0]))){
			echo $tuple[0];
			while($tuple = mysqli_fetch_array($whoFriend, MYSQL_NUM)){
			  echo '</br>'.$tuple[0];
		  	}}
		  	mysqli_free_result($whoFriend);
			echo '">'. $row['Mutual_Friends'].' Mutual Friends</span></h4></br>';
			echo '<a class="button left" onclick="acceptfunction('.$row['RequesterID'].')">Accept Friend Request</a><a class="button right" onclick="denyfunction('.$row['RequesterID'].')">Deny Friend Request</a>';
			echo'</div>
			</div>
			<hr/>';
          
		}
		if(mysqli_num_rows($rs)==0){
			echo "<h3>You have no friend requests at this time.</h3>";
		}

		mysqli_free_result($rs)
	?>
    </div>

    

  </div>


  <!-- Footer -->
	<?php require_once 'footer.php'; ?>
	
	<!-- Start Modals -->
  	<div id="friendModal" class="reveal-modal" data-reveal>
 		<h3>Find people on Friendbook</h3>
      			<form action="people.php" method="get">
      				<label>Enter the name of who you are looking for below:</label>
      				<input type="text" name="q" />
      				<input type="submit" value="Search" title="Search" />
      			</form>
  		<a class="close-reveal-modal">&#215;</a>
	</div>

	<!-- End Modals -->
	<script src="/js/vendor/jquery.js"></script>
	<script src="/js/foundation/foundation.js"></script>
    <script src="/js/vendor/fastclick.js"></script>
    <script src="/js/foundation/foundation.topbar.js"></script>
    <script src="/js/foundation/foundation.reveal.js"></script>
    <script src="/js/foundation/foundation.alert.js"></script>
    <script src="js/foundation/foundation.tooltip.js"></script>
    <script src="js/foundation/foundation.accordion.js"></script>
    <script src="/zmultiselect/zurb5-multiselect.js"></script>
	<script>
		function acceptfunction(requesterID)
		{
			console.log("Into accept Function");
      		var str1 ='<form action="" method="POST"> <?php echo ' <input type="hidden" value="'.$user_id.'" name="accepterID" /> '; ?> <input type="hidden" value= "';
      		var str2 ='"  name="requesterID" /> </form>';
      		var result = str1 + requesterID + str2;
    		$( result ).appendTo('body').submit();
		}
		function denyfunction(requesterID)
		{
			console.log("Into Deny Function");
      		var str1 ='<form action="" method="POST"> <?php echo ' <input type="hidden" value="'.$user_id.'" name="denierID" /> '; ?> <input type="hidden" value= "';
      		var str2 ='"  name="requesterID" /> </form>';
      		var result = str1 + requesterID + str2;
    		$( result ).appendTo('body').submit();
		}
	</script>
    <script>
      $(document).foundation();

      var doc = document.documentElement;
      doc.setAttribute('data-useragent', navigator.userAgent);
    </script>
    
    
  </body>
</html>
<?php
mysqli_close($conn);
?>