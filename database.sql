show databases;
create database belajar_laravel_database;
use belajar_laravel_database;
use testing;
drop table categories;
create table categories(
    id varchar(100) not null primary key,
    name varchar(100) not null,
    description text,
    created_at timestamp default current_timestamp
)engine innodb;
desc categories;

create table counter(
    id varchar(100) not null primary key,
    counter int not null default 0
)engine innodb;
insert into counter(id, counter) values('sample', 0);

select * from counter;
