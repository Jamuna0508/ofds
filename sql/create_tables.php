CREATE DATABASE IF NOT EXISTS fuel_delivery;

USE fuel_delivery;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin', 'fuel_station', 'delivery_agent') NOT NULL
);

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    fuel_station_id INT,
    delivery_agent_id INT,
    status ENUM('pending', 'assigned', 'delivered', 'cancelled') NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES users(id),
    FOREIGN KEY (fuel_station_id) REFERENCES users(id),
    FOREIGN KEY (delivery_agent_id) REFERENCES users(id)
);
