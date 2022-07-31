CREATE TABLE Circuit_1(
    city CHAR(15) PRIMARY KEY,
    time_zone CHAR(15)
);

CREATE TABLE Circuit_2(
    city CHAR(15),
    circuit_name CHAR(50) PRIMARY KEY,
    country CHAR(15)
    longitude REAL,
    latitude REAL
);

CREATE TABLE RacesTakePlace(
    race_date  DATE PRIMARY KEY, 
    race_name  CHAR(15), 
    round_number INTEGER, 
    lap_numbers INTEGER, 
    circuit_name CHAR(50) NOT NULL,
    FOREIGN KEY(circuit_name) REFERENCES Circuit_2
);

CREATE TABLE Constructors(
    constructors_name CHAR(15) PRIMARY KEY,
    nationality CHAR(15),
    city CHAR(15)
);

CREATE TABLE Sponsorship(
    sponsorship_name CHAR(50) PRIMARY KEY
);

CREATE TABLE Sponsor(
    constructor_name CHAR(15),
    sponsorship_name CHAR(50),
    PRIMARY KEY(constructor_name,sponsorship_name),
    FOREIGN KEY(constructor_name) REFERENCES Constructors,
    FOREIGN KEY(sponsorship_name) REFERENCES Sponsorship
);

CREATE TABLE EmployTeamMembers(
    first_name CHAR(15),
    last_name CHAR(15),
    date_of_birth DATE,
    nationality CHAR(15),
    constructor_name CHAR(15) NOT NULL,
    PRIMARY KEY(first_name,last_name,date_of_birth),
    FOREIGN KEY(constructor_name) REFERENCES Constructors
);

CREATE TABLE RacingDrivers(
    first_name CHAR(15),
    last_name CHAR(15),
    date_of_birth DATE,
    driver_id INTEGER,
    PRIMARY KEY(first_name,last_name,date_of_birth),
    FOREIGN KEY(first_name,last_name,date_of_birth) REFERENCES EmployTeamMembers(first_name,last_name,date_of_birth)
);

CREATE TABLE TeamPrinciples(
    first_name CHAR(15),
    last_name CHAR(15),
    date_of_birth DATE,  
    duration CHAR(15),
    PRIMARY KEY(first_name,last_name,date_of_birth),
    FOREIGN KEY(first_name,last_name,date_of_birth) REFERENCES EmployTeamMembers(first_name,last_name,date_of_birth)
);

CREATE TABLE Drive(
    racingcar_name CHAR(15),
    racingdriver_dob DATE,
    racingdriver_firstname CHAR(15),
    racingdriver_lastname CHAR(15),
    PRIMARY KEY(racingcar_name, racingdriver_dob,racingdriver_firstname, racingdriver_lastname),
    FOREIGN KEY(racingcar_name) REFERENCES RaceingCars,
    FOREIGN KEY(racingdriver_dob, racingdriver_firstname, racingdriver_lastname) REFERENCES RacingDrivers(date_of_birth,first_name,last_name) 
);

CREATE TABLE OwnCars(
    car_name CHAR(15) PRIMARY KEY,
    constructor_name CHAR(15) NOT NULL, 
    engine CHAR(50),
    FOREIGN KEY(constructor_name) REFERENCES Constructors 
);

CREATE TABLE RacingCars(
    racingcar_name CHAR PRIMARY KEY
);

CREATE TABLE DriveSafetyCars(
    safetycar_name CHAR(30) PRIMARY KEY,
    safetycar_driver CHAR(30) NOT NULL,
    brand_name CHAR (30),
    FOREIGN KEY(safetycar_name) REFERENCES OwnCars(car_name) ON DELETE CASCADE,
    FOREIGN KEY(safetycar_driver) REFERENCES 
    SafetyCarDriver(safetycardricer_name) ON DELETE CASCADE
);

CREATE TABLE HaveResults1(
    result_time TIME PRIMARY KEY,
    race_rank INTEGER
);

CREATE TABLE HaveResults2(
    result_id INTEGER,
    race_date DATE,
    grid_position INTEGER,
    result_time TIME,
    race_status CHAR(30),
    PRIMARY KEY(result_id,race_date),
    FOREIGN KEY(race_date) REFERENCES RacesTakePlace(race_date) ON DELETE CASCADE    
);

CREATE TABLE OfficialStaff(
    officialstaff_name CHAR(30) PRIMARY KEY
);

CREATE TABLE SafetyCarDriver(
    safetycardriver_name CHAR(30) PRIMARY KEY
);

CREATE TABLE President(
    president_name CHAR(30) PRIMARY KEY,
    duration_start_date DATE,
    FOREIGN KEY(president_name) REFERENCES OfficialStaff ON DELETE CASCADE 
);

CREATE TABLE WorkFor(
    officialstaff_name CHAR(30),
    race_date DATE,
    PRIMARY KEY(officialstaff_name, race_date),
    FOREIGN KEY(officialstaff_name) REFERENCES 
    OfficialStaff  ON DELETE CASCADE,
    FOREIGN KEY(race_date) REFERENCES RacesTakePlace(race_date) ON DELETE CASCADE
);

CREATE TABLE Broadcasters(
    broadcasters_name CHAR(30) PRIMARY KEY
);

CREATE TABLE Broadcast(
    race_date DATE,
    broadcasters_name CHAR(30),
    PRIMARY KEY(race_date, broadcasters_name),
    FOREIGN KEY(race_date) REFERENCES RacesTakePlace(race_date) ON DELETE CASCADE,
    FOREIGN KEY(broadcasters_name) REFERENCES 
    Broadcasters ON DELETE CASCADE
);

CREATE TABLE Participate(
    race_date DATE,
    racingcar_name CHAR(30),
    racingdriver_dob DATE,
    racingdriver_firstname CHAR(20),
    racingdriver_lastname CHAR(20),
    PRIMARY KEY(race_date, racingcar_name, racingdriver_dob, 
    racingdriver_firstname, racingdriver_lastname),
    FOREIGN KEY(race_date) REFERENCES RacesTakePlace(race_date) ON DELETE CASCADE,
    FOREIGN KEY(racingcar_name) REFERENCES Racingcars ON DELETE CASCADE,
    FOREIGN KEY(racingdriver_dob,racingdriver_firstname,
    racingdriver_lastname) REFERENCES Racingdrivers(date_of_birth, 
    first_name, last_name) ON DELETE CASCADE
);

-- circuit1
INSERT INTO Circuit_1 VALUES ('Silverstone', 'GMT+1');
INSERT INTO Circuit_1 VALUES ('Kuala Lumpur', 'GMT+8');
INSERT INTO Circuit_1 VALUES ('Montreal', 'GMT-4');
INSERT INTO Circuit_1 VALUES ('Sakhir', 'GMT+4');
INSERT INTO Circuit_1 VALUES ('Abu Dhabi', 'GMT+4');

-- circuit2
INSERT INTO Circuit_2 VALUES ('Silverstone', 'Silverstone Circuit', 'UK', -1.01694, 52.0786);
INSERT INTO Circuit_2 VALUES ('Kuala Lumpur', 'Sepang International Circuit', 'Malaysia', 101.738, 2.76083);
INSERT INTO Circuit_2 VALUES ('Montreal', 'Circuit Gilles Villeneuve', 'Canada', 45.5, 45.5);
INSERT INTO Circuit_2 VALUES ('Sakhir', 'Bahrain International Circuit', 'Bahrain', 50.5106, 26.0325);
INSERT INTO Circuit_2 VALUES ('Abu Dhabi', 'Yes Marina Circuit', 'UAE', 54.6031, 24.4672);

-- RacesTakePlace
INSERT INTO RacesTakePlace VALUES(2008-07-6, 'British Grand Prix', 9, 52, 'Silverstone Circuit');
INSERT INTO RacesTakePlace VALUES(2009-04-5, 'Malaysian Grand Prix', 2, 56, 'Sepang International Circuit');
INSERT INTO RacesTakePlace VALUES(2008-06-8, 'Canadian Grand Prix', 7, 70, 'Circuit Gilles Villeneuve');
INSERT INTO RacesTakePlace VALUES(2009-04-26, 'Bahrain Grand Prix', 4, 57, 'Bahrain International Circuit');
INSERT INTO RacesTakePlace VALUES(2009-11-1, 'Abu Dhabi Grand Prix', 17, 55, 'Yes Marina Circuit');

-- Constructors
INSERT INTO Constructors VALUES('Spyker', 'Dutch', 'Zeewolde');
INSERT INTO Constructors VALUES('Toyota', 'Japanese', 'Toyota');
INSERT INTO Constructors VALUES('Boro', 'Dutch', 'Bovenkerk');
INSERT INTO Constructors VALUES('Jordan', 'Irish', 'Silverstone');
INSERT INTO Constructors VALUES('Sauber', 'Swiss', 'Hinwil');

-- Sponsorship
INSERT INTO Sponsorship VALUES('McGregor Fashion Group');
INSERT INTO Sponsorship VALUES('Panasonic');
INSERT INTO Sponsorship VALUES('HB Bewaking');
INSERT INTO Sponsorship VALUES('Ferrari');
INSERT INTO Sponsorship VALUES('Alfa Romeo');

-- Sponsor
INSERT INTO Sponsor VALUES('Spyker', 'McGregor Fashion Group');
INSERT INTO Sponsor VALUES('Toyota', 'Panasonic');
INSERT INTO Sponsor VALUES('Boro', 'HB Bewaking');
INSERT INTO Sponsor VALUES('Jordan', 'Ferrari');
INSERT INTO Sponsor VALUES('Sauber', 'Alfa Romeo');

--EmployTeamMembers
INSERT INTO EmployTeamMembers VALUES('Colin','Kolles',1967-12-13,'German');
INSERT INTO EmployTeamMembers VALUES('Tadashi','Yamashina',1951-05-08,'Japanese');
INSERT INTO EmployTeamMembers VALUES('Bob','Hoogenboom',1949-05-06,'Netherlands');
INSERT INTO EmployTeamMembers VALUES('James', 'Steve', 1973-05-23, 'UK');
INSERT INTO EmployTeamMembers VALUES('Jobs','Bond',1957-08-24,'United State');
INSERT INTO EmployTeamMembers VALUES('Mark','Webber',1997-04-05,'Austrilian');
INSERT INTO EmployTeamMembers VALUES('James','Speed', 1993-05-23, 'United States');
INSERT INTO EmployTeamMembers VALUES('Shinji','Nakano',1985-09-07,'Japanese');
INSERT INTO EmployTeamMembers VALUES('Martin','Brundle',1994-07-25,'German');
INSERT INTO EmployTeamMembers VALUES('Aguri', 'Suzuki', 1995-06-07,'Japanese');

--RacingDrivers
INSERT INTO RacingDrivers VALUES ('Mark','Webber',1997-04-05,1);
INSERT INTO RacingDrivers VALUES ('James','Speed',1993-05-23,2);
INSERT INTO RacingDrivers VALUES ('Shinji','Nakano',1985-09-07,3);
INSERT INTO RacingDrivers VALUES ('Martin','Brundle',1994-07-25,4);
INSERT INTO RacingDrivers VALUES ('Aguri','Suzuki',1995-06-07,5);

--TeamPrinciples
INSERT INTO TeamPrinciples VALUES ('Colin','Kolles',1967-12-13,'2005-2008');
INSERT INTO TeamPrinciples VALUES ('Tadashi','Yamashina',1951-05-08,'2012-2016');
INSERT INTO TeamPrinciples VALUES ('Bob','Hoogenboom',1949-05-06,'2013-2014');
INSERT INTO TeamPrinciples VALUES ('James','Steve',1973-05-23,'2008-2009');
INSERT INTO TeamPrinciples VALUES ('Jobs','Bond',1957-08-24,'2010-2012');

--Drive
INSERT INTO Drive VALUES ('F8-VII',1997-04-05,'Mark','Webber');
INSERT INTO Drive VALUES ('TF102',1993-05-23,'James','Speed');
INSERT INTO Drive VALUES ('A522',1985-09-07,'Shinji','Nakano');
INSERT INTO Drive VALUES ('AT03',1994-07-25,'Martin','Brundle');
INSERT INTO Drive VALUES ('AMR22',1995-06-07,'Aguri','Suzuki');

--OwnCars
INSERT INTO OwnCars VALUES ('F8-VII','Spyker','Ferrar-056');
INSERT INTO OwnCars VALUES ('TF102','Toyota','Toyota RVX-02');
INSERT INTO OwnCars VALUES ('A522','Boro','Ford');
INSERT INTO OwnCars VALUES ('AT03','Jordan','Red Bull');
INSERT INTO OwnCars VALUES ('AMR22','Sauber','Mercedes-AMG');
INSERT INTO OwnCars VALUES ('Porsche 914','Porsche','Volkswagen Type 4 F4');
INSERT INTO OwnCars VALUES ('Porsche 911','Porsche','Porsche flat-six engine');
INSERT INTO OwnCars VALUES ('Lamborghini Countach','Lamborghini','Lamborghini V12 LP400');
INSERT INTO OwnCars VALUES ('Ford Escort RS Cosworth','Fort','Cosworth YBT');
INSERT INTO OwnCars VALUES ('Mercedes-AMG GT','Mercedes','M178');

--RacingCars
INSERT INTO RacingCars VALUES ('VF-22');
INSERT INTO RacingCars VALUES ('TF-102');
INSERT INTO RacingCars VALUES ('A522');
INSERT INTO RacingCars VALUES ('AT03');
INSERT INTO RacingCars VALUES ('AMR22');

--DriverSafetyCars
INSERT INTO DriverSafetyCars VALUES ('Porsche 914','Bernd Maylander','Porsche');
INSERT INTO DriverSafetyCars VALUES ('Porsche 911','Bernd Maylander','Porsche');
INSERT INTO DriverSafetyCars VALUES ('Lamborghini Countach','Bernd Maylander','Lamborghini');
INSERT INTO DriverSafetyCars VALUES ('Ford Escort RS Cosworth','Bernd Maylander','Fort');
INSERT INTO DriverSafetyCars VALUES ('Mercedes-AMG GT','Bernd Maylander','Mercedes');

--HaveResult1
INSERT INTO HaveResults1 VALUES(00:31:18:06.876,3);
INSERT INTO HaveResults1 VALUES(00:38:45:03.023,9);
INSERT INTO HaveResults1 VALUES(00:45:33:12.391,13);
INSERT INTO HaveResults1 VALUES(00:46:46:05.486,15);
INSERT INTO HaveResults1 VALUES(00:32:29:33.828,4);

-- HaveResults2
INSERT INTO HaveResults2 VALUES (1, 2008-07-06, 5, 00:31:18:06.876, 'completed');
INSERT INTO HaveResults2 VALUES (2, 2009-04-05, 4, 00:38:45:03.023, 'completed');
INSERT INTO HaveResults2 VALUES (3, 2008-06-08, 3, 00:45:33:12.391, 'accident');
INSERT INTO HaveResults2 VALUES (4, 2009-04-05, 2, 00:46:46:05.486, 'collision');
INSERT INTO HaveResults2 VALUES (5, 2009-11-01, 4, 00:32:29:33.828, 'completed');

-- OfficialStaff
INSERT INTO OfficialStaff VALUES ('Stefano Domenicali');
INSERT INTO OfficialStaff VALUES ('Chase Carey');
INSERT INTO OfficialStaff VALUES ('Ross Brawn');
INSERT INTO OfficialStaff VALUES ('Duncan Llowarch');
INSERT INTO OfficialStaff VALUES ('Sacha Woodward Hill');
INSERT INTO OfficialStaff VALUES ('Bernd Maylander');
INSERT INTO OfficialStaff VALUES ('Eppie Wietzes');
INSERT INTO OfficialStaff VALUES ('Mark Goddard');
INSERT INTO OfficialStaff VALUES ('Max Angelelli');
INSERT INTO OfficialStaff VALUES ('Oliver Gavin');

-- SafetyCarDriver
INSERT INTO SafetyCarDriver VALUES ('Bernd Maylander');
INSERT INTO SafetyCarDriver VALUES ('Eppie Wietzes');
INSERT INTO SafetyCarDriver VALUES ('Mark Goddard');
INSERT INTO SafetyCarDriver VALUES ('Max Angelelli');
INSERT INTO SafetyCarDriver VALUES ('Oliver Gavin');

-- President
INSERT INTO President VALUES ('Stefano Domenicali', 2021-01-12);
INSERT INTO President VALUES ('Chase Carey', 2014-04-05);
INSERT INTO President VALUES ('Ross Brawn', 2019-11-02);
INSERT INTO President VALUES ('Duncan Llowarch', 2017-09-01);
INSERT INTO President VALUES ('Sacha Woodward Hill', 2020-01-12);

-- WorkFor
INSERT INTO WorkFor VALUES ('Bernd Maylander', 2008-07-06);
INSERT INTO WorkFor VALUES ('Eppie Wietzes', 2009-04-05);
INSERT INTO WorkFor VALUES ('Mark Goddard', 2008-06-08);
INSERT INTO WorkFor VALUES ('Max Angelelli', 2009-04-26);
INSERT INTO WorkFor VALUES ('Oliver Gavin', 2009-11-01);

-- Broadcasters
INSERT INTO Broadcasters VALUES ('ESPN');
INSERT INTO Broadcasters VALUES ('Fox Sports');
INSERT INTO Broadcasters VALUES ('BBC');
INSERT INTO Broadcasters VALUES ('ABC');
INSERT INTO Broadcasters VALUES ('CCTV');

-- Broadcast
INSERT INTO Broadcast VALUES (2008-07-06, 'BBC');
INSERT INTO Broadcast VALUES (2009-04-05, 'ABC');
INSERT INTO Broadcast VALUES (2008-06-08, 'ABC');
INSERT INTO Broadcast VALUES (2009-04-26, 'CCTV');
INSERT INTO Broadcast VALUES (2009-11-01, 'ESPN');

-- Participate
INSERT INTO Participate VALUES (2008-07-06, 'VF-22', 1997-04-05, 'Mark', 'Webber');
INSERT INTO Participate VALUES (2009-04-05, 'TF-102', 1993-05-23, 'James', 'Speed');
INSERT INTO Participate VALUES (2008-06-08, 'A522', 1985-09-07, 'Shinji', 'Nakano');
INSERT INTO Participate VALUES (2009-04-26, 'AT03', 1994-07-25, 'Martin', 'Brundle');
INSERT INTO Participate VALUES (2009-11-01, 'AMR22', 1995-06-07, 'Aguri', 'Suzuki');