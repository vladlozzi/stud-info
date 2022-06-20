SET NAMES UTF8;

USE `tsupp_studinfo`; TRUNCATE progress_log;
USE `tsupp_controwl`; TRUNCATE testHabar;
DROP INDEX teacher ON testHabar; DROP INDEX tests ON testHabar;
DROP INDEX stud ON testHabar; DROP INDEX answer ON testHabar;
DROP INDEX semester ON testHabar;

/*
CREATE TABLE testHabar_temp AS 
	SELECT 
		"1" AS userRole, d.id AS userId, b.id AS testsId, 
		a.teacher_link AS comment, 
		CONCAT_WS(" ",c.teacher_surname, c.teacher_name, c.teacher_pobatkovi) AS comment2, 0 AS answerId 
	FROM progress_teacher_mark a, questionTests b, catalogTeacher c, 
		catalogStudent d 
	WHERE c.id = a.teacher_link AND d.group_link = a.group_link;
*/

/*
CREATE TABLE testHabar_temp AS 
	SELECT 
		"1" AS userRole, d.id AS userId, b.id AS testsId, 
		a.teacher_link AS comment, 
		CONCAT_WS(" ",c.teacher_surname, c.teacher_name, c.teacher_pobatkovi) AS comment2, 0 AS answerId 
	FROM acadGroupTeacher a, questionTests b, catalogTeacher c, 
		catalogStudent d 
	WHERE c.id = a.teacher_link AND d.group_link = a.group_link AND 
				a.group_link > 0 AND a.teacher_link > 0 AND d.health_group > -1;
*/

CREATE TABLE testHabar_temp AS 
	SELECT 
		"1" AS userRole, d.id AS userId, b.id AS testsId, 
		a.teacherId AS comment, a.semester, 
		CONCAT_WS(" ",c.teacher_surname, c.teacher_name, c.teacher_pobatkovi) AS comment2, 0 AS answerId 
	FROM acadGroupTeacher a, questionTests b, catalogTeacher c, catalogStudent d 
	WHERE c.id = a.teacherId AND d.group_link = a.groupId AND 
				a.groupId > 0 AND a.teacherId > 0 AND d.health_group > -1;

INSERT INTO testHabar (userRole, userId, testsId, comment, comment2, answerId, semester) 
	SELECT userRole, userId, testsId, comment, comment2, answerId, semester
	FROM testHabar_temp
	GROUP BY userRole, userId, testsId, comment, comment2, answerId, semester
	ORDER BY userId, comment2, testsId;

DROP TABLE testHabar_temp;

CREATE INDEX teacher ON testHabar (comment); CREATE INDEX tests ON testHabar (testsId);
CREATE INDEX stud ON testHabar (userId); CREATE INDEX answer ON testHabar (answerId);
CREATE INDEX semester ON testHabar (semester);
-- TRUNCATE questionResults;
