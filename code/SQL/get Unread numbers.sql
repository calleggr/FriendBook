SELECT COUNT(unread) as Unread
	FROM Message
	WHERE Message.Receiver_ID = <temp>