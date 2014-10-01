BEGIN

	DECLARE temp_post_ID INT;
	DECLARE result varchar(255);
	DECLARE temp1 INT;
	DECLARE temp2 INT;
	DECLARE timeCheck INT;

	

	SELECT MAX(Post_ID)
		FROM Post
		INTO temp_post_ID;

	IF temp_post_ID IS NULL THEN
		SET temp_post_ID = 0;
	ELSE
		SET temp_post_ID = temp_post_ID + 1;
	END IF;
	
	IF ((SELECT Time_of
		FROM Post
		Where Posting_User = inPoster_ID
		ORDER BY Time_of desc
		LIMIT 1) > (SELECT Date_Sub(NOW(), INTERVAL 5 SECOND))) THEN
		SET timeCheck = 0;
	END IF;
	
	SELECT User_ID
		FROM User_Page
		Where User_ID = inPoster_ID
		INTO temp1;

	SELECT User_ID
		FROM User_Page
		Where User_ID = inWhich_Users_Page
		INTO temp2;

	IF (temp1 IS NULL OR temp2 IS NULL OR timeCheck = 0)THEN
		SET result = 'One of the users does not exist';
	ELSE
		INSERT
			INTO Post(Posting_User, Post_ID, Security, Text, Which_Users_Page)
			VALUES (inPoster_ID, temp_post_ID, inSecurity, inText, inWhich_Users_Page);
		SET result = 'Post Created';
	END IF;

	SELECT temp_post_ID;
END