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
			?>
		</a></h5>
          <div class="section-container vertical-nav" data-section data-options="deep_linking: false; one_up: true">
          <section class="section">
          	<h5 class="title"><a href="#" data-reveal-id="postModal" data-reveal>Create Post</a></h5>
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
    
    <!-- Main Feed -->
    <!-- This has been source ordered to come first in the markup (and on small devices) but to be to the right of the nav on larger screens -->
    <div class="large-7 columns">
	<?php require_once 'post.php'; ?>
      
      
     <?php
     
     $feedQuery = "	SELECT Post_ID, Post_ID AS Posted, PageName, Text, Time_of, (SELECT COUNT(Post_ID) FROM user_likes_post WHERE Post_ID = Post.Post_ID) AS Likes, 
					(SELECT COUNT(Post_ID) FROM Note WHERE Post_ID = Post.Post_ID) AS Comments, ((1 + (SELECT COUNT(SName) FROM post_about_subCat WHERE Post_ID = Post.Post_ID AND 
					SName IN (SELECT SName FROM Sub_Of_Cat WHERE CName IN (SELECT CName FROM user_likes_cat WHERE User_ID = $user_id)))) / (CURRENT_TIMESTAMP - Time_of)*500) AS Relevance, 
					Time_of AS Post_Age, (SELECT COUNT(SName) FROM post_about_subCat WHERE Post_ID = Post.Post_ID AND SName IN (SELECT SName FROM Sub_Of_Cat WHERE CName IN (SELECT CName FROM user_likes_cat 
					WHERE User_ID = $user_id))) AS About_Categories, (SELECT PageName FROM User_Page WHERE User_ID = (SELECT Which_Users_Page FROM Post WHERE Post_ID = Posted)) AS TargetUser, Posting_User 
					AS Posting_User_ID, (SELECT Which_Users_Page FROM Post WHERE Post_ID = Posted) AS Target_ID
                	FROM Post, User_Page
                	WHERE Posting_User IN (SELECT User2 FROM User_Follows_User WHERE User1 = $user_id) 
                    AND User_ID = Posting_User AND Security IN (SELECT Security FROM Post WHERE Security < 2 AND Post_ID = Post_ID)
                	ORDER BY Relevance DESC LIMIT 30";
     $feed = mysqli_query($conn, $feedQuery) or die(mysqli_error($conn));
     //this query's columns are named: Post_ID, Posted (Same as Post_ID), PageName, Text, Time_of, Likes, Comments, Relevance, Post_Age, About_Categories, TargetUser, Posting_User_ID, Target_ID
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
          '">'. $row['TargetUser']."</a>";} echo': </strong></br>' .$row['Text']. ' </p>
          <ul class="inline-list">';
          $likeOrNot = mysqli_query($conn, "SELECT * FROM user_likes_post WHERE User_ID = '$user_id' AND Post_ID ='". $row['Post_ID'] ."';")or die(mysql_error()); 
		
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
          echo '</ul>
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

    <!-- Right Sidebar -->
    <!-- On small devices this column is hidden -->
    <aside class="large-2 columns hide-for-small">
      <p><img src="/img/window.jpg" /></p>
      <p><img src="/img/spark.jpg" /></p>
      <p><img src="/img/captain-america-cover.jpg" /></p>
    </aside>

  </div>


  <!-- Footer -->
	<?php require_once 'footer.php'; ?>
	
	<!-- Start Modals -->
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
	
	<div id="postModal" class="reveal-modal" data-reveal>
  		<h3>Time to post on your wall:</h3>
  		<p class="lead">Please enter your post below:</p>
  		<form name="postForm" action=""  method="POST">
  			<?php echo '<input type="hidden" value="'.$user_id.'" name="posterID" />'; ?>
  			<?php echo '<input type="hidden" value="'.$user_id.'" name="postedOnID" />'; ?>
  			<textarea name="content"></textarea><br />
  			<div style="width:100px; float:right">
  				<label>Security</label>
        			<select name="security">
          				<option value="0">Public</option>
          				<option value="1">Friends</option>
          				<option value="2">Only Me</option>
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
	<!-- End Modals -->
	<script src="/js/vendor/jquery.js"></script>
	<script src="/js/foundation/foundation.js"></script>
    <script src="/js/vendor/fastclick.js"></script>
    <script src="/js/foundation/foundation.topbar.js"></script>
    <script src="/js/foundation/foundation.reveal.js"></script>
    <script src="/js/foundation/foundation.alert.js"></script>
    <script src="js/foundation/foundation.tooltip.js"></script>
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