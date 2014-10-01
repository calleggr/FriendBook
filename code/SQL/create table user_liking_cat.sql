CREATE TABLE User_Liking_Cat
(
	User_ID int,
	CName varchar(20),
	NumLikes int,
	PRIMARY KEY (User_ID, CName),
	FOREIGN KEY (User_ID) REFERENCES User_Page(User_ID),
	FOREIGN KEY (CName) REFERENCES Category(CName)
);
