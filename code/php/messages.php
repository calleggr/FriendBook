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
		$toDisplay = 0;
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			if(isset($_POST['timestamp'])){
			echo "<script>console.log( 'Debug Objects: You made it to the query!' );</script>";
			$senderID = $_POST['senderID'];
			$timestamp = $_POST['timestamp'];
			$toDisplay = (int) $_POST['messageNum'];
			
			
			$query = "UPDATE Message SET unread=0, Time_Sent='$timestamp' WHERE Receiver_ID = $user_id AND Time_Sent = '$timestamp' AND Sender_ID = $senderID;";
			mysqli_query($conn, $query) or die(mysqli_error($conn));
		}
		elseif(isset($_POST['toSendtoID'])){
			$sendto = $_POST['toSendtoID'];
			$body = $_POST['body'];
			$body = mysqli_real_escape_string($conn, $body);
			$body = htmlspecialchars($body, ENT_QUOTES);
			$query = "CALL sendMessage($user_id,$sendto,'$body')";
			mysqli_query($conn, $query) or die(mysqli_error($conn));
		}
		
		}
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
    		} 
    		mysqli_free_result($unread);?>]</a></h5>
          	<h5 class="title"><a href="#" data-reveal-id="friendModal" data-reveal>Search a Friend</a></h5>
          	<h5 class="title"><a href="/requests.php">Requests [<?php 
          	$unseen = mysqli_query($conn, "SELECT COUNT(*) as toAccept
                FROM temp_request
                WHERE Receiver_ID = '$user_id'"); 
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
    <dl class="accordion" data-accordion>
    	<?php
    	$query = "SELECT Sender_ID, Time_Sent, unread, PageName, Content
                FROM Message, User_Page
                WHERE Receiver_ID = $user_id AND Sender_ID = User_Page.User_ID
                ORDER BY Message.Time_Sent DESC";
		$messages = mysqli_query($conn, $query);
		$count = 1;
		while($row = mysqli_fetch_array($messages, MYSQL_ASSOC)){
			if($row['unread'] == 1){
				echo '
    			<dd>
    				<a href="#panel'.$count.'" onclick="readfunction('. $row['Sender_ID'].", '". $row['Time_Sent'] ."', ". $count .')"><i class="fi-mail"></i> '.$row['PageName'].' <small class="right">'.$row['Time_Sent'].'</small></a>
    				<div id="panel'.$count.'" class="content">
   	   					<p>'.$row['Content'].'</p>
      					<a href="#" data-reveal-id="replyModal" data-reveal onclick="replyfunction('.$row['Sender_ID'].')">Reply</a>
    				</div>
  				</dd>
  		';
			}
			else {
				echo '
    			<dd>
    				<a href="#panel'.$count.'"><i class="fi-check"></i> '.$row['PageName'].' <small class="right">'.$row['Time_Sent'].'</small></a>
    				<div id="panel'.$count.'" class="content'; 
    				if($count == $toDisplay){
    					echo " active";
    				}    				
    				echo'">
      					<p>'.$row['Content'].'</p>
      					<a href="#" data-reveal-id="replyModal" data-reveal onclick="replyfunction('.$row['Sender_ID'].')">Reply</a>
    				</div>
  				</dd>
  		';
			}
		
  		$count = $count + 1;
  		}
		mysqli_free_result($messages);
  		?>
	</dl>     
    </div>

    

  </div>


  <!-- Footer -->
	<?php require_once 'footer.php'; ?>
	
	<!-- Start Modals -->
  	<div id="replyModal" class="reveal-modal" data-reveal>
  		<h3>Time to reply:</h3>
  		<p class="lead">Please enter your message below:</p>
  		<form name="replyForm" action=""  method="POST">
  			<input id="toSendtoID" type="hidden" value="#" name="toSendtoID" />
  			<textarea name="body"></textarea>
  			<input style="margin:0px;" class="button" type="submit" value="Send" title="Send" />
  		</form>
  		<a class="close-reveal-modal">&#215;</a>
	</div>
	<div id="friendModal" class="reveal-modal" data-reveal>
 		<h3>Find people on Friendbook</h3>
      			<form action="people.php" method="get">
      				<label>Enter the name of who you are looking for below:</label>
      				<input type="text" name="q" />
      				<input style="margin:0px;" class="button" type="submit" value="Search" title="Search" />
      			</form>
  		<a class="close-reveal-modal">&#215;</a>
	</div>

	<!-- End Modals -->
	<script>console.log( 'Debug Objects: You made it to the script!' );</script>
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
		function replyfunction(IDtoSendto){
			document.getElementById("toSendtoID").value = IDtoSendto;
		}
		function readfunction(senderID, timestamp, messageNum){
			console.log( 'Debug Objects: You made it to the function!' );
			//Okay here I need make it so you UPDATE message that it is read and then set which messageNUM should be open.
			var str1 ='<form action="" method="POST"> <input type="hidden" value= "';
			      		var str2 ='"  name="senderID" /><input type="hidden" value= "';
      		      		var str3 ='"  name="timestamp" /><input type="hidden" value= "';
      		      		var str4 ='"  name="messageNum" /></form>';
      		var result = str1 + senderID + str2 + timestamp + str3 + messageNum + str4;
    		$( result ).appendTo('body').submit();
		}
	</script>
    <script>
      $(document).foundation();

      var doc = document.documentElement;
      doc.setAttribute('data-useragent', navigator.userAgent);
    </script>
</body>
<?php
mysqli_close($conn);
?>
</html>
