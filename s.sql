CREATE DATABASE lawfirm_db;
USE lawfirm_db;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('client', 'avocat',) NOT NULL,
    address TEXT,
    birthday DATE,
    cin VARCHAR(20),
    photo_url VARCHAR(255)
);

-- Information table (for lawyers)
CREATE TABLE information (
    id_information INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    photo_url VARCHAR(255),
    biography TEXT,
    specialties TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Disponibilite table (availability)
CREATE TABLE disponibilite (
    id_disponibilite INT AUTO_INCREMENT PRIMARY KEY,
    date_debut DATETIME NOT NULL,
    date_fin DATETIME NOT NULL,
    status ENUM('available', 'booked', 'unavailable') DEFAULT 'available',
    id_user INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id)
);

-- Reservation table
CREATE TABLE reservation (
    id_reservation INT AUTO_INCREMENT PRIMARY KEY,
    id_client INT,
    id_avocat INT,
    reservation_date DATETIME NOT NULL,
    status ENUM('pending', 'approved', 'rejected', 'completed') DEFAULT 'pending',
    FOREIGN KEY (id_client) REFERENCES users(id),
    FOREIGN KEY (id_avocat) REFERENCES users(id)
);