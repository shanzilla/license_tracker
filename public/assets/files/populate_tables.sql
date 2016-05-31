-- Shannon Lenise Fitzgerald
-- CS750 Cybersecurity
-- May 4, 2016
-- Database: 'license_tracker'

INSERT INTO cars (car_id, make, model, color, license_plate) VALUES
(1, 'Toyota', 'Prius', 'Gray', 'GBB747'),
(2, 'Honda', 'Civic', 'Red', '636BQI'),
(3, 'Ford', 'Escort', 'Blue', '9AJNJO'),
(4, 'Toyota', 'Corolla', 'Silver', 'BIRX40'),
(5, 'Fiat', '500', 'Gray', '6WD928'),
(6, 'Kia', 'Rio', 'Black', 'SDA98S');

INSERT INTO incidents (incident_id, car_id, status, description) VALUES
(1, 1, 'Ran checkpoint', ''),
(2, 2, 'Ran checkpoint', ''),
(3, 3, 'Insufficient funds', ''),
(4, 4, 'Ran checkpoint', ''),
(5, 5, 'Stolen car', 'Checkpoint officer noted stolen plate');
(6, 6, 'Ran checkpoint', '');

INSERT INTO users (user_id, username, password, email, first_name, last_name, privilege_level, create_time) VALUES
(1, 'sfitzgerald', '$2y$10$MjQ3MTM5MWM1NDI4MWI1YeK3x', 'shannon@licensetrack.com', 'Shannon', 'Fitzgerald', 3, '2016-05-01 01:54:27'),
(2, 'angozi', '$2y$10$NDYzNTE5MDY4YzI2ZGU5YOsGC', 'ayo@licensetrack.com', 'Ayo', 'Ngozi', 1, '2016-05-01 01:54:27'),
(3, 'hwelsch', '$2y$10$MDA5MzcyYTllNTc5MjAzYexov', 'harriet@licensetrack.com', 'Harriet', 'Welsch', 1, '2016-05-01 01:54:27'),
(4, 'kdougherty', '$2y$10$ZmEzMGY1NzJjOTA1MGNhMe6M.', 'kerri@licensetrack.com', 'Kerri', 'Dougherty', 2, '2016-05-01 01:54:27');
