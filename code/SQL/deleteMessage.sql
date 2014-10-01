CREATE PROCEDURE deleteMessage(IN sendID int, IN inTime TimeStamp)
BEGIN

	DELETE FROM Message
		Where Sender_ID = sendID AND Time_Sent = inTime
END