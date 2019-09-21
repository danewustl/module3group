use news;
create table stories(
    link varchar(512) not null,
    storyId integer not null auto_increment,
    poster integer not null,
    primary key (storyId),
    foreign key (poster) references user (userId),

) engine = InnoDB default character set = utf8 collate = utf8_general_ci;
