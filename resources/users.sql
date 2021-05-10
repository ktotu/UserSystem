CREATE TABLE IF NOT EXISTS users
(
    name          TEXT NOT NULL PRIMARY KEY,
    display_name  TEXT NOT NULL,
    password_hash TEXT NOT NULL,
    device        TEXT NOT NULL,
    address       TEXT NOT NULL,
    uuid          TEXT NOT NULL,
    xuid          TEXT
);