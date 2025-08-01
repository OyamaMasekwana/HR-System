CREATE DATABASE company_hr;
USE company_hr;
CREATE TABLE Department (
    departmentId INT PRIMARY KEY AUTO_INCREMENT,
    departmentName VARCHAR(255) UNIQUE NOT NULL
);

CREATE TABLE users (
	id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    passwordHash VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL
);

CREATE TABLE Employee (
    employeeId INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    position VARCHAR(255),
    salary DECIMAL(10, 2),
    employmentHistory TEXT,
    contact VARCHAR(255),
    departmentId INT,
    department VARCHAR(255),
    FOREIGN KEY (departmentId) REFERENCES Department(departmentId)
);

CREATE TABLE Attendance (
    attendanceId INT PRIMARY KEY AUTO_INCREMENT,
    employeeId INT NOT NULL,
    attendanceDate DATE NOT NULL,
    status VARCHAR(50) NOT NULL, -- e.g., 'Present', 'Absent'
    FOREIGN KEY (employeeId) REFERENCES Employee(employeeId)
);

CREATE TABLE LeaveType (
    leaveTypeId INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    maxDays INT NOT NULL
);

CREATE TABLE LeaveRequest (
    leaveId INT PRIMARY KEY AUTO_INCREMENT,
    employeeId INT,
    leaveTypeId INT NOT NULL,
    leaveDate DATE NOT NULL,
    endDate DATE NOT NULL,
    reason TEXT NOT NULL,
    daysRequested INT NOT NULL,
    status ENUM('Pending', 'Approved', 'Denied') DEFAULT 'Pending',
    dateRequested DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employeeId) REFERENCES Employee(employeeId),
    FOREIGN KEY (leaveTypeId) REFERENCES LeaveType(leaveTypeId)
);

CREATE TABLE Payroll (
    payrollId INT PRIMARY KEY AUTO_INCREMENT,
    employeeId INT NOT NULL,
    payPeriod DATE NOT NULL,
    basicSalary DECIMAL(10, 2) NOT NULL,
    allowances DECIMAL(10, 2) DEFAULT 0,
    deductions DECIMAL(10, 2) DEFAULT 0,
    netPay DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (employeeId) REFERENCES Employee(employeeId)
);

CREATE TABLE PerformanceReviews (
    reviewId INT PRIMARY KEY AUTO_INCREMENT,
    employeeId INT NOT NULL,
    reviewDate DATE NOT NULL,
    workQuality INT NOT NULL CHECK (workQuality BETWEEN 20 AND 30),
    Communication INT NOT NULL CHECK (Communication BETWEEN 15 AND 30),
    teamwork INT NOT NULL CHECK (teamwork BETWEEN 15 AND 30),
    overallScore DECIMAL(10,1),
    FOREIGN KEY (employeeId) REFERENCES Employee(employeeId)
);



ALTER TABLE Employee
ADD COLUMN dob DATE,
ADD COLUMN avgHours DECIMAL(4,1),
ADD COLUMN teamwork INT DEFAULT 0;

INSERT INTO Department (departmentName)
VALUES  ('Development'),
        ('HR'),
        ('QA'),
        ('Sales'),
        ('Marketing'),
        ('Design'),
        ('IT'),
        ('Finance'),
        ('Support');

INSERT INTO Employee (employeeId, name, position, salary, employmentHistory, contact, departmentId,department)
VALUES  (1, 'Sibongile Nkosi', 'Software Engineer', 70000.00, 'Joined in 2015, promoted to Senior in 2018', 'sibongile.nkosi@moderntech.com', (SELECT departmentId FROM Department WHERE departmentName = 'Development'), 'Development'),
        (2, 'Lungile Moyo', 'HR Manager', 80000.00, 'Joined in 2013, promoted to Manager in 2017', 'lungile.moyo@moderntech.com', (SELECT departmentId FROM Department WHERE departmentName = 'HR'), 'HR'),
        (3, 'Thabo Molefe', 'Quality Analyst', 55000.00, 'Joined in 2018', 'thabo.molefe@moderntech.com', (SELECT departmentId FROM Department WHERE departmentName = 'QA'),'QA'),
        (4, 'Keshav Naidoo', 'Sales Representative', 60000.00, 'Joined in 2020', 'keshav.naidoo@moderntech.com', (SELECT departmentId FROM Department WHERE departmentName = 'Sales'),'Sales'),
        (5, 'Zanele Khumalo', 'Marketing Specialist', 58000.00, 'Joined in 2019', 'zanele.khumalo@moderntech.com', (SELECT departmentId FROM Department WHERE departmentName = 'Marketing'),'Marketing'),
        (6, 'Sipho Zulu', 'UI/UX Designer', 65000.00, 'Joined in 2016', 'sipho.zulu@moderntech.com', (SELECT departmentId FROM Department WHERE departmentName = 'Design'),'Design'),
        (7, 'Naledi Moeketsi', 'DevOps Engineer', 72000.00, 'Joined in 2017', 'naledi.moeketsi@moderntech.com', (SELECT departmentId FROM Department WHERE departmentName = 'IT'),'IT'),
        (8, 'Farai Gumbo', 'Content Strategist', 56000.00, 'Joined in 2021', 'farai.gumbo@moderntech.com', (SELECT departmentId FROM Department WHERE departmentName = 'Marketing'),'Marketing'),
        (9, 'Karabo Dlamini', 'Accountant', 62000.00, 'Joined in 2018', 'karabo.dlamini@moderntech.com', (SELECT departmentId FROM Department WHERE departmentName = 'Finance'),'Finance'),
        (10, 'Fatima Patel', 'Customer Support Lead', 58000.00, 'Joined in 2016', 'fatima.patel@moderntech.com', (SELECT departmentId FROM Department WHERE departmentName = 'Support'),'Support');

INSERT INTO Attendance (employeeId, attendanceDate, status)
VALUES  (1, '2025-07-25', 'Present'),
        (1, '2025-07-26', 'Absent'),
        (1, '2025-07-27', 'Present'),
        (1, '2025-07-28', 'Present'),
        (1, '2025-07-29', 'Present'),
        (2, '2025-07-25', 'Present'),
        (2, '2025-07-26', 'Present'),
        (2, '2025-07-27', 'Absent'),
        (2, '2025-07-28', 'Present'),
        (2, '2025-07-29', 'Present'),
        (3, '2025-07-25', 'Present'),
        (3, '2025-07-26', 'Present'),
        (3, '2025-07-27', 'Present'),
        (3, '2025-07-28', 'Absent'),
        (3, '2025-07-29', 'Present'),
        (4, '2025-07-25', 'Absent'),
        (4, '2025-07-26', 'Present'),
        (4, '2025-07-27', 'Present'),
        (4, '2025-07-28', 'Present'),
        (4, '2025-07-29', 'Present'),
        (5, '2025-07-25', 'Present'),
        (5, '2025-07-26', 'Present'),
        (5, '2025-07-27', 'Absent'),
        (5, '2025-07-28', 'Present'),
        (5, '2025-07-29', 'Present'),
        (6, '2025-07-25', 'Present'),
        (6, '2025-07-26', 'Present'),
        (6, '2025-07-27', 'Absent'),
        (6, '2025-07-28', 'Present'),
        (6, '2025-07-29', 'Present'),
        (7, '2025-07-25', 'Present'),
        (7, '2025-07-26', 'Present'),
        (7, '2025-07-27', 'Present'),
        (7, '2025-07-28', 'Absent'),
        (7, '2025-07-29', 'Present'),
        (8, '2025-07-25', 'Present'),
        (8, '2025-07-26', 'Absent'),
        (8, '2025-07-27', 'Present'),
        (8, '2025-07-28', 'Present'),
        (8, '2025-07-29', 'Present'),
        (9, '2025-07-25', 'Present'),
        (9, '2025-07-26', 'Present'),
        (9, '2025-07-27', 'Present'),
        (9, '2025-07-28', 'Absent'),
        (9, '2025-07-29', 'Present'),
        (10, '2025-07-25', 'Present'),
        (10, '2025-07-26', 'Present'),
        (10, '2025-07-27', 'Absent'),
        (10, '2025-07-28', 'Present'),
        (10, '2025-07-29', 'Present');
        
        
INSERT INTO LeaveType (name, description, maxDays)
VALUES  ('Annual Leave', 'Paid time off work', 21),
        ('Sick Leave', 'Leave for health reasons', 10),
        ('Maternity Leave', 'Leave for new parents', 90),
        ('Unpaid Leave', 'Leave without pay', 30);
        
INSERT INTO LeaveRequest(employeeId, leaveTypeId, leaveDate, endDate, reason, daysRequested, status, dateRequested)
VALUES  (1, 1, '2025-07-22', '2025-07-31', 'Vacation', 10, 'Pending', '2025-07-01'),
		(1, 2, '2024-12-01', '2024-12-03', 'Flu', 3, 'Approved', '2024-11-25'),
		(2, 4, '2025-07-15', '2025-07-17', 'Personal time off', 3, 'Denied', '2025-07-01'),
		(2, 2, '2024-12-02', '2024-12-04', 'Doctor appointment', 3, 'Approved', '2024-11-28'),
		(3, 2, '2025-07-10', '2025-07-12', 'Cold/Flu', 3, 'Approved', '2025-07-05'),
		(3, 1, '2024-12-05', '2024-12-15', 'Family holiday', 11, 'Denied', '2024-11-20'),
		(4, 2, '2025-07-20', '2025-07-21', 'Doctor appointment', 2, 'Approved', '2025-07-15'),
		(5, 2, '2024-12-01', '2024-12-03', 'Stomach ache', 3, 'Approved', '2024-11-25'),
		(6, 2, '2025-07-18', '2025-07-20', 'Medical recovery', 3, 'Approved', '2025-07-10'),
		(7, 1, '2025-07-22', '2025-07-31', 'Family responsibility', 10, 'Pending', '2025-07-01'),
		(8, 4, '2024-12-02', '2024-12-30', 'Family vacation', 29, 'Approved', '2024-11-15'),
		(9, 2, '2025-07-19', '2025-07-21', 'Dental surgery', 3, 'Pending', '2025-07-10'),
		(10, 2, '2024-12-03', '2024-12-13', 'Family responsibility', 11, 'Denied', '2024-11-20');
        
ALTER TABLE users
ADD COLUMN employeeId INT,
ADD FOREIGN KEY (employeeId) REFERENCES Employee(employeeId);

INSERT INTO PerformanceReviews (employeeId, reviewDate, workQuality, Communication, teamwork, overallScore)
VALUES  -- Sibongile Nkosi 
		(1, '2025-06-15', 28, 27, 26, ROUND((28 + 27 + 26) / 3, 1)),
		(1, '2024-12-10', 26, 28, 25, ROUND((26 + 28 + 25) / 3, 1)),
		
		-- Lungile Moyo 
		(2, '2025-06-20', 29, 30, 28, ROUND((29 + 30 + 28) / 3, 1)),
		(2, '2024-12-15', 28, 29, 27, ROUND((28 + 29 + 27) / 3, 1)),
		
		-- Thabo Molefe 
		(3, '2025-06-10', 25, 24, 23, ROUND((25 + 24 + 23) / 3, 1)),
		(3, '2024-12-05', 24, 25, 22, ROUND((24 + 25 + 22) / 3, 1)),
		
		-- Keshav Naidoo 
		(4, '2025-06-18', 27, 26, 25, ROUND((27 + 26 + 25) / 3, 1)),
		(4, '2024-12-12', 26, 25, 24, ROUND((26 + 25 + 24) / 3, 1)),
		
		-- Zanele Khumalo 
		(5, '2025-06-12', 26, 27, 25, ROUND((26 + 27 + 25) / 3, 1)),
		(5, '2024-12-08', 25, 26, 24, ROUND((25 + 26 + 24) / 3, 1)),
		
		-- Sipho Zulu 
		(6, '2025-06-22', 28, 27, 29, ROUND((28 + 27 + 29) / 3, 1)),
		(6, '2024-12-18', 27, 26, 28, ROUND((27 + 26 + 28) / 3, 1)),
		
		-- Naledi Moeketsi 
		(7, '2025-06-25', 30, 28, 27, ROUND((30 + 28 + 27) / 3, 1)),
		(7, '2024-12-20', 29, 27, 26, ROUND((29 + 27 + 26) / 3, 1)),
		
		-- Farai Gumbo 
		(8, '2025-06-08', 24, 25, 23, ROUND((24 + 25 + 23) / 3, 1)),
		(8, '2024-12-03', 23, 24, 22, ROUND((23 + 24 + 22) / 3, 1)),
		
		-- Karabo Dlamini 
		(9, '2025-06-30', 27, 28, 26, ROUND((27 + 28 + 26) / 3, 1)),
		(9, '2024-12-25', 26, 27, 25, ROUND((26 + 27 + 25) / 3, 1)),
		
		-- Fatima Patel 
		(10, '2025-06-05', 29, 30, 28, ROUND((29 + 30 + 28) / 3, 1)),
		(10, '2024-11-30', 28, 29, 27, ROUND((28 + 29 + 27) / 3, 1));

        
        






