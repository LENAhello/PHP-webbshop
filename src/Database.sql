<?php
    function table($res) {
        $header = false;   
        echo "<table border='1'>";
        while($row = $res->fetch_assoc()) {

        if(!$header) {
            ?>
            <thead>
                <tr>
            <?php
                foreach($row as $key => $val) {
                echo "<th>" . $key . "</th>";
                }
            ?>
                </tr>
            </thead>
            <?php
                $header = true;
            }
            echo "<tr>";
            foreach($row as $key => $val) {
                echo "<td>" . $val . "</td>";
                if($key == 'image'){
                    echo '<td> <img src=" ' . $val . '"> </td>';
                }
            }
            echo "</tr>";
        }
        echo "</table>";
    }
    $sql = "SELECT * FROM Products";
    $result = $connection->query($sql);
    table($result);
?>

CREATE DATABASE webbshop;
-- Table products
CREATE TABLE Products(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description VARCHAR(500),
    price DECIMAL(5,2),
    image VARCHAR(500)
);

INSERT INTO Products (name, description, price, image) VALUES
('Burrata', 'Burrata cheese: one of mankind´s highest achievements (and guiltiest pleasures!) in the medium of curds and cream.', 160.5, 'https://saxelbycheese.com/cdn/shop/products/Saxelby_cheeses-111-2_580x.jpg?v=1661359810'), 
('Bayley Hazen Blue', 'This blue cheese is so good it´s almost obscene. Named after the Bayley Hazen road, which winds its way through Vermont´s picturesque northeast kingdom, it is creamy and chocolaty and salty, oh my.', 230.33, 'https://saxelbycheese.c om/cdn/shop/files/Saxelby_cheeses-284-2_580x.jpg?v=1682625530'),
('Cabot Clothbound Cheddar', 'This cheddar cheese has been dubbed addictive by certain devoted fans. A hefty, beautiful, and rustic wheel, Cabot clothbound cheddar is rich and caramelly, speckled with bits of crystalline crunchy goodness.', 200.5, 'https://saxelbycheese.com/cdn/shop/products/Saxelby_cheeses-558-2_580x.jpg?v=1663785632'),
('Nancy´s Camembert', 'A silky, buttery, bloomy rind sheeps milk triple creme cheese that can put your favorite brie or camembert to shame.', 390.99, 'https://saxelbycheese.com/cdn/shop/products/Saxelby_cheeses-130_580x.jpg?v=1661361544'),
('Cabot Clothbound Cheddar', 'This cheddar cheese has been dubbed addictive by certain devoted fans. A hefty, beautiful, and rustic wheel, Cabot clothbound cheddar is rich and caramelly, speckled with bits of crystalline crunchy goodness.', 999.5, 'https://saxelbycheese.com/cdn/shop/products/Saxelby_cheeses-558-2_580x.jpg?v=1663785632');

-- Table customers
CREATE TABLE Customers(
    id INT PRIMARY KEY AUTO_INCREMENT,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    mobilenumber INT NULL,
    adress VARCHAR(200) NOT NULL,
    postcode INT NOT NULL,
    country VARCHAR(200) NULL,
    epost VARCHAR(500) NOT NULL
);

INSERT INTO Customers (firstname, lastname, mobilenumber, adress, postcode, country, epost) VALUES 
('Emma', 'Johnson', 701234567,'123 Oak Street', 11223, 'Borås', 'emma.johnson@example.com'), 
('Oliver', 'Smith',701274517,'456 Elm Avenue', 22334, 'Halmstad', 'oliver.smith@example.com'),
('Lucas', 'Andersson',765374517,' 321 Pine Road', 44337, 'Malmö', 'lucas.andersson@example.com');

-- Table Orders
CREATE TABLE Orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    status ENUM('Ordered', 'Packed', 'Shipped', 'Paid') NOT NULL,
    order_date DATE NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    customer_id INT NULL,
    FOREIGN KEY (customer_id) REFERENCES Customers(id)
);

INSERT INTO Orders (status, order_date, total_amount, customer_id) VALUES
('Packed', '2024-11-23', 999.50, 4);

-- Table Order_lines
CREATE TABLE Order_lines (
   	id INT AUTO_INCREMENT PRIMARY KEY,
    quantity INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES Orders(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (product_id) REFERENCES Products(id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO Order_lines (quantity, amount, order_id, product_id) VALUES
(1, 500.20, 1, 12);

SELECT o.id, ol.quantity, o.total_amount, o.status, o.order_date, c.firstname, c.country
                FROM Orders o
                JOIN Order_lines ol ON o.id = ol.order_id
                JOIN Customers c ON c.id = o.customer_id
                GROUP BY c.firstname, o.id, ol.quantity, o.total_amount, o.status, o.order_date, c.country;

-- UPDATE orders
UPDATE `Orders` SET `total_amount` = '230.33 ' WHERE `Orders`.`id` = 2;

-- UPDATE products
UPDATE `Products` SET `image` = 'https://www.gfifoods.com/media/catalog/product/cache/608c797bf41e8874bcf75172f32fd01b/b/a/bayley_hazen_blue.jpg' WHERE `Products`.`id` = 7;

CREATE TABLE Product_Images (
    id INT PRIMARY KEY AUTO_INCREMENT,
    image_path VARCHAR(255) NOT NULL, 
    alt_text VARCHAR(255),
    product_id INT NOT NULL,
    FOREIGN KEY (product_id) REFERENCES Products(id) ON DELETE CASCADE
);

INSERT INTO Product_Images (product_id, image_path, alt_text)
VALUES 
(1, 'image_path', 'alt_text'),
INSERT INTO Product_Images (product_id, image_path, alt_text)
VALUES 
(7, 'https://www.gfifoods.com/media/catalog/product/cache/608c797bf41e8874bcf75172f32fd01b/b/a/bayley_hazen_blue.jpg', 'This blue cheese is so good it´s almost obscene. Named after the Bayley Hazen road, which winds its way through Vermont´s picturesque northeast kingdom, it is creamy and chocolaty and salty, oh my.');
(9,'https://saxelbycheese.com/cdn/shop/products/Saxelby_cheeses-130_580x.jpg?v=1661361544','A silky, buttery, bloomy rind sheeps milk triple creme cheese that can put your favorite brie or camembert to shame.'),
(10,'https://saxelbycheese.com/cdn/shop/products/Saxelby_cheeses-558-2_580x.jpg?v=1663785632', 'This cheddar cheese has been dubbed addictive by certain devoted fans. A hefty, beautiful, and rustic wheel, Cabot clothbound cheddar is rich and caramelly, speckled with bits of crystalline crunchy goodness.'),
(11,'https://saxelbycheese.com/cdn/shop/files/Saxelby_cheeses-165_580x.jpg?v=1685131358', 'Kunik is a dreamy mold-ripened goats´ milk cheese spiked with fresh Jersey cream in the Adirondack Mountains at Nettle Meadow Farm.'),
(12,'https://saxelbycheese.com/cdn/shop/products/Saxelby_cheeses-077_580x.jpg?v=1666627040', 'A new take on two classic cheeses! Orange cheddar cheese meets blue cheese in an epiphany of complementary colors - and awesome flavor too!');
(6, 'https://saxelbycheese.com/cdn/shop/products/Saxelby_cheeses-111-2_580x.jpg?v=1661359810','Burrata cheese: one of mankind´s highest achievements (and guiltiest pleasures!) in the medium of curds and cream.')


CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY,       
    name VARCHAR(50) NOT NULL,              
    email VARCHAR(255) NOT NULL,   
    password VARCHAR(255) NOT NULL,         
    customer_id INT DEFAULT NULL,           
    type ENUM('admin', 'customer') NOT NULL DEFAULT 'customer',
    FOREIGN KEY (customer_id) REFERENCES Customers(id) ON DELETE SET NULL ON UPDATE CASCADE
);

INSERT INTO Users (name, email, password, customer_id)
VALUES
('Emma Johnson', 'emma.johnson@example.com', 'emma123', 1),
('Oliver Smith', 'oliver.smith@example.com', 'oliver123', 2),
('Lucas Andersson', 'lucas.andersson@example.com', 'lucas123', 3);