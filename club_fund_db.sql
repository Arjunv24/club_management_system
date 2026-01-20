

USE club_fund_db;

-- Members Table
CREATE TABLE members (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100),
  phone VARCHAR(15),
  email VARCHAR(100),
  status ENUM('active', 'inactive') DEFAULT 'active'
);

-- Collections Table
CREATE TABLE collections (
  id INT PRIMARY KEY AUTO_INCREMENT,
  month VARCHAR(20),
  amount DECIMAL(10,2),
  due_date DATE
);

-- Payments Table
CREATE TABLE payments (
  id INT PRIMARY KEY AUTO_INCREMENT,
  member_id INT,
  collection_id INT,
  amount_paid DECIMAL(10,2),
  date_paid DATE,
  FOREIGN KEY (member_id) REFERENCES members(id),
  FOREIGN KEY (collection_id) REFERENCES collections(id)
);

-- Admins Table
CREATE TABLE admins (
  id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(50),
  password VARCHAR(255)
);
