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
		if (isset($_GET['ID'])) {
			$pageOwnerID = $_GET['ID'];
		} else {
			header('Location: /');
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
    		} mysqli_free_result($unread);?>]</a></h5>
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
    	if($_SERVER['REQUEST_METHOD'] == 'POST'){
    	If(isset($_POST['City'])){
    		$city = $_POST['City'];
			$job = $_POST['Job'];
			$relationship = $_POST['Relationship'];
			$religion = $_POST['Religion'];
			$city = mysqli_real_escape_string($conn, $city);
 			$city = htmlspecialchars($city, ENT_QUOTES);
			$job = mysqli_real_escape_string($conn, $job);
 			$job = htmlspecialchars($job, ENT_QUOTES);
			$relationship = mysqli_real_escape_string($conn, $relationship);
 			$relationship = htmlspecialchars($relationship, ENT_QUOTES);
			$religion = mysqli_real_escape_string($conn, $religion);
 			$religion = htmlspecialchars($religion, ENT_QUOTES);
			if(!(empty($city) || empty($job) || empty($religion) || empty($relationship))){
				mysqli_query($conn, "UPDATE User_Page SET Religion = '$religion', City = '$city', Job = '$job', Relationship = '$relationship' WHERE User_ID = $user_id") or die(mysqli_error($conn));
				echo '<div data-alert data-options="animation_speed:500;" style="margin-bottom:25px" class=" row alert-box success round"> Update successful! <a href="#" class="close">&times;</a></div>';
			}
			else{
				echo '<div data-alert data-options="animation_speed:500;" style="margin-bottom:25px" class=" row alert-box warning round"> You cannot leave a field empty! <a href="#" class="close">&times;</a></div>';
			}
    	}
		elseif(isset($_POST['messageSenderID'])){
				$messageSenderID = $_POST['messageSenderID'];
				$messageRecieverID = $_POST['messageRecieverID'];
				$body = $_POST['text'];
				$body = mysqli_real_escape_string($conn, $body);
 				$body = htmlspecialchars($body, ENT_QUOTES);
				if(!(empty($body))){
				mysqli_query($conn, "CALL sendMessage($messageSenderID, $messageRecieverID, '$body')") or die(mysqli_error($conn));
				echo '<div data-alert data-options="animation_speed:500;" style="margin-bottom:25px" class=" row alert-box success round"> Message sent! <a href="#" class="close">&times;</a></div>';
			}
			else{
				echo '<div data-alert data-options="animation_speed:500;" style="margin-bottom:25px" class=" row alert-box warning round"> You must send something! <a href="#" class="close">&times;</a></div>';
			}
		}
		elseif (isset($_POST['unfollowReciever'])) {
			$unrec = $_POST['unfollowReciever'];
			$unreq = $_POST['unfollowRequester'];
			mysqli_query($conn, "DELETE FROM User_Follows_User WHERE User1 = $unrec AND User2 = $unreq") or die(mysqli_error($conn));
			mysqli_query($conn, "DELETE FROM User_Follows_User WHERE User1 = $unreq AND User2 = $unrec") or die(mysqli_error($conn));
			echo '<div data-alert data-options="animation_speed:500;" style="margin-bottom:25px" class=" row alert-box success round"> User Unfollowed! <a href="#" class="close">&times;</a></div>';
		}
		elseif (isset($_POST['followReciever'])) {
			$rec = $_POST['followReciever'];
			$req = $_POST['followRequester'];
			mysqli_query($conn, "INSERT INTO temp_request (Requester_ID, Receiver_ID) VALUES ($req, $rec);") or die(mysqli_error($conn));
			echo '<div data-alert data-options="animation_speed:500;" style="margin-bottom:25px" class=" row alert-box success round"> User Requested! <a href="#" class="close">&times;</a></div>';
		}
		}
    	if(strcmp($user_id, $pageOwnerID) != 0){
    	$pageInfoQuery ="SELECT PageName, Gender, Religion, Relationship, Birthday, City, Job, User_ID, (SELECT COUNT(PageName) FROM User_Page 
    	WHERE User_ID IN (SELECT User2 FROM User_Follows_User WHERE User1 = $pageOwnerID AND User2 != $pageOwnerID)) AS NumFriends
        FROM User_Page WHERE User_ID = $pageOwnerID";
		$pageInfo = mysqli_query($conn, $pageInfoQuery) or die(mysqli_error($conn));
		$info = mysqli_fetch_array($pageInfo, MYSQL_ASSOC);
		$PageOwnersName = $info['PageName'];
		//PageName, Gender, Religion, Relationship, Birthday, City, Job, User_ID, NumFriends
    	echo '<div class="panel" style="padding-bottom: 75px;"><h3>'.$info['PageName'].'</h3></br>
    		<div class="row">
    			<div class="large-6 columns">
    				<p>Brithday: '.$info['Birthday'].'</br>
    					City: '.$info['City'].' </br>
    				 Job: '.$info['Job'].'</p>
    			</div>
    			<div class="large-6 columns">
    				<p>Gender: '.$info['Gender'].' </br>
    					Religion: '.$info['Religion'].'</br>
    				 Relationship: '.$info['Relationship'].'</p>
    			</div>
    		</div>
    		<div class="large-4 columns">
    		<a class="button" href="#" data-reveal-id="postModal" data-reveal>Create Post</a>
    		</div>
    		<div class="large-4 columns">
    		<a class="button" href="#" data-reveal-id="messageModal" data-reveal>Send Message</a>
    		</div>
    		<div class="large-4 columns">';
			$request = mysqli_query($conn, "SELECT * FROM temp_request WHERE Requester_ID = '$pageOwnerID' AND Receiver_ID ='$user_id';")or die(mysqli_error($conn)); 
			if((mysqli_num_rows($request) == 1)){
				echo '<a class="button disabled right">Request recieved</a>';
    			mysqli_free_result($request);
			}
			else {
				mysqli_free_result($request);
			$pending = mysqli_query($conn, "SELECT * FROM temp_request WHERE Requester_ID = '$user_id' AND Receiver_ID ='$pageOwnerID';")or die(mysqli_error($conn)); 
			
    		if((mysqli_num_rows($pending) == 1)){
    			echo '<a class="button disabled right">Request pending</a>';
    			mysqli_free_result($pending);
    		}
    		
			else {
			mysqli_free_result($pending);
    		$following = mysqli_query($conn, "SELECT * FROM User_Follows_User WHERE User1 = '$user_id' AND User2 ='$pageOwnerID';")or die(mysqli_error($conn)); 
			if((mysqli_num_rows($following) == 1)){
    			echo '<a href="#" class="button right" onclick="unfollowfunction('.$user_id.', '.$pageOwnerID.')">Unfollow User</a>';
    			mysqli_free_result($following);
    		}
			else{
				echo '<a href="#" class="button right" onclick="followfunction('.$user_id.', '.$pageOwnerID.')">Follow User</a>';
				mysqli_free_result($following);
			}}
    		
    		}
			
    		echo'</div>
      	</div>';
    	mysqli_free_result($pageInfo);
    	}
		else{
			$pageInfoQuery ="SELECT PageName, Gender, Religion, Relationship, Birthday, City, Job, User_ID, (SELECT COUNT(PageName) FROM User_Page 
    	WHERE User_ID IN (SELECT User2 FROM User_Follows_User WHERE User1 = $pageOwnerID AND User2 != $pageOwnerID)) AS NumFriends
        FROM User_Page WHERE User_ID = $pageOwnerID";
		$pageInfo = mysqli_query($conn, $pageInfoQuery) or die(mysqli_error($conn));
		$info = mysqli_fetch_array($pageInfo, MYSQL_ASSOC);
		$PageOwnersName = $info['PageName'];
		//PageName, Gender, Religion, Relationship, Birthday, City, Job, User_ID, NumFriends
    	echo '<div class="panel"><h3>'.$info['PageName'].'</h3></br>
    		<div class="row">
    		<form method="POST" action="" style="margin:0px;">
    				<div class="row">
    				<div class="large-6 columns">
    				<p>Brithday: '.$info['Birthday'].'</p>
    				<label>City: <input name="City" type="text" value="'.$info['City'].'"></label>
    				 <label>Job: <input name="Job" type="text" value="'.$info['Job'].'"></label>
    				 </div><div class="large-6 columns">
    					<p>Gender: '.$info['Gender'].'</p>
    					<label>Religion: <input name="Religion" type="text" value="'.$info['Religion'].'"></label>
    				 <label>Relationship: <input name="Relationship" type="text" value="'.$info['Relationship'].'"></label>
    				</div>
    			<input style="margin:0px; margin-left:25px;" class="button left" type="submit" value="Update" title="Update" ><a class="button right" style="margin:0px; margin-right:25px;" href="#" data-reveal-id="postModal" data-reveal>Create Post</a>
    		</form></div></div>
      		</div>';
			mysqli_free_result($pageInfo);
		}
    	?>
    	
    <!--//Populate wall posts-->
	<?php require_once 'post.php'; ?>
      
      
     <?php
     
     $feedQuery = "SELECT Post_ID, Post_ID AS Posted, PageName, Text, Time_of, (SELECT COUNT(Post_ID) FROM user_likes_post WHERE Post_ID = Post.Post_ID) AS Likes, 
     			(SELECT COUNT(Post_ID) FROM Note WHERE Post_ID = Post.Post_ID) AS Comments, (SELECT PageName FROM User_Page WHERE User_ID = (SELECT Which_Users_Page FROM Post WHERE Post_ID = Posted)) AS TargetUser, 
     			Posting_User AS Posting_User_ID, (SELECT Which_Users_Page FROM Post WHERE Post_ID = Posted) AS Target_ID, Security
                FROM Post, User_Page WHERE Which_Users_Page = $pageOwnerID AND Posting_User = User_ID AND 
               ((($pageOwnerID IN (SELECT User1 FROM User_Follows_User WHERE User2 = $pageOwnerID)) AND (Security IN (SELECT Security FROM Post WHERE Security < 2 AND Post_ID = Post_ID))) OR 
               (Security IN (SELECT Security FROM Post WHERE Security = 0 AND Post_ID = Post_ID)) OR ($user_id = Which_Users_Page))
                ORDER BY Time_of DESC LIMIT 30";
     $feed = mysqli_query($conn, $feedQuery) or die(mysqli_error($conn));
     //this query's columns are named: Post_ID, Posted (Same as Post_ID), PageName, Text, Time_of, Likes, Comments, TargetUser, Posting_User_ID, Target_ID, Security
     while ($row = mysqli_fetch_array($feed, MYSQL_ASSOC)) {
     	$commentQuery = "SELECT User_Page.PageName AS Poster, User_Page.User_ID AS PosterID, Note.Text AS Text, Note.Note_ID
						FROM User_Page, Note
						WHERE Note.Post_ID = ".$row['Post_ID']." 
						AND Note.Text IS NOT NULL 
						AND Note.User_ID = User_Page.User_ID
						ORDER BY Note.time_of ASC
						";
     	$comments = mysqli_query($conn, $commentQuery) or die(mysqli_error($conn));
      	echo '<!-- Feed Entry -->
      	<div class="row">
      	<div class="large-2 columns small-3"><img src="/img/80x80.jpg" /></div>
      	<div class="large-10 columns"><small class="right">'. $row['Time_of'].'</small>
          <p><strong><a href="/page.php?ID='.$row['Posting_User_ID'].'">' .$row['PageName'].'</a>'; if(strcmp($row['PageName'], $row['TargetUser']) != 0){echo ' &#187; <a href="/page.php?ID='.$row['Target_ID'].
          '">'
          . $row['TargetUser']."</a>";} echo': </strong></br>' .$row['Text']. ' </p>
          <ul class="inline-list">';
          $likeOrNot = mysqli_query($conn, "SELECT * FROM user_likes_post WHERE User_ID = '$user_id' AND Post_ID ='". $row['Post_ID'] ."';")or die(mysqli_error($conn)); 
		
			if(!(mysqli_num_rows($likeOrNot) == 1)){//Does not currently like the subject
				echo '<li><a href="#" onclick="likeFunction(' .$row['Post_ID']. ')" class="likeLink">Like</a></li>';
			}
			else {//currently likes the subject
				echo '<li><a href="#" onclick="unlikeFunction(' .$row['Post_ID']. ')" class="unlikeLink">Unlike</a></li>';
			}
            
            mysqli_free_result($likeOrNot);
			
            echo '<li><a href="*' .$row['Post_ID']. '" data-reveal-id="replyModal" data-reveal class="commentLink">Reply</a></li>
          </ul>
          <ul class="inline-list">
          ';
		  //Some logic for displying who liked this stuff!
			$whoLikeQuery = "SELECT PageName FROM user_likes_post, User_Page WHERE (User_Page.User_ID = user_likes_post.User_ID AND user_likes_post.Post_ID = ".$row['Post_ID'].")";
     		$whoLike = mysqli_query($conn, $whoLikeQuery) or die(mysqli_error($conn));
			echo '<li><span data-tooltip class="has-tip" title="';
			$tuple = mysqli_fetch_array($whoLike, MYSQL_NUM);
			if(!(empty($tuple[0]))){
			echo $tuple[0];
			while($tuple = mysqli_fetch_array($whoLike, MYSQL_NUM)){
			  echo '</br>'.$tuple[0];
		  	}}
		  	mysqli_free_result($whoLike);
		  	echo '">' .$row['Likes']. ' Likes</span></li>';
			
		  
		  
		  echo' <li>' .$row['Comments']. ' Comments</li>';
		  //categories
		  	$subcQuery = "SELECT SName AS SubCategory
							FROM post_about_subCat
							WHERE Post_ID =".$row['Post_ID']."
							AND SNAME IS NOT NULL";
     		$subc = mysqli_query($conn, $subcQuery) or die(mysqli_error($conn));
			echo '<li><span data-tooltip class="has-tip" title="';
			$tuple = mysqli_fetch_array($subc, MYSQL_NUM);
			if(!(empty($tuple[0]))){
			echo $tuple[0];
			while($tuple = mysqli_fetch_array($subc, MYSQL_NUM)){
			  echo '</br>'.$tuple[0];
		  	}}
			$num = mysqli_num_rows($subc);
		  	echo '">' .$num. ' Categories</span></li>';
		  	mysqli_free_result($subc);
			//endcategories
          echo'</ul>
          <!-- Start Comments -->';
		  
          	while ($tuple = mysqli_fetch_array($comments, MYSQL_ASSOC)) {
          		$noteText = $tuple['Text'];
          		if(!(empty($noteText))){
          			echo '<div class="row">
            			<div class="large-2 columns small-3"><img src="/img/50x50.jpg" /></div>
            			<div class="large-10 columns"><p> <strong><a href="/page.php?ID='. $tuple['PosterID'] .'">'.$tuple['Poster'].'</a>:</strong></br>'.$noteText.' </p></div>
          				</div>';
		  		}
		  }
		  mysqli_free_result($comments);
         echo '<!-- End Comments -->
        </div>
      </div>
      <!-- End Feed Entry -->
		<hr />
      	';
         
     }
     mysqli_free_result($feed);     
     ?>
      
    

  </div>


  <!-- Footer -->
	<?php require_once 'footer.php'; ?>
	
	<!-- Start Modals -->
	<div id="postModal" class="reveal-modal" data-reveal>
  		<h3>Time to post on <?php if(strcmp($user_id, $pageOwnerID) == 0){ echo 'your'; } else{ echo $PageOwnersName . "'s";} ?> wall:</h3>
  		<p class="lead">Please enter your post below:</p>
  		<form name="postForm" action=""  method="POST">
  			<?php echo '<input type="hidden" value="'.$user_id.'" name="posterID" />'; ?>
  			<?php echo '<input type="hidden" value="'.$pageOwnerID.'" name="postedOnID" />'; ?>
  			<textarea name="content"></textarea><br />
  			<div style="width:100px; float:right">
  				<label>Security</label>
        			<select name="security">
          				<option value="0">Public</option>
          				<option value="1">Friends</option>
          				<?php if(strcmp($user_id, $pageOwnerID) == 0){ echo '<option value="2">Only Me</option>'; }?>
        			</select>
      		</div>
      		<div style="width: 500px">
      			<input style="display: none;" type="text" id="live" name="cat" />
      			<label>Category</label>
      			<select id="cat" name="category">
      				<?php require_once 'suggest_cat.php'; ?>
      			</select>
      		</div>
  			<input style="margin:0px;" class="button" type="submit" value="Post" title="Post" />
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
	<div id="replyModal" class="reveal-modal" data-reveal>
  		<h3>Time to comment:</h3>
  		<p class="lead">Please enter your comment below:</p>
  		<form name="commentForm" action=""  method="POST">
  			<?php echo '<input type="hidden" value="'.$user_id.'" name="commenterID" />'; ?>
  			<input id="postID" type="hidden" value="#" name="postID" />
  			<textarea name="body"></textarea>
  			<input style="margin:0px;" class="button" type="submit" value="Comment" title="Comment" />
  		</form>
  		<a class="close-reveal-modal">&#215;</a>
	</div>
	<div id="messageModal" class="reveal-modal" data-reveal>
  		<h3>Time to send a message:</h3>
  		<p class="lead">Please enter your message to <?php echo $PageOwnersName; ?> below:</p>
  		<form name="commentForm" action=""  method="POST">
  			<?php echo '<input type="hidden" value="'.$user_id.'" name="messageSenderID" />'; ?>
  			<?php echo '<input type="hidden" value="'.$pageOwnerID.'" name="messageRecieverID" />'; ?>
  			<textarea name="text"></textarea>
  			<input style="margin:0px;" class="button" type="submit" value="Send" title="Send" />
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
        $("select#cat").zmultiselect({
            live: "#live",
            //placeholder: "",
            filter: true,
            //filterPlaceholder: 'MyFilter...',
            filterResult: true,
            filterResultText: "Showed"        
        });
    </script>
    <script>
    	function likeFunction(postID)
		{
      		var str1 ='<form action="" method="POST"> <?php echo ' <input type="hidden" value="'.$user_id.'" name="likerID" /> '; ?> <input type="hidden" value= "';
      		var str2 ='"  name="likedPostID" /> </form>';
      		var result = str1 + postID + str2;
    		$( result ).appendTo('body').submit();
		}
		function unlikeFunction(postID)
		{
      		var str1 ='<form action="" method="POST"> <?php echo ' <input type="hidden" value="'.$user_id.'" name="unlikerID" /> '; ?> <input type="hidden" value= "';
      		var str2 ='"  name="unlikedPostID" /> </form>';
      		var result = str1 + postID + str2;
    		$( result ).appendTo('body').submit();
		}
		function unfollowfunction(requester, reciever)
		{
			console.log("unfollowing =>"+requester+ "is unfollowing "+reciever);
      		var str1 ='<form action="" method="POST"> <input type="hidden" value= "';
      		var str2 ='"  name="unfollowReciever" /> <input type="hidden" value= "';
      		var str3 = '" name="unfollowRequester" /> </form>';
      		var result = str1 + reciever + str2 + requester + str3;
    		$( result ).appendTo('body').submit();
		}
		function followfunction(requester, reciever)
		{
			console.log("following =>"+requester+ "is following "+reciever);
      		var str1 ='<form action="" method="POST"> <input type="hidden" value= "';
      		var str2 ='"  name="followReciever" /> <input type="hidden" value= "';
      		var str3 = '" name="followRequester" /> </form>';
      		var result = str1 + reciever + str2 + requester + str3;
    		$( result ).appendTo('body').submit();
		}
    </script>
    <script>
      $(document).foundation();

      var doc = document.documentElement;
      doc.setAttribute('data-useragent', navigator.userAgent);
      $('a.commentLink').click(function(){
      	var postID = this.href.split('*')[1];
    	document.getElementById("postID").value = postID;
		});		
    </script>
    
    
  </body>
</html>
<?php
mysqli_close($conn);
?>