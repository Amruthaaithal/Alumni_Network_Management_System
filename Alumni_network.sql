-- ALUMNI Table
CREATE TABLE ALUMNI (
    AlumniID VARCHAR(50),
    FirstName VARCHAR(50),
    LastName VARCHAR(50),
    Password VARCHAR(255),
    Company VARCHAR(100),
    Degree VARCHAR(50),
    GraduationYear YEAR,
    CurrentJob VARCHAR(100),
    SocialLinks TEXT
);

-- COORDINATORS Table
CREATE TABLE COORDINATORS (
    CoordinatorID INT PRIMARY KEY AUTO_INCREMENT,
    AlumniID VARCHAR(50),
    EventID INT
    FOREIGN KEY (AlumniID) REFERENCES ALUMNI(AlumniID)
    FOREIGN KEY (EventID) REFERENCES EVENTS(EventID)
);

-- EVENTS Table
CREATE TABLE EVENTS (
    EventID INT PRIMARY KEY AUTO_INCREMENT,
    EventName VARCHAR(100),
    EventType VARCHAR(50),
    EventDescription TEXT,
    Date DATE,
    Time TIME,
    Location VARCHAR(100),
    OrganiserID VARCHAR(50),
    FOREIGN KEY (OrganiserID) REFERENCES ALUMNI(AlumniID)
);

-- JOB_POSTINGS Table
CREATE TABLE JOB_POSTINGS (
    JobID INT PRIMARY KEY AUTO_INCREMENT,
    Title VARCHAR(100),
    Description TEXT,
    Company VARCHAR(100),
    Location VARCHAR(100),
    Requirements TEXT,
    PostedBy VARCHAR(50),
    PostedDate DATE,
    Deadline DATE,
    FOREIGN KEY (PostedBy) REFERENCES ALUMNI(AlumniID)
);

-- REGISTRATIONS Table
CREATE TABLE REGISTRATIONS (
    RegistrationID INT PRIMARY KEY AUTO_INCREMENT,
    AlumniID INT,
    EventID INT,
    RegistrationDate DATE,
    FOREIGN KEY (AlumniID) REFERENCES ALUMNI(AlumniID),
    FOREIGN KEY (EventID) REFERENCES EVENTS(EventID)
);


DELIMITER//
CREATE PROCEDURE sp_check_alumni_id(IN p_alumniId VARCHAR(50))
BEGIN
    SELECT COUNT(*) FROM ALUMNI WHERE AlumniId = p_alumniId;
END //
DELIMITER;


DELIMITER //
CREATE PROCEDURE sp_getAlumniCredentials(IN in_alumniID VARCHAR(50))
BEGIN
    SELECT AlumniID, FirstName, Password
    FROM ALUMNI
    WHERE AlumniID = in_alumniID;
END //
DELIMITER ;


DELIMITER //
CREATE PROCEDURE sp_register_alumni( IN 
    p_alumniId VARCHAR(50),
    p_firstName VARCHAR(50),
    p_lastName VARCHAR(50),
    p_password VARCHAR(255),
    p_company VARCHAR(100),
    p_degree VARCHAR(100),
    p_graduationYear INT,
    p_currentJob VARCHAR(100),
    p_socialLinks VARCHAR(255)
)
BEGIN
    INSERT INTO ALUMNI (AlumniId, FirstName, LastName, Password, Company, Degree, GraduationYear, CurrentJob, SocialLinks)
    VALUES (p_alumniId, p_firstName, p_lastName, p_password, p_company, p_degree, p_graduationYear, p_currentJob, p_socialLinks);
END //
DELIMITER;


DELIMITER //
CREATE PROCEDURE sp_updateAlumniPassword(
    IN alumniID VARCHAR(50),
    IN newPassword VARCHAR(255)
)
BEGIN
    UPDATE alumni
    SET Password = newPassword
    WHERE AlumniID = alumniID;
END //

DELIMITER ;


DELIMITER //
CREATE TRIGGER after_event_insert
AFTER INSERT ON EVENTS
FOR EACH ROW
BEGIN
    INSERT INTO COORDINATORS (AlumniID, EventID)
    VALUES (NEW.OrganiserID, NEW.EventID);
END;
//
DELIMITER ;

