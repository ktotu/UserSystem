CREATE TABLE IF NOT EXISTS passwords
(
    name_hash TEXT NOT NULL PRIMARY KEY,
    password  TEXT NOT NULL
);