UPDATE `settings` SET `value` = '{\"version\":\"34.0.0\", \"code\":\"3400\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table users add extra text null after preferences;

-- SEPARATOR --