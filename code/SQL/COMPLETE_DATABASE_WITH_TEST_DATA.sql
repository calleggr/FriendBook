-- phpMyAdmin SQL Dump
-- version 4.0.6deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 21, 2014 at 06:15 PM
-- Server version: 5.5.35-0ubuntu0.13.10.2
-- PHP Version: 5.5.3-1ubuntu2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `fb`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `createNote`(IN `inPost_ID` INT, IN `inUser_ID` INT, IN `inBody` VARCHAR(140))
BEGIN
	DECLARE temp_id INT;
	DECLARE temp_post_id INT;
	DECLARE temp_user_id INT;
	DECLARE timeCheck INT;
	DECLARE result varchar(255);
	DECLARE temp_sec INT;

	
	SELECT MAX(Note_ID)
		FROM Note
		INTO temp_id;
		
	IF temp_id IS NULL THEN
		SET temp_id = 0;
	ELSE
		SET temp_id = temp_id + 1;
	END IF;
	
	IF ((SELECT Time_of
		FROM Note
		Where User_ID = inUser_ID
		ORDER BY Time_of desc
		LIMIT 1) > (SELECT Date_Sub(NOW(), INTERVAL 5 SECOND))) THEN
		SET timeCheck = 0;
	END IF;
	
	
	SELECT User_ID
		FROM User_Page
		Where User_ID = inUser_ID
		INTO temp_user_id;
	
	SELECT Post_ID
		FROM Post
		WHERE Post_ID = inPost_ID
		INTO temp_post_ID;
		
	IF (temp_post_ID IS NULL OR temp_user_ID IS NULL OR timeCheck = 0)THEN
		SET result = 'Oops something went amiss';
	ELSE
		INSERT
			INTO Note(Note_ID, User_ID, Text, Post_ID)
			VALUES (temp_id, inUser_ID, inBody, inPost_ID);
		SET result = 'Post Created';
	END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `createPost`(IN `inPoster_ID` INT, IN `inWhich_Users_Page` INT, IN `inText` VARCHAR(140), IN `inSecurity` INT)
BEGIN

	DECLARE temp_post_ID INT;
	DECLARE result varchar(255);
	DECLARE temp1 INT;
	DECLARE temp2 INT;
	DECLARE timeCheck INT;

	

	SELECT MAX(Post_ID)
		FROM Post
		INTO temp_post_ID;

	IF temp_post_ID IS NULL THEN
		SET temp_post_ID = 0;
	ELSE
		SET temp_post_ID = temp_post_ID + 1;
	END IF;
	
	IF ((SELECT Time_of
		FROM Post
		Where Posting_User = inPoster_ID
		ORDER BY Time_of desc
		LIMIT 1) > (SELECT Date_Sub(NOW(), INTERVAL 5 SECOND))) THEN
		SET timeCheck = 0;
	END IF;
	
	SELECT User_ID
		FROM User_Page
		Where User_ID = inPoster_ID
		INTO temp1;

	SELECT User_ID
		FROM User_Page
		Where User_ID = inWhich_Users_Page
		INTO temp2;

	IF (temp1 IS NULL OR temp2 IS NULL OR timeCheck = 0)THEN
		SET result = 'One of the users does not exist';
	ELSE
		INSERT
			INTO Post(Posting_User, Post_ID, Security, Text, Which_Users_Page)
			VALUES (inPoster_ID, temp_post_ID, inSecurity, inText, inWhich_Users_Page);
		SET result = 'Post Created';
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `createPostAboutCat`(IN `inPoster_ID` INT, IN `inWhich_Users_Page` INT, IN `inText` VARCHAR(140), IN `inSecurity` INT, IN `inSub1` VARCHAR(20), IN `inSub2` VARCHAR(20), IN `inSub3` VARCHAR(20), IN `inSub4` VARCHAR(20), IN `inSub5` VARCHAR(20))
BEGIN

	DECLARE temp_post_ID INT;
	DECLARE result varchar(255);
	DECLARE temp1 INT;
	DECLARE temp2 INT;
	DECLARE timeCheck INT;

	

	SELECT MAX(Post_ID)
		FROM Post
		INTO temp_post_ID;

	IF temp_post_ID IS NULL THEN
		SET temp_post_ID = 0;
	ELSE
		SET temp_post_ID = temp_post_ID + 1;
	END IF;
	
	IF ((SELECT Time_of
		FROM Post
		Where Posting_User = inPoster_ID
		ORDER BY Time_of desc
		LIMIT 1) > (SELECT Date_Sub(NOW(), INTERVAL 5 SECOND))) THEN
		SET timeCheck = 0;
	END IF;
	
	SELECT User_ID
		FROM User_Page
		Where User_ID = inPoster_ID
		INTO temp1;

	SELECT User_ID
		FROM User_Page
		Where User_ID = inWhich_Users_Page
		INTO temp2;

	IF (temp1 IS NULL OR temp2 IS NULL OR timeCheck = 0)THEN
		SET result = 'One of the users does not exist';
	ELSE
		INSERT
			INTO Post(Posting_User, Post_ID, Security, Text, Which_Users_Page)
			VALUES (inPoster_ID, temp_post_ID, inSecurity, inText, inWhich_Users_Page);
		SET result = 'Post Created';
	END IF;
	
	
	IF (inSub1 IS NOT NULL)THEN
		INSERT
			INTO post_about_subCat(Post_ID, Sname)
			VALUES (temp_post_ID, inSub1);
	END IF;
	IF(inSub2 IS NOT NULL)THEN
		INSERT
			INTO post_about_subCat(Post_ID, Sname)
			VALUES (temp_post_ID, inSub2);
	END IF;
	IF(inSub3 IS NOT NULL)THEN
		INSERT
			INTO post_about_subCat(Post_ID, Sname)
			VALUES (temp_post_ID, inSub3);
	END IF;
	IF(inSub4 IS NOT NULL)THEN
		INSERT
			INTO post_about_subCat(Post_ID, Sname)
			VALUES (temp_post_ID, inSub4);
	END IF;
	IF(inSub5 IS NOT NULL)THEN
		INSERT
			INTO post_about_subCat(Post_ID, Sname)
			VALUES (temp_post_ID, inSub5);
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `createUser_Page`(IN `inFirstName` VARCHAR(30), IN `inLastName` VARCHAR(30), IN `inBirthday` VARCHAR(20), IN `inEmail` VARCHAR(50), IN `inPassword` VARCHAR(10), IN `inGender` VARCHAR(6))
BEGIN
	DECLARE temp_id int;
	DECLARE result varchar(255);
	DECLARE temp_page_name varchar(20);
	DECLARE temp_email varchar(50);

	SET temp_page_name = CONCAT(inFirstName, ' ', inLastName);
	
	SELECT Email
		FROM User_Page
		WHERE Email = inEmail
		INTO temp_email;
		
	

	
	SELECT MAX(User_ID)
		FROM User_Page
		INTO temp_id;
	
	IF temp_id IS NULL THEN
		SET temp_id = 0;
	ELSE
		SET temp_id = temp_id + 1;
	END IF;
	IF temp_email IS NULL THEN
		INSERT 
			INTO User_Page (User_ID, FirstName, LastName, Birthday, Email, Password, Gender, Relationship, Religion, City, Job, PageName)
			VALUES (temp_id, inFirstName, inLastName, inBirthday, inEmail, inPassword, inGender, 'Not Specified', 'Not Specified' , 'Not Specified' , 'Not Specified', temp_page_name);
		INSERT
			INTO User_Follows_User(User1, User2)
			VALUES (temp_id, temp_id);
		SET result = 'User Page Created';
	ELSE
		SET result = 'That email already has an account';
	END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteMessage`(IN sendID int, IN inTime TimeStamp)
BEGIN

	DELETE FROM Message
		Where Sender_ID = sendID AND Time_Sent = inTime;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Request_Accepted`(IN reqID INT, IN recID INT)
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sendMessage`(IN `sendID` INT, IN `recID` INT, IN `body` VARCHAR(140))
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
			INTO Message(Content, Sender_ID, Receiver_ID, unread)
			VALUES (body, sendID, recID, 1);
		SET result = 'Message Sent';
	END IF;
	
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `userLikesNote`(IN `inUser` INT, IN `noteID` INT)
BEGIN
	DECLARE result varchar(255);
	DECLARE temp1 INT;
	DECLARE temp2 INT;
	DECLARE doubleCheck int;

	SET doubleCheck = -1;
	
	SELECT User_ID
		FROM User_Page
		Where User_ID = inUser
		INTO temp1;

	SELECT Note_ID
		FROM Note
		Where Note_ID = noteID
		INTO temp2;
		
	SELECT Note_ID
		FROM user_likes_note
		Where inUser = User_ID AND noteID = Note_ID
		INTO doubleCheck; 
			
	IF (temp1 IS NULL OR temp2 IS NULL OR doubleCheck>0)THEN
		SET result = 'OOPS';
	ELSE
		INSERT
			INTO user_likes_note(User_ID, NOTE_ID)
			VALUES (inUser, noteID);
		SET result = 'YOU LIKE IT';
	END IF;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Category`
--

CREATE TABLE IF NOT EXISTS `Category` (
  `CName` varchar(20) NOT NULL,
  PRIMARY KEY (`CName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Category`
--

INSERT INTO `Category` (`CName`) VALUES
('Acting'),
('America'),
('Books'),
('Cars'),
('Celebrities'),
('Children'),
('College'),
('Dating'),
('Disney'),
('Fashion'),
('Feeling'),
('Gender'),
('Gossip'),
('Guns and Weaponry'),
('High School'),
('History'),
('Hunting'),
('Lego'),
('Marriage'),
('Math'),
('Movies'),
('Music'),
('Musicals'),
('Nature'),
('Pranks'),
('Programming'),
('Quotes'),
('Race'),
('Religion'),
('Science'),
('Scifi'),
('Social Networking'),
('Sports'),
('Star Wars'),
('Toys'),
('Travel'),
('TV Shows'),
('Videogames'),
('World'),
('YouTube');

-- --------------------------------------------------------

--
-- Table structure for table `Message`
--

CREATE TABLE IF NOT EXISTS `Message` (
  `Time_Sent` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Content` varchar(140) DEFAULT NULL,
  `Sender_ID` int(11) NOT NULL DEFAULT '0',
  `Receiver_ID` int(11) DEFAULT NULL,
  `unread` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`Time_Sent`,`Sender_ID`),
  KEY `Sender_ID` (`Sender_ID`),
  KEY `Receiver_ID` (`Receiver_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Message`
--

INSERT INTO `Message` (`Time_Sent`, `Content`, `Sender_ID`, `Receiver_ID`, `unread`) VALUES
('2014-02-18 02:11:38', 'hey dude', 2, 4, 0),
('2014-02-18 13:34:46', 'My first message!', 0, 1, 0),
('2014-02-19 15:39:19', 'My second message!', 7, 1, 0),
('2014-02-19 15:44:36', 'u smell gud', 2, 4, 0),
('2014-02-19 15:44:39', 'cmon respond dood', 2, 4, 0),
('2014-02-19 15:47:17', 'i know where u sleep, dood', 3, 5, 0),
('2014-02-19 16:13:04', 'Testing the sending of a message!', 4, 1, 0),
('2014-02-19 16:20:59', 'My First reply!', 1, 4, 0),
('2014-02-19 16:21:15', '&gt;.&lt;', 4, 1, 0),
('2014-02-20 04:41:10', 'hey guy', 4, 1, 0),
('2014-02-20 05:48:36', 'Hi my First message to Jon!', 1, 5, 0),
('2014-02-20 05:48:52', 'Haha awesome :D', 5, 1, 0),
('2014-02-20 17:58:32', 'Yo', 5, 9, 0),
('2014-02-20 17:58:45', 'i am reply', 9, 5, 0),
('2014-02-20 17:58:58', 'I am a Re: Re:', 5, 9, 1),
('2014-02-20 17:59:46', 'Yo', 5, 1, 0),
('2014-02-20 18:01:29', 'Hey man this reply thing work?', 1, 4, 0),
('2014-02-20 18:34:07', 'Sup?', 1, 5, 0),
('2014-02-20 18:52:07', 'yes', 4, 1, 0),
('2014-02-20 18:52:16', 'hi', 16, 1, 0),
('2014-02-20 18:57:14', 'Hello\r\n', 18, 1, 0),
('2014-02-20 18:57:16', 'Sup :D', 5, 1, 0),
('2014-02-20 19:02:05', 'This is a message!', 5, 1, 0),
('2014-02-20 19:02:16', 'Another', 5, 1, 0),
('2014-02-20 19:03:33', 'Hi Hello Sir!', 1, 16, 1);

-- --------------------------------------------------------

--
-- Table structure for table `Note`
--

CREATE TABLE IF NOT EXISTS `Note` (
  `Note_ID` int(11) NOT NULL,
  `User_ID` int(11) DEFAULT NULL,
  `Text` varchar(140) DEFAULT NULL,
  `Post_ID` int(11) DEFAULT NULL,
  `time_of` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`Note_ID`),
  KEY `Post_ID` (`Post_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Note`
--

INSERT INTO `Note` (`Note_ID`, `User_ID`, `Text`, `Post_ID`, `time_of`) VALUES
(0, 5, 'I&#039;m a comment', 12, '2014-02-19 21:07:19'),
(1, 1, 'But crowded', 6, '2014-02-20 01:03:10'),
(2, 1, 'Your right it is!', 0, '2014-02-20 01:13:35'),
(3, 5, 'test', 11, '2014-02-20 02:37:38'),
(4, 1, 'Thanks man!', 14, '2014-02-20 03:14:54'),
(5, 5, 'srsly', 15, '2014-02-20 04:45:59'),
(6, 14, 'Frozen is by far the best movie of my young life ', 10, '2014-02-20 15:43:27'),
(8, 9, 'oi!', 2, '2014-02-20 17:55:39'),
(9, 5, 'Brilliant. You should publish this in a poem book!', 20, '2014-02-20 17:55:58'),
(10, 9, 'I AM FRIEND', 21, '2014-02-20 17:57:37'),
(11, 9, 'COOL', 4, '2014-02-20 17:57:44'),
(12, 9, 'i am reply', 12, '2014-02-20 17:58:08'),
(13, 1, 'Fo realz', 19, '2014-02-20 17:59:16'),
(14, 5, 'SUP', 23, '2014-02-20 19:07:55'),
(15, 4, 'biff!', 2, '2014-02-21 16:18:54'),
(16, 4, 'good\r\n', 24, '2014-02-21 16:19:15');

-- --------------------------------------------------------

--
-- Table structure for table `Post`
--

CREATE TABLE IF NOT EXISTS `Post` (
  `Post_ID` int(11) NOT NULL,
  `Text` varchar(140) DEFAULT NULL,
  `Time_of` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Security` int(11) DEFAULT NULL,
  `Posting_User` int(11) DEFAULT NULL,
  `Which_Users_Page` int(11) DEFAULT NULL,
  PRIMARY KEY (`Post_ID`),
  KEY `Posting_User` (`Posting_User`),
  KEY `Which_Users_Page` (`Which_Users_Page`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Post`
--

INSERT INTO `Post` (`Post_ID`, `Text`, `Time_of`, `Security`, `Posting_User`, `Which_Users_Page`) VALUES
(0, 'Star Wars is awesome', '2014-02-19 17:24:28', 1, 1, 1),
(1, 'I made a friendbook! :) So excited', '2014-02-19 17:26:47', 1, 5, 5),
(2, 'I like cars', '2014-02-19 17:28:01', 0, 5, 5),
(3, 'I like nice cars', '2014-02-19 17:28:17', 0, 5, 5),
(4, 'cars are cool', '2014-02-19 17:28:36', 0, 5, 5),
(5, 'This site is really cool', '2014-02-19 17:50:05', 0, 4, 4),
(6, 'The CS lab is cool and fun', '2014-02-19 17:50:56', 0, 4, 4),
(7, 'this is a post about posting about posts', '2014-02-19 18:01:05', 0, 4, 4),
(8, 'i like cars', '2014-02-19 18:03:13', 0, 4, 4),
(9, 'I hate my wife', '2014-02-19 18:04:25', 0, 4, 4),
(10, 'I love frozen! #letitgo   let it go! let it go!', '2014-02-19 18:05:35', 0, 4, 4),
(11, 'i am flabbergasted by this site', '2014-02-19 18:08:18', 0, 4, 4),
(12, 'I like friendbook', '2014-02-19 21:07:08', 0, 5, 5),
(13, 'Just got a new GTR. Can&#039;t wait to bring it to the track!', '2014-02-20 02:51:26', 0, 5, 5),
(14, 'Hey, happy birthday! :)', '2014-02-20 03:14:18', 0, 5, 1),
(15, 'you are good at php', '2014-02-20 04:41:23', 0, 4, 1),
(16, 'I <3 windows amirite', '2014-02-20 05:41:32', 0, 0, 0),
(17, 'My first post on Jon&#039;s Wall!', '2014-02-20 05:48:33', 1, 1, 5),
(18, 'greg n roky r so kewl', '2014-02-20 13:17:51', 0, 13, 13),
(19, 'Yo dis friendbook is too dope dough', '2014-02-20 15:42:18', 0, 14, 14),
(20, 'OFDSAJGKLJFLJFSLKJASDF LOLOLO', '2014-02-20 17:52:20', 0, 9, 9),
(21, 'Welcome to friendbook! :)', '2014-02-20 17:53:56', 0, 5, 9),
(22, 'This new social network is great, I&#039;m going to use this all the time now!!!', '2014-02-20 18:09:48', 0, 15, 15),
(23, 'wattup', '2014-02-20 18:51:43', 0, 16, 16),
(24, 'Hey how is it going?', '2014-02-20 18:53:29', 1, 1, 4),
(25, 'Welcome to friendbook! :)', '2014-02-20 19:03:10', 0, 5, 16),
(26, 'åšè¿™ä¸ªçš„äººæ˜¯ä¸ªå¤§è¨æ¯”XDDDDDDD', '2014-02-20 19:15:16', 0, 20, 20),
(27, 'bill gates is very cool', '2014-02-21 16:22:35', 0, 4, 4);

-- --------------------------------------------------------

--
-- Table structure for table `post_about_subCat`
--

CREATE TABLE IF NOT EXISTS `post_about_subCat` (
  `Post_ID` int(11) NOT NULL DEFAULT '0',
  `SName` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`Post_ID`,`SName`),
  KEY `SName` (`SName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `post_about_subCat`
--

INSERT INTO `post_about_subCat` (`Post_ID`, `SName`) VALUES
(0, 'A New Hope'),
(18, 'A New Hope'),
(22, 'A New Hope'),
(20, 'America'),
(2, 'BMW'),
(9, 'Divorce'),
(2, 'Dodge'),
(3, 'Dodge'),
(13, 'Dodge'),
(24, 'Feeling Accepted'),
(17, 'Feeling Accomplished'),
(27, 'Feeling Anxious'),
(11, 'Feeling Bewildered'),
(16, 'Feeling Bewildered'),
(16, 'Feeling Bouncy'),
(16, 'Feeling Cheerful'),
(16, 'Feeling Chipper'),
(16, 'Feeling Complacent'),
(5, 'Ferrari'),
(7, 'Ferrari'),
(8, 'Ferrari'),
(12, 'friendbook'),
(21, 'friendbook'),
(25, 'friendbook'),
(10, 'Frozen'),
(3, 'Honda'),
(4, 'Honda'),
(6, 'Java'),
(25, 'Manliness'),
(15, 'PHP');

-- --------------------------------------------------------

--
-- Table structure for table `SubCategory`
--

CREATE TABLE IF NOT EXISTS `SubCategory` (
  `SName` varchar(20) NOT NULL,
  PRIMARY KEY (`SName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `SubCategory`
--

INSERT INTO `SubCategory` (`SName`) VALUES
('A New Hope'),
('America'),
('Babies'),
('Beauty and the Beast'),
('BMW'),
('Channing Tatum'),
('Chrysler'),
('Divorce'),
('Dodge'),
('Eragon'),
('Fashion Modeling'),
('Feeling Accepted'),
('Feeling Accomplished'),
('Feeling Aggravated'),
('Feeling Alone'),
('Feeling Amused'),
('Feeling Angry'),
('Feeling Annoyed'),
('Feeling Anxious'),
('Feeling Apathetic'),
('Feeling Ashamed'),
('Feeling Awake'),
('Feeling Bewildered'),
('Feeling Bittersweet'),
('Feeling Blah'),
('Feeling Blank'),
('Feeling Blissful'),
('Feeling Bored'),
('Feeling Bouncy'),
('Feeling Calm'),
('Feeling Cheerful'),
('Feeling Chipper'),
('Feeling Cold'),
('Feeling Complacent'),
('Feeling Confused'),
('Feeling Content'),
('Feeling Cranky'),
('Feeling Crushed'),
('Feeling Curious'),
('Feeling Cynical'),
('Feeling Dark'),
('Feeling Depressed'),
('Feeling Determined'),
('Feeling Devious'),
('Feeling Dirty'),
('Feeling Disappointed'),
('Feeling Discontent'),
('Feeling Ditzy'),
('Feeling Dorky'),
('Feeling Drained'),
('Feeling Ecstatic'),
('Feeling Energetic'),
('Feeling Enraged'),
('Feeling Enthralled'),
('Feeling Envious'),
('Feeling Exanimate'),
('Feeling Excited'),
('Feeling Flabbergaste'),
('Feeling Flirty'),
('Feeling Frustrated'),
('Feeling Full'),
('Feeling Geeky'),
('Feeling Giddy'),
('Feeling Giggety'),
('Feeling Giggly'),
('Feeling Gloomy'),
('Feeling Good'),
('Feeling Grateful'),
('Feeling Groggy'),
('Feeling Grumpy'),
('Feeling Guilty'),
('Feeling Happy'),
('Feeling Hopeful'),
('Feeling Hot'),
('Feeling Hungry'),
('Feeling Hyper'),
('Feeling Impressed'),
('Feeling Indescribabl'),
('Feeling Indifferent'),
('Feeling Infuriated'),
('Feeling Irate'),
('Feeling Irritated'),
('Feeling Jank'),
('Feeling Jealous'),
('Feeling Jubilant'),
('Feeling Lazy'),
('Feeling Lethargic'),
('Feeling Listless'),
('Feeling Lonely'),
('Feeling Loved'),
('Feeling Mad'),
('Feeling Melancholy'),
('Feeling Mellow'),
('Feeling Mischievous'),
('Feeling Moody'),
('Feeling Morose'),
('Feeling Naughty'),
('Feeling Nerdy'),
('Feeling Numb'),
('Feeling Okay'),
('Feeling Optimistic'),
('Feeling Peaceful'),
('Feeling Pessimistic'),
('Feeling Pleased'),
('Feeling Predatory'),
('Feeling Quixotic'),
('Feeling Recumbent'),
('Feeling Refreshed'),
('Feeling Rejected'),
('Feeling Rejuvenated'),
('Feeling Relaxed'),
('Feeling Relieved'),
('Feeling Restless'),
('Feeling Rushed'),
('Feeling Sad'),
('Feeling Satisfied'),
('Feeling Shocked'),
('Feeling Sick'),
('Feeling Silly'),
('Feeling Sketchy'),
('Feeling Sleepy'),
('Feeling Smart'),
('Feeling Stressed'),
('Feeling Surprised'),
('Feeling Sympathetic'),
('Feeling Thankful'),
('Feeling Tired'),
('Feeling Touched'),
('Feeling Uncomfortabl'),
('Feeling Whack'),
('Ferrari'),
('First Date'),
('friendbook'),
('Frozen'),
('George Washington'),
('Government'),
('Having Kids'),
('Honda'),
('Java'),
('Jonah Hill'),
('Justin Bieber'),
('Legos'),
('Manliness'),
('Morgan Freeman'),
('Mulan'),
('PHP'),
('Rose-Hulman Institut'),
('Stanford'),
('Tangled'),
('The Lord of the Ring'),
('Toddlers'),
('UCLA'),
('UCSB'),
('USC'),
('Valentine''s Day'),
('Woman Power'),
('World');

-- --------------------------------------------------------

--
-- Table structure for table `Sub_Of_Cat`
--

CREATE TABLE IF NOT EXISTS `Sub_Of_Cat` (
  `SName` varchar(20) NOT NULL DEFAULT '',
  `CName` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`SName`,`CName`),
  KEY `CName` (`CName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Sub_Of_Cat`
--

INSERT INTO `Sub_Of_Cat` (`SName`, `CName`) VALUES
('America', 'America'),
('George Washington', 'America'),
('Government', 'America'),
('Eragon', 'Books'),
('The Lord of the Ring', 'Books'),
('BMW', 'Cars'),
('Chrysler', 'Cars'),
('Dodge', 'Cars'),
('Ferrari', 'Cars'),
('Honda', 'Cars'),
('Channing Tatum', 'Celebrities'),
('Jonah Hill', 'Celebrities'),
('Morgan Freeman', 'Celebrities'),
('Rose-Hulman Institut', 'College'),
('Stanford', 'College'),
('UCLA', 'College'),
('UCSB', 'College'),
('USC', 'College'),
('First Date', 'Dating'),
('Valentine''s Day', 'Dating'),
('Beauty and the Beast', 'Disney'),
('Frozen', 'Disney'),
('Mulan', 'Disney'),
('Tangled', 'Disney'),
('Fashion Modeling', 'Fashion'),
('Feeling Accepted', 'Feeling'),
('Feeling Accomplished', 'Feeling'),
('Feeling Aggravated', 'Feeling'),
('Feeling Alone', 'Feeling'),
('Feeling Amused', 'Feeling'),
('Feeling Angry', 'Feeling'),
('Feeling Annoyed', 'Feeling'),
('Feeling Anxious', 'Feeling'),
('Feeling Apathetic', 'Feeling'),
('Feeling Ashamed', 'Feeling'),
('Feeling Awake', 'Feeling'),
('Feeling Bewildered', 'Feeling'),
('Feeling Bittersweet', 'Feeling'),
('Feeling Blah', 'Feeling'),
('Feeling Blank', 'Feeling'),
('Feeling Blissful', 'Feeling'),
('Feeling Bored', 'Feeling'),
('Feeling Bouncy', 'Feeling'),
('Feeling Calm', 'Feeling'),
('Feeling Cheerful', 'Feeling'),
('Feeling Chipper', 'Feeling'),
('Feeling Cold', 'Feeling'),
('Feeling Complacent', 'Feeling'),
('Feeling Confused', 'Feeling'),
('Feeling Content', 'Feeling'),
('Feeling Cranky', 'Feeling'),
('Feeling Crushed', 'Feeling'),
('Feeling Curious', 'Feeling'),
('Feeling Cynical', 'Feeling'),
('Feeling Dark', 'Feeling'),
('Feeling Depressed', 'Feeling'),
('Feeling Determined', 'Feeling'),
('Feeling Devious', 'Feeling'),
('Feeling Dirty', 'Feeling'),
('Feeling Disappointed', 'Feeling'),
('Feeling Discontent', 'Feeling'),
('Feeling Ditzy', 'Feeling'),
('Feeling Dorky', 'Feeling'),
('Feeling Drained', 'Feeling'),
('Feeling Ecstatic', 'Feeling'),
('Feeling Energetic', 'Feeling'),
('Feeling Enraged', 'Feeling'),
('Feeling Enthralled', 'Feeling'),
('Feeling Envious', 'Feeling'),
('Feeling Exanimate', 'Feeling'),
('Feeling Excited', 'Feeling'),
('Feeling Flabbergaste', 'Feeling'),
('Feeling Flirty', 'Feeling'),
('Feeling Frustrated', 'Feeling'),
('Feeling Full', 'Feeling'),
('Feeling Geeky', 'Feeling'),
('Feeling Giddy', 'Feeling'),
('Feeling Giggety', 'Feeling'),
('Feeling Giggly', 'Feeling'),
('Feeling Gloomy', 'Feeling'),
('Feeling Good', 'Feeling'),
('Feeling Grateful', 'Feeling'),
('Feeling Groggy', 'Feeling'),
('Feeling Grumpy', 'Feeling'),
('Feeling Guilty', 'Feeling'),
('Feeling Happy', 'Feeling'),
('Feeling Hopeful', 'Feeling'),
('Feeling Hot', 'Feeling'),
('Feeling Hungry', 'Feeling'),
('Feeling Hyper', 'Feeling'),
('Feeling Impressed', 'Feeling'),
('Feeling Indescribabl', 'Feeling'),
('Feeling Indifferent', 'Feeling'),
('Feeling Infuriated', 'Feeling'),
('Feeling Irate', 'Feeling'),
('Feeling Irritated', 'Feeling'),
('Feeling Jank', 'Feeling'),
('Feeling Jealous', 'Feeling'),
('Feeling Jubilant', 'Feeling'),
('Feeling Lazy', 'Feeling'),
('Feeling Lethargic', 'Feeling'),
('Feeling Listless', 'Feeling'),
('Feeling Lonely', 'Feeling'),
('Feeling Loved', 'Feeling'),
('Feeling Mad', 'Feeling'),
('Feeling Melancholy', 'Feeling'),
('Feeling Mellow', 'Feeling'),
('Feeling Mischievous', 'Feeling'),
('Feeling Moody', 'Feeling'),
('Feeling Morose', 'Feeling'),
('Feeling Naughty', 'Feeling'),
('Feeling Nerdy', 'Feeling'),
('Feeling Numb', 'Feeling'),
('Feeling Okay', 'Feeling'),
('Feeling Optimistic', 'Feeling'),
('Feeling Peaceful', 'Feeling'),
('Feeling Pessimistic', 'Feeling'),
('Feeling Pleased', 'Feeling'),
('Feeling Predatory', 'Feeling'),
('Feeling Quixotic', 'Feeling'),
('Feeling Recumbent', 'Feeling'),
('Feeling Refreshed', 'Feeling'),
('Feeling Rejected', 'Feeling'),
('Feeling Rejuvenated', 'Feeling'),
('Feeling Relaxed', 'Feeling'),
('Feeling Relieved', 'Feeling'),
('Feeling Restless', 'Feeling'),
('Feeling Rushed', 'Feeling'),
('Feeling Sad', 'Feeling'),
('Feeling Satisfied', 'Feeling'),
('Feeling Shocked', 'Feeling'),
('Feeling Sick', 'Feeling'),
('Feeling Silly', 'Feeling'),
('Feeling Sketchy', 'Feeling'),
('Feeling Sleepy', 'Feeling'),
('Feeling Smart', 'Feeling'),
('Feeling Stressed', 'Feeling'),
('Feeling Surprised', 'Feeling'),
('Feeling Sympathetic', 'Feeling'),
('Feeling Thankful', 'Feeling'),
('Feeling Tired', 'Feeling'),
('Feeling Touched', 'Feeling'),
('Feeling Uncomfortabl', 'Feeling'),
('Feeling Whack', 'Feeling'),
('Manliness', 'Gender'),
('Woman Power', 'Gender'),
('Justin Bieber', 'Gossip'),
('Legos', 'Lego'),
('Babies', 'Marriage'),
('Divorce', 'Marriage'),
('Having Kids', 'Marriage'),
('Toddlers', 'Marriage'),
('Java', 'Programming'),
('PHP', 'Programming'),
('friendbook', 'Social Networking'),
('A New Hope', 'Star Wars'),
('World', 'World');

-- --------------------------------------------------------

--
-- Table structure for table `temp_request`
--

CREATE TABLE IF NOT EXISTS `temp_request` (
  `Requester_ID` int(11) NOT NULL DEFAULT '0',
  `Receiver_ID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Requester_ID`,`Receiver_ID`),
  KEY `Receiver_ID` (`Receiver_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `temp_request`
--

INSERT INTO `temp_request` (`Requester_ID`, `Receiver_ID`) VALUES
(0, 1),
(6, 1),
(15, 1),
(17, 1),
(4, 8),
(15, 8),
(1, 13),
(1, 14),
(11, 15),
(5, 17),
(5, 18),
(5, 21),
(1, 22);

-- --------------------------------------------------------

--
-- Table structure for table `User_Follows_User`
--

CREATE TABLE IF NOT EXISTS `User_Follows_User` (
  `User1` int(11) NOT NULL DEFAULT '0',
  `User2` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`User1`,`User2`),
  KEY `User2` (`User2`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `User_Follows_User`
--

INSERT INTO `User_Follows_User` (`User1`, `User2`) VALUES
(0, 0),
(1, 0),
(2, 0),
(3, 0),
(4, 0),
(0, 1),
(1, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(9, 1),
(16, 1),
(18, 1),
(0, 2),
(2, 2),
(4, 2),
(5, 2),
(0, 3),
(3, 3),
(0, 4),
(1, 4),
(2, 4),
(4, 4),
(5, 4),
(11, 4),
(14, 4),
(15, 4),
(1, 5),
(2, 5),
(4, 5),
(5, 5),
(6, 5),
(7, 5),
(8, 5),
(9, 5),
(16, 5),
(1, 6),
(5, 6),
(6, 6),
(8, 6),
(9, 6),
(1, 7),
(5, 7),
(7, 7),
(8, 7),
(9, 7),
(5, 8),
(6, 8),
(7, 8),
(8, 8),
(9, 8),
(1, 9),
(5, 9),
(6, 9),
(7, 9),
(8, 9),
(9, 9),
(10, 10),
(4, 11),
(11, 11),
(12, 12),
(13, 13),
(4, 14),
(14, 14),
(4, 15),
(15, 15),
(1, 16),
(5, 16),
(16, 16),
(17, 17),
(1, 18),
(18, 18),
(19, 19),
(20, 20),
(21, 21),
(22, 22);

-- --------------------------------------------------------

--
-- Table structure for table `user_likes_cat`
--

CREATE TABLE IF NOT EXISTS `user_likes_cat` (
  `User_ID` int(11) NOT NULL DEFAULT '0',
  `CName` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`User_ID`,`CName`),
  KEY `CName` (`CName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_likes_cat`
--

INSERT INTO `user_likes_cat` (`User_ID`, `CName`) VALUES
(5, 'America'),
(4, 'Cars'),
(5, 'Cars'),
(5, 'Celebrities'),
(5, 'College'),
(5, 'Dating'),
(5, 'Disney'),
(0, 'Feeling'),
(5, 'Guns and Weaponry'),
(5, 'Lego'),
(5, 'Marriage'),
(0, 'Programming'),
(1, 'Programming'),
(5, 'Religion'),
(0, 'World'),
(1, 'World');

-- --------------------------------------------------------

--
-- Table structure for table `user_likes_post`
--

CREATE TABLE IF NOT EXISTS `user_likes_post` (
  `User_ID` int(11) NOT NULL DEFAULT '0',
  `Post_ID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`User_ID`,`Post_ID`),
  KEY `Post_ID` (`Post_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_likes_post`
--

INSERT INTO `user_likes_post` (`User_ID`, `Post_ID`) VALUES
(5, 0),
(5, 1),
(4, 2),
(4, 3),
(5, 3),
(5, 4),
(4, 5),
(4, 6),
(4, 7),
(4, 8),
(5, 10),
(5, 12),
(9, 12),
(5, 13),
(9, 13),
(1, 14),
(3, 14),
(4, 14),
(5, 14),
(11, 14),
(4, 15),
(5, 15),
(0, 16),
(5, 17),
(1, 18),
(1, 19),
(9, 20),
(9, 21),
(1, 23),
(5, 23),
(16, 23),
(1, 24),
(4, 24),
(4, 27),
(5, 27);

--
-- Triggers `user_likes_post`
--
DROP TRIGGER IF EXISTS `like_incrementer`;
DELIMITER //
CREATE TRIGGER `like_incrementer` AFTER INSERT ON `user_likes_post`
 FOR EACH ROW BEGIN
DECLARE tempUID int;
DECLARE tempPID int;
DECLARE tempLikes int;
DECLARE	tempSub varchar(20);
DECLARE tempCat varchar(20);
DECLARE tempSize int;
DECLARE i int;

DECLARE NullCheck int;


SET tempUID = NEW.User_ID;
SET tempPID = NEW.Post_ID;

CREATE TEMPORARY TABLE TempSubs(Post_ID int, SName  varchar(20));
INSERT INTO TempSubs SELECT Post_ID, SName FROM post_about_subCat WHERE Post_ID = NEW.Post_ID;

SELECT COUNT(*) FROM TempSubs INTO tempSize;
SET i=0;
WHILE(i<tempSize) DO

	SELECT SName FROM TempSubs ORDER BY SName LIMIT 1 OFFSET i INTO tempSub;
	SELECT CName FROM Sub_Of_Cat where SName = tempSub INTO tempCat;
	SELECT NumLikes FROM User_Liking_Cat WHERE User_ID = tempUID AND CName = tempCat INTO tempLikes;
	IF(tempLikes IS NULL) THEN

		INSERT
			INTO User_Liking_Cat(User_ID, CName, NumLikes)
			VALUES (tempUID, tempCat, 1);
	
	
	ELSEIF(tempLikes >= 1 AND tempLikes < 3) THEN

		UPDATE User_Liking_Cat
			SET NumLikes = tempLikes + 1
			WHERE tempUID = User_ID AND tempCat = CName;
	END IF;
	
	IF(tempLikes = 3) THEN
		SELECT User_ID FROM user_likes_cat WHERE User_ID = tempUID AND CName = tempCat INTO NullCheck;
		IF(NullCheck IS NULL) THEN
			INSERT
				INTO user_likes_cat(User_ID, CName)
				VALUES (tempUID, tempCat);
		END IF;
	END IF;
	SET i = i+1;
	
END WHILE;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `User_Liking_Cat`
--

CREATE TABLE IF NOT EXISTS `User_Liking_Cat` (
  `User_ID` int(11) NOT NULL DEFAULT '0',
  `CName` varchar(20) NOT NULL DEFAULT '',
  `NumLikes` int(11) DEFAULT NULL,
  PRIMARY KEY (`User_ID`,`CName`),
  KEY `CName` (`CName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `User_Liking_Cat`
--

INSERT INTO `User_Liking_Cat` (`User_ID`, `CName`, `NumLikes`) VALUES
(0, 'Feeling', 3),
(1, 'Cars', 3),
(1, 'Feeling', 1),
(1, 'Marriage', 1),
(1, 'Social Networking', 1),
(1, 'Star Wars', 2),
(4, 'Cars', 3),
(4, 'Feeling', 2),
(4, 'Programming', 2),
(5, 'Cars', 3),
(5, 'Disney', 1),
(5, 'Feeling', 2),
(5, 'Programming', 1),
(5, 'Social Networking', 2),
(5, 'Star Wars', 1),
(9, 'America', 2),
(9, 'Cars', 1),
(9, 'Social Networking', 2);

-- --------------------------------------------------------

--
-- Table structure for table `User_Page`
--

CREATE TABLE IF NOT EXISTS `User_Page` (
  `Relationship` varchar(20) DEFAULT NULL,
  `Religion` varchar(20) DEFAULT NULL,
  `Gender` varchar(4) DEFAULT NULL,
  `City` varchar(50) DEFAULT NULL,
  `Birthday` varchar(20) DEFAULT NULL,
  `Job` varchar(20) DEFAULT NULL,
  `PageName` varchar(20) DEFAULT NULL,
  `User_ID` int(11) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `FirstName` varchar(30) NOT NULL,
  `LastName` varchar(30) NOT NULL,
  `Password` varchar(10) NOT NULL,
  PRIMARY KEY (`User_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `User_Page`
--

INSERT INTO `User_Page` (`Relationship`, `Religion`, `Gender`, `City`, `Birthday`, `Job`, `PageName`, `User_ID`, `Email`, `FirstName`, `LastName`, `Password`) VALUES
('Not Specified', 'Dream Theater', 'Male', 'Hard Rock Cafe', '12/12/12', 'Rose Prof', 'Greg Wilkin', 0, 'kidrocklover@kickrockfanz.gov', 'Greg', 'Wilkin', 'password'),
('Single', 'Christian', 'Male', 'Prineville, OR', '9/24/1993', 'Student', 'Tyler Rockwood', 1, 'trock@rose.edu', 'Tyler', 'Rockwood', 'qqqqqq6'),
('Not Specified', 'Not Specified', 'Male', 'Not Specified', '2000-02-22', 'Not Specified', 'Jon Smith', 2, 'jon.smith@hotmail.com', 'Jon', 'Smith', 'qwerty'),
('Not Specified', 'Not Specified', 'Male', 'Not Specified', '2002-01-21', 'Not Specified', 'Jon Rockwood', 3, 'jrockwood@hotmail.com', 'Jon', 'Rockwood', 'asdf'),
('Nothing :(', 'Scientology', 'Male', 'Huntington', '1994-03-16', 'Nothing', 'Greg Callegari', 4, 'calle.greg@gmail.com', 'Greg', 'Callegari', 'password'),
('In a Relationship', 'Christian', 'Male', 'San Francisco, CA', '1994-04-28', 'Student', 'Jonathan Jungck', 5, 'jonathan@jungck.com', 'Jonathan', 'Jungck', 'password'),
('Not Specified', 'Not Specified', 'Male', 'Not Specified', '1973-12-24', 'Not Specified', 'Timothy Rockwood', 6, 'tim.rockwood@hotmail.com', 'Timothy', 'Rockwood', 'stud'),
('Not Specified', 'Not Specified', 'Fema', 'Not Specified', '1989-12-31', 'Not Specified', 'Anne Smith', 7, 'asmith@rose.edu', 'Anne', 'Smith', 'smith1'),
('Not Specified', 'Not Specified', 'Male', 'Not Specified', '0001-12-25', 'Not Specified', 'Jesus Christ', 8, 'jesus@christ.com', 'Jesus', 'Christ', 'holybible'),
('engineer', 'log', 'Male', 'jimmyjammyjohnnytommy', '2014-02-06', 'slave', 'George Costanza', 9, 'caokx@rose-hulman.edu', 'George', 'Costanza', ' jkl'),
('Not Specified', 'Not Specified', 'Male', 'Not Specified', '0004-04-04', 'Not Specified', 'Han Solo', 10, 'han@milleniumfalcon.com', 'Han', 'Solo', 'hi'),
('Not Specified', 'Not Specified', 'Male', 'Not Specified', '03/01/1994', 'Not Specified', 'Dev Chanana', 11, 'chanand@rose-hulman.edu', 'Dev', 'Chanana', 'supalaser2'),
('Not Specified', 'Not Specified', 'Male', 'Not Specified', '1992-04-10', 'Not Specified', 'Peter Heidlauf', 12, 'pheidlauf@gmail.com', 'Peter', 'Heidlauf', 'jkl;&#039;'),
('Not Specified', 'Not Specified', 'Male', 'Not Specified', '1993-10-05', 'Not Specified', 'Anthony Adamo', 13, 'anthonysk8s@gmail.com', 'anthony', 'adamo', 'password'),
('Nothing', 'Born Again', 'Male', 'AlecCity', '1993-11-23', 'Homeless', 'Alec Mitchell', 14, 'mitcheat@rose-hulman.edu', 'Alec', 'Mitchell', 'password'),
('Not Specified', 'Not Specified', 'Male', 'Not Specified', '1955-10-28', 'Not Specified', 'Bill Gates', 15, 'spencer_balash@yahoo.com', 'Bill', 'Gates', 'bonerjams9'),
('Not Specified', 'Not Specified', 'Male', 'Not Specified', '1966-04-19', 'Not Specified', 'Hi Hello', 16, 'asdf@asdf', 'Hi', 'Hello', 'asdf'),
('Not Specified', 'Not Specified', 'Male', 'Not Specified', '1993-01-05', 'Not Specified', 'Peter Samyn', 17, '11samype@gmail.com', 'Peter', 'Samyn', '74p62p'),
('Not Specified', 'Not Specified', 'Male', 'Not Specified', '1994-10-04', 'Not Specified', 'Garrett Barnes', 18, 'barnesgl@rose-hulman.edu', 'Garrett', 'Barnes', 'Laser0994'),
('Not Specified', 'Not Specified', 'Male', 'Not Specified', '2009-11-27', 'Not Specified', 'KJKJK KJKJKJ', 19, 'KJKJKJ@KJKJ.COM', 'KJKJK', 'KJKJKJ', 'YKK1992'),
('Not Specified', 'Not Specified', 'Fema', 'Not Specified', '2012-10-30', 'Not Specified', 'JKJKJK KJKJKJ', 20, 'PETERYKK888@GMAIL.COM', 'JKJKJK', 'KJKJKJ', 'YKK1992'),
('Not Specified', 'Not Specified', 'Male', 'Not Specified', '2014-02-20', 'Not Specified', 'Miku Wang', 21, 'wangz3@rose-hulman.edu', 'Miku', 'Wang', '1'),
('Not Specified', 'Not Specified', 'Male', 'Not Specified', '1991-08-01', 'Not Specified', 'Michael Bush', 22, 'bushma1@rose-hulman.edu', 'Michael', 'Bush', '20100MaD!s');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Message`
--
ALTER TABLE `Message`
  ADD CONSTRAINT `Message_ibfk_1` FOREIGN KEY (`Sender_ID`) REFERENCES `User_Page` (`User_ID`),
  ADD CONSTRAINT `Message_ibfk_2` FOREIGN KEY (`Receiver_ID`) REFERENCES `User_Page` (`User_ID`);

--
-- Constraints for table `Note`
--
ALTER TABLE `Note`
  ADD CONSTRAINT `Note_ibfk_1` FOREIGN KEY (`Post_ID`) REFERENCES `Post` (`Post_ID`);

--
-- Constraints for table `Post`
--
ALTER TABLE `Post`
  ADD CONSTRAINT `Post_ibfk_1` FOREIGN KEY (`Posting_User`) REFERENCES `User_Page` (`User_ID`),
  ADD CONSTRAINT `Post_ibfk_2` FOREIGN KEY (`Which_Users_Page`) REFERENCES `User_Page` (`User_ID`);

--
-- Constraints for table `post_about_subCat`
--
ALTER TABLE `post_about_subCat`
  ADD CONSTRAINT `post_about_subCat_ibfk_1` FOREIGN KEY (`Post_ID`) REFERENCES `Post` (`Post_ID`),
  ADD CONSTRAINT `post_about_subCat_ibfk_2` FOREIGN KEY (`SName`) REFERENCES `SubCategory` (`SName`);

--
-- Constraints for table `Sub_Of_Cat`
--
ALTER TABLE `Sub_Of_Cat`
  ADD CONSTRAINT `Sub_Of_Cat_ibfk_1` FOREIGN KEY (`SName`) REFERENCES `SubCategory` (`SName`),
  ADD CONSTRAINT `Sub_Of_Cat_ibfk_2` FOREIGN KEY (`CName`) REFERENCES `Category` (`CName`);

--
-- Constraints for table `temp_request`
--
ALTER TABLE `temp_request`
  ADD CONSTRAINT `temp_request_ibfk_1` FOREIGN KEY (`Requester_ID`) REFERENCES `User_Page` (`User_ID`),
  ADD CONSTRAINT `temp_request_ibfk_2` FOREIGN KEY (`Receiver_ID`) REFERENCES `User_Page` (`User_ID`);

--
-- Constraints for table `User_Follows_User`
--
ALTER TABLE `User_Follows_User`
  ADD CONSTRAINT `User_Follows_User_ibfk_1` FOREIGN KEY (`User1`) REFERENCES `User_Page` (`User_ID`),
  ADD CONSTRAINT `User_Follows_User_ibfk_2` FOREIGN KEY (`User2`) REFERENCES `User_Page` (`User_ID`);

--
-- Constraints for table `user_likes_cat`
--
ALTER TABLE `user_likes_cat`
  ADD CONSTRAINT `user_likes_cat_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `User_Page` (`User_ID`),
  ADD CONSTRAINT `user_likes_cat_ibfk_2` FOREIGN KEY (`CName`) REFERENCES `Category` (`CName`);

--
-- Constraints for table `user_likes_post`
--
ALTER TABLE `user_likes_post`
  ADD CONSTRAINT `user_likes_post_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `User_Page` (`User_ID`),
  ADD CONSTRAINT `user_likes_post_ibfk_2` FOREIGN KEY (`Post_ID`) REFERENCES `Post` (`Post_ID`);

--
-- Constraints for table `User_Liking_Cat`
--
ALTER TABLE `User_Liking_Cat`
  ADD CONSTRAINT `User_Liking_Cat_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `User_Page` (`User_ID`),
  ADD CONSTRAINT `User_Liking_Cat_ibfk_2` FOREIGN KEY (`CName`) REFERENCES `Category` (`CName`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
