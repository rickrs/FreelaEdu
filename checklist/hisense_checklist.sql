-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 29-Nov-2024 às 16:44
-- Versão do servidor: 5.7.36
-- versão do PHP: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `hisense_checklist`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `categorias`
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria_nome` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `categorias`
--

INSERT INTO `categorias` (`id`, `categoria_nome`) VALUES
(1, 'TV'),
(2, 'Dish Washing'),
(3, 'Adegas'),
(4, 'Refrigeradores');

-- --------------------------------------------------------

--
-- Estrutura da tabela `categoria_detalhes`
--

DROP TABLE IF EXISTS `categoria_detalhes`;
CREATE TABLE IF NOT EXISTS `categoria_detalhes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria_id` int(11) NOT NULL,
  `campo` varchar(255) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `opcoes` text,
  `ordem` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categoria_id` (`categoria_id`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `categoria_detalhes`
--

INSERT INTO `categoria_detalhes` (`id`, `categoria_id`, `campo`, `tipo`, `opcoes`, `ordem`) VALUES
(1, 2, 'Exibicao_tipo', 'text', '', 1),
(2, 1, 'exibicao_tipo', 'select', 'Paredão Multimarcas,SMALL HOME,HOME TOP,Rack Hisense,Rack Loja,Pilha,Vitrine,Ponta de Gôndola', 1),
(3, 1, 'produto_ligado', 'boolean', NULL, 2),
(4, 1, 'modo_loja', 'boolean', NULL, 3),
(5, 1, 'material_pop', 'checkbox', 'Testeira,Stopper,Sidebanner,Sidebanner 2,Papel de Forração,Cubo,Totem,Balões', 4),
(6, 1, 'preco_visivel', 'boolean', NULL, 5),
(7, 1, 'preco_valor', 'decimal', NULL, 6),
(8, 1, 'foto_exibicao', 'file', NULL, 7),
(9, 1, 'foto_precario', 'file', NULL, 8),
(10, 2, 'exibicao_tipo', 'select', 'Chão Multimarcas,Espaço Eletrodoméstico,Espaço Hisense,Vitrine', 1),
(11, 2, 'produto_ligado', 'boolean', NULL, 2),
(12, 2, 'material_pop', 'checkbox', 'Adesivo 10 anos,Selo do Inmetro,Selo Energia Procel,Cubo,Totem,Papel de Forração,Balões,Outros', 3),
(13, 2, 'preco_visivel', 'boolean', NULL, 4),
(14, 2, 'preco_valor', 'decimal', NULL, 5),
(15, 2, 'foto_exibicao', 'file', NULL, 6),
(16, 2, 'foto_precario', 'file', NULL, 7),
(17, 2, 'lava_seca_adesivos', 'checkbox', 'Adesivo Lava e Seca,Adesivo Pausar e Adicionar Roupa,Tampo Frontal', 8),
(18, 3, 'exibicao_tipo', 'select', 'Chão Multimarcas,Espaço Hisense,Vitrine', 1),
(19, 3, 'produto_ligado', 'boolean', NULL, 2),
(20, 3, 'material_pop', 'checkbox', 'Selo do Inmetro,Selo Energia Procel,Cubo,Totem,Balões', 3),
(21, 3, 'preco_visivel', 'boolean', NULL, 4),
(22, 3, 'preco_valor', 'decimal', NULL, 5),
(23, 3, 'foto_exibicao', 'file', NULL, 6),
(24, 3, 'foto_precario', 'file', NULL, 7),
(25, 4, 'exibicao_tipo', 'select', 'Chão Multimarcas,Espaço Hisense,Vitrine', 1),
(26, 4, 'produto_ligado', 'boolean', NULL, 2),
(27, 4, 'material_pop', 'checkbox', 'Selo do Inmetro,Selo Energia Procel,Cubo,Totem,Balões,Adesivo Fruta das gavetas,POP Exibição de Latinhas', 3),
(28, 4, 'preco_visivel', 'boolean', NULL, 4),
(29, 4, 'preco_valor', 'decimal', NULL, 5),
(30, 4, 'foto_exibicao', 'file', NULL, 6),
(31, 4, 'foto_precario', 'file', NULL, 7),
(32, 4, 'adesivos_especificos', 'checkbox', 'Adesivo Fruta das gavetas,POP Exibição de Latinhas', 8),
(33, 1, 'a tv e velha', 'boolean', '', 9);

-- --------------------------------------------------------

--
-- Estrutura da tabela `checklist_detalhes`
--

DROP TABLE IF EXISTS `checklist_detalhes`;
CREATE TABLE IF NOT EXISTS `checklist_detalhes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `checklist_id` int(11) NOT NULL,
  `sku_id` int(11) NOT NULL,
  `campo` varchar(255) NOT NULL,
  `valor` text,
  PRIMARY KEY (`id`),
  KEY `checklist_id` (`checklist_id`),
  KEY `sku_id` (`sku_id`)
) ENGINE=MyISAM AUTO_INCREMENT=67 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `checklist_detalhes`
--

INSERT INTO `checklist_detalhes` (`id`, `checklist_id`, `sku_id`, `campo`, `valor`) VALUES
(1, 1, 2, 'campo_2', 'SMALL HOME'),
(2, 1, 2, 'campo_3', '1'),
(3, 1, 2, 'campo_4', '1'),
(4, 1, 2, 'campo_5', '[\"Testeira\",\"Stopper\",\"Sidebanner\"]'),
(5, 1, 2, 'campo_6', '1'),
(6, 1, 2, 'campo_7', '23'),
(7, 1, 2, 'campo_8', 'uploads/674944ee9f15d_OIP (4).jpg'),
(8, 1, 2, 'campo_9', 'uploads/674944ee9f25d_OIP (3).jpg'),
(9, 1, 8, 'campo_2', 'Pilha'),
(10, 1, 8, 'campo_3', '0'),
(11, 1, 8, 'campo_4', '1'),
(12, 1, 8, 'campo_5', '[\"Stopper\",\"Papel de Forra\\u00e7\\u00e3o\",\"Totem\"]'),
(13, 1, 8, 'campo_6', '0'),
(14, 1, 8, 'campo_7', '678'),
(15, 1, 8, 'campo_8', 'uploads/6749451d8f24c_R (2).jpg'),
(16, 1, 8, 'campo_9', 'uploads/6749451d8f35a_OIP (6).jpg'),
(17, 1, 1, 'campo_2', 'Rack Hisense'),
(18, 1, 1, 'campo_3', '1'),
(19, 1, 1, 'campo_4', '1'),
(20, 1, 1, 'campo_5', '[\"Testeira\",\"Stopper\",\"Sidebanner\"]'),
(21, 1, 1, 'campo_6', '1'),
(22, 1, 1, 'campo_7', '43.00'),
(23, 1, 1, 'campo_8', 'uploads/6749458b74cf7_procuraimg.jpg'),
(24, 1, 1, 'campo_9', 'uploads/6749458b74e30_applause-and-hand-clapping-vector-background.jpg'),
(25, 1, 10, 'campo_1', 'sdfsdf'),
(26, 1, 10, 'campo_10', 'EspaÃ§o Hisense'),
(27, 1, 10, 'campo_11', '0'),
(28, 1, 10, 'campo_12', '[\"Adesivo 10 anos\",\"Selo do Inmetro\",\"Selo Energia Procel\",\"Cubo\"]'),
(29, 1, 10, 'campo_13', '0'),
(30, 1, 10, 'campo_14', '456745'),
(31, 1, 10, 'campo_17', '[\"Adesivo Pausar e Adicionar Roupa\"]'),
(32, 1, 10, 'campo_15', 'uploads/674945c191082_40751409_m.jpg'),
(33, 1, 10, 'campo_16', 'uploads/674945c191174_OIF.jpg'),
(34, 2, 1, 'campo_2', 'Rack Hisense'),
(35, 2, 1, 'campo_3', '0'),
(36, 2, 1, 'campo_4', '0'),
(37, 2, 1, 'campo_5', '[\"Stopper\",\"Sidebanner\",\"Sidebanner 2\",\"Bal\\u00f5es\"]'),
(38, 2, 1, 'campo_6', '1'),
(39, 2, 1, 'campo_7', '23423'),
(40, 2, 1, 'campo_8', 'uploads/67494a53cc7c1_R (2).jpg'),
(41, 2, 1, 'campo_9', 'uploads/67494a53ccaa8_OIP (6).jpg'),
(42, 3, 3, 'campo_2', 'SMALL HOME'),
(43, 3, 3, 'campo_3', '1'),
(44, 3, 3, 'campo_4', '1'),
(45, 3, 3, 'campo_5', '[\"Sidebanner 2\",\"Cubo\",\"Totem\"]'),
(46, 3, 3, 'campo_6', '0'),
(47, 3, 3, 'campo_7', '777'),
(48, 3, 3, 'campo_33', '0'),
(49, 3, 3, 'campo_8', 'uploads/6749da08e6eef_OIP (1).jpg'),
(50, 3, 3, 'campo_9', 'uploads/6749da08e702e_40751409_m.jpg'),
(51, 3, 7, 'campo_2', 'Pilha'),
(52, 3, 7, 'campo_3', '1'),
(53, 3, 7, 'campo_4', '0'),
(54, 3, 7, 'campo_5', '[\"Sidebanner\",\"Cubo\"]'),
(55, 3, 7, 'campo_6', '0'),
(56, 3, 7, 'campo_7', '88'),
(57, 3, 7, 'campo_33', '1'),
(58, 3, 7, 'campo_8', 'uploads/6749da25abdca_R (2).jpg'),
(59, 3, 7, 'campo_9', 'uploads/6749da25abed6_OIP (6).jpg'),
(60, 3, 15, 'campo_18', 'EspaÃ§o Hisense'),
(61, 3, 15, 'campo_19', '1'),
(62, 3, 15, 'campo_20', '[\"Cubo\",\"Totem\",\"Bal\\u00f5es\"]'),
(63, 3, 15, 'campo_21', '0'),
(64, 3, 15, 'campo_22', '99'),
(65, 3, 15, 'campo_23', 'uploads/6749da3ea2139_OIP.jpg'),
(66, 3, 15, 'campo_24', 'uploads/6749da3ea229d_R.jpg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `checklist_form`
--

DROP TABLE IF EXISTS `checklist_form`;
CREATE TABLE IF NOT EXISTS `checklist_form` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_login_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `loja_id` int(11) NOT NULL,
  `bandeira` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_login_id` (`user_login_id`),
  KEY `fk_loja_id` (`loja_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `checklist_form`
--

INSERT INTO `checklist_form` (`id`, `user_login_id`, `created_at`, `loja_id`, `bandeira`) VALUES
(1, 3, '2024-11-29 04:50:30', 3, 'casas bahia'),
(2, 3, '2024-11-29 05:00:08', 1, 'casas bahia'),
(3, 3, '2024-11-29 15:14:14', 1, 'ponto frio'),
(4, 3, '2024-11-29 15:32:01', 1, 'iiiii');

-- --------------------------------------------------------

--
-- Estrutura da tabela `checklist_imagens`
--

DROP TABLE IF EXISTS `checklist_imagens`;
CREATE TABLE IF NOT EXISTS `checklist_imagens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `checklist_id` int(11) DEFAULT NULL,
  `imagem_nome` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `checklist_id` (`checklist_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `checklist_imagens`
--

INSERT INTO `checklist_imagens` (`id`, `checklist_id`, `imagem_nome`, `created_at`) VALUES
(1, 1, '8kE4Cg-aQt-uBWsRkdGtsQ.jpg', '2024-09-23 17:12:29'),
(2, 1, '9QWc4TPuRguuhYT8xR7ajg.jpg', '2024-09-23 17:12:29'),
(3, 1, '85Xr38IOTd6ERwRR2H0jsg.jpg', '2024-09-23 17:12:29'),
(4, 2, '8kE4Cg-aQt-uBWsRkdGtsQ.jpg', '2024-09-23 18:26:47'),
(5, 2, 'c4H9ygiQQraHfSmPO-lPKw.jpg', '2024-09-23 18:26:47'),
(6, 2, 'GxTXpVdmQR-OaM60yKll1g.jpg', '2024-09-23 18:26:47');

-- --------------------------------------------------------

--
-- Estrutura da tabela `lojas`
--

DROP TABLE IF EXISTS `lojas`;
CREATE TABLE IF NOT EXISTS `lojas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_loja` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `lojas`
--

INSERT INTO `lojas` (`id`, `nome_loja`) VALUES
(1, 'loja 002'),
(3, 'hrrttyryrt'),
(4, 'fastshop moema'),
(5, 'ponto frio barueri'),
(6, 'loja do ze.');

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

DROP TABLE IF EXISTS `produtos`;
CREATE TABLE IF NOT EXISTS `produtos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria_id` int(11) NOT NULL,
  `nome_produto` varchar(255) NOT NULL,
  `sku` varchar(100) NOT NULL,
  `status_produto` enum('ativo','inativo') DEFAULT 'ativo',
  PRIMARY KEY (`id`),
  KEY `categoria_id` (`categoria_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `produtos`
--

INSERT INTO `produtos` (`id`, `categoria_id`, `nome_produto`, `sku`, `status_produto`) VALUES
(1, 1, 'MINI 120HZ TV 55 HISENSE 55U7N', '55U7N', 'ativo'),
(2, 1, 'MINI 120HZ TV 65 HISENSE 65U7N', '65U7N', 'ativo'),
(3, 1, 'MINI 120HZ TV 75 HISENSE 75U7N', '75U7N', 'ativo'),
(4, 1, 'MINI 120HZ TV 75 HISENSE 75U8N', '75U8N', 'ativo'),
(5, 1, 'MINI 120HZ TV 85 HISENSE 85U8N', '85U8N', 'ativo'),
(6, 1, 'MINI 60HZ TV 55 HISENSE 55U6N', '55U6N', 'ativo'),
(7, 1, 'QLED TV 50 HISENSE 50Q6N', '50Q6N', 'ativo'),
(8, 1, 'SMART TV 32 HISENSE 32A4N', '32A4N', 'ativo'),
(9, 2, 'L/S 13KG INVERT HISENSE WD5Q1342BT1 127V TITANIUM', 'WD5Q1342BT1', 'ativo'),
(10, 2, 'L/S 13KG INVERT HISENSE WD5Q1342BT2 220V TITANIUM', 'WD5Q1342BT2', 'ativo'),
(11, 2, 'L/S 13 e 8 Kg [Wi-Fi] TT WD3S1343BT1', 'WD3S1343BT1', 'ativo'),
(12, 2, 'L/S 13 e 8 Kg [Wi-Fi] BC WD3S1343BW2', 'WD3S1343BW2', 'ativo'),
(13, 3, 'ADEGA - BEVERAGE COOLER - 152L - HISENSE HBL-14W', 'HBL-14W', 'ativo'),
(14, 3, 'ADEGA - WINE COOLER - 152L - HISENSE HWL-54W', 'HWL-54W', 'ativo'),
(15, 3, 'FRIGOBAR RR121NW2A', 'RR121NW2A', 'ativo'),
(16, 3, 'FRIGOBAR RR157NW3A', 'RR157NW3A', 'ativo'),
(17, 4, 'Freezer Horizontal FC185NW2A', 'FC185NW2A', 'ativo'),
(18, 4, 'Freezer Horizontal FC257NW2A', 'FC257NW2A', 'ativo'),
(19, 4, 'REF FF INVERTER 397L HISENSE RB-52W1ANRI 127V INOX', 'RB-52W1ANRI', 'ativo'),
(20, 4, 'REF INVERTER SIDE BY SIDE 533L - HISENSE - INOX - RS-69W1AIQI - 127V', 'RS-69W1AIQI', 'ativo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `user_login`
--

DROP TABLE IF EXISTS `user_login`;
CREATE TABLE IF NOT EXISTS `user_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `nome_usuario` varchar(100) NOT NULL,
  `tipo` enum('admin','user') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `user_login`
--

INSERT INTO `user_login` (`id`, `user_name`, `user_password`, `nome_usuario`, `tipo`, `created_at`) VALUES
(2, 'admin@hisense.com.br', '$2y$10$E.lzGDutOhF3tKumgJJjDeNgOfUKGIo1eHsTwDCZgJwIHnFl2xOYS', 'administrador', 'admin', '2024-09-19 16:57:37'),
(3, 'diego@hisense.com.br', '$2y$10$ryoOnufbzZVnAj19ZsMgPuzgTkE3hpuzlwpf2V1f7YfaWDnSs1mD2', 'Diego Silva', 'user', '2024-09-19 17:27:03');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
