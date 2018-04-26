DROP DATABASE MGZ;

CREATE DATABASE IF NOT EXISTS MGZ CHARACTER SET = utf8 COLLATE utf8_general_ci;

USE MGZ;

CREATE TABLE accounts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name CHAR(20) NOT NULL,
    last_name CHAR(20) NOT NULL,
    nick_name VARCHAR(40),
    email CHAR(50) NOT NULL,
    password CHAR(255) NOT NULL,
    photo VARCHAR(100),
    marital_status CHAR(20),
    data_of_birth DATETIME,
    gender CHAR(10),
    about TEXT,
    hometown VARCHAR(50)
);

CREATE TABLE phones (
    account_id INT,
    phone CHAR(15),
    FOREIGN KEY (account_id) REFERENCES accounts(id)
);

CREATE TABLE friends (
    account_id INT,
    frined_id INT,
    FOREIGN KEY (account_id) REFERENCES accounts(id),
    FOREIGN KEY (frined_id) REFERENCES accounts(id)
);

CREATE TABLE requests (
    send_id INT,
    received_id INT,
    sent_at DATETIME NOT NULL,
    PRIMARY KEY (send_id, received_id),
    FOREIGN KEY (send_id) REFERENCES accounts(id),
    FOREIGN KEY (received_id) REFERENCES accounts(id)
);

CREATE TABLE messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    message TEXT NOT NULL,
    sent_at DATETIME NOT NULL,
    send_id INT,
    received_id INT,
    FOREIGN KEY (send_id) REFERENCES accounts(id),
    FOREIGN KEY (received_id) REFERENCES accounts(id)
);

CREATE TABLE posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    caption TEXT,
    image VARCHAR(100),
    status BOOLEAN NOT NULL,
    created_at DATETIME NOT NULL,
    account_id INT,
    FOREIGN KEY (account_id) REFERENCES accounts(id)
);

CREATE TABLE comments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    body TEXT,
    created_at DATETIME NOT NULL,
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
