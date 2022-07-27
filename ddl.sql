CREATE TABLE Circuit_1(
    city CHAR[15] PRIMARY KEY,
    time_zone CHAR[15],
);

CREATE TABLE Circuit_2(
    city CHAR[15],
    name CHAR[15] PRIMARY KEY,
    country CHAR[15]
    longitude REAL,
    latitude REAL
);

CREATE TABLE RacesTakePlace(
    date  DATE PRIMARY KEY, 
    name  CHAR[15], 
    round INTEGER, 
    lap_numbers integer, 
    circuit_name CHAR[15],
    FOREIGN KEY(circuit_name) REFERENCES Circuit_2
);

CREATE TABLE Constructors(
    name CHAR[15] PRIMARY KEY,
    nationality CHAR[15],
    city CHAR[15]
);

CREATE TABLE Sponsorship(
    name CHAR[50] PRIMARY KEY
);

CREATE TABLE Sponsor(
    constructor_name CHAR[15],
    sponsorship_name CHAR[15]
    PRIMARY KEY(constructor_name,sponsorship_name),
    FOREIGN KEY(constructor_name) REFERENCES Constructors,
    FOREIGN KEY(sponsorship_name) REFERENCES Sponsorship
);

CREATE TABLE EmployTeamMembers(
    first_name CHAR[15],
    last_name CHAR[15],
    date_of_birth DATE,
    nationality CHAR[15],
    constructor_name CHAR[15],
    PRIMARY KEY(first_name,last_name,date_of_birth),
    FOREIGN KEY(constructor_name) REFERENCES Constructors
);

CREATE TABLE RacingDrivers(
    first_name CHAR[15],
    last_name CHAR[15],
    date_of_birth DATE,
    driver_id INTEGER,
    PRIMARY KEY(first_name,last_name,date_of_birth),
    FOREIGN KEY(first_name,last_name,date_of_birth) REFERENCES TeamPrinciples
);

CREATE TABLE TeamPrinciples(
    first_name CHAR[15],
    last_name CHAR[15],
    date_of_birth DATE,  
    duration CHAR[15],
    PRIMARY KEY(first_name,last_name,date_of_birth),
    FOREIGN KEY(first_name,last_name,date_of_birth) REFERENCES TeamPrinciples
);

CREATE TABLE Drive(
    racingcar_name CHAR[15],
    racingdriver_dob DATE,
    racingdriver_firstname CHAR[15],
    racingdriver_lastname CHAR[15],
    PRIMARY KEY(racingcar_name, racingdriver_dob,racingdriver_firstname, racingdriver_lastname),
    FOREIGN KEY(racingcar_name) REFERENCES RaceingCars,
    FOREIGN KEY(racingdriver_dob, racingdriver_firstname, racingdriver_lastname) REFERENCES RacingDrivers
);

CREATE TABLE OwnCars(
    car_name CHAR[15] PRIMARY KEY,
    constructor_name CHAR[15],
    engine CHAR[15],
    FOREIGN KEY(constructor_name) REFERENCES Constructors
);

CREATE TABLE RacingCars(
    name CHAR PRIMARY KEY
);

CREATE TABLE DriveSafetyCars(
    safetycar_name CHAR PRIMARY KEY,
    safetycar_driver CHAR UNIQUE NOT NULL,
    brand_name CHAR,
    FOREIGN KEY(safetycar_name) REFERENCES OwnCars(car_name) ON DELETE CASCADE,
    FOREIGN KEY(safetycar_driver) REFERENCES 
    SafetyCarDriver(name) ON DELETE CASCADE
);

CREATE TABLE HaveResults1(
    time TIME PRIMARY KEY,
    rank INTEGER,
);

CREATE TABLE HaveResults2(
    result_id INTEGER,
    race_date DATE,
    grid_position INTEGER,
    time TIME,
    status CHAR,
    PRIMARY KEY(result_id,race_date),
    FOREIGN KEY(race_date) REFERENCES Races(date) ON DELETE CASCADE    
);

CREATE TABLE OfficialStaff(
    name CHAR(30) PRIMARY KEY
);

CREATE TABLE SafetyCarDriver(
    name CHAR(30) PRIMARY KEY
);

CREATE TABLE President(
    name CHAR(30) PRIMARY KEY,
    start_date date,
    FOREIGN KEY(name) REFERENCES OfficialStaff(name) ON DELETE CASCADE 
);

CREATE TABLE Workfor(
    officialstaff_name CHAR(30),
    race_date DATE,
    PRIMARY KEY(officialstaff_name,race_date),
    FOREIGN KEY(officialstaff_name) REFERENCES 
    OfficialStaff(name) ON DELETE CASCADE,
    FOREIGN KEY(race_date) REFERENCES Races(date) ON DELETE CASCADE
);

CREATE TABLE Broadcasters(
    name CHAR(30) PRIMARY KEY
);

CREATE TABLE Broadcast(
    race_date DATE,
    broadcasters_name CHAR(30),
    PRIMARY KEY(race_date, broadcasters_name),
    FOREIGN KEY(race_date) REFERENCES Races(date) ON DELETE CASCADE,
    FOREIGN KEY(broadcasters_name) REFERENCES 
    Broadcasters(name) ON DELETE CASCADE
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
    FOREIGN KEY(racingcar_name) REFERENCES Racingcars(name) ON DELETE CASCADE,
    FOREIGN KEY(racingdriver_dob,racingdriver_firstname,
    racingdriver_lastname) REFERENCES Racingdrivers(date_of_birth, 
    first_name, last_name) ON DELETE CASCADE
);


