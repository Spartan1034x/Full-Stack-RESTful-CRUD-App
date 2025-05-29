DROP DATABASE IF EXISTS offroadDB;

CREATE DATABASE offroadDB;

USE offroadDB;

DROP TABLE IF EXISTS dirtbikes;

CREATE TABLE dirtbikes (
    db_id INT(5) AUTO_INCREMENT PRIMARY KEY,
    make VARCHAR(20) NOT NULL,
    model VARCHAR(40) NOT NULL,
    man_year INT DEFAULT 2000,
    size_cc INT,
    price DECIMAL(8,2)
);

INSERT INTO dirtbikes (make, model, man_year, size_cc, price) 
VALUES  
('Honda', 'CR', 2002, 250, 4599.99),
('Yamaha', 'YZ', 2020, 250, 6999.99),
('Kawasaki', 'KX', 2021, 450, 8499.99),
('Suzuki', 'RM-Z', 2019, 450, 7499.99),
('KTM', 'SX-F', 2022, 350, 9599.99),
('Honda', 'CRF', 2018, 450, 8199.99),
('Yamaha', 'WR', 2023, 450, 9899.99),
('Husqvarna', 'FC', 2020, 250, 8699.99),
('Kawasaki', 'KLX', 2021, 300, 5699.99),
('Beta', 'RR', 2022, 390, 9299.99),
('GasGas', 'MC', 2020, 250, 7999.99),
('Honda', 'XR', 2003, 400, 3499.99),
('Yamaha', 'TTR', 2015, 230, 3999.99),
('Suzuki', 'DR-Z', 2019, 125, 3399.99),
('Kawasaki', 'KX', 2022, 250, 7599.99),
('KTM', 'XC', 2021, 300, 9299.99),
('Honda', 'CRF', 2023, 250, 8899.99),
('Yamaha', 'YZ', 2021, 125, 6199.99),
('Suzuki', 'RM', 2017, 250, 6999.99),
('KTM', 'XC', 2021, 450, 9299.99),
('Honda', 'CRF', 2021, 250, 8899.99),
('Yamaha', 'YZ', 2023, 125, 6199.99),
('Suzuki', 'RM', 2011, 250, 6999.99),
('GasGas', 'EC', 2021, 300, 9499.99);
