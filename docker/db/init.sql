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


CREATE TABLE roles
(
    id        serial PRIMARY KEY,
    role_name varchar(50) NOT NULL UNIQUE
);

CREATE TABLE users_roles
(
    id_user integer NOT NULL REFERENCES users (id) ON DELETE CASCADE,
    id_role integer NOT NULL REFERENCES roles (id) ON DELETE CASCADE,
    PRIMARY KEY (id_user, id_role)
);

CREATE TABLE bans
(
    id        serial PRIMARY KEY,
    id_user   integer   NOT NULL REFERENCES users (id) ON DELETE CASCADE,
    reason    text,
    banned_at timestamp NOT NULL DEFAULT now()
);


INSERT INTO roles (role_name)
VALUES ('user'),
       ('admin');

CREATE OR REPLACE FUNCTION prevent_removing_user_role()
    RETURNS TRIGGER AS
$$
BEGIN
    IF OLD.id_role = (SELECT id
                      FROM roles
                      WHERE role_name = 'user'
                      LIMIT 1) THEN
        RAISE EXCEPTION 'Cannot remove user role from user: each user must have at least the user role.';
    END IF;

    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trig_prevent_removing_user_role
    BEFORE DELETE
    ON users_roles
    FOR EACH ROW
EXECUTE FUNCTION prevent_removing_user_role();
