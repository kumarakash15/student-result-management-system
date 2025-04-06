-- admin table query
CREATE TABLE admin (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    UserName VARCHAR(100) NOT NULL,
    Password VARCHAR(1000) NOT NULL,
    updationDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- class table
CREATE TABLE tblclasses (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    Branch VARCHAR(100) NOT NULL,
    Semester INT(11) NOT NULL
);

-- subject table
CREATE TABLE tblsubject (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    Branch VARCHAR(100) NOT NULL,
    Sem INT(11) NOT NULL,
    SubName VARCHAR(255) NOT NULL,
    SubCode VARCHAR(100) NOT NULL
);

-- student table
CREATE TABLE tblstudents (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    StudentName VARCHAR(255) NOT NULL,
    RollId VARCHAR(100) NOT NULL UNIQUE,
    StudentEmail VARCHAR(255) NOT NULL,
    Gender ENUM('Male', 'Female', 'Other') NOT NULL,
    DOB DATE NOT NULL,
    Branch VARCHAR(100) NOT NULL,
    Semester INT(11) NOT NULL,
    Section VARCHAR(10) NOT NULL,
    RegDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- mark table
CREATE TABLE tblmark (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    StudentName VARCHAR(255) NOT NULL,
    RollId VARCHAR(100) NOT NULL,
    Branch VARCHAR(100) NOT NULL,
    Section VARCHAR(10) NOT NULL,
    DOB DATE NOT NULL,
    Semester INT(11) NOT NULL,
    Gender ENUM('Male', 'Female', 'Other') NOT NULL,
    StudentEmail VARCHAR(255) NOT NULL,
    SubCode VARCHAR(100) NOT NULL,
    SubName VARCHAR(255) NOT NULL,
    FullMark INT(11) NOT NULL DEFAULT 50, 
    Mark INT(11) NOT NULL,
    UNIQUE (RollId, SubCode)  -- Unique combination of RollId and SubCode
);


-- username password
INSERT INTO admin (UserName, Password)
VALUES ('admin', MD5('admin'));
