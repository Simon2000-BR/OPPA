-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 05/12/2024 às 19:58
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `oppa`
--
CREATE DATABASE IF NOT EXISTS `oppa` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `oppa`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `animais`
--

DROP TABLE IF EXISTS `animais`;
CREATE TABLE `animais` (
  `id` int(11) NOT NULL,
  `nome` varchar(187) NOT NULL,
  `alimentacao` varchar(222) NOT NULL,
  `idade` int(11) NOT NULL,
  `producao` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `anotacoes`
--

DROP TABLE IF EXISTS `anotacoes`;
CREATE TABLE `anotacoes` (
  `id` int(11) NOT NULL,
  `titulo` varchar(222) NOT NULL,
  `descricao` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `aves`
--

DROP TABLE IF EXISTS `aves`;
CREATE TABLE `aves` (
  `id` int(11) NOT NULL,
  `nome` varchar(245) NOT NULL,
  `alimentacao` varchar(225) NOT NULL,
  `idade` int(11) NOT NULL,
  `producao` varchar(235) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `bovinos`
--

DROP TABLE IF EXISTS `bovinos`;
CREATE TABLE `bovinos` (
  `id` int(11) NOT NULL,
  `nome` varchar(155) NOT NULL,
  `alimentacao` varchar(245) NOT NULL,
  `idade` int(11) NOT NULL,
  `producao` varchar(235) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cadastro`
--

DROP TABLE IF EXISTS `cadastro`;
CREATE TABLE `cadastro` (
  `id` int(11) NOT NULL,
  `nome` varchar(94) DEFAULT NULL,
  `email` varchar(230) DEFAULT NULL,
  `senha` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionarios`
--

DROP TABLE IF EXISTS `funcionarios`;
CREATE TABLE `funcionarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(188) NOT NULL,
  `cargo` varchar(245) NOT NULL,
  `salario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `insumos`
--

DROP TABLE IF EXISTS `insumos`;
CREATE TABLE `insumos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `quantidade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `maquinarios`
--

DROP TABLE IF EXISTS `maquinarios`;
CREATE TABLE `maquinarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(239) NOT NULL,
  `funcionando` varchar(235) NOT NULL,
  `local` varchar(198) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ovelhas`
--

DROP TABLE IF EXISTS `ovelhas`;
CREATE TABLE `ovelhas` (
  `id` int(11) NOT NULL,
  `nome` varchar(155) NOT NULL,
  `alimentacao` varchar(245) NOT NULL,
  `idade` int(11) NOT NULL,
  `producao` varchar(235) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `suinos`
--

DROP TABLE IF EXISTS `suinos`;
CREATE TABLE `suinos` (
  `id` int(11) NOT NULL,
  `nome` varchar(155) NOT NULL,
  `alimentacao` varchar(245) NOT NULL,
  `idade` int(11) NOT NULL,
  `producao` varchar(235) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `animais`
--
ALTER TABLE `animais`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `anotacoes`
--
ALTER TABLE `anotacoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `aves`
--
ALTER TABLE `aves`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `bovinos`
--
ALTER TABLE `bovinos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `cadastro`
--
ALTER TABLE `cadastro`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `insumos`
--
ALTER TABLE `insumos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `maquinarios`
--
ALTER TABLE `maquinarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `ovelhas`
--
ALTER TABLE `ovelhas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `suinos`
--
ALTER TABLE `suinos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `animais`
--
ALTER TABLE `animais`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `anotacoes`
--
ALTER TABLE `anotacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `aves`
--
ALTER TABLE `aves`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `bovinos`
--
ALTER TABLE `bovinos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cadastro`
--
ALTER TABLE `cadastro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `insumos`
--
ALTER TABLE `insumos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `maquinarios`
--
ALTER TABLE `maquinarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ovelhas`
--
ALTER TABLE `ovelhas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `suinos`
--
ALTER TABLE `suinos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
