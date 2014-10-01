BEGIN
DECLARE tempUID int;
DECLARE tempPID int;
DECLARE tempLikes int;
DECLARE	tempSub varchar(20);
DECLARE tempCat varchar(20);
DECLARE tempSize int;
DECLARE i int;

DECLARE NullCheck int;


SET tempUID = NEW.User_ID;
SET tempPID = NEW.Post_ID;

CREATE TEMPORARY TABLE TempSubs(Post_ID int, SName  varchar(20));
INSERT INTO TempSubs SELECT Post_ID, SName FROM post_about_subCat WHERE Post_ID = NEW.Post_ID;

SELECT COUNT(*) FROM TempSubs INTO tempSize;
SET i=0;
WHILE(i<tempSize) DO

	SELECT SName FROM TempSubs ORDER BY SName LIMIT 1 OFFSET i INTO tempSub;
	SELECT CName FROM Sub_Of_Cat where SName = tempSub INTO tempCat;
	SELECT NumLikes FROM User_Liking_Cat WHERE User_ID = tempUID AND CName = tempCat INTO tempLikes;
	IF(tempLikes IS NULL) THEN

		INSERT
			INTO User_Liking_Cat(User_ID, CName, NumLikes)
			VALUES (tempUID, tempCat, 1);
	
	
	ELSEIF(tempLikes >= 1 AND tempLikes < 3) THEN

		UPDATE User_Liking_Cat
			SET NumLikes = tempLikes + 1
			WHERE tempUID = User_ID AND tempCat = CName;
	END IF;
	
	IF(tempLikes = 3) THEN
		SELECT User_ID FROM user_likes_cat WHERE User_ID = tempUID AND CName = tempCat INTO NullCheck;
		IF(NullCheck IS NULL) THEN
			INSERT
				INTO user_likes_cat(User_ID, CName)
				VALUES (tempUID, tempCat);
		END IF;
	END IF;
	SET i = i+1;
	
END WHILE;
END