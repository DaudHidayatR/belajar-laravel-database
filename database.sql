show databases;
create database belajar_laravel_database;
use belajar_laravel_database;

create table catagories(
    id varchar(100) not null primary key,
    name varchar(100) not null,
    description text,
    created_at timestamp default current_timestamp
)engine innodb;
desc catagories;

