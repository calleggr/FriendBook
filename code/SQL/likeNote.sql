CREATE PROCEDURE userLikesNote(IN inUser int, IN noteID int)

BEGIN
	DECLARE result varchar(255);
	DECLARE temp1 INT;
	DECLARE temp2 INT;
	DECLARE doubleCheck int;

	SET doubleCheck = -1;
	
	SELECT User_ID
		FROM User_Page
		Where User_ID = inUser
		INTO temp1;

	SELECT Note_ID
		FROM Note
		Where Note_ID = noteID
		INTO temp2;
		
	SELECT Note_ID
		FROM user_likes_note
		Where inUser = User_ID AND noteID = Note_ID
		INTO doubleCheck; 
			
	IF (temp1 IS NULL OR temp2 IS NULL OR doubleCheck>0)THEN
		SET result = 'OOPS';
	ELSE
		INSERT
			INTO user_likes_note(User_ID, NOTE_ID)
			VALUES (inUser, noteID);
		SET result = 'YOU LIKE IT';
	END IF;

	SELECT result;
END