-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 24/07/2025 às 10:35
-- Versão do servidor: 9.1.0
-- Versão do PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `constrular`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `projetos`
--

DROP TABLE IF EXISTS `projetos`;
CREATE TABLE IF NOT EXISTS `projetos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `status` enum('pendente','aprovado','rejeitado','cancelado') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'pendente',
  `email` varchar(150) NOT NULL,
  `email_contato` varchar(250) NOT NULL,
  `descricao` text NOT NULL,
  `data_agendamento` date DEFAULT NULL,
  `resposta_admin` text,
  `tipo_projeto` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `projetos`
--

INSERT INTO `projetos` (`id`, `titulo`, `imagem`, `status`, `email`, `email_contato`, `descricao`, `data_agendamento`, `resposta_admin`, `tipo_projeto`, `data_criacao`) VALUES
(26, 'DDFDDDF', '688207a7e21e7.jpeg', 'aprovado', 'raiane.silvah2806@gmail.com', 'raiane.silvah2806@gmail.com', 'o', '2025-07-27', '', 'comercial', '2025-07-24 10:15:03'),
(27, 'ola', '6882080d4f603.jpeg', '', 'gabriel@gmail.com', 'gabriel@gmail.com', 'yyy', NULL, 'ff', 'reforma', '2025-07-24 10:16:45'),
(25, 'renova casa ', '68820661b3c23.jpg', 'cancelado', 'raiane.silvah2806@gmail.com', 'raiane.silvah2806@gmail.com', 'o', NULL, NULL, 'residencial', '2025-07-24 10:09:37');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(155) NOT NULL,
  `apelido` varchar(250) NOT NULL,
  `email` varchar(155) NOT NULL,
  `senha` varchar(155) NOT NULL,
  `perfil_foto` varchar(155) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `telefone` varchar(20) NOT NULL,
  `data_nascimento` date NOT NULL,
  `endereco` varchar(200) NOT NULL,
  `ativo` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `apelido`, `email`, `senha`, `perfil_foto`, `telefone`, `data_nascimento`, `endereco`, `ativo`, `created_at`) VALUES
(1, 'Admin', '', 'admin@gmail.com', 'admin123', 'perfil_admin_gmail_com_1753101704_4008.jpg', '+351 946 449 226', '0000-00-00', '', 1, '2025-07-20 17:38:33');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
