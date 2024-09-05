vendor - http://localhost/CarAppoinment/vendor
------------------------------------------------------------------------------------------


CREATE DATABASE IF NOT EXISTS carservice;
===========================================


CREATE TABLE vendor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    shop_name VARCHAR(100) NOT NULL,
    shop_id VARCHAR(50) NOT NULL UNIQUE,
    location VARCHAR(100) NOT NULL,
    shop_photo VARCHAR(255),
    password VARCHAR(255) NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'
);

---------------------------------------------------------------------------------------------

CREATE TABLE service_list (
    shop_id INT NOT NULL,
    service_name VARCHAR(255) NOT NULL,
    service_type VARCHAR(100) NOT NULL,
    service_price DECIMAL(10, 2) NOT NULL,
    number_days INT NOT NULL,
    service_description TEXT,
    service_photo VARCHAR(255) 
);

ALTER TABLE service_list ADD COLUMN service_id INT AUTO_INCREMENT PRIMARY KEY;

----------------------------------------------------------------------------------------------