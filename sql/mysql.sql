CREATE TABLE ann_annonces (
    lid         INT(11)      NOT NULL AUTO_INCREMENT,
    cid         INT(11)      NOT NULL DEFAULT '0',
    title       VARCHAR(100) NOT NULL DEFAULT '',
    type        VARCHAR(100) NOT NULL DEFAULT '',
    description TEXT         NOT NULL,
    tel         VARCHAR(15)  NOT NULL DEFAULT '',
    price       VARCHAR(100) NOT NULL DEFAULT '',
    typeprix    VARCHAR(100) NOT NULL DEFAULT '',
    date        VARCHAR(25)           DEFAULT NULL,
    email       VARCHAR(100) NOT NULL DEFAULT '',
    submitter   VARCHAR(60)  NOT NULL DEFAULT '',
    usid        VARCHAR(6)   NOT NULL DEFAULT '',
    town        VARCHAR(200) NOT NULL DEFAULT '',
    country     VARCHAR(200) NOT NULL DEFAULT '',
    valid       VARCHAR(11)  NOT NULL DEFAULT '',
    photo       VARCHAR(100) NOT NULL DEFAULT '',
    view        VARCHAR(10)  NOT NULL DEFAULT '0',
    PRIMARY KEY (lid)
)
    ENGINE = ISAM;

CREATE TABLE ann_categories (
    cid     INT(11)         NOT NULL AUTO_INCREMENT,
    pid     INT(5) UNSIGNED NOT NULL DEFAULT '0',
    title   VARCHAR(50)     NOT NULL DEFAULT '',
    img     VARCHAR(150)    NOT NULL DEFAULT '',
    ordre   INT(5)          NOT NULL DEFAULT '0',
    affprix INT(5)          NOT NULL DEFAULT '0',
    PRIMARY KEY (cid)
)
    ENGINE = ISAM;

CREATE TABLE ann_type (
    id_type  INT(11)      NOT NULL AUTO_INCREMENT,
    nom_type VARCHAR(150) NOT NULL DEFAULT '',
    PRIMARY KEY (id_type)
)
    ENGINE = ISAM;


INSERT INTO ann_type
VALUES (1, 'for sale');
INSERT INTO ann_type
VALUES (2, 'for exchange');
INSERT INTO ann_type
VALUES (3, 'search');
INSERT INTO ann_type
VALUES (4, 'for lend');


CREATE TABLE ann_prix (
    id_prix  INT(11)      NOT NULL AUTO_INCREMENT,
    nom_prix VARCHAR(150) NOT NULL DEFAULT '',
    PRIMARY KEY (id_prix)
)
    ENGINE = ISAM;


INSERT INTO ann_prix
VALUES (1, 'exact price');
INSERT INTO ann_prix
VALUES (2, 'a day');
INSERT INTO ann_prix
VALUES (3, 'a week');
INSERT INTO ann_prix
VALUES (4, 'a quarter');
INSERT INTO ann_prix
VALUES (5, 'a month');
INSERT INTO ann_prix
VALUES (6, 'a year');
INSERT INTO ann_prix
VALUES (7, 'to discuss');
INSERT INTO ann_prix
VALUES (8, 'maximum');
INSERT INTO ann_prix
VALUES (9, 'minimum');
