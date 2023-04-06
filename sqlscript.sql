DROP TABLE Applicant_Has_Education;
DROP TABLE Applicant_Has_Skill;
DROP TABLE Job_Has_Location;
DROP TABLE Requires;
DROP TABLE Applies;
DROP TABLE Employs;
DROP TABLE Education;
DROP TABLE Skill;
DROP TABLE Applicant;
DROP TABLE Branch;
DROP TABLE Location4;
DROP TABLE Location3;
DROP TABLE Location1;
DROP TABLE HiringManager;
DROP TABLE InternshipJob;
DROP TABLE PartTimeJob;
DROP TABLE FullTimeJob;
DROP TABLE Job2;
DROP TABLE Job1;
DROP TABLE Company;



CREATE TABLE Company (
CompanyID INTEGER PRIMARY KEY,
Name VARCHAR(80),
NumberOfEmployees INTEGER
);

CREATE TABLE Job1(
JobID INTEGER PRIMARY KEY,
ApplicationDeadline VARCHAR(20),
Remote VARCHAR(20),
PositionName VARCHAR(40),
StartDate VARCHAR(20),
CompanyID INTEGER NOT NULL,
FOREIGN KEY (CompanyID) REFERENCES Company
ON DELETE CASCADE);

CREATE TABLE Job2(
PositionName VARCHAR(40) PRIMARY KEY,
JobCategory VARCHAR(40)
);

CREATE TABLE FullTimeJob (
JobID INTEGER PRIMARY KEY,
Salary INTEGER,
FOREIGN KEY (JobID) REFERENCES Job1 
ON DELETE CASCADE
);

CREATE TABLE PartTimeJob (
JobID INTEGER PRIMARY KEY,
HoursPerWeek INTEGER,
Wage INTEGER,
FOREIGN KEY (JobID) REFERENCES Job1
ON DELETE CASCADE
);

CREATE TABLE InternshipJob (
JobID INTEGER PRIMARY KEY,
Duration INTEGER,
Wage INTEGER,
FOREIGN KEY (JobID) REFERENCES Job1
ON DELETE CASCADE
);



CREATE TABLE HiringManager (
Email VARCHAR(80) PRIMARY KEY,
PhoneNumber VARCHAR(80),
Name VARCHAR(80)
);

CREATE TABLE Location1 (
Country VARCHAR(40),
PostalCode VARCHAR(20),
Province VARCHAR(80),
PRIMARY KEY (Country, PostalCode)
);

CREATE TABLE Location3 (
Country VARCHAR(40),
PostalCode VARCHAR(20),
City VARCHAR(80),
PRIMARY KEY (Country, PostalCode)
);

CREATE TABLE Location4 (
Country VARCHAR(40),
PostalCode VARCHAR(20),
Addy VARCHAR(80),
PRIMARY KEY (Country, PostalCode, Addy)
);


CREATE TABLE Branch (
BranchName VARCHAR(80),
NumberOfEmployees INTEGER,
CompanyID INTEGER,
Country VARCHAR(40),
Province VARCHAR(20),
City VARCHAR(20),
Addy VARCHAR(80),
PostalCode VARCHAR(20),
PRIMARY KEY (BranchName, CompanyID),
FOREIGN KEY (Country, PostalCode, Addy) REFERENCES Location4,
FOREIGN KEY (Country, PostalCode) REFERENCES Location3,
FOREIGN KEY (Country, PostalCode) REFERENCES Location1,
FOREIGN KEY (CompanyID) REFERENCES Company
ON DELETE CASCADE
);


CREATE TABLE Applicant (
UserID INTEGER PRIMARY KEY,
Email VARCHAR(80) NOT NULL,
Name VARCHAR(80),
Addy VARCHAR(80),
PhoneNumber VARCHAR(30),
UNIQUE (Email)
);

CREATE TABLE Skill (
SkillID INTEGER PRIMARY KEY,
SkillName VARCHAR(80) NOT NULL,
SkillLevel INTEGER NOT NULL
);

CREATE TABLE Education (
NameOfInstitution VARCHAR(80),
Major VARCHAR(40),
Degree VARCHAR(40),
PRIMARY KEY (NameOfInstitution, Major, Degree)
);



CREATE TABLE Employs (
CompanyID INTEGER,
Email VARCHAR(80),
PRIMARY KEY (CompanyID, Email),
FOREIGN KEY (Email) REFERENCES HiringManager,
FOREIGN KEY (CompanyID) REFERENCES Company
ON DELETE CASCADE
);



CREATE TABLE Applies (
Status VARCHAR(80),
DateApplied VARCHAR(20),
UserID INTEGER,
JobID INTEGER,
PRIMARY KEY (UserID, JobID),
FOREIGN KEY (UserID) REFERENCES Applicant,
FOREIGN KEY (JobID) REFERENCES Job1
ON DELETE CASCADE
);

CREATE TABLE Requires (
JobID INTEGER,
SkillID INTEGER,
PRIMARY KEY (JobID, SkillID),
FOREIGN KEY (SkillID) REFERENCES Skill,
FOREIGN KEY (JobID) REFERENCES Job1
ON DELETE CASCADE
);

CREATE TABLE Job_Has_Location (
JobID INTEGER,
Country VARCHAR(80),
Province VARCHAR(80),
City VARCHAR(80),
Addy VARCHAR(80),
PostalCode VARCHAR(20),
PRIMARY KEY (JobID, Country, Province, City, Addy),
FOREIGN KEY (Country, PostalCode) REFERENCES Location1,
FOREIGN KEY (Country, PostalCode) REFERENCES Location3,
FOREIGN KEY (Country, PostalCode, Addy)REFERENCES Location4
);

CREATE TABLE Applicant_Has_Skill (
UserID INTEGER,
SkillID INTEGER,
PRIMARY KEY (UserID, SkillID),
FOREIGN KEY (SkillID) REFERENCES Skill,
FOREIGN KEY (UserID) REFERENCES Applicant
);

CREATE TABLE Applicant_Has_Education (
UserID INTEGER,
NameOfInstitution VARCHAR(80) NOT NULL,
Major VARCHAR(80) NOT NULL,
Degree VARCHAR(80) NOT NULL,
YearStarted INTEGER NOT NULL,
YearGraduated INTEGER,
PRIMARY KEY (UserID, NameOfInstitution, Major, Degree),
FOREIGN KEY (UserID) REFERENCES Applicant,
FOREIGN KEY (NameOfInstitution, Major, Degree) REFERENCES Education
);

INSERT INTO Company VALUES (1, 'Amazon', 1541000);
INSERT INTO Company VALUES (2, 'Aritzia', 6569);
INSERT INTO Company VALUES (3, 'PepsiCo', 315000);
INSERT INTO Company VALUES (4, 'Apple', 164000);
INSERT INTO Company VALUES (5, 'SAP', 111961);
INSERT INTO Company VALUES (6, 'Coke', 10900);
INSERT INTO Company VALUES (7, 'TD', 1456);
INSERT INTO Company VALUES (8, 'UBC', 1200);
INSERT INTO Company VALUES (9, 'Tom Ford', 200);

INSERT INTO Job1 VALUES (1, '2023-05-23', 'no', 'Salesmen', '2023-09-01', 3);
INSERT INTO Job1 VALUES (2, '2023-06-15', 'yes', 'Software Engineer', '2023-09-01', 1);
INSERT INTO Job1 VALUES (3, '2023-05-31', 'yes', 'Marketing Manager', '2023-08-15', 2);
INSERT INTO Job1 VALUES (4, '2023-06-30', 'no', 'Graphic Designer', '2023-09-01', 4);
INSERT INTO Job1 VALUES (5, '2023-06-30', 'yes', 'Customer Support Representative', '2023-08-15', 5);
INSERT INTO Job1 VALUES (6, '2023-06-30', 'no', 'Accountant', '2023-08-01', 1);
INSERT INTO Job1 VALUES (7, '2023-07-15', 'yes', 'Web Developer', '2023-10-01', 2);
INSERT INTO Job1 VALUES (8, '2023-07-31', 'no', 'Human Resources Manager', '2023-09-15', 3);
INSERT INTO Job1 VALUES (9, '2023-08-15', 'yes', 'Social Media Specialist', '2023-11-01', 4);
INSERT INTO Job1 VALUES (10, '2023-09-01', 'no', 'Office Manager', '2023-11-15', 5);
INSERT INTO Job1 VALUES (11, '2023-09-30', 'yes', 'Data Analyst', '2024-01-01', 1);
INSERT INTO Job1 VALUES (12, '2023-10-15', 'no', 'Legal Assistant', '2023-12-01', 2);
INSERT INTO Job1 VALUES (13, '2023-10-31', 'yes', 'Product Manager', '2024-01-15', 3);
INSERT INTO Job1 VALUES (14, '2023-11-15', 'no', 'Sales Representative', '2024-02-01', 4);
INSERT INTO Job1 VALUES (15, '2023-12-01', 'yes', 'Software Developer', '2024-02-01', 5);
INSERT INTO Job1 VALUES (16, '2023-11-21', 'yes', 'Legal Assistantr', '2024-07-01', 6);
INSERT INTO Job1 VALUES (17, '2022-10-21', 'yes', 'Software Developer', '2024-03-04', 6);
INSERT INTO Job1 VALUES (18, '2023-10-31', 'no', 'Software Developer', '2024-03-01', 7);
INSERT INTO Job1 VALUES (19, '2023-11-11', 'no', 'Sales Representative', '2023-03-21', 8);

INSERT INTO Job2 VALUES ('Salesmen', 'Sales');
INSERT INTO Job2 VALUES ('Software Engineer', 'IT');
INSERT INTO Job2 VALUES ('Marketing Manager', 'Marketing');
INSERT INTO Job2 VALUES ('Graphic Designer', 'Design');
INSERT INTO Job2 VALUES ('Customer Support Representative', 'CustomerService');
INSERT INTO Job2 VALUES ('Accountant', 'Finance');

INSERT INTO Job2 VALUES ('Web Developer', 'IT');
INSERT INTO Job2 VALUES ('Human Resources Manager', 'HR');
INSERT INTO Job2 VALUES ('Social Media Specialist', 'Marketing');
INSERT INTO Job2 VALUES ('Office Manager', 'Administration');
INSERT INTO Job2 VALUES ('Data Analyst', 'IT');
INSERT INTO Job2 VALUES ('Legal Assistant', 'Legal');
INSERT INTO Job2 VALUES ('Product Manager', 'Management');
INSERT INTO Job2 VALUES ('Sales Representative', 'Sales');
INSERT INTO Job2 VALUES ('Software Developer', 'IT');

INSERT INTO FullTimeJob VALUES (1, 50000);
INSERT INTO FullTimeJob VALUES (2, 70000);
INSERT INTO FullTimeJob VALUES (3, 50000);
INSERT INTO FullTimeJob VALUES (4, 30000);
INSERT INTO FullTimeJob VALUES (5, 36000);
INSERT INTO FullTimeJob VALUES (16, 38000);
INSERT INTO FullTimeJob VALUES (17, 115000);
INSERT INTO FullTimeJob VALUES (18, 95000);
INSERT INTO FullTimeJob VALUES (19, 36000);

INSERT INTO PartTimeJob VALUES (6, 15, 16.50);
INSERT INTO PartTimeJob VALUES (7, 20, 30);
INSERT INTO PartTimeJob VALUES (8, 20, 35);
INSERT INTO PartTimeJob VALUES (9, 7, 22.75);
INSERT INTO PartTimeJob VALUES (10, 10,19);

INSERT INTO InternshipJob VALUES (11, 8, 17);
INSERT INTO InternshipJob VALUES (12, 12, 20.5);
INSERT INTO InternshipJob VALUES (13, 8, 28);
INSERT INTO InternshipJob VALUES (14, 9, 18.5);
INSERT INTO InternshipJob VALUES (15, 16, 32);


INSERT INTO HiringManager VALUES ('smith@ubc.ca', 7781234567, 'John Smith');
INSERT INTO HiringManager VALUES ('sharon@outlook.com', 1234567890, 'Sharon Lee');
INSERT INTO HiringManager VALUES ('oliver@gmail.com', 6874706123, 'Oliver Jones');
INSERT INTO HiringManager VALUES ('jon@hotmail.com', 42810274208, 'Jon Williams');
INSERT INTO HiringManager VALUES ('emma@yahoo.com', 0138297382, 'Emma Brown');

INSERT INTO Applicant VALUES (1, 'johnsmiths@hotmail.com', 'John Smiths', 
'4621 Flinderation Road', '(569)-898-4344');

INSERT INTO Applicant VALUES (2, 'bob@gmail.com', 'Bob Echo', '1772 New York Avenue', 
'(677)-442-6726');
INSERT INTO Applicant VALUES (3, 'govind@hotmail.com', 'Govind Nuur', '996 Perine Street',
 '(837)-763-6568');
INSERT INTO Applicant VALUES (4, 'lilavati@gmail.com', 'Lilavati Rajani', '3282 Union Street', 
'(707)-207-4888');
INSERT INTO Applicant VALUES (5, 'neoneela@hotmail.com', 'Neo Neela', '4621 Flinderation Road',
'(676) 952-0954');

INSERT INTO Skill VALUES (1, 'Teamwork', 5);
INSERT INTO Skill VALUES (2, 'Communication', 4);
INSERT INTO Skill VALUES (3, 'Problem Solving', 5);
INSERT INTO Skill VALUES (4, 'Leadership', 3);
INSERT INTO Skill VALUES (5, 'Time Management', 4);

INSERT INTO Education VALUES ('University of British Columbia', 'Marketing', 'Bachelor');
INSERT INTO Education VALUES ('Washington State University', 'Chemical Engineering', 'Master');
INSERT INTO Education VALUES ('University of Toronto', 'Cognitive Science', 'Bachelor');
INSERT INTO Education VALUES ('Stanford University', 'Computer Science', 'PHD');
INSERT INTO Education VALUES ('University College London', 'Psychology', 'Doctorate');


INSERT INTO Employs VALUES ('1', 'smith@ubc.ca');
INSERT INTO Employs VALUES ('2', 'sharon@outlook.com');
INSERT INTO Employs VALUES ('3', 'oliver@gmail.com');
INSERT INTO Employs VALUES ('4', 'jon@hotmail.com');
INSERT INTO Employs VALUES ('5', 'emma@yahoo.com');

INSERT INTO Location1 VALUES ('Canada', 'V6C 2G8','British Columbia');
INSERT INTO Location1 VALUES ('Canada', 'V3N 2N7','British Columbia');
INSERT INTO Location1 VALUES ('United States of America', '90001', 'California');
INSERT INTO Location1 VALUES ('Canada', 'T2R 1M4', 'Alberta');
INSERT INTO Location1 VALUES ('Canada', 'N6A 3K7', 'Ontario');

INSERT INTO Location3 VALUES ('Canada', 'V6C 2G8', 'Vancouver');
INSERT INTO Location3 VALUES ('Canada', 'V3N 2N7', 'Burnaby');
INSERT INTO Location3 VALUES ('United States of America', '90001', 'Los Angeles');
INSERT INTO Location3 VALUES ('Canada', 'T2R 1M4', 'Calgary');
INSERT INTO Location3 VALUES ('Canada', 'N6A 3K7', 'London');

INSERT INTO Location4 VALUES ('Canada', 'V6C 2G8', '291 Burrard Street');
INSERT INTO Location4 VALUES ('Canada', 'V3N 2N7', '8098 11th Ave');
INSERT INTO Location4 VALUES ('United States of America', '90001', '6908 S Central Ave');
INSERT INTO Location4 VALUES ('Canada', 'T2R 1M4', '1030 10 Ave SW');
INSERT INTO Location4 VALUES ('Canada', 'N6A 3K7', '1151 Richmond St');


INSERT INTO Branch VALUES ('Vancouver Branch', 50, 3, 'Canada', 'British Columbia', 'Vancouver',
'291 Burrard Street', 'V6C 2G8');
INSERT INTO Branch VALUES ('Burnaby Branch', 35, 1, 'Canada', 'British Columbia', 'Burnaby', 
'8098 11th Ave', 'V3N 2N7');
INSERT INTO Branch VALUES ('LA Branch', 46, 2, 'United States of America', 'California', 
'Los Angeles', '6908 S Central Ave', '90001');
INSERT INTO Branch VALUES ('Calgary Branch', 28, 4, 'Canada', 'Alberta', 'Calgary', 
'1030 10 Ave SW', 'T2R 1M4');
INSERT INTO Branch VALUES ('London Branch', 62, 5, 'Canada', 'Ontario', 'London', 
'1151 Richmond St', 'N6A 3K7');

INSERT INTO Applies VALUES ('Applied', '2023-02-28', 1, 2);
INSERT INTO Applies VALUES ('Applied', '2023-03-01', 2, 3);
INSERT INTO Applies VALUES ('Applied', '2023-02-28', 3, 5);
INSERT INTO Applies VALUES ('Applied', '2023-02-27', 4, 7);
INSERT INTO Applies VALUES ('Applied', '2023-03-01', 5, 12);

INSERT INTO Requires VALUES (1, 2);
INSERT INTO Requires VALUES (2, 3);
INSERT INTO Requires VALUES (3, 1);
INSERT INTO Requires VALUES (4, 5);
INSERT INTO Requires VALUES (5, 4);



INSERT INTO Job_Has_Location VALUES (1, 'Canada', 'British Columbia', 'Vancouver', 
'291 Burrard Street', 'V6C 2G8');
INSERT INTO Job_Has_Location VALUES (2, 'Canada', 'British Columbia', 'Burnaby', '8098 11th Ave',
'V3N 2N7');
INSERT INTO Job_Has_Location VALUES (3, 'United States of America', 'California', 'Los Angeles',
'6908 S Central Ave', '90001');
INSERT INTO Job_Has_Location VALUES (4, 'Canada', 'Alberta', 'Calgary', '1030 10 Ave SW', 'T2R 1M4');
INSERT INTO Job_Has_Location VALUES (5, 'Canada', 'Ontario', 'London', '1151 Richmond St', 'N6A 3K7');

INSERT INTO Applicant_Has_Skill VALUES (1, 1);
INSERT INTO Applicant_Has_Skill VALUES (1, 2);
INSERT INTO Applicant_Has_Skill VALUES (3, 1);
INSERT INTO Applicant_Has_Skill VALUES (4, 5);
INSERT INTO Applicant_Has_Skill VALUES (5, 3);


INSERT INTO Applicant_Has_Education VALUES (1, 'University of British Columbia', 'Marketing',
'Bachelor', 2001, 2005);
INSERT INTO Applicant_Has_Education VALUES (2, 'University of British Columbia', 'Marketing',
'Bachelor', 2004, 2008);
INSERT INTO Applicant_Has_Education VALUES (1, 'University of Toronto', 'Cognitive Science',
'Bachelor', 2016, 2021);
INSERT INTO Applicant_Has_Education VALUES (3, 'Stanford University', 'Computer Science',
'PHD', 2016, 2020);
INSERT INTO Applicant_Has_Education VALUES (3, 'University College London', 'Psychology',
'Doctorate', 2010, 2013);