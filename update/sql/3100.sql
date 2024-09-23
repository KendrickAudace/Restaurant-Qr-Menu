UPDATE `settings` SET `value` = '{\"version\":\"31.0.0\", \"code\":\"3100\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table users add currency varchar(4) null after language;

-- SEPARATOR --

alter table plans drop codes_ids;

-- SEPARATOR --