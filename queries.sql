USE doingsdone;

INSERT INTO user (email,name,password)
VALUES
('email1@example.com','Саша','235235'),
('email2@example.com','Женя','235236'),
('email3@example.com','Константин','235237');

INSERT INTO project(user,name) VALUES
(1,'Входящие'),
(3,'Учеба'),
(1,'Работа'),
(3,'Домашние дела'),
(2,'Авто');

INSERT INTO task (user_id,project_id,status,title,file)
VALUES
(1,3,0,'Собеседование в IT компании','link1'),
(2,2,0,'Выполнить тестовое задание','link2'),
(3,2,1,'Сделать задание первого раздела','link3'),
(2,4,0,'Встреча с другом','link4'),
(3,4,0,'Купить корм для кота','link4'),
(1,4,0,'Заказать пиццу','link5');

UPDATE task SET status =1
WHERE task = 1;

UPDATE task SET title = 'Купить корм для питомцев'
WHERE task = 5;

SELECT * FROM task
WHERE project_id = 4;

SELECT * FROM task
LEFT JOIN project ON task.project_id = project.id
WHERE user_id = 2;
