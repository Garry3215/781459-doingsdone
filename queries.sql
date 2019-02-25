insert into users
    set name = "Вова", email = "vova@mail.ru", password = "123456";
insert into users
    set name = "Саша", email = "sasha@mail.ru", password = "654321";

insert into project
    set user_id = 1, name = "Входящие";
insert into project
    set user_id = 1, name = "Учеба";
insert into project
    set user_id = 1, name = "Работа";
insert into project
    set user_id = 1, name = "Домашние дела";
insert into project
    set user_id = 1, name = "Авто";


      insert into task
      	set user_id = 1, project_id = 3, status = 0, name = "Собеседование в IT компании", date_must_done = "2019-02-10 00:00:00";
      insert into task
          set user_id = 1, project_id = 3, status = 0, name = "Выполнить тестовое задание", date_must_done = "2019-12-25 00:00:00";
      insert into task
      	set user_id = 1, project_id = 2, status = 1, name = "Сделать задание первого раздела", date_must_done = "2019-12-21 00:00:00";
      insert into task
          set user_id = 1, project_id = 1, status = 0, name = "Встреча с другом", date_must_done = "2019-12-22 00:00:00";
      insert into task
          set user_id = 1, project_id = 4, status = 0, name = "Купить корм для кота";
      insert into task
          set user_id = 1, project_id = 4, status = 0, name = "Заказать пиццу";

//получить список из всех проектов для одного пользователя
select name from project
    where user_id = 1;

//получить список из всех задач для одного проекта;
select name from task
    where project_id = 3;

//пометить задачу как выполненную;
update task set status = 1 where id = 9;

//обновить название задачи по её идентификатору.
update task set name = "Сделать задание 21-го раздела" where id = 9;


insert into project
    set user_id = 2, name = "Учеба";
insert into project
    set user_id = 2, name = "Работа";


      insert into task
        set user_id = 2, project_id = 7, status = 0, name = "Сделать задание №1", date_must_done = "2019-02-20 00:00:00";
      insert into task
          set user_id = 2, project_id = 7, status = 0, name = "Поругаться с начальством", date_must_done = "2019-12-19 00:00:00";
