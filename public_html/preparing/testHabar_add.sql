SET NAMES UTF8;

USE `tsupp_controwl`;

DROP TABLE IF EXISTS testHabar_add;

CREATE TABLE testHabar_add AS 
	SELECT 
		"1" AS userRole, d.id AS userId, b.id AS testsId, 
		a.teacher_link AS comment, 
		CONCAT_WS(" ",c.teacher_surname, c.teacher_name, c.teacher_pobatkovi) AS comment2, 0 AS answerId 
	FROM acadGroupTeacher a, questionTests b, catalogTeacher c, 
		catalogStudent d 
	WHERE c.id = a.teacher_link AND d.group_link = a.group_link AND 
				a.group_link > 0 AND a.teacher_link > 0 AND d.health_group > -1 AND 
				d.id NOT IN (
					SELECT DISTINCT
						userId 
					FROM 
						testHabar
				)
;
