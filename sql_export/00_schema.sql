
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `airports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `airports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` char(3) NOT NULL,
  `name` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `search_vector` text GENERATED ALWAYS AS (concat(`name`,' ',`city`,' ',`code`)) STORED,
  PRIMARY KEY (`id`),
  UNIQUE KEY `airports_code_unique` (`code`),
  FULLTEXT KEY `search_vector_index` (`search_vector`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `banks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `account_name` varchar(255) NOT NULL,
  `account_number` varchar(255) NOT NULL,
  `swift_code` varchar(255) DEFAULT NULL,
  `iban` varchar(255) DEFAULT NULL,
  `routing_number` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) NOT NULL,
  `instructions` text DEFAULT NULL,
  `deadline` varchar(255) NOT NULL COMMENT 'Deadline in hours, e.g. 24, 48',
  `country_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `banks_country_id_foreign` (`country_id`),
  CONSTRAINT `banks_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bookings` (
  `id` char(36) NOT NULL,
  `reference_number` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `office_id` varchar(255) DEFAULT NULL,
  `ota_id` char(36) DEFAULT NULL,
  `flight_offer_id` text DEFAULT NULL,
  `origin_location` varchar(255) DEFAULT NULL,
  `origin_destination` varchar(255) DEFAULT NULL,
  `carrier_code` varchar(255) DEFAULT NULL,
  `route_model` int(11) DEFAULT NULL,
  `departure_date` datetime DEFAULT NULL,
  `cabin` varchar(255) DEFAULT NULL,
  `class` varchar(255) DEFAULT NULL,
  `ama_client_ref` varchar(255) DEFAULT NULL,
  `reservation_id` varchar(255) DEFAULT NULL,
  `pnr` varchar(255) DEFAULT NULL,
  `base_price` bigint(20) unsigned NOT NULL DEFAULT 0,
  `taxes_and_fees` bigint(20) unsigned NOT NULL DEFAULT 0,
  `total_price` bigint(20) unsigned NOT NULL DEFAULT 0,
  `markup_fee` bigint(20) unsigned NOT NULL DEFAULT 0,
  `contact_phone` varchar(255) DEFAULT NULL,
  `customer_first_name` varchar(255) DEFAULT NULL,
  `customer_last_name` varchar(255) DEFAULT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `reservation_date` datetime DEFAULT NULL,
  `offer_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`offer_data`)),
  `skylink_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`skylink_data`)),
  `order_status` int(11) NOT NULL DEFAULT 5,
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'NGN',
  `payment_method` enum('stripe','bank') DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `ticket_issued_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending_payment',
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `cancelled_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bookings_reference_number_unique` (`reference_number`),
  KEY `bookings_user_id_foreign` (`user_id`),
  KEY `bookings_reservation_id_index` (`reservation_id`),
  KEY `bookings_order_status_index` (`order_status`),
  KEY `bookings_customer_email_index` (`customer_email`),
  KEY `bookings_cancelled_by_foreign` (`cancelled_by`),
  KEY `bookings_status_index` (`status`),
  CONSTRAINT `bookings_cancelled_by_foreign` FOREIGN KEY (`cancelled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `iso_alpha2` varchar(2) DEFAULT NULL,
  `iso_alpha3` varchar(3) NOT NULL,
  `numeric_code` int(11) NOT NULL,
  `dialing_code` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=249 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `flight_route_live_fares`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `flight_route_live_fares` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `flight_route_id` bigint(20) unsigned NOT NULL,
  `airline` varchar(255) DEFAULT NULL,
  `airline_code` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) unsigned NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'GBP',
  `stops` tinyint(3) unsigned DEFAULT NULL,
  `duration_minutes` int(10) unsigned DEFAULT NULL,
  `departure_code` char(3) DEFAULT NULL,
  `departure_time` varchar(255) DEFAULT NULL,
  `departure_date` date DEFAULT NULL,
  `arrival_code` char(3) DEFAULT NULL,
  `arrival_time` varchar(255) DEFAULT NULL,
  `arrival_date` date DEFAULT NULL,
  `all_offer` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`all_offer`)),
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
  `searched_for_departure_date` date DEFAULT NULL,
  `searched_for_return_date` date DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `flight_route_live_fares_flight_route_id_index` (`flight_route_id`),
  CONSTRAINT `flight_route_live_fares_flight_route_id_foreign` FOREIGN KEY (`flight_route_id`) REFERENCES `flight_routes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1211 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `flight_routes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `flight_routes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `origin_code` char(3) NOT NULL,
  `destination_code` char(3) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `distance_miles` int(10) unsigned DEFAULT NULL,
  `avg_duration_minutes` int(10) unsigned DEFAULT NULL,
  `has_direct_flights` tinyint(1) NOT NULL DEFAULT 1,
  `lowest_fare_gbp` decimal(8,2) NOT NULL,
  `primary_airline` varchar(255) DEFAULT NULL,
  `is_indexable` tinyint(1) NOT NULL DEFAULT 1,
  `search_intent_metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`search_intent_metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `flight_routes_origin_code_destination_code_unique` (`origin_code`,`destination_code`),
  UNIQUE KEY `flight_routes_slug_unique` (`slug`),
  KEY `flight_routes_origin_code_index` (`origin_code`),
  KEY `flight_routes_destination_code_index` (`destination_code`),
  KEY `flight_routes_lowest_fare_gbp_index` (`lowest_fare_gbp`),
  KEY `flight_routes_is_indexable_index` (`is_indexable`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `general_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `general_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255) NOT NULL,
  `emails` text DEFAULT NULL COMMENT 'Additional email addresses',
  `phone_number` varchar(255) NOT NULL,
  `support_email` varchar(255) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `admin_email` varchar(255) DEFAULT NULL,
  `notify_admin_new_reservation` tinyint(1) NOT NULL DEFAULT 1,
  `notify_admin_stripe_no_ticket` tinyint(1) NOT NULL DEFAULT 1,
  `notify_admin_247_api_down` tinyint(1) NOT NULL DEFAULT 1,
  `notify_admin_simlesspay_api_down` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `itineraries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `itineraries` (
  `id` char(36) NOT NULL,
  `booking_id` char(36) NOT NULL,
  `itinerary_title` varchar(255) DEFAULT NULL,
  `itinerary_summary` varchar(255) DEFAULT NULL,
  `itinerary_index` int(11) DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `duration_in_minutes` int(11) DEFAULT NULL,
  `segments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`segments`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `itineraries_booking_id_index` (`booking_id`),
  CONSTRAINT `itineraries_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `markup_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `markup_rules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `operator` varchar(255) NOT NULL,
  `threshold_price` decimal(15,2) NOT NULL,
  `markup_type` enum('percentage','flat') NOT NULL,
  `markup_value` decimal(15,2) NOT NULL,
  `currency_code` varchar(3) NOT NULL DEFAULT 'gbp',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` char(36) NOT NULL,
  `transaction_ref` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'NGN',
  `status` enum('pending','completed','refunded','canceled','failed','paid') NOT NULL DEFAULT 'pending',
  `payment_method` varchar(255) NOT NULL DEFAULT 'bank_transfer',
  `stripe_session_id` varchar(255) DEFAULT NULL,
  `gateway_response` text DEFAULT NULL,
  `verified_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_transaction_ref_unique` (`transaction_ref`),
  KEY `payments_verified_by_foreign` (`verified_by`),
  KEY `payments_booking_id_index` (`booking_id`),
  KEY `payments_status_index` (`status`),
  CONSTRAINT `payments_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=159 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `price_in_pounds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `price_in_pounds` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` char(36) NOT NULL,
  `currency` varchar(255) NOT NULL DEFAULT 'GBP',
  `price` decimal(12,2) NOT NULL,
  `tax` decimal(12,2) NOT NULL,
  `markup` decimal(12,2) NOT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `price_in_pounds_booking_id_foreign` (`booking_id`),
  CONSTRAINT `price_in_pounds_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=159 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `subscribers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscribers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `is_subscribed` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscribers_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `traveler_pricings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `traveler_pricings` (
  `id` char(36) NOT NULL,
  `booking_id` char(36) NOT NULL,
  `traveler_id` varchar(255) DEFAULT NULL,
  `fare_option` varchar(255) DEFAULT NULL,
  `traveler_type` varchar(255) DEFAULT NULL,
  `price` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`price`)),
  `price_breakdown` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`price_breakdown`)),
  `fare_details_by_segment` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`fare_details_by_segment`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `traveler_pricings_booking_id_index` (`booking_id`),
  KEY `traveler_pricings_traveler_id_index` (`traveler_id`),
  CONSTRAINT `traveler_pricings_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `travelers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `travelers` (
  `id` char(36) NOT NULL,
  `booking_id` char(36) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `base_price` bigint(20) unsigned NOT NULL DEFAULT 0,
  `taxes_and_fees` bigint(20) unsigned NOT NULL DEFAULT 0,
  `total_price` bigint(20) unsigned NOT NULL DEFAULT 0,
  `gender` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `country_calling_code` varchar(255) DEFAULT NULL,
  `date_of_birth` datetime DEFAULT NULL,
  `traveler_id` varchar(255) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `travelers_booking_id_index` (`booking_id`),
  KEY `travelers_email_index` (`email`),
  KEY `travelers_traveler_id_index` (`traveler_id`),
  CONSTRAINT `travelers_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'customer',
  `gender` enum('male','female') DEFAULT NULL,
  `phone_code` varchar(255) DEFAULT NULL,
  `phone_no` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

