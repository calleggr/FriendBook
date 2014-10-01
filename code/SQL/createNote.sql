BEGIN
	DECLARE temp_id INT;
	DECLARE temp_post_id INT;
	DECLARE temp_user_id INT;
	DECLARE timeCheck INT;
	DECLARE result varchar(255);
	DECLARE temp_sec INT;

	
	SELECT MAX(Note_ID)
		FROM Note
		INTO temp_id;
		
	IF temp_id IS NULL THEN
		SET temp_id = 0;
	ELSE
		SET temp_id = temp_id + 1;
	END IF;
	
	IF ((SELECT Time_of
		FROM Note
		Where User_ID = inUser_ID
		ORDER BY Time_of desc
		LIMIT 1) > (SELECT Date_Sub(NOW(), INTERVAL 5 SECOND))) THEN
		SET timeCheck = 0;
	END IF;
	
	
	SELECT User_ID
		FROM User_Page
		Where User_ID = inUser_ID
		INTO temp_user_id;
	
	SELECT Post_ID
		FROM Post
		WHERE Post_ID = inPost_ID
		INTO temp_post_ID;
		
	SELECT Security
		FROM Post
		WHERE Post_ID = inPost_ID
		INTO temp_sec;
		
	IF (temp_post_ID IS NULL OR temp_user_ID IS NULL OR timeCheck = 0)THEN
		SET result = 'Oops something went amiss';
	ELSE
		INSERT
			INTO Note(Note_ID, User_ID, Text, Post_ID, Security)
			VALUES (temp_id, inUser_ID, inBody, inPost_ID, temp_sec);
		SET result = 'Post Created';
	END IF;

END