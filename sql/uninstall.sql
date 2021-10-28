TRUNCATE TABLE `mc_cartpay_transport`;
DROP TABLE `mc_cartpay_transport`;
TRUNCATE TABLE `mc_transport`;
DROP TABLE `mc_transport`;

DELETE FROM `mc_plugins_module` WHERE `module_name` = 'transport';

DELETE FROM `mc_plugins` WHERE `name` = 'transport';

DELETE FROM `mc_admin_access` WHERE `id_module` IN (
    SELECT `id_module` FROM `mc_module` as m WHERE m.name = 'transport'
);