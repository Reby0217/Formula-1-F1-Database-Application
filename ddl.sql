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
    FOREIGN KEY(constructor_name) REFERENCES Constructors NOT NULL,
    FOREIGN KEY(sponsorship_name) REFERENCES Sponsorship NOT NULL
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
    FOREIGN KEY(racingcar_name) REFERENCES RaceingCars NOT NULL,
    FOREIGN KEY(racingdriver_dob, racingdriver_firstname, racingdriver_lastname) REFERENCES RacingDrivers(date_of_birth,first_name,last_name) NOT NULL
);

CREATE TABLE OwnCars(
    car_name CHAR(15) PRIMARY KEY,
    constructor_name CHAR(15) NOT NULL, 
    engine CHAR(50),
    FOREIGN KEY(constructor_name) REFERENCES Constructors NOT NULL
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
    SafetyCarDriver(safetycardricer_name) NOT NULL ON DELETE CASCADE
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
    FOREIGN KEY(race_date) REFERENCES Race(date) ON DELETE CASCADE,
    FOREIGN KEY(racingcar_name) REFERENCES Racingcars ON DELETE CASCADE,
    FOREIGN KEY(racingdriver_dob,racingdriver_firstname,
    racingdriver_lastname) REFERENCES Racingdrivers(date_of_birth, 
    first_name, last_name) ON DELETE CASCADE
);


