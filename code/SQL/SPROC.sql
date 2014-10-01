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

END