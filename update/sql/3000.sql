UPDATE `settings` SET `value` = '{\"version\":\"30.0.0\", \"code\":\"3000\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table users add preferences text after timezone;

-- SEPARATOR --