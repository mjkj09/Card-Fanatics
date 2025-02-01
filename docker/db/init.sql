-- init.sql

CREATE TABLE users
(
    id         serial PRIMARY KEY,
    email      varchar(255)            NOT NULL UNIQUE,
    password   varchar(255)            NOT NULL,
    created_at timestamp DEFAULT now() NOT NULL
);

CREATE TABLE users_details
(
    id        serial PRIMARY KEY,
    id_user   integer     NOT NULL REFERENCES users ON DELETE CASCADE,
    name      varchar(50) NOT NULL,
    surname   varchar(50) NOT NULL,
    phone     varchar(20),
    instagram varchar(30)
);

CREATE TABLE collections
(
    id   serial PRIMARY KEY,
    name varchar(100) NOT NULL UNIQUE
);

CREATE TABLE cards
(
    id             serial PRIMARY KEY,
    code           varchar(20) NOT NULL,
    id_collection  integer     NOT NULL REFERENCES collections ON DELETE CASCADE,
    parallel       varchar(30),
    player_name    varchar(50) NOT NULL,
    player_surname varchar(50) NOT NULL,
    CONSTRAINT cards_code_collection_parallel_player_uk
        UNIQUE (code, id_collection, parallel, player_name, player_surname)
);

CREATE TABLE users_cards
(
    id        serial PRIMARY KEY,
    id_user   integer           NOT NULL REFERENCES users ON DELETE CASCADE,
    id_card   integer           NOT NULL REFERENCES cards ON DELETE CASCADE,
    card_type varchar(50)       NOT NULL,
    quantity  integer DEFAULT 1 NOT NULL,
    CONSTRAINT unique_user_card UNIQUE (id_user, id_card, card_type)
);
