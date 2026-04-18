-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 13/02/2026 às 23:12
-- Versão do servidor: 11.8.3-MariaDB-log
-- Versão do PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;



-- --------------------------------------------------------

--
-- Estrutura para tabela `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `nome` text NOT NULL,
  `email` text NOT NULL,
  `contato` text DEFAULT NULL,
  `senha` text NOT NULL,
  `nivel` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `token_recover` text DEFAULT NULL,
  `avatar` text DEFAULT NULL,
  `2fa` text DEFAULT NULL,
  `2fa_auth_login` text DEFAULT NULL,
  `2fa_auth_withdraw` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `admin_users`
--

INSERT INTO `admin_users` (`id`, `nome`, `email`, `contato`, `senha`, `nivel`, `status`, `token_recover`, `avatar`, `2fa`, `2fa_auth_login`, `2fa_auth_withdraw`) VALUES
(1, 'admin', 'admin@gmail.com', NULL, '$2a$12$00nK0dcuOx4aydHADC87X.tCVOsBJsMQ4h6jTkpbU/xXskf95/fRm', 0, 1, NULL, NULL, '2803', '280324', '280324');

-- --------------------------------------------------------

--
-- Estrutura para tabela `afiliados_config`
--

CREATE TABLE `afiliados_config` (
  `id` int(11) NOT NULL,
  `cpaLvl1` decimal(10,2) DEFAULT NULL,
  `cpaLvl2` decimal(10,2) DEFAULT NULL,
  `cpaLvl3` decimal(10,2) DEFAULT NULL,
  `chanceCpa` decimal(5,2) DEFAULT NULL,
  `revShareFalso` decimal(5,2) DEFAULT NULL,
  `revShareLvl1` decimal(5,2) DEFAULT NULL,
  `revShareLvl2` decimal(5,2) DEFAULT NULL,
  `revShareLvl3` decimal(5,2) DEFAULT NULL,
  `minDepForCpa` decimal(10,2) DEFAULT NULL,
  `minResgate` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `afiliados_config`
--

INSERT INTO `afiliados_config` (`id`, `cpaLvl1`, `cpaLvl2`, `cpaLvl3`, `chanceCpa`, `revShareFalso`, `revShareLvl1`, `revShareLvl2`, `revShareLvl3`, `minDepForCpa`, `minResgate`) VALUES
(1, 0.00, 0.00, 0.00, 1.00, 0.00, 15.00, 0.00, 0.00, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `banner`
--

CREATE TABLE `banner` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `img` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `banner`
--

INSERT INTO `banner` (`id`, `titulo`, `criado_em`, `img`, `status`) VALUES
(1, 'Banner 1', '2024-06-28 18:10:47', '1753663892_1752016760_IMG_0662.png', 1),
(2, 'Banner 2', '2024-06-28 18:08:02', '1750744371_BANNER REGRAS DE RECARGA KING777.png', 1),
(3, 'Banner 3', '2024-06-28 18:10:47', '1750708139_BANNER SUPORTE  KING777.png', 1),
(4, 'Banner 4', '2024-06-28 18:08:02', '1748301677_f416c36d-250a-416c-a400-c54a0882d9602A.png', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `bau`
--

CREATE TABLE `bau` (
  `id` int(11) NOT NULL,
  `num` text DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `is_get` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `bau`
--

INSERT INTO `bau` (`id`, `num`, `status`, `token`, `is_get`) VALUES
(486037, '', 'user novo', '4831687b7b0146cc2abecc5f12691067d0234aaa58003b71f37255931c096403', 0),
(486038, '', 'user novo demo', '2306cef20599412c8e4846ef45583733', 0),
(486039, '', 'user novo demo', '311f11ffda0c828c56810297514fa604', 0),
(486040, '', 'user novo demo', '7e23d72b207146e1f8bf9420120f0a58', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `bonus_config`
--

CREATE TABLE `bonus_config` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `porcentagem` decimal(5,2) NOT NULL,
  `limite_usos` int(11) DEFAULT NULL,
  `usos_atuais` int(11) DEFAULT 0,
  `ativo` tinyint(1) DEFAULT 1,
  `data_criacao` timestamp NULL DEFAULT current_timestamp(),
  `data_expiracao` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `bonus_config`
--

INSERT INTO `bonus_config` (`id`, `nome`, `porcentagem`, `limite_usos`, `usos_atuais`, `ativo`, `data_criacao`, `data_expiracao`) VALUES
(1, 'Bônus Primeiro Depósito 100%', 100.00, 1000, 63, 1, '2025-06-24 04:07:55', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `bonus_historico`
--

CREATE TABLE `bonus_historico` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bonus_config_id` int(11) NOT NULL,
  `valor_depositado` decimal(10,2) NOT NULL,
  `porcentagem_aplicada` decimal(5,2) NOT NULL,
  `valor_bonus` decimal(10,2) NOT NULL,
  `data_aplicacao` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `bspay`
--

CREATE TABLE `bspay` (
  `id` int(11) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `client_id` text DEFAULT NULL,
  `client_secret` text DEFAULT NULL,
  `atualizado` varchar(45) DEFAULT NULL,
  `ativo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `bspay`
--

INSERT INTO `bspay` (`id`, `url`, `client_id`, `client_secret`, `atualizado`, `ativo`) VALUES
(1, 'https://api.pixupbr.com', 'Client ID', 'Client Secret', '0', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `bullspay`
--

CREATE TABLE `bullspay` (
  `id` int(11) NOT NULL,
  `client_id` varchar(255) NOT NULL DEFAULT '',
  `client_secret` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT 'https://ggpixapi.com/api/v1',
  `ativo` tinyint(1) NOT NULL DEFAULT 0,
  `atualizado` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `bullspay`
--

INSERT INTO `bullspay` (`id`, `client_id`, `client_secret`, `url`, `ativo`, `atualizado`, `created_at`, `updated_at`) VALUES
(1, 'BP ID', 'BP SECRET', 'https://ggpixapi.com/api/v1', 1, 1, '2025-09-05 16:52:48', '2025-09-06 01:01:22');

-- --------------------------------------------------------

--
-- Estrutura para tabela `config`
--

CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `nome_site` mediumtext DEFAULT NULL,
  `descricao` mediumtext DEFAULT NULL,
  `grupoplataforma` varchar(255) DEFAULT NULL,
  `logo` mediumtext DEFAULT NULL,
  `avatar` mediumtext DEFAULT NULL,
  `telegram` mediumtext DEFAULT NULL,
  `instagram` mediumtext DEFAULT NULL,
  `whatsapp` mediumtext DEFAULT NULL,
  `suporte` mediumtext DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `sublogo` mediumtext DEFAULT NULL,
  `facebookads` mediumtext DEFAULT NULL,
  `rodapelogo` text DEFAULT NULL,
  `favicon` text DEFAULT NULL,
  `logoapp` text DEFAULT NULL,
  `googleAnalytics` mediumtext DEFAULT NULL,
  `minplay` int(11) DEFAULT NULL,
  `minsaque` double DEFAULT NULL,
  `maxsaque` int(11) DEFAULT 1000,
  `saque_automatico` int(11) NOT NULL,
  `rollover` int(11) DEFAULT NULL,
  `minimo_deposito` int(255) DEFAULT NULL,
  `mindep` mediumtext DEFAULT NULL,
  `jackpot` int(11) DEFAULT NULL,
  `navbar` int(11) DEFAULT NULL,
  `numero_jackpot` int(11) DEFAULT NULL,
  `jackpot_custom` mediumtext DEFAULT NULL,
  `cor_padrao` varchar(45) NOT NULL,
  `background_padrao` varchar(50) DEFAULT NULL,
  `custom_css` longtext NOT NULL,
  `texto` varchar(45) NOT NULL,
  `img_seo` mediumtext DEFAULT NULL,
  `keyword` mediumtext DEFAULT NULL,
  `marquee` mediumtext DEFAULT NULL,
  `status_topheader` int(11) NOT NULL DEFAULT 0,
  `cor_topheader` varchar(48) DEFAULT '#ed1c24',
  `niveisbau` mediumtext DEFAULT NULL,
  `qntsbaus` int(11) DEFAULT NULL,
  `nvlbau` int(11) DEFAULT NULL,
  `pessoasbau` int(11) DEFAULT NULL,
  `tema` int(11) DEFAULT NULL,
  `versao_app_android` mediumtext DEFAULT NULL,
  `versao_app_ios` mediumtext DEFAULT NULL,
  `mensagem_app` mediumtext DEFAULT NULL,
  `link_app_android` mediumtext DEFAULT NULL,
  `link_app_ios` mediumtext DEFAULT NULL,
  `broadcast` mediumtext DEFAULT NULL,
  `limite_saque` int(11) DEFAULT 0,
  `sort_jackpot` int(11) DEFAULT 1,
  `baixar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Despejando dados para a tabela `config`
--

INSERT INTO `config` (`id`, `nome`, `nome_site`, `descricao`, `grupoplataforma`, `logo`, `avatar`, `telegram`, `instagram`, `whatsapp`, `suporte`, `email`, `sublogo`, `facebookads`, `rodapelogo`, `favicon`, `logoapp`, `googleAnalytics`, `minplay`, `minsaque`, `maxsaque`, `saque_automatico`, `rollover`, `minimo_deposito`, `mindep`, `jackpot`, `navbar`, `numero_jackpot`, `jackpot_custom`, `cor_padrao`, `background_padrao`, `custom_css`, `texto`, `img_seo`, `keyword`, `marquee`, `status_topheader`, `cor_topheader`, `niveisbau`, `qntsbaus`, `nvlbau`, `pessoasbau`, `tema`, `versao_app_android`, `versao_app_ios`, `mensagem_app`, `link_app_android`, `link_app_ios`, `broadcast`, `limite_saque`, `sort_jackpot`, `baixar`) VALUES
(1, 'KING777', '', 'Grupo F.S.', '—', 'logo.png.webp', 'favicon.png', 'seugrupoaqui', '', '', NULL, '', '', '', NULL, 'favicon.png.webp', 'logoapp.png', '', 1, 2, 5000, 0, 3, 2, '10,20,30,50,100,150,200,300,400,500,1000,2000', 2, 1, 1, NULL, '#0096DD', '#010e24', '', '', '154504365733.png', 'Cassino, plataforma chinesa, casa pagante', 'BEM VINDO A KING777, UMA PLATAFORMA DO GRUPO FS', 0, '#0096dd', '10,20,30,40,50', 100, 5, 1, 37, '2.5.0.1', '2.5.0.1', 'Baixe o APP', 'Baixe o APP', 'Baixe o APP', '0', 5000, 2, 'baixar.png');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cupom`
--

CREATE TABLE `cupom` (
  `id` int(11) NOT NULL,
  `nome` text NOT NULL,
  `tipo` int(11) NOT NULL DEFAULT 2,
  `valor` int(11) NOT NULL,
  `qtd` int(11) NOT NULL DEFAULT 0,
  `qtd_insert` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cupom`
--

INSERT INTO `cupom` (`id`, `nome`, `tipo`, `valor`, `qtd`, `qtd_insert`, `status`) VALUES
(1, 'Bônus de Depósito', 2, 10, 1000, 5, 0),
(2, 'Bônus de Depósito 2x', 2, 20, 1000, 10, 0),
(3, 'Bônus de Depósito 3x', 2, 30, 1000, 15, 0),
(4, 'Bônus de Depósito 4x', 2, 40, 1000, 20, 0),
(5, 'Bônus de Depósito 5x', 2, 50, 1000, 25, 0),
(6, 'Bônus de Depósito 6x', 2, 60, 1000, 30, 0),
(7, 'Bônus de Depósito 7x', 2, 70, 1000, 35, 0),
(8, 'Bônus de Depósito 8x', 2, 80, 1000, 40, 0),
(9, 'Bônus de Depósito 9x', 2, 90, 1000, 45, 0),
(10, 'Bônus de Depósito 10x', 2, 100, 1000, 50, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `cupom_usados`
--

CREATE TABLE `cupom_usados` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_cupom` int(11) NOT NULL,
  `valor` int(11) NOT NULL DEFAULT 0,
  `data_time` datetime DEFAULT NULL,
  `tipo` enum('cadastro','deposito') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `expfypay`
--

CREATE TABLE `expfypay` (
  `id` int(11) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `client_id` text DEFAULT NULL,
  `client_secret` text DEFAULT NULL,
  `atualizado` varchar(45) DEFAULT NULL,
  `ativo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `expfypay`
--

INSERT INTO `expfypay` (`id`, `url`, `client_id`, `client_secret`, `atualizado`, `ativo`) VALUES
(1, 'https://expfypay.com', 'pk_b05af5004158e36f4e668d899562182e2e81c87c3bc80bd6', 'sk_7971a2658c80461cbbf79f5dee151898aac7dbcab746c20f287ed1d1c731b521', '0', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `financeiro`
--

CREATE TABLE `financeiro` (
  `id` int(11) NOT NULL,
  `usuario` int(11) DEFAULT NULL,
  `saldo` decimal(10,2) DEFAULT NULL,
  `bonus` decimal(10,2) DEFAULT NULL,
  `saldo_afiliados` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fivepay`
--

CREATE TABLE `fivepay` (
  `id` int(11) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `client_id` text DEFAULT NULL,
  `client_secret` text DEFAULT NULL,
  `atualizado` varchar(45) DEFAULT NULL,
  `ativo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `fivepay`
--

INSERT INTO `fivepay` (`id`, `url`, `client_id`, `client_secret`, `atualizado`, `ativo`) VALUES
(1, 'https://safira-pg.fun', '12257412314253', '8758fac62ba9bceb45c42629c159b214523a146adf34077235aeea30bdd3398d', '0', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `fiverscan`
--

CREATE TABLE `fiverscan` (
  `id` int(11) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `agent_code` text DEFAULT NULL,
  `agent_token` text DEFAULT NULL,
  `ativo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `fiverscan`
--

INSERT INTO `fiverscan` (`id`, `url`, `agent_code`, `agent_token`, `ativo`) VALUES
(1, 'https://api.playfivers.com', '2e9c343a-73de-4bb0-9dec-1dfd4a70cc11', 'd2352e22-a2b6-4738-bba8-454356fbea45', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `floats`
--

CREATE TABLE `floats` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `img` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

--
-- Despejando dados para a tabela `floats`
--

INSERT INTO `floats` (`id`, `titulo`, `criado_em`, `img`, `status`) VALUES
(1, 'Float 1', '2024-06-28 21:10:47', '/image/1730417905960..gif', 1),
(2, 'Float 2', '2024-06-28 21:08:02', '/image/1730328870512..webp', 1),
(3, 'Float 3', '2024-06-28 21:08:02', '/image/1709636294056..gif', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `game_code` text NOT NULL,
  `game_name` text NOT NULL,
  `banner` text DEFAULT NULL,
  `status` int(11) NOT NULL,
  `provider` text DEFAULT NULL,
  `popular` int(11) NOT NULL DEFAULT 0,
  `type` text DEFAULT NULL,
  `game_type` int(11) DEFAULT NULL,
  `distribution` varchar(255) NOT NULL DEFAULT 'apipg16',
  `free_bonus_rounds` int(11) DEFAULT 0,
  `game_original` tinyint(1) DEFAULT 0 COMMENT '0 = jogo falso, 1 = jogo original',
  `supports_free_spins` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `games`
--

INSERT INTO `games` (`id`, `game_code`, `game_name`, `banner`, `status`, `provider`, `popular`, `type`, `game_type`, `distribution`, `free_bonus_rounds`, `game_original`, `supports_free_spins`) VALUES
(2136, '777super-strike', '777 Super Strike', 'https://maxapigames.com/panel/assets/games/777.png', 1, 'REDTIGER', 1, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2137, 'alice', 'Alice\'s Wonder', 'https://maxapigames.com/panel/assets/games/aliceswonders.png', 1, 'ASKMESLOTS', 1, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2138, 'animal-kingdom', 'Animal Kingdom', 'https://maxapigames.com/panel/assets/games/animalkingdom.webp', 1, 'WG', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2139, 'anubis-wrath', 'Anubis Wrath', 'https://maxapigames.com/panel/assets/games/anubiswrath.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2140, 'asgardian-rising', 'Asgardian Rising', 'https://maxapigames.com/panel/assets/games/asgardianrising.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2141, 'astros', 'Astros', 'https://maxapigames.com/panel/assets/games/astros.webp', 1, 'REDTIGER', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2142, 'bakery-bonanza', 'Bakery Bonanza', 'https://maxapigames.com/panel/assets/games/bakerybinanza.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2143, 'bali-vacation', 'Bali Vacation', 'https://maxapigames.com/panel/assets/games/balivacation.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2144, 'bao-boon-chin', 'Bao Boon Chin', 'https://maxapigames.com/panel/assets/games/baoboonchin.png', 1, 'JILI', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2145, 'battleground-royale', 'Battleground Royale', 'https://maxapigames.com/panel/assets/games/battlegroundroyale.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2146, 'beauty-and-the-kingdom', 'Beauty And The Kingdom', 'https://maxapigames.com/panel/assets/games/beautyandthekingdom.png', 1, 'slot-jdb', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2147, 'big-bass-bonanza', 'Big Bass Bonanza', 'https://maxapigames.com/panel/assets/games/bigbassbonanza.webp', 1, 'PRAGMATIC', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2148, 'big-bass-boxing', 'Big Bass Boxing', 'https://maxapigames.com/panel/assets/games/bigbassboxing.png', 1, 'PRAGMATIC', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2149, 'big-three-dragons', 'Big Three Dragons', 'https://maxapigames.com/panel/assets/games/bigthreedragons.png', 1, 'slot-jdb', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2150, 'bikini-paradise', 'Bikini Paradise', 'https://maxapigames.com/panel/assets/games/bikiniparadise.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2151, 'black-myth-wukong', 'Black Myth Wukong', 'https://maxapigames.com/panel/assets/games/blackmythwukong.webp', 1, 'WG', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2152, 'blessingdragon', 'Blessing of the Dragon', 'https://maxapigames.com/panel/assets/games/blessingofthedragon.png', 1, 'ASKMESLOTS', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2153, 'blessingox', 'Blessing of the Ox', 'https://maxapigames.com/panel/assets/games/blessingoftheox.png', 1, 'ASKMESLOTS', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2154, 'blessingtiger', 'Blessing of the Tiger', 'https://maxapigames.com/panel/assets/games/blessingofthetiger.png', 1, 'ASKMESLOTS', 1, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2155, 'boxing-king', 'Boxing King', 'https://maxapigames.com/panel/assets/games/boxingking.png', 1, 'JILI', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2156, 'bubble-beauty', 'Bubble Beauty', 'https://maxapigames.com/panel/assets/games/bubblebeauty.png', 1, 'JILI', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2157, 'buffalo-win', 'Buffalo Win', 'https://maxapigames.com/panel/assets/games/buffalowin.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2158, 'butterfly-blossom', 'Butterfly Blossom', 'https://maxapigames.com/panel/assets/games/butterflyblossom.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2159, 'caishen-wins', 'Caishen Wins', 'https://maxapigames.com/panel/assets/games/caishenwins.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2160, 'candy-baby', 'Candy Baby', 'https://maxapigames.com/panel/assets/games/candybaby.png', 1, 'JILI', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2161, 'candy-bonanza', 'Candy Bonanza', 'https://maxapigames.com/panel/assets/games/candybonanza.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2162, 'cash-mania', 'Cash Mania', 'https://maxapigames.com/panel/assets/games/cashmania.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2163, 'chocolate-deluxe', 'Chocolate Deluxe', 'https://maxapigames.com/panel/assets/games/chocolatedeluxe.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2164, 'christmas-morning', 'Christmas Morning', 'https://maxapigames.com/panel/assets/games/christmasmorning.webp', 1, 'REDTIGER', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2165, 'christmas-multihops', 'Christmas Multihops', 'https://maxapigames.com/panel/assets/games/christmasmultihops.webp', 1, 'REDTIGER', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2166, 'circus-delight', 'Circus Delight', 'https://maxapigames.com/panel/assets/games/circusdelight.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2167, 'clashofwars', 'Clash of Wars', 'https://maxapigames.com/panel/assets/games/clashofwar.png', 1, 'ASKMESLOTS', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2168, 'clover-craze', 'Clover Craze', 'https://maxapigames.com/panel/assets/games/clovercraze.webp', 1, 'REDTIGER', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2169, 'cocktail-night', 'Cocktail Night', 'https://maxapigames.com/panel/assets/games/cocktailnight.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2170, 'coin-master-go', 'Coin Master Go', 'https://maxapigames.com/panel/assets/games/coinmastergo.webp', 1, 'CPGAMES', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2171, 'congo-cash', 'Congo Cash', 'https://maxapigames.com/panel/assets/games/congocash.webp', 1, 'PRAGMATIC', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2172, 'cowboys-gold', 'Cowboys Gold', 'https://maxapigames.com/panel/assets/games/cowboysgold.webp', 1, 'PRAGMATIC', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2173, 'crazy-777-wg', 'Crazy 777', 'https://maxapigames.com/panel/assets/games/crazy777.png', 1, 'WG', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2174, 'crazy-777', 'Crazy 777', 'https://maxapigames.com/panel/assets/games/crazy777-cpgames.webp', 1, 'CPGAMES', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2175, 'crazy-birds', 'Crazy Birds', 'https://maxapigames.com/panel/assets/games/crazybirds.webp', 1, 'CPGAMES', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2176, 'crazy-fafafa', 'Crazy Fafafa', 'https://maxapigames.com/panel/assets/games/fafafa.png', 1, 'JILI', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2177, 'crazy-gems', 'Crazy Gems', 'https://maxapigames.com/panel/assets/games/crazygems.webp', 1, 'CPGAMES', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2178, 'crazy-piggy', 'Crazy Piggy', 'https://maxapigames.com/panel/assets/games/crazypiggy.webp', 1, 'CPGAMES', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2179, 'crazy777', 'Crazy777', 'https://maxapigames.com/panel/assets/games/crazy777-jili.png', 1, 'JILI', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2180, 'cruise-royal', 'Cruise Royal', 'https://maxapigames.com/panel/assets/games/cruiseroyale.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2181, 'crypto-gold', 'Crypto Gold', 'https://maxapigames.com/panel/assets/games/cryptogold.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2182, 'cupidgarden', 'Cupid Garden', 'https://maxapigames.com/panel/assets/games/cupidgarden.png', 1, 'ASKMESLOTS', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2183, 'curse-of-the-werewolf-megaways', 'Curse of the Werewolf Megaways', 'https://maxapigames.com/panel/assets/games/curseofthewerewolfmegaways.webp', 1, 'PRAGMATIC', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2184, 'cyber-go', 'Cyber Go', 'https://maxapigames.com/panel/assets/games/cybergo.webp', 1, 'CPGAMES', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2185, 'destiny-of-sun-and-moon', 'Destiny Of Sun And Moon', 'https://maxapigames.com/panel/assets/games/destinyofsunemoon.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2186, 'diner-delights', 'Diner Delights', 'https://maxapigames.com/panel/assets/games/dinerdelights.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2187, 'doomsday-rampage', 'Doomsday Rampage', 'https://maxapigames.com/panel/assets/games/doomsday-rampage.jpg', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2188, 'double-fortune', 'Double Fortune', 'https://maxapigames.com/panel/assets/games/doublefortune.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2189, 'dr-francashstein', 'Dr Francashstein', 'https://maxapigames.com/panel/assets/games/drfrancashstein.png', 1, 'REDTIGER', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2190, 'dragon', 'Dragon', 'https://maxapigames.com/panel/assets/games/dragon.png', 1, 'slot-jdb', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2191, 'dragon-hatch', 'Dragon Hatch', 'https://maxapigames.com/panel/assets/games/dragonhatch.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2192, 'dragon-hatch-2', 'Dragon Hatch 2', 'https://maxapigames.com/panel/assets/games/dragonhatch2.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2193, 'dragon-legend', 'Dragon Legend', 'https://maxapigames.com/panel/assets/games/dragonlegend.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2194, 'dragon-tiger-luck', 'Dragon Tiger Luck', 'https://maxapigames.com/panel/assets/games/dragontigerluck.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2195, 'dragon-vs-tiger', 'Dragon Vs Tiger', 'https://maxapigames.com/panel/assets/games/dragonvstiger.webp', 1, 'WG', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2196, 'dragons-treasure', 'Dragons Treasure', 'https://maxapigames.com/panel/assets/games/dragonstreasure.webp', 1, 'WG', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2197, 'dragons-treasure-2', 'Dragons Treasure 2', 'https://maxapigames.com/panel/assets/games/dragonstreasure2.webp', 1, 'WG', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2198, 'dreams-of-macau', 'Dreams Of Macau', 'https://maxapigames.com/panel/assets/games/dreamsofmacau.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2199, 'duofu-duogai-wg', 'Duofu Duogai', 'https://maxapigames.com/panel/assets/games/duofuduogai.png', 1, 'WG', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2200, 'earn-to-die', 'Earn To Die', '', 1, 'SSR', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2201, 'emerald-king', 'Emerald King', 'https://maxapigames.com/panel/assets/games/emeraldkingrainbowroad.png', 1, 'PRAGMATIC', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2202, 'emoji-riches', 'Emoji Riches', 'https://maxapigames.com/panel/assets/games/emojiriches.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2203, 'eye-of-the-storm', 'Eye of the Storm', 'https://maxapigames.com/panel/assets/games/eyeofthestorm.png', 1, 'PRAGMATIC', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2204, 'fighter-legend', 'Fighter Legend', '', 1, 'SSR', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2205, 'fishin-reels', 'Fishin Reels', 'https://maxapigames.com/panel/assets/games/fishinreels.png', 1, 'PRAGMATIC', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2206, 'fishing-go', 'Fishing Go', 'https://maxapigames.com/panel/assets/games/fishinggo.webp', 1, 'CPGAMES', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2207, 'fishing-master', 'Fishing Master', 'https://maxapigames.com/panel/assets/games/fishingmaster.webp', 1, 'WG', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2208, 'flirting-scholar', 'Flirting Scholar', 'https://maxapigames.com/panel/assets/games/flirtingscholar.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2209, 'flirting-scholar-tango', 'Flirting Scholar Tango', 'https://maxapigames.com/panel/assets/games/flirtinscholartango.png', 1, 'slot-jdb', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2210, 'forge-of-wealth', 'Forge Of Wealth', 'https://maxapigames.com/panel/assets/games/forgeofwealth.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2211, 'formosa-bear', 'Formosa Bear', 'https://maxapigames.com/panel/assets/games/formosabear.png', 1, 'slot-jdb', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2212, 'fortune-dragon', 'Fortune Dragon', 'https://maxapigames.com/panel/assets/games/fortunedragon.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2213, 'fortune-dragon-fofo', 'Fortune Dragon Fofo', 'https://maxapigames.com/panel/assets/games/fortune-dragon-fofo.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2214, 'fortune-gems', 'Fortune Gems', 'https://maxapigames.com/panel/assets/games/fortunegems.png', 1, 'JILI', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2215, 'fortune-gods', 'Fortune Gods', 'https://maxapigames.com/panel/assets/games/fortunegods.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2216, 'fortune-mouse', 'Fortune Mouse', 'https://maxapigames.com/panel/assets/games/fortunemouse.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2217, 'fortune-ox', 'Fortune Ox', 'https://maxapigames.com/panel/assets/games/fortuneox.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2218, 'fortune-ox-wg', 'Fortune Ox', 'https://maxapigames.com/panel/assets/games/fortuneox-wg.webp', 1, 'WG', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2219, 'fortune-pig', 'Fortune Pig', 'https://maxapigames.com/panel/assets/games/fortunepig.png', 1, 'JILI', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2220, 'fortune-rabbit', 'Fortune Rabbit', 'https://maxapigames.com/panel/assets/games/fortunerabbit.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2221, 'fortune-rabbit-wg', 'Fortune Rabbit', 'https://maxapigames.com/panel/assets/games/fortunerabbit-wg.webp', 1, 'WG', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2222, 'fortune-snake', 'Fortune Snake', 'https://maxapigames.com/panel/assets/games/fortune-snake.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2223, 'fortune-tiger', 'Fortune Tiger', 'https://maxapigames.com/panel/assets/games/fortunetiger.png', 1, 'PGSOFT', 1, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2224, 'fortune-tiger-wg', 'Fortune Tiger', 'https://maxapigames.com/panel/assets/games/fortunetiger-wg.webp', 1, 'WG', 1, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2225, 'fortune-tiger-3', 'Fortune Tiger 3', 'https://maxapigames.com/panel/assets/games/fortune-tiger-3.png', 1, 'PGSOFT', 1, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2226, 'fortune-tiger-fofo', 'Fortune Tiger Fofo', 'https://maxapigames.com/panel/assets/games/fortune-tiger-fofo.png', 1, 'PGSOFT', 1, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2227, 'fortune-toucan-wg', 'Fortune Toucan', 'https://maxapigames.com/panel/assets/games/fortunetoucan.webp', 1, 'WG', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2228, 'four-treasures', 'Four Treasures', 'https://maxapigames.com/panel/assets/games/fourtreasures.png', 1, 'slot-jdb', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2229, 'futebol-fever', 'Futebol Fever', 'https://maxapigames.com/panel/assets/games/futebol-fever.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2230, 'galactic-gems', 'Galactic Gems', 'https://maxapigames.com/panel/assets/games/galaticgems.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2231, 'ganesha-fortune', 'Ganesha Fortune', 'https://maxapigames.com/panel/assets/games/ganeshafortune.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2232, 'ganesha-gold', 'Ganesha Gold', 'https://maxapigames.com/panel/assets/games/ganeshagold.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2233, 'garuda-gems', 'Garuda Gems', 'https://maxapigames.com/panel/assets/games/garudagems.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2234, 'gates-of-olympus', 'Gates Of Olympus', 'https://maxapigames.com/panel/assets/games/gatesofolympus.png', 1, 'PRAGMATIC', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2235, 'geishas-revenge', 'Geishas Revenge', 'https://maxapigames.com/panel/assets/games/geishasrevenge.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2236, 'gem-savior-conquest', 'Gem Savior Conquest', 'https://maxapigames.com/panel/assets/games/gemsaviourconquest.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2237, 'gem-saviour-sword', 'Gem Saviour Sword', 'https://maxapigames.com/panel/assets/games/gemsavioursword.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2238, 'gems-bonanza', 'Gems Bonanza', 'https://maxapigames.com/panel/assets/games/gemsbonanza.png', 1, 'PRAGMATIC', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2239, 'gemstone-gold', 'Gemstone Gold', 'https://maxapigames.com/panel/assets/games/gemstonegold.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2240, 'genies-3-wishes', 'Genies 3 Wishes', 'https://maxapigames.com/panel/assets/games/genies3wishes.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2241, 'giga-blast', 'Giga Blast', 'https://maxapigames.com/panel/assets/games/gigablast.webp', 1, 'REDTIGER', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2242, 'golden-queen', 'Golden Queen', 'https://maxapigames.com/panel/assets/games/goldenqueen.png', 1, 'JILI', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2243, 'govdisco', 'Government Disco', 'https://maxapigames.com/panel/assets/games/governmentdisco.png', 1, 'ASKMESLOTS', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2244, 'graffiti-rush', 'Graffiti Rush', 'https://maxapigames.com/panel/assets/games/graffiti-rush.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2245, 'great-rhino-deluxe', 'Great Rhino Deluxe', 'https://maxapigames.com/panel/assets/games/greatrhinodeluxe.webp', 1, 'PRAGMATIC', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2246, 'guardians-of-ice-and-fire', 'Guardians Of Ice And Fire', 'https://maxapigames.com/panel/assets/games/guardiansoficeandfire.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2247, 'hawaiian-tiki', 'Hawaiian Tiki', 'https://maxapigames.com/panel/assets/games/hawaiiantiky.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2248, 'heist-stakes', 'Heist Stakes', 'https://maxapigames.com/panel/assets/games/heiststakes.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2249, 'hercules-son-of-zeus', 'Hercules Son Of Zeus', 'https://maxapigames.com/panel/assets/games/herculessonofzeus.png', 1, 'PRAGMATIC', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2250, 'hip-hop-panda', 'Hip Hop Panda', 'https://maxapigames.com/panel/assets/games/hiphoppanda.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2251, 'honey-trap-of-diao-chan', 'Honey Trap Of Diao Chan', 'https://maxapigames.com/panel/assets/games/honeytrapofdiaochan.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2252, 'hot-to-burn-hold-and-spin', 'Hot To Burn Hold And Spin', 'https://maxapigames.com/panel/assets/games/hottoburnholdandspin.png', 1, 'PRAGMATIC', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2253, 'incan-wonders', 'Incan Wonders', 'https://maxapigames.com/panel/assets/games/incan-wonders.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2254, 'jack-frosts-winter', 'Jack Frosts Winter', 'https://maxapigames.com/panel/assets/games/jackfrostswinter.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2255, 'jewels-of-prosperity', 'Jewels Of Prosperity', 'https://maxapigames.com/panel/assets/games/jewelsofprosperity.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2256, 'john-hunter-and-the-mayan-gods', 'John Hunter and the Mayan Gods', 'https://maxapigames.com/panel/assets/games/jhonhunterandthemayangods.webp', 1, 'PRAGMATIC', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2257, 'joker-king', 'Joker King', 'https://maxapigames.com/panel/assets/games/jokerking.png', 1, 'PRAGMATIC', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2258, 'jokers-jewel-cash', 'Jokers Jewel Cash', 'https://maxapigames.com/panel/assets/games/jokersjewelscash.png', 1, 'PRAGMATIC', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2259, 'journey-to-the-wealth', 'Journey To The Wealth', 'https://maxapigames.com/panel/assets/games/journeytothewealth.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2260, 'jungle-delight', 'Jungle Delight', 'https://maxapigames.com/panel/assets/games/jungledelight.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2261, 'jungle-fruit', 'Jungle Fruit', 'https://maxapigames.com/panel/assets/games/junglefruit.webp', 1, 'CPGAMES', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2262, 'jungle-gems', 'Jungle Gems', 'https://maxapigames.com/panel/assets/games/junglegems.webp', 1, 'CPGAMES', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2263, 'jungle-kings', 'Jungle Kings', 'https://maxapigames.com/panel/assets/games/jungleking.png', 1, 'CPGAMES', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2264, 'jungle-party', 'Jungle Party', 'https://maxapigames.com/panel/assets/games/jungleparty.webp', 1, 'CPGAMES', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2265, 'jurassic-kingdom', 'Jurassic Kingdom', 'https://maxapigames.com/panel/assets/games/jurassickingdom.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2266, 'labubu-zimomo', 'Labubu Zimomo', 'https://maxapigames.com/panel/assets/games/labubu-zimomo.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2267, 'legend-of-hou-yi', 'Legend Of Hou Yi', 'https://maxapigames.com/panel/assets/games/legendofhouui.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2268, 'legendary-monkey-king', 'Legendary Monkey King', 'https://maxapigames.com/panel/assets/games/legendarymonkeyking.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2269, 'leopard-of-gold', 'Leopard Of Gold', 'https://maxapigames.com/panel/assets/games/leopardofgold.webp', 1, 'WG', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2270, 'leprechaun-riches', 'Leprechaun Riches', 'https://maxapigames.com/panel/assets/games/leprechaunriches.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2271, 'lions-megaways-2', 'Lions Megaways 2', 'https://maxapigames.com/panel/assets/games/lionsmegaways2.png', 1, 'PRAGMATIC', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2272, 'lucky-777', 'Lucky 777', '', 1, 'CPGAMES', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2273, 'lucky-ball', 'Lucky Ball', 'https://maxapigames.com/panel/assets/games/luckyball.png', 1, 'JILI', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2274, 'lucky-cat', 'Lucky Cat', 'https://maxapigames.com/panel/assets/games/luckycat.webp', 1, 'CPGAMES', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2275, 'lucky-cat-ii', 'Lucky Cat Ii', 'https://maxapigames.com/panel/assets/games/luckycat2.webp', 1, 'CPGAMES', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2276, 'lucky-clover-lady', 'Lucky Clover Lady', 'https://maxapigames.com/panel/assets/games/luckycloaverlady.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2277, 'lucky-doggy', 'Lucky Doggy', 'https://maxapigames.com/panel/assets/games/luckydoggy.webp', 1, 'WG', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2278, 'lucky-dragon', 'Lucky Dragon', 'https://maxapigames.com/panel/assets/games/luckydragon.webp', 1, 'CPGAMES', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2279, 'lucky-goldbricks', 'Lucky Goldbricks', 'https://maxapigames.com/panel/assets/games/luckygoldbricks.png', 1, 'JILI', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2280, 'lucky-lion', 'Lucky Lion', 'https://maxapigames.com/panel/assets/games/luckylion.png', 1, 'slot-jdb', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2281, 'lucky-miner', 'Lucky Miner', 'https://maxapigames.com/panel/assets/games/luckyminer.png', 1, 'slot-jdb', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2282, 'lucky-neko', 'Lucky Neko', 'https://maxapigames.com/panel/assets/games/luckyneko.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2283, 'lucky-new-year-tiger-treasures', 'Lucky New Year Tiger Treasures', 'https://maxapigames.com/panel/assets/games/luckynewyeartigertreasures.webp', 1, 'PRAGMATIC', 1, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2284, 'lucky-panda', 'Lucky Panda', 'https://maxapigames.com/panel/assets/games/luckypanda.webp', 1, 'CPGAMES', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2285, 'lucky-phoenix', 'Lucky Phoenix', 'https://maxapigames.com/panel/assets/games/luckyphoenix.png', 1, 'PRAGMATIC', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2286, 'lucky-piggy', 'Lucky Piggy', 'https://maxapigames.com/panel/assets/games/luckypiggy.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2287, 'lucky-qilin', 'Lucky Qilin', 'https://maxapigames.com/panel/assets/games/luckyqilin.png', 1, 'slot-jdb', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2288, 'lucky-santa', 'Lucky Santa', 'https://maxapigames.com/panel/assets/games/luckysanta.webp', 1, 'CPGAMES', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2289, 'lucky-seven', 'Lucky Seven', 'https://maxapigames.com/panel/assets/games/luckyseven.png', 1, 'slot-jdb', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2290, 'lucky-wheel', 'Lucky Wheel', 'https://maxapigames.com/panel/assets/games/luckywheel.webp', 1, 'CPGAMES', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2291, 'madame-destiny-megaways', 'Madame Destiny Megaways', 'https://maxapigames.com/panel/assets/games/madamedestinymegaways.png', 1, 'PRAGMATIC', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2292, 'mafia-mayhem', 'Mafia Mayhem', 'https://maxapigames.com/panel/assets/games/mafiamayhem.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2293, 'mafiaways', 'Mafia Ways', 'https://maxapigames.com/panel/assets/games/mafiaways.png', 1, 'ASKMESLOTS', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2294, 'mafiaways2', 'Mafia Ways II', 'https://maxapigames.com/panel/assets/games/mafiaways2.png', 1, 'ASKMESLOTS', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2295, 'mahjongxmas', 'Mahjong Legend Xmas', 'https://maxapigames.com/panel/assets/games/mahjonglegendxmas.png', 1, 'ASKMESLOTS', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2296, 'mahjong-ways', 'Mahjong Ways', 'https://maxapigames.com/panel/assets/games/mahjongways.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2297, 'mahjong-ways-wg', 'Mahjong Ways', 'https://maxapigames.com/panel/assets/games/mahjongways-wg.webp', 1, 'WG', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2298, 'mahjong-ways-2', 'Mahjong Ways 2', 'https://maxapigames.com/panel/assets/games/mahjongways2.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2299, 'mahjong-ways-2-wg', 'Mahjong Ways 2', 'https://maxapigames.com/panel/assets/games/mahjongways2-wg.webp', 1, 'WG', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2300, 'mais-fortuna-e-riqueza', 'Mais Fortuna e Riqueza', 'https://maxapigames.com/panel/assets/games/maisfortunaeriqueza.webp', 1, 'WG', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2301, 'majestic-treasures', 'Majestic Treasures', 'https://maxapigames.com/panel/assets/games/majestictreasures.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2302, 'margem-da-agua', 'Margem da Agua', 'https://maxapigames.com/panel/assets/games/margemdaagua.png', 1, 'WG', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2303, 'mask-carnival', 'Mask Carnival', 'https://maxapigames.com/panel/assets/games/maskcarnival.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2304, 'medusa', 'Medusa', 'https://maxapigames.com/panel/assets/games/medusa.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2305, 'mermaid-riches', 'Mermaid Riches', 'https://maxapigames.com/panel/assets/games/mermaidriches.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2306, 'midas-fortune', 'Midas Fortune', 'https://maxapigames.com/panel/assets/games/midasfortune.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2307, 'moneybags-man', 'Moneybags Man', 'https://maxapigames.com/panel/assets/games/moneybagsman.png', 1, 'slot-jdb', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2308, 'monkey-king', 'Monkey King', 'https://maxapigames.com/panel/assets/games/monkeyking.png', 1, 'slot-jdb', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2309, 'moon-spell', 'Moon Spell', 'https://maxapigames.com/panel/assets/games/moonspell.webp', 1, 'REDTIGER', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2310, 'mr-hallow-win', 'Mr Hallow Win', 'https://maxapigames.com/panel/assets/games/mrhallowwin.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2311, 'mr-treasure-fortune', 'Mr Treasure Fortune', 'https://maxapigames.com/panel/assets/games/mrtreasurefortune.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2312, 'mr-turtle', 'Mr Turtle', 'https://maxapigames.com/panel/assets/games/mrturtle.webp', 1, 'WG', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2313, 'muay-thai-champion', 'Muay Thai Champion', 'https://maxapigames.com/panel/assets/games/muaythaichampion.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2314, 'museum-mystery', 'Museum Mystery', 'https://maxapigames.com/panel/assets/games/museummistery.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2315, 'mysterious-egypt', 'Mysterious Egypt', 'https://maxapigames.com/panel/assets/games/mysteriousegipt.png', 1, 'PRAGMATIC', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2316, 'napoleon', 'Napoleon', 'https://maxapigames.com/panel/assets/games/napoleon.png', 1, 'slot-jdb', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2317, 'new-year', 'New Year', 'https://maxapigames.com/panel/assets/games/newyear.png', 1, 'slot-jdb', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2318, 'nightmare-family-megaways', 'Nightmare Family Megaways', 'https://maxapigames.com/panel/assets/games/nightmarefamilymegaways.png', 1, 'REDTIGER', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2319, 'ninja-raccoon-frenzy', 'Ninja Raccoon Frenzy', 'https://maxapigames.com/panel/assets/games/ninjaraccoonfrenzy.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2320, 'ninja-run', 'Ninja Run', '', 1, 'SSR', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2321, 'ninja-vs-samurai', 'Ninja Vs Samurai', 'https://maxapigames.com/panel/assets/games/ninjavssamurai.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2322, 'ocean-go', 'Ocean Go', 'https://maxapigames.com/panel/assets/games/oceango.webp', 1, 'CPGAMES', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2323, 'open-sesame', 'Open Sesame', 'https://maxapigames.com/panel/assets/games/opensesame.png', 1, 'slot-jdb', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2324, 'oriental-prosperity', 'Oriental Prosperity', 'https://maxapigames.com/panel/assets/games/orientalprosperity.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2325, 'party-night', 'Party Night', 'https://maxapigames.com/panel/assets/games/partynight.png', 1, 'JILI', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2326, 'petranger', 'Pet Ranger', 'https://maxapigames.com/panel/assets/games/petranger.png', 1, 'ASKMESLOTS', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2327, 'pharaoh-treasure', 'Pharaoh Treasure', 'https://maxapigames.com/panel/assets/games/pharaohtreasure.png', 1, 'JILI', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2328, 'phoenix-rises', 'Phoenix Rises', 'https://maxapigames.com/panel/assets/games/phoenixrises.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2329, 'piggy-gold', 'Piggy Gold', 'https://maxapigames.com/panel/assets/games/piggygold.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2330, 'pinata-wins', 'Pinata Wins', 'https://maxapigames.com/panel/assets/games/pinatawins.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2331, 'plushie-frenzy', 'Plushie Frenzy', 'https://maxapigames.com/panel/assets/games/plushiefrenzy.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2332, 'prosperity-fortune-tree', 'Prosperity Fortune Tree', 'https://maxapigames.com/panel/assets/games/prosperityfortunetree.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2333, 'prosperity-lion', 'Prosperity Lion', 'https://maxapigames.com/panel/assets/games/prosperitylion.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2334, 'queen-of-bounty', 'Queen Of Bounty', 'https://maxapigames.com/panel/assets/games/queenofbounty.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2335, 'racing-fever', 'Racing Fever', '', 1, 'SSR', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2336, 'raider-janes-crypt-of-fortune', 'Raider Janes Crypt Of Fortune', 'https://maxapigames.com/panel/assets/games/raidersjanycriptoffortune.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2337, 'rave-party-fever', 'Rave Party Fever', 'https://maxapigames.com/panel/assets/games/ravepartyfever.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2338, 'redline-rush', 'Redline Rush', 'https://maxapigames.com/panel/assets/games/redlinerush.webp', 1, 'REDTIGER', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2339, 'reel-love', 'Reel Love', 'https://maxapigames.com/panel/assets/games/reellove.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2340, 'rio-carnival', 'Rio Carnival', 'https://maxapigames.com/panel/assets/games/riocarnival.webp', 1, 'CPGAMES', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2341, 'rio-fantasia', 'Rio Fantasia', 'https://maxapigames.com/panel/assets/games/riofantasia.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2342, 'rise-of-apollo', 'Rise Of Apollo', 'https://maxapigames.com/panel/assets/games/riseofapollo.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2343, 'rolling-in-money', 'Rolling In Money', 'https://maxapigames.com/panel/assets/games/rollinginmoney.png', 1, 'slot-jdb', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2344, 'romax', 'RomaX', 'https://maxapigames.com/panel/assets/games/romax.png', 1, 'JILI', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2345, 'rooster-rumble', 'Rooster Rumble', 'https://maxapigames.com/panel/assets/games/roosterrumble.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2346, 'safari-wilds', 'Safari Wilds', 'https://maxapigames.com/panel/assets/games/safariwilds.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2347, 'samba-dance', 'Samba Dance', 'https://maxapigames.com/panel/assets/games/sambadance.webp', 1, 'WG', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2348, 'santas-gift-rush', 'Santas Gift Rush', 'https://maxapigames.com/panel/assets/games/santasgiftrush.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2349, 'sea-boat-adventure', 'Sea Boat Adventure', 'https://maxapigames.com/panel/assets/games/seaboatadventure.avif', 1, 'REDTIGER', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2350, 'sevensevenseven', 'SevenSevenSeven', 'https://maxapigames.com/panel/assets/games/sevensevenseven.png', 1, 'JILI', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2351, 'shanghai-beauty', 'Shanghai Beauty', 'https://maxapigames.com/panel/assets/games/shangaibeauty.png', 1, 'JILI', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2352, 'shaolin-soccer', 'Shaolin Soccer', 'https://maxapigames.com/panel/assets/games/shaolinsoccer.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2353, 'shark-bounty', 'Shark Bounty', 'https://maxapigames.com/panel/assets/games/sharkbounty.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2354, 'songkran-splash', 'Songkran Splash', 'https://maxapigames.com/panel/assets/games/songkransplhash.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2355, 'spartan-king', 'Spartan King', 'https://maxapigames.com/panel/assets/games/spartanking.webp', 1, 'PRAGMATIC', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2356, 'speed-winner', 'Speed Winner', 'https://maxapigames.com/panel/assets/games/speedwinner.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2357, 'spirited-wonders', 'Spirited Wonders', 'https://maxapigames.com/panel/assets/games/spiritedwonders.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2358, 'starlight-princess', 'Starlight Princess', 'https://maxapigames.com/panel/assets/games/starlightprincess.webp', 1, 'PRAGMATIC', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2359, 'starlight-princess-1000', 'Starlight Princess 1000', 'https://maxapigames.com/panel/assets/games/starlightprincess1000.webp', 1, 'PRAGMATIC', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2360, 'starship-crisis', 'Starship Crisis', '', 1, 'SSR', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2361, 'steam-squad', 'Steam Squad', 'https://maxapigames.com/panel/assets/games/steamsquad.webp', 1, 'REDTIGER', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2362, 'sugarlicious-everyway', 'Sugarlicious Everyway', 'https://maxapigames.com/panel/assets/games/sugarliciouseveryway.webp', 1, 'REDTIGER', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2363, 'super-ace', 'Super Ace', 'https://maxapigames.com/panel/assets/games/superace.png', 1, 'JILI', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2364, 'super-golf-drive', 'Super Golf Drive', 'https://maxapigames.com/panel/assets/games/supergolfdrive.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2365, 'super-maquina-de-frutas', 'Super Maquina De Frutas', 'https://maxapigames.com/panel/assets/games/supermaquinadefrutas.webp', 1, 'WG', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2366, 'supermarket-spree', 'Supermarket Spree', 'https://maxapigames.com/panel/assets/games/supermarketspree.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2367, 'tank-summons', 'Tank Summons', '', 1, 'SSR', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2368, 'thai-river-wonders', 'Thai River Wonders', 'https://maxapigames.com/panel/assets/games/thairiverwonders.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2369, 'the-crazy-piggles', 'The Crazy Piggles', 'https://maxapigames.com/panel/assets/games/thecrazypiggles.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2370, 'the-great-icescape', 'The Great Icescape', 'https://maxapigames.com/panel/assets/games/thegreaticescape.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2371, 'the-hand-of-midas', 'The Hand Of Midas', 'https://maxapigames.com/panel/assets/games/thehandofmidas.png', 1, 'PRAGMATIC', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2372, 'the-llama-adventure', 'The Llama Adventure', 'https://maxapigames.com/panel/assets/games/thellamaadventure.png', 1, 'slot-jdb', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2373, 'the-queens-banquet', 'The Queens Banquet', 'https://maxapigames.com/panel/assets/games/thequeensbanquet.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2374, 'the-vault-wg', 'The Vault', 'https://maxapigames.com/panel/assets/games/thevault.png', 1, 'WG', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2375, 'the-zombie-infection', 'The Zombie Infection', '', 1, 'SSR', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2376, 'totem-wonders', 'Totem Wonders', 'https://maxapigames.com/panel/assets/games/totemwonders.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2377, 'treasure-dragon', 'Treasure Dragon', 'https://maxapigames.com/panel/assets/games/treasuredragon.webp', 1, 'WG', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2378, 'treasure-marmosets', 'Treasure Marmosets', 'https://maxapigames.com/panel/assets/games/treasuremarmosets.webp', 1, 'WG', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2379, 'treasures-of-aztec', 'Treasures Of Aztec', 'https://maxapigames.com/panel/assets/games/treasuresofaztec.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2380, 'triple-pot-gold', 'Triple Pot Gold', 'https://maxapigames.com/panel/assets/games/triplepotgold.png', 1, 'PRAGMATIC', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2381, 'triple-tigers', 'Triple Tigers', 'https://maxapigames.com/panel/assets/games/tripletigers.webp', 1, 'PRAGMATIC', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2382, 'tsar-treasures', 'Tsar Treasures', 'https://maxapigames.com/panel/assets/games/tsartreasures.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2383, 'ultimate-striker', 'Ultimate Striker', 'https://maxapigames.com/panel/assets/games/ultimatestriker.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2384, 'vampires-charm', 'Vampires Charm', 'https://maxapigames.com/panel/assets/games/vampirescharms.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2385, 'vault-cracker-megaways', 'Vault Cracker Megaways', 'https://maxapigames.com/panel/assets/games/vaultcrackermegaways.webp', 1, 'REDTIGER', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2386, 'wanted-wildz-extreme', 'Wanted Wildz Extreme', 'https://maxapigames.com/panel/assets/games/wantedwildzextreme.jpg', 1, 'REDTIGER', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2387, 'warship-crisis', 'Warship Crisis', '', 1, 'SSR', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2388, 'ways-f-the-qilin', 'Ways F The Qilin', 'https://maxapigames.com/panel/assets/games/waysoftheqilin.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2389, 'wild-ape', 'Wild Ape', 'https://maxapigames.com/panel/assets/games/thewildape.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2390, 'wild-bandito', 'Wild Bandito', 'https://maxapigames.com/panel/assets/games/wildbandito.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2391, 'wild-bounty-showdown-wg', 'Wild Bounty Showdown', 'https://maxapigames.com/panel/assets/games/wildbountyshowdown-wg.png', 1, 'WG', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2392, 'wild-bounty-showdown', 'Wild Bounty Showdown', 'https://maxapigames.com/panel/assets/games/wildbountyshowdown-pg.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2393, 'wild-coaster', 'Wild Coaster', 'https://maxapigames.com/panel/assets/games/wildcoaster.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2394, 'wild-fireworks', 'Wild Fireworks', 'https://maxapigames.com/panel/assets/games/wildfireworks.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2395, 'wild-waker', 'Wild Waker', 'https://maxapigames.com/panel/assets/games/wildwalker.webp', 1, 'PRAGMATIC', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2396, 'wild-west-gold-blazing-bounty', 'Wild West Gold Blazing Bounty', 'https://maxapigames.com/panel/assets/games/wildwestgoldblazybounty.png', 1, 'PRAGMATIC', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2397, 'win-win-fish-prawn-crab', 'Win Win Fish Prawn Crab', 'https://maxapigames.com/panel/assets/games/winwinfishprawncrab.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2398, 'win-win-won', 'Win Win Won', 'https://maxapigames.com/panel/assets/games/winwinwon.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2399, 'wings-of-iguazu', 'Wings Of Iguazu', 'https://maxapigames.com/panel/assets/games/wingsofiguazu.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2400, 'winning-mask', 'Winning Mask', 'https://maxapigames.com/panel/assets/games/winningmask.png', 1, 'slot-jdb', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2401, 'world-cup-wg', 'World Cup', 'https://maxapigames.com/panel/assets/games/worldcup.png', 1, 'WG', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2402, 'yakuza-honor', 'Yakuza Honor', 'https://maxapigames.com/panel/assets/games/yakuzahonor.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2403, 'zeus-lightning-megaways', 'Zeus Lightning Megaways', 'https://maxapigames.com/panel/assets/games/zeuslightningmegaways.webp', 1, 'REDTIGER', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0),
(2404, 'zombie-outbreak', 'Zombie Outbreak', 'https://maxapigames.com/panel/assets/games/zombieoutbreak.png', 1, 'PGSOFT', 0, 'slot', 3, 'MaxApiGames', 0, 0, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico_comissoes`
--

CREATE TABLE `historico_comissoes` (
  `id` int(11) NOT NULL,
  `afiliado_id` int(11) NOT NULL,
  `valor_comissao` decimal(10,2) NOT NULL,
  `transacao_id` varchar(255) NOT NULL,
  `gateway` varchar(50) NOT NULL COMMENT 'Gateway que processou (fivepay, expfypay, etc)',
  `data_processamento` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Histórico de comissões processadas';

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico_play`
--

CREATE TABLE `historico_play` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nome_game` text NOT NULL,
  `bet_money` decimal(10,2) NOT NULL DEFAULT 0.00,
  `win_money` decimal(10,2) NOT NULL DEFAULT 0.00,
  `txn_id` text NOT NULL,
  `created_at` datetime NOT NULL,
  `status_play` int(11) NOT NULL,
  `rebate_processado` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `historico_play`
--

INSERT INTO `historico_play` (`id`, `id_user`, `nome_game`, `bet_money`, `win_money`, `txn_id`, `created_at`, `status_play`, `rebate_processado`) VALUES
(94874, 928376389, 'fortune-snake', 5.00, 0.00, '60d51173b15d85717d91209f8d6d233c', '2026-02-13 08:31:24', 1, NULL),
(94875, 928376389, 'fortune-snake', 5.00, 0.00, '8f8e1dfd249a72365464a3d80c8a8b57', '2026-02-13 08:31:24', 1, NULL),
(94876, 928376389, 'fortune-snake', 5.00, 0.00, 'c777bfa7d634d7d35b18571fbe13a579', '2026-02-13 08:31:25', 1, NULL),
(94877, 928376389, 'fortune-snake', 5.00, 0.00, 'c10daa6637815ba61e2a33fc8064c697', '2026-02-13 08:31:26', 1, NULL),
(94878, 928376389, 'fortune-snake', 5.00, 0.00, 'b21a7b9ab3a5c1d99359b5bea85375ea', '2026-02-13 08:31:26', 1, NULL),
(94879, 928376389, 'fortune-snake', 5.00, 0.00, '6f81d25177d783de318e6a1fce881643', '2026-02-13 08:33:07', 1, NULL),
(94880, 928376389, 'fortune-dragon', 4.00, 0.00, 'e6725b96f3f55a961de2f591327ea596', '2026-02-13 08:33:29', 1, NULL),
(94881, 928376389, 'fortune-dragon-fofo', 0.50, 0.00, '1a1f5c6846663116ce85e50fa007aaf9', '2026-02-13 08:33:50', 1, NULL),
(94882, 928376389, 'fortune-dragon-fofo', 0.50, 0.00, '5005f56005e55918164e67fea64c4453', '2026-02-13 08:33:53', 1, NULL),
(94883, 928376389, 'fortune-dragon-fofo', 0.50, 0.00, '62e54f9f8eec0336959f91dcc0906372', '2026-02-13 08:33:54', 1, NULL),
(94884, 928376389, 'fortune-dragon-fofo', 0.50, 0.00, '08ae33c78a2ecc97709a8400b1c657ed', '2026-02-13 08:33:55', 1, NULL),
(94885, 928376389, 'fortune-dragon-fofo', 0.50, 0.00, 'c1a5dd4b5a49f93c056f1d210c5756cb', '2026-02-13 08:33:56', 1, NULL),
(94886, 928376389, 'clashofwars', 2.80, 0.00, '59764b7c830462ac29cefc9201415cd7', '2026-02-13 08:40:49', 1, NULL),
(94887, 928376389, 'clashofwars', 2.80, 0.00, '49dff763a6986dac62df2ca06aad60b5', '2026-02-13 08:40:58', 1, NULL),
(94888, 928376389, 'clashofwars', 2.80, 0.00, '35a5507de1ebbe3cab480e752bc7a144', '2026-02-13 08:41:21', 1, NULL),
(94889, 928376389, 'fortune-ox', 5.00, 0.00, 'd570565707ba7436789e0a1dc31db81b', '2026-02-13 08:47:12', 1, NULL),
(94890, 928376389, 'ninja-vs-samurai', 4.00, 0.00, 'bee3a0b3fbe105cfff4f5041b13cb63d', '2026-02-13 08:48:20', 1, NULL),
(94891, 928376389, 'piggy-gold', 5.00, 0.00, '71f2d282cb7d89316a90823977defbdc', '2026-02-13 08:49:36', 1, NULL),
(94892, 928376389, 'pinata-wins', 4.00, 0.00, '63ecf8c2208465c29fdc08d720e15c1e', '2026-02-13 08:50:21', 1, NULL),
(94893, 928376389, 'doomsday-rampage', 0.50, 0.00, '4633d27f24e54829e5e35bb8c44be194', '2026-02-13 08:50:37', 1, NULL),
(94894, 928376389, 'doomsday-rampage', 0.50, 0.00, '37b5a7a12a469a50e2949223183ec032', '2026-02-13 08:50:38', 1, NULL),
(94895, 928376389, 'doomsday-rampage', 0.50, 0.00, 'f2560b245c360fa04b0f82aeb78c45ea', '2026-02-13 08:50:41', 1, NULL),
(94896, 928376389, 'doomsday-rampage', 0.50, 0.00, '12ba3f48c535374194caf5ac1f7b3755', '2026-02-13 08:50:44', 1, NULL),
(94897, 928376389, 'doomsday-rampage', 0.50, 0.00, '0b5aa0be896badc3cc55ae18eecaca66', '2026-02-13 08:50:45', 1, NULL),
(94898, 928376389, 'doomsday-rampage', 0.50, 0.00, 'af87e695e90b103066ad7dc2fe44cddb', '2026-02-13 08:50:46', 1, NULL),
(94899, 928376389, 'doomsday-rampage', 0.50, 0.00, 'e49d78d339e4503f5488349bc6681e1e', '2026-02-13 08:50:47', 1, NULL),
(94900, 928376389, 'doomsday-rampage', 0.50, 0.00, 'a6321ef47022358d8b21938b2345e143', '2026-02-13 08:50:48', 1, NULL),
(94901, 928376389, 'doomsday-rampage', 0.50, 0.00, '093ccc0f9ed16a3f8e28114f4f5f7dd4', '2026-02-13 08:50:48', 1, NULL),
(94902, 928376389, 'doomsday-rampage', 0.50, 0.00, '0b3e9a95e8dc7ec3bdda79780d522b56', '2026-02-13 08:50:49', 1, NULL),
(94903, 928376389, 'doomsday-rampage', 0.50, 0.00, '58ff925b541d723110738bf9b383aaf0', '2026-02-13 08:50:50', 1, NULL),
(94904, 928376389, 'phoenix-rises', 4.00, 0.00, 'e2b2eade0290111b590b1d0a1f1fa9c4', '2026-02-13 08:51:31', 1, NULL),
(94905, 928376389, 'phoenix-rises', 4.00, 0.00, '389e059b8bfeb7f83ecb053b898d086c', '2026-02-13 08:51:32', 1, NULL),
(94906, 928376389, 'plushie-frenzy', 3.00, 0.00, '1e9632f8b96da120ac1a0cf36d49661a', '2026-02-13 08:58:04', 1, NULL),
(94907, 928376389, 'plushie-frenzy', 3.00, 0.00, '118d6ce4772143f43168031f77e361fa', '2026-02-13 08:58:05', 1, NULL),
(94908, 928376389, 'plushie-frenzy', 3.00, 0.00, '49802f166456e9a331dfa3520a54064d', '2026-02-13 08:58:06', 1, NULL),
(94909, 928376389, 'prosperity-lion', 4.50, 0.00, '33dbbf2e243c0288f99e2c3e2fd0baa3', '2026-02-13 09:00:00', 1, NULL),
(94910, 928376389, 'queen-of-bounty', 4.00, 0.00, 'afa6cca7e0fed1a688517b97f4d3eca5', '2026-02-13 09:00:29', 1, NULL),
(94911, 928376389, 'queen-of-bounty', 4.00, 0.00, '4d0c8377255495ac4951daab9e5beb78', '2026-02-13 10:06:00', 1, NULL),
(94912, 928376389, 'reel-love', 4.00, 0.00, 'c59176e7048ab7b815c0fa194e71eb98', '2026-02-13 10:07:26', 1, NULL),
(94913, 928376389, 'the-hand-of-midas', 2.00, 0.00, '7fec105fb88f05f58e27d609b896b6b6', '2026-02-13 10:10:30', 1, NULL),
(94914, 928376389, 'the-hand-of-midas', 2.00, 0.00, '1903b31b8126371974aace50d39b3766', '2026-02-13 10:10:31', 1, NULL),
(94915, 928376389, 'the-hand-of-midas', 2.00, 0.00, 'a0a6e01d40819ad05a228e4c624284bd', '2026-02-13 10:10:33', 1, NULL),
(94916, 928376389, 'rave-party-fever', 0.00, 0.00, '8ac8867ac6c8fb894c821dceab736108', '2026-02-13 10:11:16', 1, NULL),
(94917, 928376389, 'rave-party-fever', 0.00, 0.00, '93bc0d6e93df663d390a97d74cda0631', '2026-02-13 10:11:19', 1, NULL),
(94918, 928376389, 'rave-party-fever', 0.00, 0.00, '3165a5db161fb0259668972691766c67', '2026-02-13 10:11:24', 1, NULL),
(94919, 928376389, 'fortune-tiger-fofo', 0.50, 0.00, 'a2d0f19d9541a98b5bfa04005aa1ac1c', '2026-02-13 10:12:46', 1, NULL),
(94920, 928376389, 'fortune-tiger-fofo', 0.50, 0.00, 'dc2c0d4605f95ceab6d76f47de8af87b', '2026-02-13 10:12:48', 1, NULL),
(94921, 928376389, 'oriental-prosperity', 4.00, 0.00, '7b85772242ed84b1b28dcef1b8e16146', '2026-02-13 10:15:46', 1, NULL),
(94922, 928376389, 'fortune-tiger-fofo', 0.50, 0.00, '0d51f8e980bf6b63e2902a81b28ddb8e', '2026-02-13 10:17:41', 1, NULL),
(94923, 928376389, 'fortune-ox-wg', 1.00, 0.00, 'a351ac0526623621a48c2d34f3d4f2d3', '2026-02-13 10:18:19', 1, NULL),
(94924, 928376389, 'phoenix-rises', 4.00, 0.00, 'c7b214c3ee57f1eceda0cefb044d29b7', '2026-02-13 10:19:27', 1, NULL),
(94925, 928376389, 'new-year', 0.50, 0.00, '521d591e26a4e18d00f231f8b99ac1bb', '2026-02-13 10:19:51', 1, NULL),
(94926, 928376389, 'new-year', 0.50, 0.00, '83c7612fd2b9ae247b6b766f845fd88f', '2026-02-13 10:19:54', 1, NULL),
(94927, 928376389, 'pinata-wins', 4.00, 0.00, '400bf6a515b921c52a38739b64b6d059', '2026-02-13 10:20:27', 1, NULL),
(94928, 928376389, 'fortune-snake', 5.00, 0.00, '2423678f502ac54c9971393124fff718', '2026-02-13 10:34:48', 1, NULL),
(94929, 928376389, 'ninja-vs-samurai', 4.00, 0.00, '6944a299d4783de790feea748da1583d', '2026-02-13 10:35:12', 1, NULL),
(94930, 928376389, 'safari-wilds', 4.00, 0.00, '8cb5021a10eae387341c8aeb19bc4159', '2026-02-13 10:35:48', 1, NULL),
(94931, 928376390, 'fortune-tiger-3', 0.40, 0.30, '2649d1efa19fa391e17f29aeee2a44d9', '2026-02-13 22:07:16', 1, NULL),
(94932, 928376390, 'fortune-tiger-3', 0.40, 20.00, '9fa0397dd73f304aaec2f20d391be538', '2026-02-13 22:07:21', 1, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico_rebate`
--

CREATE TABLE `historico_rebate` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `txn_id` text NOT NULL,
  `nivel_id` bigint(20) NOT NULL,
  `data_criacao` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico_vip`
--

CREATE TABLE `historico_vip` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `bonus` float NOT NULL,
  `data` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `igamewin`
--

CREATE TABLE `igamewin` (
  `id` int(11) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `agent_code` text DEFAULT NULL,
  `agent_token` text DEFAULT NULL,
  `ativo` int(11) DEFAULT NULL,
  `rtp` int(11) NOT NULL DEFAULT 50
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `igamewin`
--

INSERT INTO `igamewin` (`id`, `url`, `agent_code`, `agent_token`, `ativo`, `rtp`) VALUES
(1, 'https://igamewin.com/api/v1', 'Agent Code', 'Agent Token', 1, 65);

-- --------------------------------------------------------

--
-- Estrutura para tabela `infinitysoft`
--

CREATE TABLE `infinitysoft` (
  `id` int(11) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `agent_code` text DEFAULT NULL,
  `agent_token` text DEFAULT NULL,
  `agent_secret` text DEFAULT NULL,
  `ativo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `infinitysoft`
--

INSERT INTO `infinitysoft` (`id`, `url`, `agent_code`, `agent_token`, `agent_secret`, `ativo`) VALUES
(1, NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `maxapigames`
--

CREATE TABLE `maxapigames` (
  `id` int(11) NOT NULL,
  `url` text NOT NULL,
  `agent_code` text DEFAULT NULL,
  `agent_token` text DEFAULT NULL,
  `agent_secret` text DEFAULT NULL,
  `ativo` int(11) NOT NULL DEFAULT 0,
  `rtp` int(11) DEFAULT 65
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `maxapigames`
--

INSERT INTO `maxapigames` (`id`, `url`, `agent_code`, `agent_token`, `agent_secret`, `ativo`, `rtp`) VALUES
(1, 'https://maxapigames.com/api/v2', '123', '123', '123', 1, 65);

-- --------------------------------------------------------

--
-- Estrutura para tabela `member_feedback`
--

CREATE TABLE `member_feedback` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT 'ID do usuário da tabela usuarios',
  `content` text NOT NULL COMMENT 'Conteúdo do feedback',
  `status` tinyint(1) DEFAULT 0 COMMENT '0: não revisado, 1: revisado',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='Tabela de feedbacks dos membros';

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensagens`
--

CREATE TABLE `mensagens` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `content` varchar(255) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

--
-- Despejando dados para a tabela `mensagens`
--

INSERT INTO `mensagens` (`id`, `titulo`, `content`, `criado_em`, `status`) VALUES
(1, 'R1', 'R1', '2024-06-28 21:10:47', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `metodos_pagamentos`
--

CREATE TABLE `metodos_pagamentos` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `realname` varchar(255) DEFAULT NULL,
  `pix_id` varchar(255) DEFAULT NULL,
  `flag` tinyint(1) DEFAULT NULL,
  `pix_account` varchar(255) DEFAULT NULL,
  `state` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pay_valores_cassino`
--

CREATE TABLE `pay_valores_cassino` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tipo` int(11) NOT NULL DEFAULT 0 COMMENT '0: CPA / 1: REV / 2: GAMES',
  `data_time` datetime NOT NULL,
  `game` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pgclone`
--

CREATE TABLE `pgclone` (
  `id` int(11) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `agent_code` text DEFAULT NULL,
  `agent_token` text DEFAULT NULL,
  `agent_secret` text DEFAULT NULL,
  `ativo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `pgclone`
--

INSERT INTO `pgclone` (`id`, `url`, `agent_code`, `agent_token`, `agent_secret`, `ativo`) VALUES
(1, 'https://api.pgclone.com', 'Agent Code', 'Agent Token', 'Agent Secret', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `playfiver`
--

CREATE TABLE `playfiver` (
  `id` int(11) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `agent_code` text DEFAULT NULL,
  `agent_token` text DEFAULT NULL,
  `agent_secret` varchar(255) DEFAULT NULL,
  `ativo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

--
-- Despejando dados para a tabela `playfiver`
--

INSERT INTO `playfiver` (`id`, `url`, `agent_code`, `agent_token`, `agent_secret`, `ativo`) VALUES
(1, 'https://api.playfivers.com', 'Agent Code', 'Agent Token', 'Agent Secret', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `popups`
--

CREATE TABLE `popups` (
  `id` int(11) NOT NULL,
  `titulo` longtext DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `redirect_url` mediumtext DEFAULT NULL,
  `img` mediumtext NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `popups`
--

INSERT INTO `popups` (`id`, `titulo`, `content`, `criado_em`, `redirect_url`, `img`, `status`) VALUES
(1, 'Recomende amigos para abrir baús de tesouro', '.', '2024-09-05 08:34:42', NULL, '../image/6825f9687204d.png', 0),
(2, 'Recomendo amigos', 'W1', '2024-09-05 08:34:42', '', '../image/685a985ebec85.jpg', 2),
(3, '777Esperto', '777Esperto', '2024-09-05 08:34:42', '', '../image/67ed996af245a.webp', 0),
(4, 'R1-VERSACE', 'R1-VERSACE', '2024-09-05 08:34:42', NULL, '../image/6834f964c96de.png', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `promocoes`
--

CREATE TABLE `promocoes` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `img` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `promocoes`
--

INSERT INTO `promocoes` (`id`, `titulo`, `criado_em`, `img`, `status`) VALUES
(1, 'Banner 1', '2024-06-28 18:10:47', '1750701876_BANNER INDIQUE E GANHE (1).png', 1),
(2, 'Banner 2', '2024-06-28 18:08:02', '1748476309_BANNER 2.png', 0),
(3, 'Banner 3', '2024-06-28 18:10:47', '1748476331_BANNER 3.png', 0),
(4, 'Banner 4', '2024-06-28 18:08:02', '1748476348_BANNER 4.png', 0),
(5, 'Banner 5', '2024-06-28 18:08:02', '1748476371_BANNER 5.png', 0),
(6, 'Banner 6', '2024-06-28 18:08:02', '1748476517_BANNER 6.png', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `provedores`
--

CREATE TABLE `provedores` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(20) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `provedores`
--

INSERT INTO `provedores` (`id`, `code`, `name`, `type`, `status`) VALUES
(1, 'PGSOFT', 'PGSoft', 'slot', 1),
(2, 'SPRIBE', 'Spribe', 'slot', 1),
(3, 'PRAGMATIC', 'PP', 'slot', 1),
(4, 'PRAGMATIC', 'Pragmatic Play', 'slot', 1),
(5, 'HABANERO', 'Habanero', 'live', 1),
(6, 'BOOONGO', 'Booongo', 'live', 1),
(7, 'PLAYSON', 'Playson', 'slot', 1),
(8, 'CQ9', 'CQ9', 'slot', 1),
(9, 'EVOPLAY', 'Evoplay', 'slot', 1),
(10, 'TOPTREND', 'TopTrend Gaming', 'slot', 1),
(11, 'DREAMTECH', 'DreamTech', 'slot', 1),
(12, 'PGSOFT', 'PG Soft', 'slot', 1),
(13, 'REELKINGDOM', 'Reel Kingdom', 'slot', 1),
(14, 'EZUGI', 'Ezugi', 'slot', 1),
(15, 'EVOLUTION', 'Evolution', 'slot', 1),
(16, 'PRAGMATICLIVE', 'Pragmatic Play Live', 'slot', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `solicitacao_saques`
--

CREATE TABLE `solicitacao_saques` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `transacao_id` text NOT NULL,
  `valor` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tipo` text NOT NULL,
  `pix` text NOT NULL,
  `telefone` varchar(50) DEFAULT NULL,
  `data_cad` date NOT NULL,
  `data_hora` time NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `gateway_usado` varchar(50) DEFAULT NULL COMMENT 'Gateway usado (FivePay, BSPay, etc.)',
  `data_aprovacao` datetime DEFAULT NULL COMMENT 'Data e hora da aprovação',
  `resposta_gateway` text DEFAULT NULL COMMENT 'Resposta JSON do gateway',
  `data_att` datetime DEFAULT NULL,
  `tipo_saque` int(11) NOT NULL DEFAULT 0 COMMENT '0: cassino / 1: afiliados',
  `transaction_id_gateway` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `suitpay`
--

CREATE TABLE `suitpay` (
  `id` int(11) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `client_id` text DEFAULT NULL,
  `client_secret` text DEFAULT NULL,
  `atualizado` varchar(45) DEFAULT NULL,
  `ativo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `suitpay`
--

INSERT INTO `suitpay` (`id`, `url`, `client_id`, `client_secret`, `atualizado`, `ativo`) VALUES
(1, 'https://api.versellpay.com', 'versellpay-418', '22e5336f-faf0-496e-885c-24a12969325d', '0', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tarefas_concluidas`
--

CREATE TABLE `tarefas_concluidas` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `welfare_id` varchar(20) NOT NULL,
  `concluido_em` datetime DEFAULT NULL,
  `recompensa` decimal(10,2) DEFAULT 0.00,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tema`
--

CREATE TABLE `tema` (
  `id` int(11) NOT NULL,
  `cor_padrao` varchar(45) DEFAULT NULL,
  `custom_css` longtext DEFAULT NULL,
  `texto` varchar(45) DEFAULT NULL,
  `status_topheader` int(11) NOT NULL DEFAULT 0,
  `cor_topheader` varchar(48) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `tema`
--

INSERT INTO `tema` (`id`, `cor_padrao`, `custom_css`, `texto`, `status_topheader`, `cor_topheader`) VALUES
(0, '#9BCD10', '', '#FFFFFF', 0, '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tokens_recuperacoes`
--

CREATE TABLE `tokens_recuperacoes` (
  `id_usuario` int(11) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `transacoes`
--

CREATE TABLE `transacoes` (
  `id` int(11) NOT NULL,
  `transacao_id` varchar(255) DEFAULT NULL,
  `usuario` int(11) DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `tipo` enum('deposito','saque') DEFAULT NULL,
  `data_hora` varchar(255) DEFAULT NULL,
  `qrcode` longtext DEFAULT NULL,
  `code` text DEFAULT NULL,
  `status` enum('pago','processamento','expirado') DEFAULT NULL,
  `external_id` varchar(255) DEFAULT NULL,
  `comissao` decimal(10,2) DEFAULT NULL,
  `afiliado_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `user_activity`
--

CREATE TABLE `user_activity` (
  `user_id` int(11) NOT NULL,
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `user_activity`
--

INSERT INTO `user_activity` (`user_id`, `last_activity`) VALUES
(928376389, '2026-02-13 21:46:58'),
(928376390, '2026-02-13 22:05:10');

-- --------------------------------------------------------

--
-- Estrutura para tabela `user_free_rounds`
--

CREATE TABLE `user_free_rounds` (
  `id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `game_id` int(11) NOT NULL,
  `rounds` int(11) NOT NULL DEFAULT 0,
  `used` tinyint(1) DEFAULT 0 COMMENT 'Se as rodadas foram usadas',
  `used_at` timestamp NULL DEFAULT NULL COMMENT 'Quando as rodadas foram usadas',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `celular` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `saldo` decimal(10,2) DEFAULT 0.00,
  `saldo_afiliados` decimal(10,2) DEFAULT 0.00,
  `rev` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_rev` decimal(10,2) NOT NULL DEFAULT 0.00,
  `real_name` varchar(255) NOT NULL,
  `spassword` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `data_cad` datetime DEFAULT NULL,
  `invite_code` varchar(255) NOT NULL,
  `invitation_code` varchar(255) DEFAULT NULL,
  `cpf` varchar(11) DEFAULT NULL,
  `tipo_pagamento` int(11) NOT NULL DEFAULT 0,
  `ip_cadastro` varchar(45) DEFAULT NULL,
  `senha_saque` int(11) NOT NULL DEFAULT 0,
  `senhaparasacar` varchar(255) DEFAULT NULL,
  `pessoas_convidadas` int(11) NOT NULL DEFAULT 0,
  `statusaff` int(11) NOT NULL DEFAULT 0,
  `banido` int(11) DEFAULT 0,
  `historico` text DEFAULT NULL,
  `favoritos` text DEFAULT NULL,
  `vip` int(11) NOT NULL DEFAULT 0,
  `recompensa_vip` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_recompensa_vip` decimal(10,2) NOT NULL DEFAULT 0.00,
  `data_nascimento` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `avatar` int(255) NOT NULL DEFAULT 1,
  `facebook` varchar(255) DEFAULT NULL,
  `whatsapp` varchar(255) DEFAULT NULL,
  `telegram` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `birth` varchar(255) DEFAULT NULL,
  `indicador_id` int(11) DEFAULT NULL,
  `tipo_conta` enum('usuario','afiliado') DEFAULT 'usuario',
  `comissao_deposito` decimal(5,2) DEFAULT 0.00,
  `pessoas_convidadas_real` int(11) NOT NULL DEFAULT 0,
  `chanceCpa_individual` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `mobile`, `celular`, `password`, `saldo`, `saldo_afiliados`, `rev`, `total_rev`, `real_name`, `spassword`, `url`, `token`, `data_cad`, `invite_code`, `invitation_code`, `cpf`, `tipo_pagamento`, `ip_cadastro`, `senha_saque`, `senhaparasacar`, `pessoas_convidadas`, `statusaff`, `banido`, `historico`, `favoritos`, `vip`, `recompensa_vip`, `total_recompensa_vip`, `data_nascimento`, `email`, `avatar`, `facebook`, `whatsapp`, `telegram`, `twitter`, `birth`, `indicador_id`, `tipo_conta`, `comissao_deposito`, `pessoas_convidadas_real`, `chanceCpa_individual`) VALUES
(928376389, 'asdasda1231', '119129391', '$2y$12$KVRviAeVYQIX3SSELw8j2uBCr65t4H3S8tWkxTWDs/7WOFcyXFJGm', 12235.10, 0.00, 0.00, 0.00, 'asdasda1231', '$2y$12$KVRviAeVYQIX3SSELw8j2uBCr65t4H3S8tWkxTWDs/7WOFcyXFJGm', 'https://discoteca-777.fun/', '4831687b7b0146cc2abecc5f12691067d0234aaa58003b71f37255931c096403', '2026-02-13 03:17:40', '6591059', NULL, NULL, 1, '2804:1b1:f942:b12b:90ec:db5d:3805:cb80', 0, NULL, 0, 0, 0, '1414,1415,1413,1473,1329,1327,1326,2048,1957,1953,1943,1954,1952,1956,1944,1898,1918,1948,2050,2052,2055,2060,2061,2059,2062,2063,2064,2065,2067,2068,1949,1873,2070,2102,2120,2073,2079,2077,2101,2099', NULL, 0, 0.00, 0.00, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'usuario', 0.00, 0, NULL),
(928376390, 'demo11', '51948930942', '$2y$10$nyYP5cgaSnABt/HNS2CWLuHHUIei5wl.VqDH7rjl4QIYOavV.ER9C', 119.50, 0.00, 0.00, 0.00, 'demo11', '$2y$10$nyYP5cgaSnABt/HNS2CWLuHHUIei5wl.VqDH7rjl4QIYOavV.ER9C', 'https://discoteca-777.fun/', '2306cef20599412c8e4846ef45583733', '2026-02-13 19:04:42', '8033529', NULL, NULL, 1, '2804:1b1:f942:b12b:2596:18f8:aff5:2670', 0, NULL, 0, 1, 0, '2136,2225', NULL, 0, 0.00, 0.00, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'usuario', 0.00, 0, NULL),
(928376391, 'demo12', '99968772476', '$2y$10$eoqHgZRDK6mV/OCRBTdd9.dq7z/PjZp7jFCh7ii41Qcaj60yImseW', 100.00, 0.00, 0.00, 0.00, 'demo12', '$2y$10$eoqHgZRDK6mV/OCRBTdd9.dq7z/PjZp7jFCh7ii41Qcaj60yImseW', 'https://discoteca-777.fun/', '311f11ffda0c828c56810297514fa604', '2026-02-13 19:04:49', '1602134', NULL, NULL, 1, '2804:1b1:f942:b12b:2596:18f8:aff5:2670', 0, NULL, 0, 1, 0, NULL, NULL, 0, 0.00, 0.00, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'usuario', 0.00, 0, NULL),
(928376392, 'demo13', '48921214326', '$2y$10$4F4hBCR9XnWPH/290Sn1VuF60uVQjupiUxo4DrsRmdCuOVhmS6o8q', 100.00, 0.00, 0.00, 0.00, 'demo13', '$2y$10$4F4hBCR9XnWPH/290Sn1VuF60uVQjupiUxo4DrsRmdCuOVhmS6o8q', 'https://discoteca-777.fun/', '7e23d72b207146e1f8bf9420120f0a58', '2026-02-13 19:04:52', '7388234', NULL, NULL, 1, '2804:1b1:f942:b12b:2596:18f8:aff5:2670', 0, NULL, 0, 1, 0, NULL, NULL, 0, 0.00, 0.00, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'usuario', 0.00, 0, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `versell`
--

CREATE TABLE `versell` (
  `id` int(11) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `client_id` text DEFAULT NULL,
  `client_secret` text DEFAULT NULL,
  `atualizado` varchar(45) DEFAULT NULL,
  `ativo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `versell`
--

INSERT INTO `versell` (`id`, `url`, `client_id`, `client_secret`, `atualizado`, `ativo`) VALUES
(1, 'https://api.versellpay.com', 'versellpay-48', '5d52db13-cff9-4195-920e-87d872087e95', '0', 0);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `view_free_rounds_stats`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `view_free_rounds_stats` (
`game_code` text
,`game_name` text
,`provider` text
,`rounds_configured` int(11)
,`unique_users` bigint(21)
,`total_rounds_used` decimal(32,0)
,`avg_rounds_per_user` decimal(14,4)
,`first_usage` timestamp
,`last_usage` timestamp
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `view_games_free_rounds`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `view_games_free_rounds` (
`id` int(11)
,`game_code` text
,`game_name` text
,`provider` text
,`free_bonus_rounds` int(11)
,`game_original` tinyint(1)
,`supports_free_spins` tinyint(1)
,`status` int(11)
,`users_used_count` bigint(21)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `view_user_free_rounds_usage`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `view_user_free_rounds_usage` (
`user_email` varchar(255)
,`game_id` int(11)
,`game_code` text
,`game_name` text
,`provider` text
,`rounds` int(11)
,`max_rounds_available` int(11)
,`created_at` timestamp
,`expires_at` timestamp
,`game_original` tinyint(1)
,`status_expiracao` varchar(13)
);

-- --------------------------------------------------------

--
-- Estrutura para tabela `visita_site`
--

CREATE TABLE `visita_site` (
  `id` int(11) NOT NULL,
  `nav_os` text DEFAULT NULL,
  `mac_os` text DEFAULT NULL,
  `ip_visita` text DEFAULT NULL,
  `refer_visita` text DEFAULT NULL,
  `data_cad` date DEFAULT NULL,
  `hora_cad` time DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `pais` text DEFAULT NULL,
  `cidade` text DEFAULT NULL,
  `estado` text DEFAULT NULL,
  `ads_tipo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `visita_site`
--

INSERT INTO `visita_site` (`id`, `nav_os`, `mac_os`, `ip_visita`, `refer_visita`, `data_cad`, `hora_cad`, `id_user`, `pais`, `cidade`, `estado`, `ads_tipo`) VALUES
(6820, 'Chrome', 'Windows 10', '2804:1b1:f942:b12b:90ec:db5d:3805:cb80', 'https://discoteca-777.fun', '2026-02-13', '02:48:43', 1, 'Brazil', 'Campinas', 'Sao Paulo', NULL),
(6821, 'Chrome', 'Windows 10', '2804:1b1:f942:b12b:2596:18f8:aff5:2670', 'https://discoteca-777.fun/admin/login', '2026-02-13', '18:36:21', 1, 'Brazil', 'Campinas', 'Sao Paulo', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `webhook`
--

CREATE TABLE `webhook` (
  `id` int(11) NOT NULL,
  `nome` text NOT NULL,
  `bot_id` varchar(255) NOT NULL,
  `chat_id` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `webhook`
--

INSERT INTO `webhook` (`id`, `nome`, `bot_id`, `chat_id`, `status`) VALUES
(1, 'Cadastros e Pixs', '8107042435:AAH-DDwub4sm1I3CtMk2mEXU4LozIyC8o6Q', '-4880683315', 1),
(2, 'Saques', '7691515435:AAF4-eKCjnQE-RuLdbIEaih6yuzeEkgfBII', '-4952646081', 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `afiliados_config`
--
ALTER TABLE `afiliados_config`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `banner`
--
ALTER TABLE `banner`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `bau`
--
ALTER TABLE `bau`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `bonus_config`
--
ALTER TABLE `bonus_config`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `bonus_historico`
--
ALTER TABLE `bonus_historico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `bonus_config_id` (`bonus_config_id`);

--
-- Índices de tabela `bspay`
--
ALTER TABLE `bspay`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `bullspay`
--
ALTER TABLE `bullspay`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `cupom`
--
ALTER TABLE `cupom`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `cupom_usados`
--
ALTER TABLE `cupom_usados`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `expfypay`
--
ALTER TABLE `expfypay`
  ADD UNIQUE KEY `id` (`id`);

--
-- Índices de tabela `financeiro`
--
ALTER TABLE `financeiro`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fivepay`
--
ALTER TABLE `fivepay`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fiverscan`
--
ALTER TABLE `fiverscan`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `floats`
--
ALTER TABLE `floats`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Índices de tabela `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_games_supports_free_spins` (`supports_free_spins`),
  ADD KEY `idx_games_original_free_spins` (`game_original`,`supports_free_spins`);

--
-- Índices de tabela `historico_comissoes`
--
ALTER TABLE `historico_comissoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_afiliado` (`afiliado_id`),
  ADD KEY `idx_transacao` (`transacao_id`),
  ADD KEY `idx_data` (`data_processamento`);

--
-- Índices de tabela `historico_play`
--
ALTER TABLE `historico_play`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `historico_rebate`
--
ALTER TABLE `historico_rebate`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Índices de tabela `igamewin`
--
ALTER TABLE `igamewin`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `infinitysoft`
--
ALTER TABLE `infinitysoft`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `maxapigames`
--
ALTER TABLE `maxapigames`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `member_feedback`
--
ALTER TABLE `member_feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_uid` (`uid`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Índices de tabela `mensagens`
--
ALTER TABLE `mensagens`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Índices de tabela `metodos_pagamentos`
--
ALTER TABLE `metodos_pagamentos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pay_valores_cassino`
--
ALTER TABLE `pay_valores_cassino`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pgclone`
--
ALTER TABLE `pgclone`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `playfiver`
--
ALTER TABLE `playfiver`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Índices de tabela `popups`
--
ALTER TABLE `popups`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `promocoes`
--
ALTER TABLE `promocoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `provedores`
--
ALTER TABLE `provedores`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `solicitacao_saques`
--
ALTER TABLE `solicitacao_saques`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status_gateway` (`status`,`gateway_usado`);

--
-- Índices de tabela `suitpay`
--
ALTER TABLE `suitpay`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tarefas_concluidas`
--
ALTER TABLE `tarefas_concluidas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_welfare` (`id_usuario`,`welfare_id`);

--
-- Índices de tabela `tema`
--
ALTER TABLE `tema`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `transacoes`
--
ALTER TABLE `transacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_afiliado` (`afiliado_id`),
  ADD KEY `idx_comissao` (`comissao`);

--
-- Índices de tabela `user_activity`
--
ALTER TABLE `user_activity`
  ADD PRIMARY KEY (`user_id`);

--
-- Índices de tabela `user_free_rounds`
--
ALTER TABLE `user_free_rounds`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_game_unique` (`user_email`,`game_id`),
  ADD UNIQUE KEY `unique_user_game` (`user_email`,`game_id`),
  ADD KEY `idx_used` (`used`),
  ADD KEY `idx_expires` (`expires_at`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_indicador` (`indicador_id`),
  ADD KEY `idx_tipo_conta` (`tipo_conta`);

--
-- Índices de tabela `versell`
--
ALTER TABLE `versell`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `visita_site`
--
ALTER TABLE `visita_site`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `webhook`
--
ALTER TABLE `webhook`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `afiliados_config`
--
ALTER TABLE `afiliados_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `banner`
--
ALTER TABLE `banner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `bau`
--
ALTER TABLE `bau`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=486041;

--
-- AUTO_INCREMENT de tabela `bonus_config`
--
ALTER TABLE `bonus_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `bonus_historico`
--
ALTER TABLE `bonus_historico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT de tabela `bullspay`
--
ALTER TABLE `bullspay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `cupom`
--
ALTER TABLE `cupom`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `cupom_usados`
--
ALTER TABLE `cupom_usados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=754;

--
-- AUTO_INCREMENT de tabela `financeiro`
--
ALTER TABLE `financeiro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `floats`
--
ALTER TABLE `floats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2405;

--
-- AUTO_INCREMENT de tabela `historico_comissoes`
--
ALTER TABLE `historico_comissoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT de tabela `historico_play`
--
ALTER TABLE `historico_play`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94933;

--
-- AUTO_INCREMENT de tabela `historico_rebate`
--
ALTER TABLE `historico_rebate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `igamewin`
--
ALTER TABLE `igamewin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=674;

--
-- AUTO_INCREMENT de tabela `maxapigames`
--
ALTER TABLE `maxapigames`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `member_feedback`
--
ALTER TABLE `member_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de tabela `mensagens`
--
ALTER TABLE `mensagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `metodos_pagamentos`
--
ALTER TABLE `metodos_pagamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT de tabela `pay_valores_cassino`
--
ALTER TABLE `pay_valores_cassino`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `promocoes`
--
ALTER TABLE `promocoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `provedores`
--
ALTER TABLE `provedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `solicitacao_saques`
--
ALTER TABLE `solicitacao_saques`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT de tabela `tarefas_concluidas`
--
ALTER TABLE `tarefas_concluidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `transacoes`
--
ALTER TABLE `transacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1117;

--
-- AUTO_INCREMENT de tabela `user_free_rounds`
--
ALTER TABLE `user_free_rounds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=928376393;

--
-- AUTO_INCREMENT de tabela `visita_site`
--
ALTER TABLE `visita_site`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6822;

-- --------------------------------------------------------

--
-- Estrutura para view `view_free_rounds_stats`
--
DROP TABLE IF EXISTS `view_free_rounds_stats`;


-- --------------------------------------------------------

--
-- Estrutura para view `view_games_free_rounds`
--
DROP TABLE IF EXISTS `view_games_free_rounds`;


-- --------------------------------------------------------

--
-- Estrutura para view `view_user_free_rounds_usage`
--
DROP TABLE IF EXISTS `view_user_free_rounds_usage`;


--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `historico_comissoes`
--
ALTER TABLE `historico_comissoes`
  ADD CONSTRAINT `historico_comissoes_ibfk_1` FOREIGN KEY (`afiliado_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `member_feedback`
--
ALTER TABLE `member_feedback`
  ADD CONSTRAINT `fk_feedback_usuario` FOREIGN KEY (`uid`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
