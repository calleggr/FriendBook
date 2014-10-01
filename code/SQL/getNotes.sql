--ROCK is POST ID

SELECT User_Page.PageName as Poster, Note.Text as Text, (COUNT(*) From user_likes_note Where user_likes_note.Note_ID  = Note.Note_ID) as Likes, Note.Note_ID
	FROM User_Page, Note, user_likes_note
	WHERE Note.Post_ID = 11 AND Note.text IS NOT NULL AND Note.User_ID = User_Page.User_ID
	ORDER BY Note.time_of DESC
	
--work	
SELECT User_Page.PageName AS Poster, Note.Text AS TEXT, Note.Note_ID
FROM User_Page, Note
WHERE Note.Post_ID =11
AND Note.text IS NOT NULL 
AND Note.User_ID = User_Page.User_ID
ORDER BY Note.time_of DESC 
LIMIT 0 , 30