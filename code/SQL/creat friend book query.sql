USE master;
GO
IF DB_ID ('FriendBook') IS NOT NULL
Drop database [FriendBook];
GO
-- Get the SQL Server data path
DECLARE @data_path nvarchar(256);
SET @data_path = (SELECT SUBSTRING(physical_name, 1, CHARINDEX(N'master.mdf', LOWER(physical_name)) - 1)
                  FROM master.sys.master_files
                  WHERE database_id = 1 AND file_id = 1);

Print @data_path
EXECUTE ('CREATE DATABASE [FriendBook]
ON
PRIMARY  
    (NAME = [FriendBook],
    FILENAME = '''+ @data_path + 'FriendBook.mdf'',
    SIZE = 6MB,
    MAXSIZE = 30MB,
    FILEGROWTH = 12%)
LOG ON 
   (NAME = [FriendBook],
    FILENAME = '''+ @data_path + 'FriendBookLog.ldf'',
    SIZE = 3MB,
    MAXSIZE = 22MB,
    FILEGROWTH = 17%)'
);
Go