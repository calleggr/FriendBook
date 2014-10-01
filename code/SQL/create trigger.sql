BEGIN
DECLARE tempUID int;
DECLARE tempCNAME varchar(20);
DECLARE tempLikes int;
DECLARE NullCheck int;
SET tempUID = NEW.User_ID;
SET tempCNAME = NEW.CName;
--SELECT User_ID FROM User_Liking_Cat WHERE tempUID = User_ID AND tempCName = CNAME INTO NullCheck;
IF (OLD.User_ID IS NOT NULL)THEN
	--SELECT NumLikes FROM User_Liking_Cat WHERE tempUID = User_ID AND tempCName = CNAME  INTO tempLikes;
	SET tempLikes = OLD.NumLikes;
	IF(tempLikes < 3)THEN
		UPDATE User_Liking_Cat
			SET NumLikes = (tempLikes+1)
			WHERE tempUID = User_ID AND tempCName = CNAME;
	END IF;
	IF(tempLikes = 3)THEN
		INSERT
			INTO user_likes_cat(User_ID, CName)
			VALUES(tempUID, tempCNAME);
	END IF;
ELSE
	INSERT
		INTO User_Liking_Cat(User_ID, CName, NumLikes)
		VALUES(tempUID, tempCNAME, 1);
END IF;
END