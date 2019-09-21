create table comments(
    comment varchar(3000) not null,
    commentId integer not null auto_increment,
    replyTo integer,
    storyId integer not null,
    commenter integer not null,
    foreign key (replyTo) references comments (commentId),
    foreign key (storyId) references story (storyId),
    foreign key (commenter) references user (userId),
    primary key (commentId)
) engine = InnoDB default character set = utf8 collate = utf8_general_ci;