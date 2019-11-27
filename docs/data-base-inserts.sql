-- --------------------------------------------------------
-- Servidor:                     192.168.75.129
-- Versão do servidor:           5.7.26 - MySQL Community Server (GPL)
-- OS do Servidor:               Linux
-- HeidiSQL Versão:              10.1.0.5464
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Copiando estrutura do banco de dados para users
CREATE DATABASE IF NOT EXISTS `users` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `users`;

-- Copiando estrutura para tabela users.log_transaction
CREATE TABLE IF NOT EXISTS `log_transaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_transaction` int(11) NOT NULL,
  `payee_id` int(11) NOT NULL,
  `payer_id` int(11) NOT NULL,
  `ammount` decimal(15,2) NOT NULL,
  `authorization_code` varchar(37) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela users.log_transaction: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `log_transaction` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_transaction` ENABLE KEYS */;

-- Copiando estrutura para tabela users.log_user_account_balance
CREATE TABLE IF NOT EXISTS `log_user_account_balance` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_account` int(11) NOT NULL,
  `ammount` int(11) NOT NULL,
  `ammount_operation` int(11) NOT NULL COMMENT 'diff between old and new amount',
  `type_operation` int(11) NOT NULL COMMENT 'add  or remove',
  `update_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela users.log_user_account_balance: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `log_user_account_balance` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_user_account_balance` ENABLE KEYS */;

-- Copiando estrutura para tabela users.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela users.migrations: ~7 rows (aproximadamente)
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(16, '2019_11_26_124854_user_table_migration', 1),
	(17, '2019_11_26_135631_user_consumer_table_migration', 1),
	(18, '2019_11_26_142001_user_seller_table_migration', 1),
	(19, '2019_11_26_142623_user_account_balance_table_migration', 1),
	(20, '2019_11_26_142639_log_user_account_balance_table_migration', 1),
	(21, '2019_11_26_142709_trasaction_table_migration', 1),
	(22, '2019_11_26_142724_log_trasaction_table_migration', 1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

-- Copiando estrutura para tabela users.transaction
CREATE TABLE IF NOT EXISTS `transaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `payee_id` int(11) NOT NULL,
  `payer_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `authorization_code` varchar(37) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_transaction` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela users.transaction: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `transaction` DISABLE KEYS */;
INSERT INTO `transaction` (`id`, `payee_id`, `payer_id`, `amount`, `authorization_code`, `date_transaction`) VALUES
	(1, 1, 1, 80.00, '6fa47341-1127-11ea-93ca-0242ac160002', '2019-11-27 15:06:06');
/*!40000 ALTER TABLE `transaction` ENABLE KEYS */;

-- Copiando estrutura para tabela users.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cpf` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `full_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'test user dev',
  `password` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `phone_number` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_full_name_search_index` (`full_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela users.user: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `cpf`, `email`, `full_name`, `username`, `password`, `phone_number`) VALUES
	(1, '35590959802', 'diego.silva_19@yahoo.com.br', 'Diego Silva', NULL, '123', '11986542491'),
	(2, '57871231073', 'carlos@yahoo.com.br', 'Calors santos', NULL, '123', '11986542421');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

-- Copiando estrutura para tabela users.user_account_balance
CREATE TABLE IF NOT EXISTS `user_account_balance` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `type_user_account` enum('seller','consumer') COLLATE utf8_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela users.user_account_balance: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `user_account_balance` DISABLE KEYS */;
INSERT INTO `user_account_balance` (`id`, `id_user`, `type_user_account`, `amount`) VALUES
	(1, 1, 'consumer', 0.00),
	(2, 2, 'seller', 0.00);
/*!40000 ALTER TABLE `user_account_balance` ENABLE KEYS */;

-- Copiando estrutura para tabela users.user_consumer
CREATE TABLE IF NOT EXISTS `user_consumer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_consumer_id_user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela users.user_consumer: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `user_consumer` DISABLE KEYS */;
INSERT INTO `user_consumer` (`id`, `id_user`, `username`) VALUES
	(1, 1, 'dtavares');
/*!40000 ALTER TABLE `user_consumer` ENABLE KEYS */;

-- Copiando estrutura para tabela users.user_seller
CREATE TABLE IF NOT EXISTS `user_seller` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cnpj` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
  `fantasy_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `social_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `id_user` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_seller_id_user_unique` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela users.user_seller: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `user_seller` DISABLE KEYS */;
INSERT INTO `user_seller` (`id`, `cnpj`, `fantasy_name`, `social_name`, `username`, `id_user`) VALUES
	(1, '45786375000167', 'Empresa nova', 'Nnova .com', 'empresa-tes', 2);
/*!40000 ALTER TABLE `user_seller` ENABLE KEYS */;

-- Copiando estrutura para trigger users.before_insert_transaction_generate_authorization
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `before_insert_transaction_generate_authorization` BEFORE INSERT ON `transaction` FOR EACH ROW SET new.authorization_code = uuid()//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
