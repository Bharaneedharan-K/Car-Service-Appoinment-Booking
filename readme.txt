Admin - http://localhost/Car-Service-Appoinment-Booking/admin/
Vendor - http://localhost/Car-Service-Appoinment-Booking/vendor/
Customer - http://localhost/Car-Service-Appoinment-Booking/customer/login/login.php
==================================================================================================

CREATE DATABASE IF NOT EXISTS carservice;

==================================================================================================


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

ALTER TABLE vendor
ADD COLUMN gst_no VARCHAR(20) AFTER shop_id,
ADD COLUMN district VARCHAR(100) AFTER location,
ADD COLUMN google_map_location_url VARCHAR(255) AFTER shop_photo;

CREATE TABLE vendor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    shop_name VARCHAR(100) NOT NULL,
    shop_id VARCHAR(50) NOT NULL UNIQUE,
    gst_no VARCHAR(20),
    location VARCHAR(100) NOT NULL,
    district VARCHAR(100),
    shop_photo VARCHAR(255),
    google_map_location_url VARCHAR(255),
    password VARCHAR(255) NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'
);


==================================================================================================

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

==================================================================================================

CREATE TABLE home_service_list (
    service_id INT AUTO_INCREMENT PRIMARY KEY,
    shop_id INT NOT NULL,
    service_name VARCHAR(255) NOT NULL,
    service_price DECIMAL(10, 2) NOT NULL,
    number_days INT NOT NULL,
    service_description TEXT,
    service_photo VARCHAR(255)
);

==================================================================================================

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY, 
    name VARCHAR(100) NOT NULL,            
    username VARCHAR(50) NOT NULL UNIQUE,    
    phone_no VARCHAR(15) NOT NULL UNIQUE,          
    email VARCHAR(100) NOT NULL UNIQUE,     
    address TEXT NOT NULL,            
    password VARCHAR(255) NOT NULL,   
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP 
);
ALTER TABLE users 
ADD COLUMN car_brand VARCHAR(50),
ADD COLUMN car_model VARCHAR(50),
ADD COLUMN district VARCHAR(50);

===================================================================================================