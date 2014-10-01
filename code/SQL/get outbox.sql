SELECT Receiver_ID, Time_Sent
	FROM Message
	WHERE Sender_ID = <temp>
	ORDER BY Message.Time_Sent DESC