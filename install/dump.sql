CREATE TABLE `users` (
`user_id` int NOT NULL AUTO_INCREMENT,
`email` varchar(320) NOT NULL,
`password` varchar(128) DEFAULT NULL,
`name` varchar(64) NOT NULL,
`billing` text,
`api_key` varchar(32) DEFAULT NULL,
`token_code` varchar(32) DEFAULT NULL,
`twofa_secret` varchar(16) DEFAULT NULL,
`anti_phishing_code` varchar(8) DEFAULT NULL,
`one_time_login_code` varchar(32) DEFAULT NULL,
`pending_email` varchar(128) DEFAULT NULL,
`email_activation_code` varchar(32) DEFAULT NULL,
`lost_password_code` varchar(32) DEFAULT NULL,
`type` tinyint NOT NULL DEFAULT '0',
`status` tinyint NOT NULL DEFAULT '0',
`is_newsletter_subscribed` tinyint NOT NULL DEFAULT '0',
`has_pending_internal_notifications` tinyint NOT NULL DEFAULT '0',
`plan_id` varchar(16) NOT NULL DEFAULT '',
`plan_expiration_date` datetime DEFAULT NULL,
`plan_settings` longtext DEFAULT NULL,
`plan_trial_done` tinyint DEFAULT '0',
`plan_expiry_reminder` tinyint DEFAULT '0',
`payment_subscription_id` varchar(64) DEFAULT NULL,
`payment_processor` varchar(16) DEFAULT NULL,
`payment_total_amount` float DEFAULT NULL,
`payment_currency` varchar(4) DEFAULT NULL,
`referral_key` varchar(32) DEFAULT NULL,
`referred_by` varchar(32) DEFAULT NULL,
`referred_by_has_converted` tinyint DEFAULT '0',
`language` varchar(32) DEFAULT 'english',
`currency` varchar(4) DEFAULT NULL,
`timezone` varchar(32) DEFAULT 'UTC',
`preferences` text,
`extra` text DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
`next_cleanup_datetime` datetime DEFAULT CURRENT_TIMESTAMP NULL,
`ip` varchar(64) DEFAULT NULL,
`continent_code` varchar(8) DEFAULT NULL,
`country` varchar(8) DEFAULT NULL,
`city_name` varchar(32) DEFAULT NULL,
`device_type` varchar(16) DEFAULT NULL,
`browser_language` varchar(32) DEFAULT NULL,
`browser_name` varchar(32) DEFAULT NULL,
`os_name` varchar(16) DEFAULT NULL,
`last_activity` datetime DEFAULT NULL,
`total_logins` int DEFAULT '0',
`user_deletion_reminder` tinyint(4) DEFAULT '0',
`source` varchar(32) DEFAULT 'direct',
PRIMARY KEY (`user_id`),
KEY `plan_id` (`plan_id`),
KEY `api_key` (`api_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --


INSERT INTO `users` (`user_id`, `email`, `password`, `api_key`, `referral_key`, `name`, `type`, `status`, `plan_id`, `plan_expiration_date`, `plan_settings`, `datetime`, `ip`, `last_activity`)
VALUES (1,'admin','$2y$10$uFNO0pQKEHSFcus1zSFlveiPCB3EvG9ZlES7XKgJFTAl5JbRGFCWy', md5(rand()), md5(rand()), 'AltumCode',1,1,'custom','2030-01-01 12:00:00', '{"stores_limit":-1,"menus_limit":-1,"categories_limit":-1,"items_limit":-1,"domains_limit":-1,"statistics_retention":90,"ordering_is_enabled":true,"additional_domains_is_enabled":true,"analytics_is_enabled":true,"qr_is_enabled":true,"removable_branding_is_enabled":true,"custom_url_is_enabled":true,"password_protection_is_enabled":true,"search_engine_block_is_enabled":true,"custom_css_is_enabled":true,"custom_js_is_enabled":true,"email_reports_is_enabled":true,"online_payments_is_enabled":true,"api_is_enabled":true,"affiliate_is_enabled":true,"no_ads":true}', NOW(),'',NOW());

-- SEPARATOR --

CREATE TABLE `users_logs` (
`id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` int DEFAULT NULL,
`type` varchar(64) DEFAULT NULL,
`ip` varchar(64) DEFAULT NULL,
`device_type` varchar(16) DEFAULT NULL,
`os_name` varchar(16) DEFAULT NULL,
`continent_code` varchar(8) DEFAULT NULL,
`country_code` varchar(8) DEFAULT NULL,
`city_name` varchar(32) DEFAULT NULL,
`browser_language` varchar(32) DEFAULT NULL,
`browser_name` varchar(32) DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `users_logs_user_id` (`user_id`),
KEY `users_logs_ip_type_datetime_index` (`ip`,`type`,`datetime`),
CONSTRAINT `users_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `plans` (
`plan_id` int NOT NULL AUTO_INCREMENT,
`name` varchar(64) NOT NULL DEFAULT '',
`description` varchar(256) NOT NULL DEFAULT '',
`translations` text NULL,
`prices` text NOT NULL,
`trial_days` int unsigned NOT NULL DEFAULT '0',
`settings` longtext NOT NULL,
`taxes_ids` text,
`color` varchar(16) DEFAULT NULL,
`status` tinyint(4) NOT NULL,
`order` int(10) unsigned DEFAULT '0',
`datetime` datetime NOT NULL,
PRIMARY KEY (`plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SEPARATOR --

CREATE TABLE `pages_categories` (
`pages_category_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`url` varchar(256) NOT NULL,
`title` varchar(256) NOT NULL DEFAULT '',
`description` varchar(256) DEFAULT NULL,
`icon` varchar(32) DEFAULT NULL,
`order` int NOT NULL DEFAULT '0',
`language` varchar(32) DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`pages_category_id`),
KEY `url` (`url`),
KEY `pages_categories_url_language_index` (`url`,`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- SEPARATOR --

CREATE TABLE `pages` (
`page_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`pages_category_id` bigint unsigned DEFAULT NULL,
`url` varchar(256) NOT NULL,
`title` varchar(256) NOT NULL DEFAULT '',
`description` varchar(256) DEFAULT NULL,
`icon` varchar(32) DEFAULT NULL,
`keywords` varchar(256) CHARACTER SET utf8mb4 DEFAULT NULL,
`editor` varchar(16) DEFAULT NULL,
`content` longtext,
`type` varchar(16) DEFAULT '',
`position` varchar(16) NOT NULL DEFAULT '',
`language` varchar(32) DEFAULT NULL,
`open_in_new_tab` tinyint DEFAULT '1',
`order` int DEFAULT '0',
`total_views` bigint unsigned DEFAULT '0',
`is_published` tinyint DEFAULT '1',
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`page_id`),
KEY `pages_pages_category_id_index` (`pages_category_id`),
KEY `pages_url_index` (`url`),
KEY `pages_is_published_index` (`is_published`),
KEY `pages_language_index` (`language`),
CONSTRAINT `pages_ibfk_1` FOREIGN KEY (`pages_category_id`) REFERENCES `pages_categories` (`pages_category_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

INSERT INTO `pages` (`pages_category_id`, `url`, `title`, `description`, `content`, `type`, `position`, `order`, `total_views`, `datetime`, `last_datetime`) VALUES
(NULL, 'https://altumcode.com/', 'Software by AltumCode', '', '', 'external', 'bottom', 1, 0, NOW(), NOW()),
(NULL, 'https://altumco.de/66qrmenu', 'Built with 66qrmenu', '', '', 'external', 'bottom', 0, 0, NOW(), NOW());

-- SEPARATOR --

CREATE TABLE `blog_posts_categories` (
`blog_posts_category_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`url` varchar(256) NOT NULL,
`title` varchar(256) NOT NULL DEFAULT '',
`description` varchar(256) DEFAULT NULL,
`order` int NOT NULL DEFAULT '0',
`language` varchar(32) DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`blog_posts_category_id`),
KEY `url` (`url`),
KEY `blog_posts_categories_url_language_index` (`url`,`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- SEPARATOR --

CREATE TABLE `blog_posts` (
`blog_post_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`blog_posts_category_id` bigint unsigned DEFAULT NULL,
`url` varchar(256) NOT NULL,
`title` varchar(256) NOT NULL DEFAULT '',
`description` varchar(256) DEFAULT NULL,
`image_description` varchar(256) DEFAULT NULL,
`keywords` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`image` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`editor` varchar(16) DEFAULT NULL,
`content` longtext,
`language` varchar(32) DEFAULT NULL,
`total_views` bigint unsigned DEFAULT '0',
`is_published` tinyint DEFAULT '1',
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`blog_post_id`),
KEY `blog_post_id_index` (`blog_post_id`),
KEY `blog_post_url_index` (`url`),
KEY `blog_posts_category_id` (`blog_posts_category_id`),
KEY `blog_posts_is_published_index` (`is_published`),
KEY `blog_posts_language_index` (`language`),
CONSTRAINT `blog_posts_ibfk_1` FOREIGN KEY (`blog_posts_category_id`) REFERENCES `blog_posts_categories` (`blog_posts_category_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `broadcasts` (
`broadcast_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(64) DEFAULT NULL,
`subject` varchar(128) DEFAULT NULL,
`content` text,
`segment` varchar(64) DEFAULT NULL,
`settings` text COLLATE utf8mb4_unicode_ci,
`users_ids` longtext CHARACTER SET utf8mb4,
`sent_users_ids` longtext,
`sent_emails` int unsigned DEFAULT '0',
`total_emails` int unsigned DEFAULT '0',
`status` varchar(16) DEFAULT NULL,
`views` bigint unsigned DEFAULT '0',
`clicks` bigint unsigned DEFAULT '0',
`last_sent_email_datetime` datetime DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`broadcast_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `broadcasts_statistics` (
`id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` int DEFAULT NULL,
`broadcast_id` bigint unsigned DEFAULT NULL,
`type` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`target` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `broadcast_id` (`broadcast_id`),
KEY `broadcasts_statistics_user_id_broadcast_id_type_index` (`broadcast_id`,`user_id`,`type`),
CONSTRAINT `broadcasts_statistics_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `broadcasts_statistics_ibfk_2` FOREIGN KEY (`broadcast_id`) REFERENCES `broadcasts` (`broadcast_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `internal_notifications` (
`internal_notification_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` int DEFAULT NULL,
`for_who` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`from_who` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`icon` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`title` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`description` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`url` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`is_read` tinyint unsigned DEFAULT '0',
`datetime` datetime DEFAULT NULL,
`read_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`internal_notification_id`),
KEY `user_id` (`user_id`),
KEY `users_notifications_for_who_idx` (`for_who`) USING BTREE,
CONSTRAINT `internal_notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `settings` (
`id` int NOT NULL AUTO_INCREMENT,
`key` varchar(64) NOT NULL DEFAULT '',
`value` longtext NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

SET @cron_key = MD5(RAND());

-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`)
VALUES
('main', '{"title":"Your title","default_language":"english","default_theme_style":"light","default_timezone":"UTC","index_url":"","terms_and_conditions_url":"","privacy_policy_url":"","not_found_url":"","se_indexing":true,"display_index_plans":true,"default_results_per_page":25,"default_order_type":"DESC","auto_language_detection_is_enabled":true,"blog_is_enabled":true,"api_is_enabled":true,"logo_light":"","logo_dark":"","logo_email":"","opengraph":"","favicon":""}'),
('languages', '{"english":{"status":"active"}}'),
('users', '{"email_confirmation":false,"welcome_email_is_enabled":false,"register_is_enabled":true,"register_only_social_logins":false,"register_display_newsletter_checkbox":false,"auto_delete_unconfirmed_users":30,"auto_delete_inactive_users":90,"user_deletion_reminder":0,"blacklisted_domains":"","blacklisted_countries":[],"login_lockout_is_enabled":true,"login_lockout_max_retries":3,"login_lockout_time":60,"lost_password_lockout_is_enabled":true,"lost_password_lockout_max_retries":3,"lost_password_lockout_time":60,"resend_activation_lockout_is_enabled":true,"resend_activation_lockout_max_retries":3,"resend_activation_lockout_time":60,"register_lockout_is_enabled":true,"register_lockout_max_registrations":3,"register_lockout_time":10}'),
('ads', '{"header":"","footer":""}'),
('captcha', '{"type":"basic","recaptcha_public_key":"","recaptcha_private_key":"","login_is_enabled":0,"register_is_enabled":0,"lost_password_is_enabled":0,"resend_activation_is_enabled":0}'),
('cron', concat('{"key":"', @cron_key, '"}')),
('email_notifications', '{"emails":"","new_user":false,"delete_user":false,"new_payment":false,"new_domain":false,"new_affiliate_withdrawal":false,"contact":false}'),
('internal_notifications', '{}'),
('content', '{"blog_is_enabled":true,"blog_share_is_enabled":true,"blog_categories_widget_is_enabled":true,"blog_popular_widget_is_enabled":true,"blog_views_is_enabled":true,"pages_is_enabled":true,"pages_share_is_enabled":true,"pages_popular_widget_is_enabled":true,"pages_views_is_enabled":true}'),
('facebook', '{"is_enabled":"0","app_id":"","app_secret":""}'),
('google', '{"is_enabled":"0","client_id":"","client_secret":""}'),
('twitter', '{"is_enabled":"0","consumer_api_key":"","consumer_api_secret":""}'),
('discord', '{"is_enabled":"0"}'),
('linkedin', '{"is_enabled":"0"}'),
('microsoft', '{"is_enabled":"0"}'),
('plan_custom', '{"plan_id":"custom","name":"Custom","status":1}'),
('plan_free', '{"plan_id":"free","name":"Free","days":null,"status":1,"settings":{"stores_limit":1,"menus_limit":5,"categories_limit":10,"items_limit":10,"domains_limit":0,"ordering_is_enabled":false,"additional_domains_is_enabled":false,"analytics_is_enabled":true,"removable_branding_is_enabled":false,"custom_url_is_enabled":true,"password_protection_is_enabled":true,"search_engine_block_is_enabled":false,"custom_css_is_enabled":false,"custom_js_is_enabled":false,"email_reports_is_enabled":false,"online_payments_is_enabled":false,"api_is_enabled":false,"affiliate_is_enabled":false,"no_ads":true}}'),
('payment', '{"is_enabled":"0","type":"both","brand_name":":)","currency":"USD","codes_is_enabled":"1"}'),
('paypal', '{"is_enabled":"0","mode":"sandbox","client_id":"","secret":""}'),
('stripe', '{"is_enabled":"0","publishable_key":"","secret_key":"","webhook_secret":""}'),
('offline_payment', '{"is_enabled":"0","instructions":"Your offline payment instructions go here.."}'),
('coinbase', '{"is_enabled":"0"}'),
('payu', '{"is_enabled":"0"}'),
('paystack', '{"is_enabled":"0"}'),
('razorpay', '{"is_enabled":"0"}'),
('mollie', '{"is_enabled":"0"}'),
('yookassa', '{"is_enabled":"0"}'),
('crypto_com', '{"is_enabled":"0"}'),
('paddle', '{"is_enabled":"0"}'),
('mercadopago', '{"is_enabled":"0"}'),
('iyzico', '{"is_enabled":"0"}'),
('midtrans', '{"is_enabled":"0"}'),
('flutterwave', '{"is_enabled":"0"}'),
('sso', '{}'),
('smtp', '{"host":"","from":"","from_name":"","encryption":"tls","port":"587","auth":"1","username":"","password":""}'),
('theme', '{"light_is_enabled": false, "dark_is_enabled": false}'),
('custom', '{"head_js":"","head_css":""}'),
('socials', '{"youtube":"","facebook":"","twitter":"","instagram":"","tiktok":"","linkedin":"","whatsapp":"","email":""}'),
('announcements', '{"guests_id":"16e2fdd0e771da32ec9e557c491fe17d","guests_content":"","guests_text_color":"#ffffff","guests_background_color":"#000000","users_id":"16e2fdd0e771da32ec9e557c491fe17d","users_content":"","users_text_color":"#dbebff","users_background_color":"#000000"}'),
('business', '{"invoice_is_enabled":"0","name":"","address":"","city":"","county":"","zip":"","country":"","email":"","phone":"","tax_type":"","tax_id":"","custom_key_one":"","custom_value_one":"","custom_key_two":"","custom_value_two":""}'),
('webhooks', '{"user_new":"","user_delete":"","payment_new":"","code_redeemed":"","contact":""}'),
('stores', '{"email_reports_is_enabled":"weekly","domains_is_enabled":"1","additional_domains_is_enabled":"1","main_domain_is_enabled":"1","logo_size_limit":"1","favicon_size_limit":"1","image_size_limit":"1","menu_image_size_limit":"1","item_image_size_limit":"1"}'),
('cookie_consent', '{"is_enabled":false,"logging_is_enabled":false,"necessary_is_enabled":true,"analytics_is_enabled":true,"targeting_is_enabled":true,"layout":"bar","position_y":"middle","position_x":"center"}'),
('license', '{"license":"xxxxxxxxxxxx","type":"Extended License"}'),
('support', '{"key": "", "expiry_datetime": "2100-01-01 00:00:00"}'),
('product_info', '{"version":"36.0.0", "code":"3600"}');

-- SEPARATOR --

CREATE TABLE `stores` (
`store_id` int unsigned NOT NULL AUTO_INCREMENT,
`domain_id` int unsigned DEFAULT NULL,
`user_id` int NOT NULL,
`url` varchar(128) NOT NULL DEFAULT '',
`name` varchar(256) DEFAULT NULL,
`description` varchar(256) DEFAULT NULL,
`settings` text,
`details` text,
`socials` text,
`currency` varchar(32) DEFAULT NULL,
`password` varchar(128) DEFAULT NULL,
`image` varchar(40) DEFAULT NULL,
`logo` varchar(40) DEFAULT NULL,
`favicon` varchar(40) DEFAULT NULL,
`opengraph` varchar(40) DEFAULT NULL,
`theme` varchar(16) DEFAULT NULL,
`timezone` varchar(32) NOT NULL DEFAULT 'UTC',
`custom_css` text,
`custom_js` text,
`pageviews` bigint unsigned NOT NULL DEFAULT '0',
`orders` bigint unsigned DEFAULT '0',
`is_se_visible` tinyint(4) DEFAULT '1',
`is_removed_branding` tinyint(4) DEFAULT '0',
`email_reports_is_enabled` tinyint(4) NOT NULL DEFAULT '0',
`email_reports_last_datetime` datetime DEFAULT NULL,
`email_orders_is_enabled` tinyint(4) DEFAULT '0',
`ordering` text,
`payment_processors` text,
`business` text,
`is_enabled` tinyint(4) NOT NULL DEFAULT '1',
`datetime` datetime NOT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`store_id`),
KEY `user_id` (`user_id`),
KEY `stores_url_idx` (`url`) USING BTREE,
KEY `stores_ibfk_2` (`domain_id`),
CONSTRAINT `stores_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- SEPARATOR --

CREATE TABLE `menus` (
`menu_id` int unsigned NOT NULL AUTO_INCREMENT,
`store_id` int unsigned NOT NULL,
`user_id` int DEFAULT NULL,
`url` varchar(128) DEFAULT NULL,
`name` varchar(256) DEFAULT NULL,
`description` text,
`image` varchar(40) DEFAULT NULL,
`pageviews` bigint unsigned NOT NULL DEFAULT '0',
`order` int unsigned DEFAULT '0',
`is_enabled` tinyint(4) DEFAULT '1',
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`menu_id`),
KEY `user_id` (`user_id`),
KEY `store_id` (`store_id`) USING BTREE,
KEY `menus_url_idx` (`url`) USING BTREE,
CONSTRAINT `menus_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `menus_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `categories` (
`category_id` int unsigned NOT NULL AUTO_INCREMENT,
`menu_id` int unsigned NOT NULL,
`store_id` int unsigned NOT NULL,
`user_id` int DEFAULT NULL,
`url` varchar(128) DEFAULT NULL,
`name` varchar(256) DEFAULT NULL,
`description` text,
`pageviews` bigint unsigned NOT NULL DEFAULT '0',
`order` int unsigned DEFAULT '0',
`is_enabled` tinyint(4) DEFAULT '1',
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`category_id`),
KEY `user_id` (`user_id`),
KEY `menu_id` (`menu_id`),
KEY `store_id` (`store_id`) USING BTREE,
KEY `categories_url_idx` (`url`) USING BTREE,
CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `categories_ibfk_2` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `categories_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `items` (
`item_id` int unsigned NOT NULL AUTO_INCREMENT,
`category_id` int unsigned NOT NULL,
`menu_id` int unsigned NOT NULL,
`store_id` int unsigned NOT NULL,
`user_id` int DEFAULT NULL,
`url` varchar(128) DEFAULT NULL,
`name` varchar(256) DEFAULT NULL,
`description` text,
`image` varchar(40) DEFAULT NULL,
`price` float NOT NULL DEFAULT '0',
`variants_is_enabled` tinyint(4) NOT NULL DEFAULT '0',
`pageviews` bigint unsigned NOT NULL DEFAULT '0',
`order` int unsigned DEFAULT '0',
`orders` bigint unsigned NOT NULL DEFAULT '0',
`is_enabled` tinyint(4) DEFAULT '1',
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`item_id`),
KEY `user_id` (`user_id`),
KEY `category_id` (`category_id`),
KEY `menu_id` (`menu_id`),
KEY `store_id` (`store_id`) USING BTREE,
KEY `url` (`url`) USING BTREE,
CONSTRAINT `items_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `items_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `items_ibfk_3` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `items_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `items_extras` (
`item_extra_id` int unsigned NOT NULL AUTO_INCREMENT,
`item_id` int unsigned NOT NULL,
`category_id` int unsigned NOT NULL,
`menu_id` int unsigned NOT NULL,
`store_id` int unsigned NOT NULL,
`user_id` int DEFAULT NULL,
`name` varchar(256) DEFAULT NULL,
`description` text,
`price` float NOT NULL DEFAULT '0',
`is_enabled` tinyint(4) DEFAULT '1',
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`item_extra_id`),
KEY `user_id` (`user_id`),
KEY `item_id` (`item_id`),
KEY `category_id` (`category_id`),
KEY `menu_id` (`menu_id`),
KEY `store_id` (`store_id`) USING BTREE,
CONSTRAINT `items_extras_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `items_extras_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `items_extras_ibfk_3` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `items_extras_ibfk_4` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `items_extras_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `items_options` (
`item_option_id` int unsigned NOT NULL AUTO_INCREMENT,
`item_id` int unsigned NOT NULL,
`category_id` int unsigned NOT NULL,
`menu_id` int unsigned NOT NULL,
`store_id` int unsigned NOT NULL,
`user_id` int DEFAULT NULL,
`name` varchar(256) DEFAULT NULL,
`options` text,
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`item_option_id`),
KEY `user_id` (`user_id`),
KEY `store_id` (`store_id`) USING BTREE,
KEY `menu_id` (`menu_id`),
KEY `category_id` (`category_id`),
KEY `item_id` (`item_id`),
CONSTRAINT `items_options_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `items_options_ibfk_2` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `items_options_ibfk_3` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `items_options_ibfk_4` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `items_options_ibfk_5` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- SEPARATOR --

CREATE TABLE `items_variants` (
`item_variant_id` int unsigned NOT NULL AUTO_INCREMENT,
`item_id` int unsigned NOT NULL,
`category_id` int unsigned NOT NULL,
`menu_id` int unsigned NOT NULL,
`store_id` int unsigned NOT NULL,
`user_id` int DEFAULT NULL,
`item_options_ids` text NOT NULL,
`price` float NOT NULL DEFAULT '0',
`is_enabled` tinyint(4) DEFAULT '1',
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`item_variant_id`),
KEY `user_id` (`user_id`),
KEY `store_id` (`store_id`) USING BTREE,
KEY `item_id` (`item_id`),
KEY `category_id` (`category_id`),
KEY `menu_id` (`menu_id`),
CONSTRAINT `items_variants_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `items_variants_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `items_variants_ibfk_3` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `items_variants_ibfk_4` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `items_variants_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `orders` (
`order_id` int unsigned NOT NULL AUTO_INCREMENT,
`store_id` int unsigned NOT NULL,
`user_id` int NOT NULL,
`order_number` bigint DEFAULT '1',
`type` varchar(36) NOT NULL COMMENT '''on_premise'', ''takeaway'', ''delivery''',
`processor` varchar(16) DEFAULT 'offline_payment',
`details` text,
`price` float NOT NULL DEFAULT '0',
`ordered_items` int DEFAULT '0',
`is_paid` tinyint(4) DEFAULT '0',
`status` tinyint(4) NOT NULL DEFAULT '0',
`datetime` datetime DEFAULT NULL,
PRIMARY KEY (`order_id`),
KEY `store_id` (`store_id`) USING BTREE,
KEY `orders_datetime_idx` (`datetime`) USING BTREE,
KEY `user_id` (`user_id`),
CONSTRAINT `orders_ibfk_4` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `orders_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `orders_items` (
`order_item_id` int unsigned NOT NULL AUTO_INCREMENT,
`order_id` int unsigned NOT NULL,
`item_variant_id` int unsigned DEFAULT NULL,
`item_id` int unsigned DEFAULT NULL,
`category_id` int unsigned DEFAULT NULL,
`menu_id` int unsigned DEFAULT NULL,
`store_id` int unsigned NOT NULL,
`item_extras_ids` text,
`data` text,
`price` float NOT NULL DEFAULT '0',
`quantity` int unsigned NOT NULL DEFAULT '1',
`datetime` datetime DEFAULT NULL,
PRIMARY KEY (`order_item_id`),
KEY `store_id` (`store_id`) USING BTREE,
KEY `order_id` (`order_id`),
KEY `orders_items_datetime_idx` (`datetime`) USING BTREE,
KEY `item_variant_id` (`item_variant_id`),
KEY `item_id` (`item_id`),
KEY `category_id` (`category_id`),
KEY `menu_id` (`menu_id`),
CONSTRAINT `orders_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `orders_items_ibfk_10` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`menu_id`) ON DELETE SET NULL ON UPDATE CASCADE,
CONSTRAINT `orders_items_ibfk_6` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `orders_items_ibfk_7` FOREIGN KEY (`item_variant_id`) REFERENCES `items_variants` (`item_variant_id`) ON DELETE SET NULL ON UPDATE CASCADE,
CONSTRAINT `orders_items_ibfk_8` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE SET NULL ON UPDATE CASCADE,
CONSTRAINT `orders_items_ibfk_9` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `statistics` (
`id` int NOT NULL AUTO_INCREMENT,
`store_id` int unsigned DEFAULT NULL,
`menu_id` int(10) unsigned DEFAULT NULL,
`category_id` int(10) unsigned DEFAULT NULL,
`item_id` int unsigned DEFAULT NULL,
`country_code` varchar(8) DEFAULT NULL,
`city_name` varchar(128) DEFAULT NULL,
`os_name` varchar(16) DEFAULT NULL,
`browser_name` varchar(32) DEFAULT NULL,
`referrer_host` varchar(256) DEFAULT NULL,
`referrer_path` varchar(1024) DEFAULT NULL,
`device_type` varchar(16) DEFAULT NULL,
`browser_language` varchar(16) DEFAULT NULL,
`utm_source` varchar(128) DEFAULT NULL,
`utm_medium` varchar(128) DEFAULT NULL,
`utm_campaign` varchar(128) DEFAULT NULL,
`is_unique` tinyint(4) DEFAULT '0',
`datetime` datetime NOT NULL,
PRIMARY KEY (`id`),
KEY `store_id` (`store_id`),
KEY `menu_id` (`menu_id`),
KEY `category_id` (`category_id`),
KEY `item_id` (`item_id`),
CONSTRAINT `statistics_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `statistics_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `statistics_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `statistics_ibfk_4` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SEPARATOR --

CREATE TABLE `domains` (
`domain_id` int unsigned NOT NULL AUTO_INCREMENT,
`store_id` int unsigned DEFAULT NULL,
`user_id` int DEFAULT NULL,
`scheme` varchar(8) NOT NULL DEFAULT '',
`host` varchar(256) NOT NULL DEFAULT '',
`custom_index_url` varchar(256) DEFAULT NULL,
`custom_not_found_url` varchar(256) DEFAULT NULL,
`type` tinyint(11) DEFAULT '1',
`is_enabled` tinyint(4) DEFAULT '0',
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`domain_id`),
KEY `user_id` (`user_id`),
KEY `domains_host_index` (`host`),
KEY `domains_type_index` (`type`),
KEY `domains_ibfk_2` (`store_id`),
CONSTRAINT `domains_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `domains_ibfk_2` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- SEPARATOR --

alter table stores add CONSTRAINT `stores_ibfk_2` FOREIGN KEY (`domain_id`) REFERENCES `domains` (`domain_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- SEPARATOR --

CREATE TABLE `email_reports` (
`id` int NOT NULL AUTO_INCREMENT,
`user_id` int NOT NULL,
`store_id` int unsigned NOT NULL,
`datetime` datetime NOT NULL,
PRIMARY KEY (`id`),
KEY `user_id` (`user_id`),
KEY `datetime` (`datetime`),
KEY `store_id` (`store_id`),
CONSTRAINT `email_reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `email_reports_ibfk_2` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `customers_payments` (
`customer_payment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`order_id` int(10) unsigned DEFAULT NULL,
`store_id` int(10) unsigned DEFAULT NULL,
`user_id` int DEFAULT NULL,
`processor` varchar(16) DEFAULT NULL,
`payment_id` varchar(128) DEFAULT NULL,
`billing` text,
`total_amount` float DEFAULT NULL,
`currency` varchar(4) DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
UNIQUE KEY `customer_payment_id` (`customer_payment_id`) USING BTREE,
KEY `order_id` (`order_id`),
KEY `store_id` (`store_id`),
KEY `user_id` (`user_id`),
CONSTRAINT `customers_payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE SET NULL ON UPDATE CASCADE,
CONSTRAINT `customers_payments_ibfk_2` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `customers_payments_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

INSERT INTO `stores` (`store_id`, `user_id`, `url`, `name`, `description`, `details`, `socials`, `currency`, `password`, `image`, `logo`, `favicon`, `theme`, `timezone`, `custom_css`, `custom_js`, `pageviews`, `is_se_visible`, `is_removed_branding`, `email_reports_is_enabled`, `email_reports_last_datetime`, `is_enabled`, `datetime`, `last_datetime`) VALUES
('1', '1', 'demo', 'Vintage Machine',  'great coffee, food & relaxing spot.', '{"address":"Lorem ipsum dolor sit amet, number 200, street consectetur adipiscing.","phone":"+100100100","website":"https:\\/\\/example.com","email":"example@example.com","hours":{"1":{"is_enabled":true,"hours":"10AM - 9PM"},"2":{"is_enabled":true,"hours":"10AM - 9PM"},"3":{"is_enabled":true,"hours":"10AM - 9PM"},"4":{"is_enabled":true,"hours":"10AM - 9PM"},"5":{"is_enabled":true,"hours":"10AM - 9PM"},"6":{"is_enabled":true,"hours":"24\\/7"},"7":{"is_enabled":true,"hours":"24\\/7"}}}', '{"facebook":"example","instagram":"example","twitter":"example"}', 'USD', NULL, '43b05d0c754dcdb7980dfaac6869ea67.jpg', 'fdf291dc2b8c9fa12a4b4ca05608fae1.png', '59e6f1e7ad6a86d2272fd74eca7e5405.png', 'new-york', 'UTC', '', '', '0', '1', '1', '0', NOW(), '1', NOW(), NOW());

-- SEPARATOR --

INSERT INTO `menus` (`menu_id`, `store_id`, `user_id`, `url`, `name`, `description`, `image`, `pageviews`, `is_enabled`, `datetime`, `last_datetime`) VALUES
('1', '1', '1', 'coffee', 'Coffee', 'Just coffee.', '60d8f4dc75993a3753050e553c661294.jpg', '0', '1', NOW(), NULL),
('2', '1', '1', 'breakfast', 'Breakfast', 'Breakfast menu is served from 10AM - 1PM.', '4095e4ca4860ed2aa22aafa341ae4122.jpg', '0', '1', NOW(), NULL),
('3', '1', '1', 'lunch-dinner', 'Lunch & Dinner', 'Served from starting from 1PM.', '219952272d78d8b1c0bfa59d903e14b7.jpg', '0', '1', NOW(), NULL);

-- SEPARATOR --

INSERT INTO `categories` (`category_id`, `menu_id`, `store_id`, `user_id`, `url`, `name`, `description`, `pageviews`, `is_enabled`, `datetime`, `last_datetime`) VALUES
('1', '1', '1', '1', 'cold', 'Cold coffee', '', '0', '1', NOW(), NULL),
('2', '1', '1', '1', 'hot', 'Hot coffee', '', '0', '1', NOW(), NOW()),
('3', '2', '1', '1', 'vegan', 'Vegan', '', '0', '1', NOW(), NULL),
('4', '3', '1', '1', 'burgers', 'Burgers', '', '0', '1', NOW(), NULL),
('5', '3', '1', '1', 'pasta', 'Pasta', '', '0', '1', NOW(), NULL);

-- SEPARATOR --

INSERT INTO `items` (`item_id`, `category_id`, `menu_id`, `store_id`, `user_id`, `url`, `name`, `description`, `image`, `price`, `variants_is_enabled`, `pageviews`, `is_enabled`, `datetime`, `last_datetime`) VALUES
('1', '2', '1', '1', '1', 'caffe-late', 'Caffe Late', 'Simple & nice caffe late.', 'cd01696b79a25e10d11f6309c3a911a3.jpg', '2', '0', '0', '1', NOW(), NOW()),
('2', '2', '1', '1', '1', 'americano', 'Americano', 'Caffe Americano is a type of coffee drink prepared by diluting an espresso with hot water, giving it a similar strength to, but different flavor from, traditionally brewed coffee. The strength of an Americano varies with the number of shots of espresso and the amount of water added.', '012e68300fca27f5459ab31595777282.jpg', '2.5', '0', '0', '1', NOW(), NOW()),
('3', '2', '1', '1', '1', 'caffe-mocha', 'Caffe Mocha', 'A caffe mocha, also called mocaccino, is a chocolate-flavoured variant of a caffè latte. Other commonly used spellings are mochaccino and also mochachino. The name is derived from the city of Mocha, Yemen, which was one of the centers of early coffee trade.', '974541b7bb20f7565fafcca5aaf7fb1e.jpg', '3', '0', '0', '1', NOW(), NOW()),
('4', '1', '1', '1', '1', 'cold-brew', 'Cold brew', 'Cold brew is really as simple as mixing ground coffee with cool water and steeping the mixture in the fridge overnight.', 'f5af9c710b8f9b8cbcc7a64e8b5048e8.jpg', '3', '0', '0', '1', NOW(), NULL),
('5', '3', '2', '1', '1', 'french-toast', 'French Toast', '', '653f2d83e8ab5c36ea11907c80693f79.jpg', '5', '0', '0', '1', NOW(), NULL),
('6', '3', '2', '1', '1', 'granola', 'Granola Bowl', '', '98e0d264361fcdddb6b36de97b35f1d1.jpg', '7.5', '0', '0', '1', NOW(), NULL),
('7', '3', '2', '1', '1', 'avocado', 'Avocado Toast', '', 'f03daa3d679d859fe3d707590edac337.jpg', '5', '0', '0', '1', NOW(), NULL),
('8', '4', '3', '1', '1', 'the-special-one', 'The special one', 'The legandary, the special one, limited edition.', '1e68a923c68c57da173fc659b013339f.jpg', '19.9', '1', '0', '1', NOW(), NOW()),
('9', '4', '3', '1', '1', 'double', 'The Double', '', 'a5bda5476ac30603270a0f3f84e778fa.jpg', '15', '0', '0', '1', NOW(), NULL),
('10', '4', '3', '1', '1', 'the-challenge', 'The Challenge', '', 'd25de049f97cfa391d1f14e49cd97aac.jpg', '30', '0', '0', '1', NOW(), NULL),
('11', '5', '3', '1', '1', 'carbonara', 'Carbonara', 'Carbonara is an Italian pasta dish from Rome made with egg, hard cheese, cured pork, and black pepper.', 'cbf61cecb5f972ca36d29c881599750d.jpg', '9', '0', '0', '1', NOW(), NOW());

-- SEPARATOR --

INSERT INTO `items_options` (`item_option_id`, `item_id`, `category_id`, `menu_id`, `store_id`, `user_id`, `name`, `options`, `datetime`, `last_datetime`) VALUES
('1', '8', '4', '3', '1', '1', 'Size', '["Small","Medium","Large"]', NOW(), NULL),
('2', '8', '4', '3', '1', '1', 'Patties', '["1","2","3"]', NOW(), NULL);

-- SEPARATOR --

INSERT INTO `items_variants` (`item_variant_id`, `item_id`, `category_id`, `menu_id`, `store_id`, `user_id`, `item_options_ids`, `price`, `is_enabled`, `datetime`, `last_datetime`) VALUES
('1', '8', '4', '3', '1', '1', '[{"item_option_id":1,"option":0},{"item_option_id":2,"option":0}]', '15', '1', NOW(), NULL),
('2', '8', '4', '3', '1', '1', '[{"item_option_id":1,"option":0},{"item_option_id":2,"option":1}]', '16', '1', NOW(), NULL),
('3', '8', '4', '3', '1', '1', '[{"item_option_id":1,"option":1},{"item_option_id":2,"option":0}]', '17', '1', NOW(), NULL),
('4', '8', '4', '3', '1', '1', '[{"item_option_id":1,"option":1},{"item_option_id":2,"option":1}]', '18', '1', NOW(), NULL),
('5', '8', '4', '3', '1', '1', '[{"item_option_id":1,"option":2},{"item_option_id":2,"option":1}]', '19', '1', NOW(), NULL),
('6', '8', '4', '3', '1', '1', '[{"item_option_id":1,"option":2},{"item_option_id":2,"option":2}]', '25', '1', NOW(), NULL);

-- SEPARATOR --

CREATE TABLE `codes` (
  `code_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `days` int(11) DEFAULT NULL,
  `code` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `discount` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '1',
  `redeemed` int(11) NOT NULL DEFAULT '0',
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`code_id`),
  KEY `type` (`type`),
  KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `plan_id` int DEFAULT NULL,
  `processor` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `frequency` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_id` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `plan` text COLLATE utf8mb4_unicode_ci,
  `billing` text COLLATE utf8mb4_unicode_ci,
  `business` text COLLATE utf8mb4_unicode_ci,
  `taxes_ids` text COLLATE utf8mb4_unicode_ci,
  `base_amount` float DEFAULT NULL,
  `total_amount` float DEFAULT NULL,
  `total_amount_default_currency` float DEFAULT NULL,
  `code` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_amount` float DEFAULT NULL,
  `currency` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_proof` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_user_id` (`user_id`),
  KEY `plan_id` (`plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

ALTER TABLE `payments`
  ADD CONSTRAINT `payments_plans_plan_id_fk` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`plan_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `payments_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- SEPARATOR --

CREATE TABLE `redeemed_codes` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `code_id` int(10) UNSIGNED NOT NULL,
  `user_id` int NOT NULL,
  `type` varchar(16),
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `code_id` (`code_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

ALTER TABLE `redeemed_codes`
  ADD CONSTRAINT `redeemed_codes_ibfk_1` FOREIGN KEY (`code_id`) REFERENCES `codes` (`code_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `redeemed_codes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- SEPARATOR --

CREATE TABLE `taxes` (
  `tax_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `value_type` enum('percentage','fixed') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('inclusive','exclusive') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_type` enum('personal','business','both') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `countries` text COLLATE utf8mb4_unicode_ci,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`tax_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `affiliates_commissions` (
  `affiliate_commission_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `referred_user_id` int DEFAULT NULL,
  `payment_id` bigint UNSIGNED DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `currency` varchar(4) DEFAULT NULL,
  `is_withdrawn` tinyint(4) UNSIGNED DEFAULT '0',
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`affiliate_commission_id`),
  UNIQUE KEY `affiliate_commission_id` (`affiliate_commission_id`),
  KEY `user_id` (`user_id`),
  KEY `referred_user_id` (`referred_user_id`),
  KEY `payment_id` (`payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SEPARATOR --

CREATE TABLE `affiliates_withdrawals` (
  `affiliate_withdrawal_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `currency` varchar(4) DEFAULT NULL,
  `note` varchar(1024) DEFAULT NULL,
  `affiliate_commissions_ids` text,
  `is_paid` tinyint(4) UNSIGNED DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`affiliate_withdrawal_id`),
  UNIQUE KEY `affiliate_withdrawal_id` (`affiliate_withdrawal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SEPARATOR --

ALTER TABLE `affiliates_commissions`
  ADD CONSTRAINT `affiliates_commissions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `affiliates_commissions_ibfk_2` FOREIGN KEY (`referred_user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `affiliates_commissions_ibfk_3` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;