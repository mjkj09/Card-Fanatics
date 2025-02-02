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
    IF OLD.id_role = (SELECT id FROM roles WHERE role_name = 'user' LIMIT 1) THEN
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

INSERT INTO collections (id, name)
VALUES (1, 'TOPPS MATCH ATTAX EURO 2024'),
       (2, 'PANINI PREMIER LEAGUE ADRENALYN XL 2025'),
       (3, 'PANINI FIFA 365 ADRENALYN XL 2025');

INSERT INTO cards (id, code, id_collection, parallel, player_name, player_surname)
VALUES (1, 'GG 7', 1, '', 'MEMPHIS', 'DEPAY'),
       (2, 'GG 5', 1, '', 'HARRY', 'KANE'),
       (3, 'GG 9', 1, '', 'CRISTIANO', 'RONALDO'),
       (4, 'TUR 11', 1, '', 'HAKAN', 'CALHANOGLU'),
       (5, 'CC 3', 1, '', 'LUKA', 'MODRIC'),
       (6, 'U XI 11', 1, 'PURPLE SAPPHIRE', 'HARRY', 'KANE'),
       (7, 'BEL 1', 1, '', 'THIBAUT', 'COURTOIS'),
       (8, 'ESP 18', 1, 'GREEN EMERALD', 'ALVARO', 'MORATA'),
       (9, 'DEN 16', 1, '', 'RASMUS', 'HOJLUND'),
       (10, 'SCO 12', 1, 'BLUE CRYSTAL', 'STUART', 'ARMSTRONG'),
       (11, 'ESP 2', 1, '', 'DANIEL', 'CARVAJAL'),
       (12, 'ALB 1', 1, 'GREEN EMERALD', 'ETRIT', 'BERISHA'),
       (13, 'SVN 1', 1, 'GREEN EMERALD', 'JAN', 'OBLAK'),
       (14, 'CHE 13', 2, '', 'JOAO', 'FELIX'),
       (15, 'ARS 9', 2, '', 'BUKAYO', 'SAKA'),
       (16, 'SRB 14', 1, 'GREEN EMERALD', 'DUSAN', 'TADIC'),
       (17, 'GG 2', 1, 'GREEN EMERALD', 'ROMELU', 'LUKAKU'),
       (18, 'GER 10', 1, '', 'ROBIN', 'GOSENS'),
       (19, 'NOT 11', 2, '', 'CHRIS', 'WOOD'),
       (20, 'ALB 14', 1, '', 'TAULANT', 'SEFERI'),
       (21, 'POR 9', 1, 'BLUE GEM', 'JOAO', 'PALHINHA'),
       (22, 'ALB 1', 1, '', 'ETRIT', 'BERISHA'),
       (23, 'MUN 4', 2, '', 'AMAD', 'DIALLO'),
       (24, '117', 3, '', 'VICTOR', 'BONIFACE'),
       (25, '1', 3, '', 'BUKAYO', 'SAKA'),
       (26, '57', 3, '', 'VIRGIL', 'VAN DIJK'),
       (27, 'CC 6', 1, '', 'LEONARDO', 'BONUCCI'),
       (28, 'CC 7', 1, '', 'DALEY', 'BLIND'),
       (29, 'CC 11', 1, '', 'XHERDAN', 'SHAQIRI'),
       (30, 'GER 15', 1, 'BLUE CRYSTAL', 'THOMAS', 'MULLER'),
       (31, 'ALB 5', 1, '', 'ARDIAN', 'ISMAJLI'),
       (32, 'CRO 4', 1, '', 'JOSKO', 'GVARDIOL'),
       (33, '315', 3, '', 'KYLIAN', 'MBAPPE'),
       (34, '430', 3, '', 'LIONEL', 'MESSI'),
       (35, '202', 2, '', 'JAMIE', 'VARDY'),
       (36, '133', 2, '', 'JEAN-PHILIPPE', 'MATETA'),
       (37, '85', 3, '', 'LEWIS', 'DUNK'),
       (38, '106', 3, '', 'REECE', 'JAMES'),
       (39, '414', 3, '', 'RICO', 'LEWIS'),
       (40, '19', 3, '', 'ERLING', 'HAALAND'),
       (41, '401', 2, '', 'HARRY', 'WINKS'),
       (42, '321', 2, '', 'PEDRO', 'PORRO'),
       (43, 'INT7', 3, '', 'FLORIAN', 'WIRTZ'),
       (44, '431', 3, '', 'AITANA', 'BONMATI'),
       (45, '389', 3, '', 'GABRIEL', 'MARTINELLI'),
       (46, '282', 2, '', 'NECO', 'WILLIAMS');

INSERT INTO users (id, email, password, created_at)
VALUES (1, 'client123@gmail.com', '$2y$10$XrUnKkBxBRvPCwYXOl46UOvd4x2sdvPp5FW1HZjv5dlg/pQXniaVm',
        '2025-02-02 21:14:09.052145'),
       (2, 'paroo@yahoo.com', '$2y$10$yzX7KLy1aIR3IiZBSuSIgufPxbYZP26YiZlSLP3YBLbyY5D5802Ly',
        '2025-02-02 21:27:22.936062'),
       (3, 'krasinska@gmail.com', '$2y$10$4y.Pyftc.XFzRMw4HT1YJuauKZOJ38N1EwSCadQoOOZp1HkbZm8a.',
        '2025-02-02 21:34:09.207094'),
       (4, 'dzwonek@onet.pl', '$2y$10$i3vSeOnRxTGGHo4HDA0wVuzSA1G3Be6MuALDmyZn34k.fvsatx2Ay',
        '2025-02-02 21:41:10.352046'),
       (5, 'marcel.piwonski@gmail.com', '$2y$10$zzjei2CuXGrDy8wqhY8A9utnnR7FZjyrzwagTuYT7IxG4e5quu.2i',
        '2025-02-02 21:49:59.724593'),
       (6, 'admin123@gmail.com', '$2y$10$pRzkHY50H.u7od1WGQh4ZOVhoT2VGwuEZk9FilciJUMqfMm8mMgV6',
        '2025-02-02 21:58:29.814497'),
       (7, 'murarz@op.pl', '$2y$10$pDamOviJYtr.e1MrAXa0..2zRDGrLehnj/SQMR.Mo0ShCxuynni.y',
        '2025-02-02 22:01:32.888363');

INSERT INTO users_details (id, id_user, name, surname, phone, instagram)
VALUES (1, 1, 'Jan', 'Kowalski', '188332114', '@kowaloo'),
       (2, 2, 'Maciej', 'Parowski', null, null),
       (3, 3, 'Cecylia', 'Krasińska', '515433223', '@cec_krasinska'),
       (4, 4, 'Karol', 'Dzwon', '351663155', '@dryndryn'),
       (5, 5, 'Marcel', 'Piwoński', '546367733', '@marcelpiwo'),
       (6, 6, 'Janina', 'Kowalska', null, null),
       (7, 7, 'Kacper', 'Murasik', null, null);

INSERT INTO users_roles (id_user, id_role)
VALUES (1, 1),
       (2, 1),
       (3, 1),
       (4, 1),
       (5, 1),
       (6, 1),
       (7, 1);

INSERT INTO users_roles (id_user, id_role)
VALUES (6, 2);


INSERT INTO bans (id_user, reason, banned_at)
VALUES (7, 'Spam', '2025-02-02 22:24:05.440605');


INSERT INTO users_cards (id, id_user, id_card, card_type, quantity)
VALUES (1, 1, 1, 'trade', 2),
       (2, 1, 2, 'trade', 1),
       (3, 1, 3, 'trade', 3),
       (4, 1, 4, 'trade', 1),
       (5, 1, 5, 'trade', 1),
       (6, 1, 6, 'wishlist', 1),
       (7, 1, 7, 'wishlist', 1),
       (8, 1, 8, 'wishlist', 1),
       (9, 2, 9, 'trade', 3),
       (10, 2, 10, 'trade', 1),
       (11, 2, 11, 'trade', 5),
       (12, 2, 12, 'trade', 2),
       (13, 2, 13, 'trade', 1),
       (14, 2, 14, 'trade', 1),
       (15, 2, 15, 'trade', 1),
       (16, 2, 16, 'wishlist', 1),
       (17, 2, 17, 'wishlist', 1),
       (18, 2, 18, 'wishlist', 1),
       (19, 2, 19, 'wishlist', 1),
       (20, 3, 20, 'trade', 2),
       (21, 3, 21, 'trade', 1),
       (22, 3, 22, 'trade', 3),
       (23, 3, 23, 'trade', 3),
       (24, 3, 24, 'trade', 2),
       (25, 3, 25, 'wishlist', 1),
       (26, 3, 26, 'wishlist', 1),
       (27, 4, 27, 'trade', 4),
       (28, 4, 28, 'trade', 1),
       (29, 4, 29, 'trade', 5),
       (30, 4, 30, 'trade', 1),
       (31, 4, 31, 'trade', 5),
       (32, 4, 32, 'wishlist', 1),
       (33, 4, 33, 'trade', 3),
       (34, 4, 34, 'trade', 1),
       (35, 4, 35, 'trade', 3),
       (36, 4, 36, 'wishlist', 1),
       (37, 5, 27, 'trade', 3),
       (41, 5, 40, 'trade', 1),
       (42, 5, 41, 'trade', 1),
       (43, 5, 42, 'trade', 1),
       (44, 5, 43, 'trade', 1),
       (45, 5, 44, 'trade', 1),
       (46, 5, 45, 'trade', 3),
       (47, 5, 46, 'wishlist', 1);
