UPDATE `settings` SET `value` = '{\"version\":\"36.0.0\", \"code\":\"3600\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table blog_posts add image_description varchar(256) null after description;
-- SEPARATOR --