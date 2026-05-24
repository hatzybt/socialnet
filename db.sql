-- Create the database
CREATE DATABASE IF NOT EXISTS socialnet;
USE socialnet;

-- Create the account table
CREATE TABLE IF NOT EXISTS account (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    fullname VARCHAR(100),
    password VARCHAR(255) NOT NULL,
    description TEXT
);

-- Create the friend table to link accounts
CREATE TABLE IF NOT EXISTS friend (
    account_id INT,
    friend_id INT,
    FOREIGN KEY (account_id) REFERENCES account(Id) ON DELETE CASCADE,
    FOREIGN KEY (friend_id) REFERENCES account(Id) ON DELETE CASCADE
);

-- Insert default test accounts
INSERT INTO account (username, fullname, password, description) VALUES 
('admin', 'System Admin', 'admin123', 'Administrator account');
