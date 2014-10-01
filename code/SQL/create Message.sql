CREATE TABLE Message
(
	Time_Sent TimeStamp,
	Content varchar(140),
	Sender_ID int,
	Receiver_ID int, 
	unread int,
	PRIMARY KEY(Time_Sent, Sender_ID),
	
	FOREIGN KEY (Sender_ID) REFERENCES User_Page(User_ID),
	FOREIGN KEY (Receiver_ID) REFERENCES User_Page(User_ID)
);