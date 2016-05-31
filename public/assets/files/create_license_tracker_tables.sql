-- Shannon Lenise Fitzgerald
-- CS750 Cybersecurity
-- May 4, 2016
-- Database: 'license_tracker'

-- Table: 'cars'

DROP TABLE IF EXISTS cars;
CREATE TABLE IF NOT EXISTS cars (
  car_id int(11) NOT NULL AUTO_INCREMENT,
  make varchar(50) NOT NULL,
  model varchar(50) NOT NULL,
  color varchar(20) NOT NULL,
  license_plate varchar(7) NOT NULL,
  PRIMARY KEY (car_id)
);

-- Table: 'incidents'

DROP TABLE IF EXISTS incidents;
CREATE TABLE IF NOT EXISTS incidents (
  incident_id int(11) NOT NULL AUTO_INCREMENT,
  car_id int(11) NOT NULL,
  status varchar(30) NOT NULL,
  description text,
  PRIMARY KEY (incident_id),
  KEY fk_incidents_cars (car_id)
);

-- Table: 'users'

DROP TABLE IF EXISTS users;
CREATE TABLE IF NOT EXISTS users (
  user_id int(11) NOT NULL AUTO_INCREMENT,
  username varchar(16) NOT NULL,
  password varchar(32) NOT NULL,
  email varchar(255) NOT NULL,
  first_name varchar(45) NOT NULL,
  last_name varchar(45) DEFAULT NULL,
  privilege_level int(3) DEFAULT '1',
  create_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id),
  UNIQUE KEY username (username),
  UNIQUE KEY email (email)
);