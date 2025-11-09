-- OLMS Database Schema + Sample Data
CREATE DATABASE IF NOT EXISTS olms_db;
USE olms_db;
CREATE TABLE IF NOT EXISTS admin (
  id INT AUTO_INCREMENT PRIMARY KEY,
  UserName VARCHAR(100) NOT NULL,
  Password VARCHAR(255) NOT NULL
);
CREATE TABLE IF NOT EXISTS tblemployees (
  id INT AUTO_INCREMENT PRIMARY KEY,
  FullName VARCHAR(100) NOT NULL,
  Email VARCHAR(100) UNIQUE NOT NULL,
  Password VARCHAR(255) NOT NULL,
  RegDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS tblleavetype (
  id INT AUTO_INCREMENT PRIMARY KEY,
  LeaveType VARCHAR(100) NOT NULL,
  Description TEXT,
  CreationDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS tblleaves (
  id INT AUTO_INCREMENT PRIMARY KEY,
  empid INT NOT NULL,
  LeaveType VARCHAR(100) NOT NULL,
  FromDate DATE NOT NULL,
  ToDate DATE NOT NULL,
  Description TEXT,
  Status VARCHAR(20) DEFAULT 'Pending',
  CreationDate DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (empid) REFERENCES tblemployees(id) ON DELETE CASCADE
);
INSERT INTO admin (UserName, Password)
VALUES ('admin', MD5('admin123'))
ON DUPLICATE KEY UPDATE Password = VALUES(Password);
INSERT INTO tblemployees (FullName, Email, Password)
VALUES ('Udhaya Kumar', 'udhaya@example.com', MD5('12345'))
ON DUPLICATE KEY UPDATE Password = VALUES(Password);
INSERT INTO tblleavetype (LeaveType, Description)
VALUES ('Casual Leave', 'For general personal reasons'),
       ('Medical Leave', 'For health-related issues'),
       ('Paid Leave', 'Leave with salary benefits')
ON DUPLICATE KEY UPDATE Description = VALUES(Description);
INSERT INTO tblleaves (empid, LeaveType, FromDate, ToDate, Description, Status)
VALUES (1, 'Casual Leave', '2025-11-10', '2025-11-12', 'Family function', 'Pending'),
       (1, 'Medical Leave', '2025-11-13', '2025-11-14', 'Fever', 'Approved');
