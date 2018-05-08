DROP DATABASE MHZ;

CREATE DATABASE IF NOT EXISTS MHZ CHARACTER SET = utf8 COLLATE utf8_general_ci;

USE MHZ;

CREATE TABLE accounts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name CHAR(20) NOT NULL,
    last_name CHAR(20) NOT NULL,
    nick_name VARCHAR(40),
    email CHAR(50) UNIQUE NOT NULL,
    password CHAR(255) NOT NULL,
    picture TEXT,
    marital_status CHAR(20),
    data_of_birth TIMESTAMP,
    gender CHAR(10),
    about TEXT,
    hometown VARCHAR(50)
);

CREATE TABLE phones (
    account_id INT,
    phone CHAR(15),
    FOREIGN KEY (account_id) REFERENCES accounts(id)
);


CREATE TABLE requests (
    send_id INT,
    received_id INT,
    status INT(2),     -- 1 request | 2 friend
    sent_at TIMESTAMP NOT NULL,
    PRIMARY KEY (send_id, received_id),
    FOREIGN KEY (send_id) REFERENCES accounts(id),
    FOREIGN KEY (received_id) REFERENCES accounts(id)
);

CREATE TABLE messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    message TEXT NOT NULL,
    sent_at TIMESTAMP NOT NULL,
    send_id INT,
    received_id INT,
    FOREIGN KEY (send_id) REFERENCES accounts(id),
    FOREIGN KEY (received_id) REFERENCES accounts(id)
);

CREATE TABLE posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    caption TEXT,
    image TEXT,
    status INT(2) NOT NULL,     -- 3 public | 2 private | 1 only me
    created_at TIMESTAMP NOT NULL,
    account_id INT,
    FOREIGN KEY (account_id) REFERENCES accounts(id)
);

CREATE TABLE comments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    body TEXT,
    created_at TIMESTAMP NOT NULL,
    account_id INT,
    post_id INT,
    FOREIGN KEY (account_id) REFERENCES accounts(id),
    FOREIGN KEY (post_id) REFERENCES posts(id)
);

CREATE TABLE likes (
    account_id INT,
    post_id INT,
    FOREIGN KEY (account_id) REFERENCES accounts(id),
    FOREIGN KEY (post_id) REFERENCES posts(id)
);
