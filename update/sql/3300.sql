UPDATE `settings` SET `value` = '{\"version\":\"33.0.0\", \"code\":\"3300\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table plans add translations text null after description;

-- SEPARATOR --

alter table plans drop column monthly_price;

-- SEPARATOR --

alter table plans drop column annual_price;

-- SEPARATOR --

alter table plans drop column lifetime_price;

-- SEPARATOR --

alter table users modify plan_settings longtext null;

-- SEPARATOR --

alter table plans modify settings longtext not null;

-- SEPARATOR --