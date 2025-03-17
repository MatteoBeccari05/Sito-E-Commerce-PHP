create database E_Commerce;

CREATE TABLE e_commerce.prodotti (
    id INT PRIMARY KEY,
    name VARCHAR(255),
    description TEXT,
    price DECIMAL(10, 2),
    processor VARCHAR(255),
    display VARCHAR(255),
    storageOptions TEXT,
    images JSON,
    colors JSON
);

INSERT into e_commerce.prodotti (id, name, description, price, processor, display, storageOptions, images, colors)
VALUES 
(1, 'iPhone 15 Pro', 'L’iPhone 15 Pro è un concentrato di innovazione e performance, dotato del potente chip A17 Pro', 1199, 'A17 Pro', 'OLED da 6,1"', '["128GB", "256GB", "512GB"]', '{"bianco": "../images/iphone15pro_white.jpg", "nero": "../images/iphone15pro_black.jpg"}', '["Bianco", "Nero"]'),
(2, 'iPhone 16', 'L’iPhone 16 è un dispositivo all’avanguardia con il nuovo chip A18', 979, 'A18', 'OLED da 6,1"', '["128GB", "256GB", "512GB"]', '{"bianco": "../images/iphone16_white.jpg", "blu": "../images/iphone16_blu.jpg", "verde": "../images/iphone16_green.jpg", "viola": "../images/iphone16_viola.jpg"}', '["Bianco", "Blu", "Verde", "Viola"]'),
(4, 'iPhone 16 Plus', 'L’iPhone 16 Plus è il compagno perfetto per chi cerca un display OLED da 6,7"', 1029, 'A18', 'OLED da 6,7"', '["128GB", "256GB", "512GB"]', '{"nero": "../images/iphone16plus_black.jpg", "rosa": "../images/iphone16plus_rosa.jpg", "verde": "../images/iphone16plus_green.jpg"}', '["Nero", "Rosa", "Verde"]'),
(5, 'iPhone 15 Pro Max', 'L\'iPhone 15 Pro Max è il modello top di gamma', 1269, 'A17', 'OLED da 6,7"', '["128GB", "256GB", "512GB"]', '{"nero": "../images/iphone15promax_black.jpg", "bianco": "../images/iphone15promax_white.jpg"}', '["Nero", "Bianco"]'),
(9, 'iPhone 16 Pro', 'L’iPhone 16 Pro rappresenta una nuova era per gli smartphone', 1299, 'A18 Pro', 'OLED da 6,1"', '["128GB", "256GB", "512GB"]', '{"nero": "../images/iphone16pro_black.jpg", "bianco": "../images/iphone16pro_white.jpg"}', '["Nero", "Bianco"]'),
(10, 'iPhone 16 Pro Max', 'L\'iPhone 16 Pro Max è il top della gamma', 1499, 'A18', 'OLED da 6,7"', '["128GB", "256GB", "512GB", "1TB"]', '{"nero": "../images/iphone16promax_black.jpg", "oro": "../images/iphone16promax_oro.jpg"}', '["Nero", "Oro"]'),
(11, 'iPhone 15', 'L’iPhone 15 è il dispositivo perfetto per chi cerca un equilibrio', 799, 'A16 Bionic', 'OLED da 6,1"', '["128GB", "256GB", "512GB"]', '{"bianco": "../images/iphone15_white.jpg", "nero": "../images/iphone15_black.jpg", "giallo": "../images/iphone15_yellow.jpg"}', '["Bianco", "Nero", "Giallo"]'),
(21, 'Galaxy S25', 'Il Galaxy S25 è dotato di un potente processore Exynos 2200', 899, 'Exynos 2200', 'Dynamic AMOLED 2X da 6,1"', '["128GB", "256GB", "512GB"]', '{"blu": "../images/S25_blue.jpg", "verde": "../images/S25_green.jpg", "grigio": "../images/S25_grigio.jpg"}', '["Blu", "Verde", "Grigio"]'),
(22, 'Galaxy S25 Plus', 'Il Galaxy S25 Plus offre un display Dynamic AMOLED 2X da 6,7"', 1049, 'Exynos 2200', 'Dynamic AMOLED 2X da 6,7"', '["128GB", "256GB", "512GB"]', '{"grigio": "../images/S25plus_grigio.jpg", "blu": "../images/S25plus_blue.jpg", "verde": "../images/S25plus_green.jpg"}', '["Grigio", "Blu", "Verde"]'),
(23, 'Galaxy S25 Ultra', 'Il Galaxy S25 Ultra è il modello di punta', 1499, 'Exynos 2200', 'Dynamic AMOLED 2X da 6,8"', '["128GB", "256GB", "512GB", "1TB"]', '{"nero": "../images/S25ultra_black.jpg"}', '["Nero"]'),
(24, 'Galaxy S24', 'Il Galaxy S24 è dotato di un potente processore Exynos 2200', 649, 'Exynos 2200', 'Display Dynamic AMOLED 2X da 6,1"', '["128GB", "256GB", "512GB"]', '{"nero": "../images/S24_black.jpg", "viola": "../images/S24_viola.jpg"}', '["Nero", "Viola"]'),
(25, 'Galaxy S24 Plus', 'Il Galaxy S24 Plus offre un display Dynamic AMOLED 2X da 6,7"', 799, 'Exynos 2200', 'Display Dynamic AMOLED 2X da 6,7"', '["128GB", "256GB", "512GB"]', '{"nero": "../images/S24plus_black.jpg", "viola": "../images/S24plus_viola.jpg"}', '["Nero", "Viola"]'),
(26, 'Galaxy S24 Ultra', 'Il Galaxy S24 Ultra è il top della gamma', 1049, 'Exynos 2200', 'Display Dynamic AMOLED 2X da 6,8"', '["128GB", "256GB", "512GB"]', '{"nero": "../images/S24ultra_black.jpg"}', '["Nero"]'),
(60, 'Galaxy Z Flip5', 'Galaxy Z Flip5 con processore Snapdragon 8 Gen 2', 1099, 'Qualcomm Snapdragon 8 Gen 2', 'Display Dynamic AMOLED 2X da 6,7"', '["256GB", "512GB", "1TB"]', '{"nero": "../images/galaxyzflip5_black.jpg", "bianco": "../images/galaxyzflip5_white.jpg"}', '["Nero", "Bianco"]'),
(61, 'Galaxy Z Fold5', 'Galaxy Z Fold5 con processore Snapdragon 8 Gen 2', 1799, 'Qualcomm Snapdragon 8 Gen 2', 'Display Dynamic AMOLED 2X da 7,6"', '["256GB", "512GB", "1TB"]', '{"nero": "../images/galaxyzfold5_black.jpg"}', '["Nero"]');


