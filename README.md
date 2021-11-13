# Pane of Hope

Work in progress...



## Database

MariaDB 10.5.12 is used for testing.

CREATE TABLE statements:

```sql
CREATE TABLE users (
	id int NOT NULL AUTO_INCREMENT,
	username varchar(128) NOT NULL,
	displayname varchar(64) NOT NULL,
	password varchar(400) NOT NULL,
	email varchar(264) NOT NULL,
	permission_level tinyint NOT NULL DEFAULT 10,
	PRIMARY KEY (id)
);

CREATE TABLE characters (
	id int NOT NULL AUTO_INCREMENT,
	name nvarchar(500) NOT NULL,
	original_name nvarchar(500),
	gender tinyint NOT NULL DEFAULT 0,
	PRIMARY KEY (id)
);

CREATE TABLE character_images (
	id int NOT NULL AUTO_INCREMENT,
	character_id int DEFAULT NULL,
	path varchar(256) NOT NULL,
	PRIMARY KEY (id),
	CONSTRAINT fk_char_img
		FOREIGN KEY (character_id)
		REFERENCES characters (id)
);
```
