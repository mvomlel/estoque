SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS `inventario_quimico` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `inventario_quimico`;

-- Tabela de edificações e cômodos
CREATE TABLE `t_laboratorio` (
  `id` int(11) NOT NULL,
  `nome` text DEFAULT NULL,
  `programa` text DEFAULT NULL
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `t_laboratorio` (`id`, `nome`, `programa`) VALUES (1, 'Engenharia de Microalgas', 'PPGEQ');
INSERT INTO `t_laboratorio` (`id`, `nome`, `programa`) VALUES (2, 'Biopolímeros em Biomedicina e
Desenvolvimento Sustentável', 'PPGEQ');
INSERT INTO `t_laboratorio` (`id`, `nome`, `programa`) VALUES (3, 'Nanotecnologia', 'PPGEQ');
INSERT INTO `t_laboratorio` (`id`, `nome`, `programa`) VALUES (4, 'Biocatálise', 'PPGEQ');
INSERT INTO `t_laboratorio` (`id`, `nome`, `programa`) VALUES (5, 'Modelagem e Síntese de Catalisadores Poliméricos', 'PPGEQ');


-- Tabela principal de inventário
CREATE TABLE `t_equip` (
  `id` int(11) NOT NULL,
  `nome` text DEFAULT NULL,
  `id_laboratorio` int(11) DEFAULT NULL,
  `estado` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `t_equip` (`id`, `nome`, `id_laboratorio`,`estado`) VALUES (1, 'Centrífuga', '4','OK');
INSERT INTO `t_equip` (`id`, `nome`, `id_laboratorio`,`estado`) VALUES (2, 'Ultrassom Banho', '4','OK');
INSERT INTO `t_equip` (`id`, `nome`, `id_laboratorio`,`estado`) VALUES (3, 'Ultrassom Sonda', '4','OK');
INSERT INTO `t_equip` (`id`, `nome`, `id_laboratorio`,`estado`) VALUES (4, 'Agitador de Banho Termostático', '4','OK');
INSERT INTO `t_equip` (`id`, `nome`, `id_laboratorio`,`estado`) VALUES (5, 'Coifa Exaustora', '5','Manutenção');
INSERT INTO `t_equip` (`id`, `nome`, `id_laboratorio`,`estado`) VALUES (6, 'Fotobiorreator', '1','OK');
INSERT INTO `t_equip` (`id`, `nome`, `id_laboratorio`,`estado`) VALUES (5, 'Coifa Exaustora', '5','OK');
INSERT INTO `t_equip` (`id`, `nome`, `id_laboratorio`,`estado`) VALUES (7, 'Espectrofotômetro', '4','OK');
INSERT INTO `t_equip` (`id`, `nome`, `id_laboratorio`,`estado`) VALUES (8, 'Microscópio Eletrônico de Varredura', '3','OK');


-- Tabela de produtos químicos
CREATE TABLE `t_produto` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `formula` varchar(100) DEFAULT NULL,
  `volume` int(11) DEFAULT NULL,
  `massa` int(11) DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `id_laboratorio` int(11) DEFAULT NULL
 
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `t_produto` (`id`, `nome`, `formula`,`volume`,`massa`,`quantidade`,`id_laboratorio`) VALUES (1, 'ACIDO ACETICO', 'C2H4O2', 2, 0, 5 ,1);
INSERT INTO `t_produto` (`id`, `nome`, `formula`,`volume`,`massa`,`quantidade`,`id_laboratorio`) VALUES (2,'ACIDO CLORIDRICO', 'HCl', 2,	0, 8,1);
INSERT INTO `t_produto` (`id`, `nome`, `formula`,`volume`,`massa`,`quantidade`,`id_laboratorio`) VALUES (3,'ACIDO FLUORIDRICO', 'HF', 15,	0, 2,4);
INSERT INTO `t_produto` (`id`, `nome`, `formula`,`volume`,`massa`,`quantidade`,`id_laboratorio`) VALUES (4,'ACIDO FOSFORICO', 'H3PO4', 2, 0,6, 3);
INSERT INTO `t_produto` (`id`, `nome`, `formula`,`volume`,`massa`,`quantidade`,`id_laboratorio`) VALUES (5,'ACIDO NITRICO', 'HNO3', 2, 0, 5, 5);
INSERT INTO `t_produto` (`id`, `nome`, `formula`,`volume`,`massa`,`quantidade`,`id_laboratorio`) VALUES (6,'ACIDO SULFURICO', 'H2SO4', 1, 0, 9, 5);
INSERT INTO `t_produto` (`id`, `nome`, `formula`,`volume`,`massa`,`quantidade`,`id_laboratorio`) VALUES (7,'HEPTANO', 'C7H16', 1, 0, 9, 1);
INSERT INTO `t_produto` (`id`, `nome`, `formula`,`volume`,`massa`,`quantidade`,`id_laboratorio`) VALUES (8,'HEXANO', 'C6H14', 1, 0, 12, 1);
INSERT INTO `t_produto` (`id`, `nome`, `formula`,`volume`,`massa`,`quantidade`,`id_laboratorio`) VALUES (9,'PENTANO', 'C5H12', 1, 0, 9, 2);
INSERT INTO `t_produto` (`id`, `nome`, `formula`,`volume`,`massa`,`quantidade`,`id_laboratorio`) VALUES (10,'NAFTALENO', 'C10H8', 0, 1, 2,2);
INSERT INTO `t_produto` (`id`, `nome`, `formula`,`volume`,`massa`,`quantidade`,`id_laboratorio`) VALUES (11,'ACETONA', 'C3H6O', 20, 0, 4,5);
INSERT INTO `t_produto` (`id`, `nome`, `formula`,`volume`,`massa`,`quantidade`,`id_laboratorio`) VALUES (12,'ETANOL', 'C2H6O', 20, 0, 14, 3);
INSERT INTO `t_produto` (`id`, `nome`, `formula`,`volume`,`massa`,`quantidade`,`id_laboratorio`) VALUES (13,'CLORETO DE SODIO', 'NaCl', 0, 50, 2, 1);
INSERT INTO `t_produto` (`id`, `nome`, `formula`,`volume`,`massa`,`quantidade`,`id_laboratorio`) VALUES (14,'ClORETO DE CALCIO', 'CaCl2', 0,	1, 6, 1);
INSERT INTO `t_produto` (`id`, `nome`, `formula`,`volume`,`massa`,`quantidade`,`id_laboratorio`) VALUES (15,'CARBONATO DE CALCIO', 'CaCO3', 0,	1, 8, 1);
INSERT INTO `t_produto` (`id`, `nome`, `formula`,`volume`,`massa`,`quantidade`,`id_laboratorio`) VALUES (16,'BICARBONATO DE SODIO', 'NaHCO3', 0,	1, 7, 2);
INSERT INTO `t_produto` (`id`, `nome`, `formula`,`volume`,`massa`,`quantidade`,`id_laboratorio`) VALUES (17,'FOSFATO DE POTASSIO', 'K3PO4', 0,	1, 10, 3);
INSERT INTO `t_produto` (`id`, `nome`, `formula`,`volume`,`massa`,`quantidade`,`id_laboratorio`) VALUES (18,'HIDROXIDO DE SODIO', 'NaOH', 0,	1, 10, 4);
INSERT INTO `t_produto` (`id`, `nome`, `formula`,`volume`,`massa`,`quantidade`,`id_laboratorio`) VALUES (19,'ACETATO DE SODIO', 'C2H3NaO2', 2,	0, 6, 4);
INSERT INTO `t_produto` (`id`, `nome`, `formula`,`volume`,`massa`,`quantidade`,`id_laboratorio`) VALUES (20,'EDTA', 'C10H16N2O8', 0,	1,	2, 1);


COMMIT;