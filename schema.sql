CREATE DATABASE doingsdone
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;
use doingsdone;
CREATE table users (
    id int auto_increment primary key,
    email char (128) not null unique,
    password char (64),
    name char (128) not null,
    date_add timestamp DEFAULT current_timestamp
);
CREATE table project (
    id int auto_increment primary key,
    user_id int not null,
    name char (128) unique
);
CREATE table task (
    id int auto_increment primary key,
    user_id int not null,
    project_id int not null,
    date_add timestamp not null DEFAULT current_timestamp,
    date_done timestamp not null,
    status tinyint(2) DEFAULT 0 not null,
    name char not null,
    file text(1500),
    date_must_done timestamp not null
);
