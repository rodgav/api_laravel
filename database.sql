create table users
(
    id             int auto_increment primary key         not null,
    email          varchar(100)                           not null,
    role           varchar(20)                            not null,
    name           varchar(100)                           not null,
    surname        varchar(100)                           not null,
    password       varchar(100)                           not null,
    created_at     timestamp    default current_timestamp not null,
    updated_at     timestamp    default current_timestamp not null,
    remember_token varchar(350) default ''                not null
);

create table cars
(
    id          int auto_increment primary key      not null,
    id_user     int                                 not null,
    title       varchar(100)                        not null,
    description text      default ''                not null,
    price       decimal(6, 2)                       not null,
    status      varchar(30)                         not null,
    created_at  timestamp default current_timestamp not null,
    updated_at  timestamp default current_timestamp not null
);

alter table cars
    add constraint id_userC foreign key (id_user) references users (id) on delete restrict on update restrict;
