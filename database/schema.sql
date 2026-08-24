CREATE DATABASE IF NOT EXISTS rao_hbmis
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE rao_hbmis;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS allocations;
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS rooms;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'warden', 'staff') NOT NULL DEFAULT 'staff',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB;

CREATE TABLE students (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    student_id VARCHAR(30) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    gender ENUM('Male', 'Female') NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    course VARCHAR(100) NOT NULL,
    year_of_study TINYINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_students_student_id (student_id),
    UNIQUE KEY uq_students_email (email)
) ENGINE=InnoDB;

CREATE TABLE rooms (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    room_number VARCHAR(20) NOT NULL,
    room_type ENUM('Single', 'Double', 'Triple', 'Shared') NOT NULL,
    capacity TINYINT UNSIGNED NOT NULL,
    floor SMALLINT UNSIGNED NOT NULL,
    gender ENUM('Male', 'Female') NOT NULL,
    status ENUM('Available', 'Occupied', 'Maintenance') NOT NULL DEFAULT 'Available',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_rooms_room_number (room_number)
) ENGINE=InnoDB;

CREATE TABLE bookings (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    student_id INT UNSIGNED NOT NULL,
    room_id INT UNSIGNED NOT NULL,
    booking_date DATE NOT NULL,
    check_in_date DATE NOT NULL,
    status ENUM('Pending', 'Confirmed', 'Cancelled') NOT NULL DEFAULT 'Pending',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_bookings_student (student_id),
    KEY idx_bookings_room (room_id),
    KEY idx_bookings_status (status),
    CONSTRAINT fk_bookings_student FOREIGN KEY (student_id) REFERENCES students (id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_bookings_room FOREIGN KEY (room_id) REFERENCES rooms (id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE allocations (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    student_id INT UNSIGNED NOT NULL,
    room_id INT UNSIGNED NOT NULL,
    allocation_date DATE NOT NULL,
    check_in_date DATE NOT NULL,
    check_out_date DATE NOT NULL,
    status ENUM('Active', 'Completed', 'Cancelled') NOT NULL DEFAULT 'Active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_allocations_student (student_id),
    KEY idx_allocations_room_status (room_id, status),
    CONSTRAINT fk_allocations_student FOREIGN KEY (student_id) REFERENCES students (id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_allocations_room FOREIGN KEY (room_id) REFERENCES rooms (id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE payments (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    student_id INT UNSIGNED NOT NULL,
    amount DECIMAL(12, 2) NOT NULL,
    payment_date DATE NOT NULL,
    payment_method ENUM('Cash', 'M-Pesa', 'Bank', 'Card') NOT NULL,
    reference_number VARCHAR(50) NOT NULL,
    status ENUM('Pending', 'Completed', 'Failed', 'Cancelled') NOT NULL DEFAULT 'Pending',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_payments_reference (reference_number),
    KEY idx_payments_student (student_id),
    KEY idx_payments_status (status),
    CONSTRAINT fk_payments_student FOREIGN KEY (student_id) REFERENCES students (id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

INSERT INTO users (full_name, email, password, role)
VALUES (
    'System Administrator',
    'admin@rao-hbmis.local',
    '$2y$10$Q0Q8u7CkUacDXkw45V9cjO3AGkNfyohMhkMCYI0r.q.OOmTjDUQh2',
    'admin'
);
