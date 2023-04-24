CREATE TABLE IF NOT EXISTS `mc_transport` (
    `id_tr` int(7) unsigned NOT NULL AUTO_INCREMENT,
    `price_tr` decimal(12,2) NULL DEFAULT '0.00',
    `name_tr` varchar(50) DEFAULT NULL,
    `postcode_tr` varchar(10) DEFAULT NULL,
    `country_tr` varchar(10) DEFAULT NULL,
    `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_tr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `mc_cartpay_transport` (
    `id_cart_tr` int(7) UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_tr` int(7) UNSIGNED DEFAULT NULL,
    `id_cart` int(7) UNSIGNED NOT NULL,
    `id_buyer` int(11) UNSIGNED NOT NULL,
    `type_ct` enum('delivery','pick_up_in_store') NOT NULL DEFAULT 'delivery',
    `lastname_ct` varchar(50) DEFAULT NULL,
    `firstname_ct` varchar(50) DEFAULT NULL,
    `street_ct` varchar(50) DEFAULT NULL,
    `city_ct` varchar(40) DEFAULT NULL,
    `postcode_ct` varchar(10) DEFAULT NULL,
    `event_ct` varchar(40) DEFAULT NULL,
    `delivery_date_ct` date DEFAULT '0000-00-00',
    `timeslot_ct` varchar(30) DEFAULT NULL,
    `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_cart_tr`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;