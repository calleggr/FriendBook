CREATE PROCEDURE sendMessage(IN sendID int, IN recID int, IN text varchar(140))
BEGIN
	DECLARE result varchar(255);
	DECLARE temp1 INT;
	DECLARE temp2 INT;
	DECLARE timeCheck INT;
	
	IF ((SELECT Time_Sent
		FROM Message
		Where Sender_ID = sendID
		ORDER BY Time_Sent desc
		LIMIT 1) > (SELECT Date_Sub(NOW(), INTERVAL 5 SECOND))) THEN
		SET timeCheck = 0;
	END IF;
	
	SELECT User_ID
		FROM User_Page
		Where User_ID = sendID
		INTO temp1;

	SELECT User_ID
		FROM User_Page
		Where User_ID = recID
		INTO temp2;
		
	IF (temp1 IS NULL OR temp2 IS NULL OR timeCheck = 0)THEN
		SET result = 'One of the users does not exist';
	ELSE
		INSERT
			INTO Messages(Content, Sender_ID, Receiver_ID, unread)
			VALUES (text, sendID, recID, 1);
		SET result = 'Message Sent';
	END IF;
	
END