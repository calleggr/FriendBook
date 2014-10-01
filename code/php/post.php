
<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    	
		if(isset($_POST['content'])){//Post
			$posterID = $_POST['posterID'];//Who is posting the post
			$posteeID = $_POST['postedOnID'];//Who's page the post is on
			$content = $_POST['content'];
			$security = $_POST['security'];
			$content = mysqli_real_escape_string($conn, $content);
 			$content = htmlspecialchars($content, ENT_QUOTES);
			$cat = $_POST['cat'];
			if(!empty($content)){
				if(!empty($cat)){
					$cat_array = explode(',', $cat, 10);
					if(count($cat_array)==1){
						mysqli_query($conn, "CALL createPostAboutCat(".$posterID. "," .$posteeID. ",'" .$content. "'," . $security . ", '" .$cat_array[0]. "', '' , '', '', '')");
	
					}
					elseif (count($cat_array)==2) {
						mysqli_query($conn, "CALL createPostAboutCat(".$posterID. "," .$posteeID. ",'" .$content. "'," . $security . ", '" .$cat_array[0]. "', '" .$cat_array[1]. "', '', '', '')");
					}
					
					elseif (count($cat_array)==3) {
						mysqli_query($conn, "CALL createPostAboutCat(".$posterID. "," .$posteeID. ",'" .$content. "'," . $security . ", '" .$cat_array[0]. "', '" .$cat_array[1]. "', '" .$cat_array[2]. "', '', '')");
						
					}
					elseif (count($cat_array)==4) {
						mysqli_query($conn, "CALL createPostAboutCat(".$posterID. "," .$posteeID. ",'" .$content. "'," . $security . ", '" .$cat_array[0]. "', '" .$cat_array[1]. "', '" .$cat_array[2]. "', '" .$cat_array[3]. "', '')");
					}
					else {
						mysqli_query($conn, "CALL createPostAboutCat(".$posterID. "," .$posteeID. ",'" .$content. "'," . $security . ", '" .$cat_array[0]. "', '" .$cat_array[1]. "', '" .$cat_array[2]. "', '" .$cat_array[3]. "', '" .$cat_array[4]. "')");						
					}
					
				}
				else {
					mysqli_query($conn, "CALL createPostAboutCat(".$posterID. "," .$posteeID. ",'" .$content. "'," . $security . ", '', '' , '', '', '')");
				}
				
				
    			echo '<div data-alert data-options="animation_speed:500;" style="margin-bottom:25px" class=" row alert-box success round"> Post successful! <a href="#" class="close">&times;</a></div>';
				
				
			}
			else {
				echo '<div data-alert data-options="animation_speed:500;" style="margin-bottom:25px" class=" row alert-box warning round"> Post error! You must post something! <a href="#" class="close">&times;</a></div>';
			}	
		}
		
		else if(isset($_POST['body'])){//Comment
			$commenterID = $_POST['commenterID'];
			$postID = $_POST['postID'];
			$body = $_POST['body'];
			$body = mysqli_real_escape_string($conn, $body);
 			$body = htmlspecialchars($body, ENT_QUOTES);
			if(!empty($body)){
				mysqli_query($conn, "CALL createNote(" . $postID . "," . $commenterID . ",'" . $body . "')") or die(mysqli_error($conn));
				echo '<div data-alert data-options="animation_speed:500;" style="margin-bottom:25px" class=" row alert-box success round"> Comment successful! <a href="#" class="close">&times;</a></div>';
			}
			else{
				echo '<div data-alert data-options="animation_speed:500;" style="margin-bottom:25px" class=" row alert-box warning round"> Comment error! You must comment something! <a href="#" class="close">&times;</a></div>';
			}
		}

		else if(isset($_POST['likerID'])){//Like !should be last!
			$likerID = $_POST['likerID'];
			$likedPostID = $_POST['likedPostID'];
			$query = "INSERT INTO user_likes_post (`User_ID`, `Post_ID`) VALUES ('".$likerID."', '".$likedPostID."');";
			mysqli_query($conn, $query) or die(mysqli_error($conn));
			echo '<div data-alert data-options="animation_speed:500;" style="margin-bottom:25px" class=" row alert-box success round"> Like successful! <a href="#" class="close">&times;</a></div>';
		}
		
		else if(isset($_POST['unlikerID'])){//unLike !should be last!
			$unlikerID = $_POST['unlikerID'];
			$unlikedPostID = $_POST['unlikedPostID'];
			$query = "DELETE FROM user_likes_post WHERE User_ID = ".$unlikerID." AND Post_ID = ".$unlikedPostID.";";
			mysqli_query($conn, $query) or die(mysqli_error($conn));
			echo '<div data-alert data-options="animation_speed:500;" style="margin-bottom:25px" class=" row alert-box success round"> Unlike successful! <a href="#" class="close">&times;</a></div>';
		}
		
    }
?>