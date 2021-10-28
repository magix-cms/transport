CREATE TABLE IF NOT EXISTS `mc_transport` (
    `id_tr` int(7) unsigned NOT NULL AUTO_INCREMENT,
    `price_tr` decimal(12,2) NULL DEFAULT '0.00',
    `name_tr` varchar(50) DEFAULT NULL,
    `postcode_tr` varchar(10) DEFAULT NULL,
    `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_tr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_cartpay_transport` (
    `id_cart_tr` int(7) unsigned NOT NULL AUTO_INCREMENT,
    `id_tr` int(7) unsigned NOT NULL,
    `id_cart` int(7) unsigned NOT NULL,
    `id_buyer` int(11) unsigned NOT NULL,
    `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_cart_tr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `mc_admin_access` (`id_role`, `id_module`, `view`, `append`, `edit`, `del`, `action`)
SELECT 1, m.id_module, 1, 1, 1, 1, 1 FROM mc_module as m WHERE name = 'transport';