CREATE PROCEDURE Request_Accepted(IN reqID INT, IN recID INT)
BEGIN
	DECLARE tempRec INT;
	DECLARE tempReq INT;
	DECLARE nullcheck INT;
	
	SELECT Receiver_ID FROM temp_request WHERE Requester_ID = reqID AND Receiver_ID = recID INTO nullcheck;
	IF(nullcheck IS NOT NULL) THEN
		DELETE FROM temp_request WHERE Requester_ID = reqID AND Receiver_ID = recID;
	END IF;
	
	INSERT
		INTO User_Follows_User(User1, User2)
		VALUES (reqID, recID);
	
	INSERT
		INTO User_Follows_User(User1, User2)
		VALUES (recID, reqID);
END