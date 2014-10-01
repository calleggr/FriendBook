
CREATE TABLE User_Page
(
	Relationship varchar(20),
	Religion varchar(20),
	Gender varchar(1),
	City varchar(20),
	Birthday varchar(20),
	Job varchar(20),
	PageName varchar(20),
	User_ID int PRIMARY KEY,
	Email varchar(20) NOT NULL,
	FirstName varchar(10) NOT NULL,
	LastName varchar(10) NOT NULL,
	Password varchar(10) NOT NULL
);


CREATE TABLE Post
(
	Post_ID int PRIMARY KEY,
	Text varchar(140),
	Time_of TimeStamp,
	Security int,
	Posting_User int,
	Which_Users_Page int,
	FOREIGN KEY (Posting_User) REFERENCES User_Page(User_ID),
	FOREIGN KEY (Which_Users_Page) REFERENCES User_Page(User_ID)
);

CREATE TABLE Note
(
	Note_ID int PRIMARY KEY,
	Security int,
	Text varchar(140),
	Post_ID int,
	FOREIGN KEY(Post_ID) REFERENCES Post(Post_ID)
);

CREATE TABLE Category
(
	CName varchar(20) PRIMARY KEY
);

CREATE TABLE SubCategory
(
		SName varchar(20) PRIMARY KEY
);

CREATE TABLE Sub_Of_Cat
(
	SName varchar(20),
	CName varchar(20),
	
	PRIMARY KEY (SName, CName),

	FOREIGN KEY (SName) REFERENCES SubCategory(SName),
	FOREIGN KEY (CName) REFERENCES Category(CName)
);

CREATE TABLE Message
(
	Time_Sent TimeStamp,
	Content varchar(140),
	Page_ID int,
	to_or_from boolean,
	PRIMARY KEY(Time_Sent, Page_ID),
	
	FOREIGN KEY (Page_ID) REFERENCES User_Page(User_ID)
);

CREATE TABLE User_Friends_With_User
(
	User1 int,
	User2 int,
	PRIMARY KEY (User1, User2),
	FOREIGN KEY (User1) REFERENCES User_Page(User_ID),
	FOREIGN KEY (User2) REFERENCES User_Page(User_ID)
);

CREATE TABLE User_Follows_User
(
	User1 int,
	User2 int,
	PRIMARY KEY (User1, User2),
	FOREIGN KEY (User1) REFERENCES User_Page(User_ID),
	FOREIGN KEY (User2) REFERENCES User_Page(User_ID)
);

CREATE TABLE user_likes_cat
(
	User_ID int,
	CName varchar(20),
	PRIMARY KEY(User_ID, CName),
	FOREIGN KEY (User_ID) REFERENCES User_Page(User_ID),
	FOREIGN KEY (CName) REFERENCES Category(CName)
);

CREATE TABLE post_about_subCat
(
	Post_ID int,
	SName varchar(20),
	PRIMARY KEY(Post_ID, SName),
	FOREIGN KEY (Post_ID) REFERENCES Post(Post_ID),
	FOREIGN KEY (SName) REFERENCES SubCategory(SName)
);

CREATE TABLE user_likes_note
(
	User_ID int,
	Note_ID int,
	PRIMARY KEY (User_ID, Note_ID),
	
	FOREIGN KEY (User_ID) REFERENCES User_Page(User_ID),
	FOREIGN KEY (Note_ID) REFERENCES Note(Note_ID)
);

CREATE TABLE user_likes_post
(
	User_ID int,
	Post_ID int,
	PRIMARY KEY (User_ID, Post_ID),
	
	FOREIGN KEY (User_ID) REFERENCES User_Page(User_ID),
	FOREIGN KEY (Post_ID) REFERENCES Post(Post_ID)
);
	