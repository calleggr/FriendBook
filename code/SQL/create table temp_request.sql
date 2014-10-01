CREATE TABLE temp_request
(
	Requester_ID int,
	Receiver_ID int,
	PRIMARY KEY (Requester_ID, Receiver_ID),
	FOREIGN KEY (Requester_ID) REFERENCES User_Page(User_ID),
	FOREIGN KEY (Receiver_ID) REFERENCES User_Page(User_ID)
);