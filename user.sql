use news;
create table users(
    username varchar(50) not null,
    userId integer not null auto_increment,
    userPass varchar(255) not null,
    unique key idx_username (username),
    primary key (userId)
) engine = InnoDB default character set = utf8 collate = utf8_general_ci;
