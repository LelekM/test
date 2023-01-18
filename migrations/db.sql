create schema lelek;
create table champions
(
    id    int auto_increment,
    name  varchar(20) null,
    hp    int         null,
    armor int         null,
    constraint champions_pk
        primary key (id)
);

