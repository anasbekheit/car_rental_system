INSERT INTO `car` (`plate_id`, `car_status`, `color`, `model_year`, `car_model`, `car_manufacturer`, `country`, `price_per_day`) VALUES
('1111-AAAA', 'active', 'RED', 2021, 'Enzo', 'Ferrari', 'Italy', 1200.00),
('2222-AAAA', 'active', 'BLACK', 2015, '520i', 'BMW', 'Germany', 400.00),
('3333-AAAA', 'active', 'WHITE', 1980, 'BRUH', 'LADA', 'RUSSIA', 50.00);

INSERT INTO `customer` ( `email`, `customer_pass`, `fname`, `lname`, `country`, `credit_card`) VALUES
( 'anas@gmail.com', '202cb962ac59075b964b07152d234b70', 'Anas', 'Ahmed', 'EG', '123'),
('amir@gmail.com', '202cb962ac59075b964b07152d234b70', 'Amir', 'Ayman', 'EG', '123'),
('hazem@gmail.com', '202cb962ac59075b964b07152d234b70', 'Hazem', 'Hashem', 'EG', '123'),
('menna@gmail.com', '202cb962ac59075b964b07152d234b70', 'Menna', 'Ibrahim', 'EG', '123');

INSERT INTO `reservation` (`plate_id`, `customer_id`, `pickup_time`, `return_time`) VALUES
('3333-AAAA', 1, '2022-01-05', '2022-01-09');
