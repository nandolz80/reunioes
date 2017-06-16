-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 16-Jun-2017 às 01:55
-- Versão do servidor: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `salareuniao`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `agenda`
--

CREATE TABLE `agenda` (
  `id` int(11) NOT NULL,
  `id_sala` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `description` text,
  `usuario` varchar(50) NOT NULL,
  `date_insert` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `color` varchar(7) NOT NULL DEFAULT '#3a87ad'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `agenda`
--

INSERT INTO `agenda` (`id`, `id_sala`, `date`, `description`, `usuario`, `date_insert`, `color`) VALUES
(4, 1, '2017-06-04 08:00:00', 'Curso ingles', 'admin', '2017-06-11 14:22:44', '#20b3c7'),
(2, 2, '2017-06-06 11:30:00', 'TI', 'admin', '2017-06-11 11:30:00', '#33ed46'),
(3, 4, '2017-05-30 13:31:00', 'Financeiro', 'admin', '2017-06-11 12:22:25', '#3a87ad'),
(15, 0, '2017-05-28 09:00:00', 'teste fixo', 'admin', '2017-06-15 19:55:02', '#22cfa5'),
(16, 0, '2017-05-28 11:00:00', 'testew fixo', 'admin', '2017-06-15 19:55:22', '#22cfa5'),
(14, 2, '2017-05-29 19:00:00', 'teste 03', 'admin', '2017-06-15 19:08:59', '#3a87ad'),
(13, 0, '2017-05-28 19:07:00', 'tste', 'admin', '2017-06-15 19:08:06', '#3a87ad'),
(12, 4, '2017-05-29 15:00:00', 'Teste sala 03', 'admin', '2017-06-15 19:06:25', '#3a87ad'),
(17, 0, '2017-05-28 08:00:00', 'Teste fixo', 'admin', '2017-06-15 19:56:01', '#3a87ad'),
(19, 0, '2017-05-28 16:00:00', 'teste', 'admin', '2017-06-15 20:04:27', '#bf3307'),
(20, 2, '2017-05-28 08:00:00', 'teste', 'admin', '2017-06-15 20:15:36', '#3a87ad'),
(21, 4, '2017-06-01 09:00:00', 'teste', 'admin', '2017-06-15 20:16:24', '#3a87ad'),
(22, 2, '2017-06-02 08:00:00', 'teeste 02', 'admin', '2017-06-15 20:17:13', '#3a87ad'),
(23, 2, '2017-06-03 09:00:00', 'teste', 'admin', '2017-06-15 20:19:27', '#3a87ad'),
(24, 4, '2017-06-04 08:00:00', 'Teste sala 02', 'admin', '2017-06-15 20:39:24', '#27bf76'),
(25, 2, '2017-06-04 08:00:00', 'teste sa', 'admin', '2017-06-15 20:40:54', '#f2bf08'),
(26, 2, '2017-06-04 09:00:00', 'asas', 'admin', '2017-06-15 20:45:44', '#3a87ad'),
(27, 2, '2017-05-31 10:00:00', 'teste', 'teste', '2017-06-15 20:47:12', '#d91dc6'),
(28, 4, '2017-06-15 08:00:00', 'Reuniao sala', 'teste', '2017-06-15 20:47:51', '#514ee3'),
(29, 2, '2017-06-16 08:00:00', 'Sala reunião', 'teste', '2017-06-15 20:48:38', '#34b1cc'),
(30, 4, '2017-06-17 12:00:00', 'Reunião almoço', 'teste', '2017-06-15 20:48:57', '#3a87ad');

-- --------------------------------------------------------

--
-- Estrutura da tabela `salas`
--

CREATE TABLE `salas` (
  `id` int(11) NOT NULL,
  `nome` varchar(80) NOT NULL,
  `descricao` varchar(100) DEFAULT NULL,
  `capacidade` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `salas`
--

INSERT INTO `salas` (`id`, `nome`, `descricao`, `capacidade`) VALUES
(1, 'Sala 01', 'Sala de vidro', 52),
(2, 'Sala 02', 'Sala corredor', 40),
(4, 'Sala 03', 'Aquário', 60);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `login` varchar(100) NOT NULL,
  `senha` varchar(20) NOT NULL,
  `lastLogin` datetime NOT NULL,
  `lastIp` varchar(50) NOT NULL,
  `id_usuario_tipo` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `login`, `senha`, `lastLogin`, `lastIp`, `id_usuario_tipo`) VALUES
(1, 'admin', 'admin', '2017-06-15 19:48:38', '127.0.0.1', 1),
(9, 'teste', 'teste', '2017-06-15 20:54:48', '127.0.0.1', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agenda`
--
ALTER TABLE `agenda`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salas`
--
ALTER TABLE `salas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agenda`
--
ALTER TABLE `agenda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `salas`
--
ALTER TABLE `salas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
