create table users
(
    id         serial
        primary key,
    email      varchar(255)            not null
        unique,
    password   varchar(255)            not null,
    created_at timestamp default now() not null
);

create table users_details
(
    id        serial
        primary key,
    id_user   integer      not null
        references users
            on delete cascade,
    name      varchar(100) not null,
    surname   varchar(100) not null,
    phone     varchar(20),
    instagram varchar(100)
);

create table collections
(
    id   serial
        primary key,
    name varchar(100) not null
        unique
);

create table cards
(
    id            serial
        primary key,
    code          varchar(50) not null,
    id_collection integer     not null
        references collections
            on delete cascade,
    parallel      varchar(50),
    constraint cards_code_collection_parallel_uk
        unique (code, id_collection, parallel)
);

create table users_cards
(
    id        serial
        primary key,
    id_user   integer           not null
        references users
            on delete cascade,
    id_card   integer           not null
        references cards
            on delete cascade,
    card_type varchar(50)       not null,
    quantity  integer default 1 not null,
    constraint unique_user_card
        unique (id_user, id_card, card_type)
);
