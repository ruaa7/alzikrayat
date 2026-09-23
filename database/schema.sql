-- Alzikrayat Photo Sharing Application - Database Schema

CREATE DATABASE IF NOT EXISTS alzikrayat
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE alzikrayat;

-- Table:users

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50)  NOT NULL,
    last_name VARCHAR(50)  NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    location VARCHAR(100) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    occupation VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Table:photos

CREATE TABLE IF NOT EXISTS photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT DEFAULT NULL,
    date_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_photos_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_photos_user_id (user_id),
    INDEX idx_photos_date_time (date_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Table:comments

CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    photo_id INT NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    date_time  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_comments_photo
        FOREIGN KEY (photo_id) REFERENCES photos(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_comments_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_comments_photo_id (photo_id),
    INDEX idx_comments_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Table: photo_tags   
-- join table linking a photo to the users tagged in it by unique key

CREATE TABLE IF NOT EXISTS photo_tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    photo_id  INT NOT NULL,
    user_id INT NOT NULL,
    tagged_by INT NOT NULL,
    date_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_tags_photo
        FOREIGN KEY (photo_id) REFERENCES photos(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_tags_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_tags_tagged_by
        FOREIGN KEY (tagged_by) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY uq_photo_user_tag (photo_id, user_id),
    INDEX idx_tags_photo_id (photo_id),
    INDEX idx_tags_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



