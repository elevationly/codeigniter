# warehouse
库存系统
2019年6月18日22:40:01
ALTER TABLE `ci_orders`   
ADD INDEX `billType` (`billType`) ,  
ADD INDEX `checked` (`checked`) ,  
ADD INDEX `isDelete` (`isDelete`) ,  
ADD INDEX `transType` (`transType`) ,  
ADD INDEX `liname` (`liname`) ,  
ADD INDEX `billNo` (`billNo`) ;

		ALTER TABLE `ci_contact`  
ADD INDEX `isDelete` (`isDelete`);
		ALTER TABLE `ci_staff`  
ADD INDEX `isDelete` (`isDelete`);
		ALTER TABLE `ci_account`  
ADD INDEX `isDelete` (`isDelete`);
		ALTER TABLE `ci_verifica_info`  
ADD INDEX `isDelete` (`isDelete`),  
ADD INDEX `nowCheck` (`nowCheck`),  
ADD INDEX `billId` (`billId`);


ALTER TABLE ci_contact ADD `check` tinyint(1) DEFAULT '0' COMMENT '0未核对 1已核对';
ALTER TABLE ci_contact ADD `xd_name` varchar(255) DEFAULT NULL COMMENT '下达项目名称';
ALTER TABLE ci_contact ADD `xd_order` varchar(255) DEFAULT NULL COMMENT '下达编号';

修改php.ini

always_populate_raw_post_data = -1

2019年7月10日22:54:46
ALTER TABLE `ci_orders_info`   
ADD INDEX `locationName` (`locationName`) ,
ADD INDEX `chuku_status` (`chuku_status`) ;  
