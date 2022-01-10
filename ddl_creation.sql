
CREATE DATABASE crs;

CREATE TABLE CAR
(
    plate_id VARCHAR(10),
    car_status VARCHAR(255),
    color VARCHAR(255),
    model_year YEAR,
    car_model VARCHAR(255),
    car_manufacturer varchar(255),
    country VARCHAR(255),
    price_per_day DOUBLE(7,2),
    CONSTRAINT car_PK PRIMARY KEY (plate_id),
    CONSTRAINT car_status_domain CHECK (car_status  IN ('active' , 'out of service'))
);

CREATE TABLE CUSTOMER
(

    customer_id INT AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    customer_pass VARCHAR(255) NOT NULL,
    fname VARCHAR(255) NOT NULL,
    lname VARCHAR(255) NOT NULL,
    country VARCHAR(255)NOT NULL,
    credit_card VARCHAR(16),
    CONSTRAINT customer_PK PRIMARY KEY (customer_id)
);

CREATE TABLE RESERVATION
(
    plate_id VARCHAR(10) NOT NULL,
    reservation_id INT  AUTO_INCREMENT,
    customer_id INT NOT NULL,
    pickup_time DATE NOT NULL,
    return_time DATE NOT NULL,
    reservation_time TIMESTAMP NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL DEFAULT '0' ,
    CONSTRAINT reservation_PK PRIMARY KEY (reservation_id),
    CONSTRAINT RESERVATION_FK1 FOREIGN KEY (plate_id) REFERENCES CAR(plate_id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT RESERVATION_FK2 FOREIGN KEY (customer_id) REFERENCES CUSTOMER(customer_id) ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TRIGGER tg_bi_reservation
    BEFORE INSERT ON reservation
    FOR EACH ROW
    SET NEW.total_amount =
            (
                SELECT CAR.price_per_day * (DATEDIFF(NEW.return_time,NEW.pickup_time))
                FROM car
                WHERE CAR.plate_id = NEW.plate_id
                LIMIT 1
            );


CREATE TRIGGER `tg_bi_reservation_overlap` BEFORE INSERT ON `reservation`
    FOR EACH ROW BEGIN
    IF(EXISTS (SELECT * FROM reservation WHERE (plate_id = NEW.plate_id) AND ( (pickup_time BETWEEN NEW.pickup_time AND NEW.return_time) OR (return_time BETWEEN NEW.pickup_time AND NEW.return_time)) )
        )
    THEN SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'anyMessageToEndUser';

    END IF;
END
