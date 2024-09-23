UPDATE `settings` SET `value` = '{\"version\":\"35.0.0\", \"code\":\"3500\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table users add next_cleanup_datetime datetime default CURRENT_TIMESTAMP null after datetime;

-- SEPARATOR --