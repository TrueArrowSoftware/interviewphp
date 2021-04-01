-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 01, 2021 at 11:28 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shoppingcart`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `addressid` bigint(20) UNSIGNED NOT NULL,
  `addresstype` varchar(20) NOT NULL DEFAULT 'default',
  `ownerid` bigint(20) NOT NULL,
  `ownertype` varchar(20) NOT NULL,
  `title` varchar(150) DEFAULT NULL,
  `subtitle` varchar(150) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL DEFAULT 'Malta',
  `adddate` datetime DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `zipcode` varchar(10) NOT NULL,
  `tag` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `attributeoption`
--

CREATE TABLE `attributeoption` (
  `optionid` bigint(20) UNSIGNED NOT NULL,
  `attribute` varchar(50) NOT NULL,
  `optionname` varchar(100) NOT NULL,
  `tag` varchar(100) DEFAULT NULL,
  `displayorder` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `attributeoption`
--

INSERT INTO `attributeoption` (`optionid`, `attribute`, `optionname`, `tag`, `displayorder`) VALUES
(1, 'color', 'Red', 'color', 1),
(2, 'color', 'Black', 'black', 3),
(3, 'color', 'White', 'white', 2),
(4, 'size', '26', '26', 4),
(5, 'size', '28', '28', 6),
(6, 'size', '30', '30', 5);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `itemid` bigint(20) UNSIGNED NOT NULL,
  `cartid` varchar(50) NOT NULL,
  `productid` bigint(20) NOT NULL,
  `variationid` bigint(11) NOT NULL DEFAULT 0,
  `productcode` varchar(50) NOT NULL,
  `productname` text NOT NULL,
  `price` float NOT NULL,
  `quantity` int(11) NOT NULL,
  `extrainfo` text DEFAULT NULL,
  `adddate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `categoryid` bigint(20) UNSIGNED NOT NULL,
  `guid` varchar(30) NOT NULL,
  `categoryname` varchar(100) NOT NULL,
  `parentid` bigint(20) NOT NULL DEFAULT 0,
  `adddate` datetime DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `displayorder` int(11) NOT NULL DEFAULT 999,
  `showinmenu` int(11) NOT NULL DEFAULT 0,
  `breadcrumb` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`categoryid`, `guid`, `categoryname`, `parentid`, `adddate`, `editdate`, `status`, `displayorder`, `showinmenu`, `breadcrumb`) VALUES
(1, 'C31C3FBD91', 'Shoes', 0, '2020-09-01 14:37:53', NULL, 1, 3, 0, NULL),
(2, '6F4B2201D5', 'Men', 1, '2020-09-01 14:38:01', NULL, 1, 2, 0, NULL),
(3, 'D7E6CF3068', 'Women', 1, '2020-09-01 14:38:09', '2020-09-01 14:38:47', 1, 1, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `companyid` bigint(20) UNSIGNED NOT NULL,
  `companyname` varchar(100) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`companyid`, `companyname`, `status`) VALUES
(1, 'Puma', 1);

-- --------------------------------------------------------

--
-- Table structure for table `configuration`
--

CREATE TABLE `configuration` (
  `settingid` bigint(20) NOT NULL,
  `settingkey` varchar(255) COLLATE utf8_bin NOT NULL,
  `settingvalue` text COLLATE utf8_bin NOT NULL,
  `displayname` varchar(100) COLLATE utf8_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `configuration`
--

INSERT INTO `configuration` (`settingid`, `settingkey`, `settingvalue`, `displayname`) VALUES
(1, 'tax', '5', 'Tax Value'),
(2, 'shipping', '9', 'Shipping Price');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `countryid` int(11) NOT NULL,
  `codenumber` bigint(20) NOT NULL DEFAULT 0,
  `countrycode2` varchar(2) COLLATE utf8mb4_bin DEFAULT NULL,
  `countrycode3` varchar(3) COLLATE utf8mb4_bin DEFAULT NULL,
  `countryname` varchar(52) COLLATE utf8mb4_bin DEFAULT NULL,
  `nationality` varchar(39) COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`countryid`, `codenumber`, `countrycode2`, `countrycode3`, `countryname`, `nationality`) VALUES
(1, 4, 'AF', 'AFG', 'Afghanistan', 'Afghan'),
(2, 248, 'AX', 'ALA', 'Åland Islands', 'Åland Island'),
(3, 8, 'AL', 'ALB', 'Albania', 'Albanian'),
(4, 12, 'DZ', 'DZA', 'Algeria', 'Algerian'),
(5, 16, 'AS', 'ASM', 'American Samoa', 'American Samoan'),
(6, 20, 'AD', 'AND', 'Andorra', 'Andorran'),
(7, 24, 'AO', 'AGO', 'Angola', 'Angolan'),
(8, 660, 'AI', 'AIA', 'Anguilla', 'Anguillan'),
(9, 10, 'AQ', 'ATA', 'Antarctica', 'Antarctic'),
(10, 28, 'AG', 'ATG', 'Antigua and Barbuda', 'Antiguan or Barbudan'),
(11, 32, 'AR', 'ARG', 'Argentina', 'Argentine'),
(12, 51, 'AM', 'ARM', 'Armenia', 'Armenian'),
(13, 533, 'AW', 'ABW', 'Aruba', 'Aruban'),
(14, 36, 'AU', 'AUS', 'Australia', 'Australian'),
(15, 40, 'AT', 'AUT', 'Austria', 'Austrian'),
(16, 31, 'AZ', 'AZE', 'Azerbaijan', 'Azerbaijani, Azeri'),
(17, 44, 'BS', 'BHS', 'Bahamas', 'Bahamian'),
(18, 48, 'BH', 'BHR', 'Bahrain', 'Bahraini'),
(19, 50, 'BD', 'BGD', 'Bangladesh', 'Bangladeshi'),
(20, 52, 'BB', 'BRB', 'Barbados', 'Barbadian'),
(21, 112, 'BY', 'BLR', 'Belarus', 'Belarusian'),
(22, 56, 'BE', 'BEL', 'Belgium', 'Belgian'),
(23, 84, 'BZ', 'BLZ', 'Belize', 'Belizean'),
(24, 204, 'BJ', 'BEN', 'Benin', 'Beninese, Beninois'),
(25, 60, 'BM', 'BMU', 'Bermuda', 'Bermudian, Bermudan'),
(26, 64, 'BT', 'BTN', 'Bhutan', 'Bhutanese'),
(27, 68, 'BO', 'BOL', 'Bolivia (Plurinational State of)', 'Bolivian'),
(28, 535, 'BQ', 'BES', 'Bonaire, Sint Eustatius and Saba', 'Bonaire'),
(29, 70, 'BA', 'BIH', 'Bosnia and Herzegovina', 'Bosnian or Herzegovinian'),
(30, 72, 'BW', 'BWA', 'Botswana', 'Motswana, Botswanan'),
(31, 74, 'BV', 'BVT', 'Bouvet Island', 'Bouvet Island'),
(32, 76, 'BR', 'BRA', 'Brazil', 'Brazilian'),
(33, 86, 'IO', 'IOT', 'British Indian Ocean Territory', 'BIOT'),
(34, 96, 'BN', 'BRN', 'Brunei Darussalam', 'Bruneian'),
(35, 100, 'BG', 'BGR', 'Bulgaria', 'Bulgarian'),
(36, 854, 'BF', 'BFA', 'Burkina Faso', 'Burkinabé'),
(37, 108, 'BI', 'BDI', 'Burundi', 'Burundian'),
(38, 132, 'CV', 'CPV', 'Cabo Verde', 'Cabo Verdean'),
(39, 116, 'KH', 'KHM', 'Cambodia', 'Cambodian'),
(40, 120, 'CM', 'CMR', 'Cameroon', 'Cameroonian'),
(41, 124, 'CA', 'CAN', 'Canada', 'Canadian'),
(42, 136, 'KY', 'CYM', 'Cayman Islands', 'Caymanian'),
(43, 140, 'CF', 'CAF', 'Central African Republic', 'Central African'),
(44, 148, 'TD', 'TCD', 'Chad', 'Chadian'),
(45, 152, 'CL', 'CHL', 'Chile', 'Chilean'),
(46, 156, 'CN', 'CHN', 'China', 'Chinese'),
(47, 162, 'CX', 'CXR', 'Christmas Island', 'Christmas Island'),
(48, 166, 'CC', 'CCK', 'Cocos (Keeling) Islands', 'Cocos Island'),
(49, 170, 'CO', 'COL', 'Colombia', 'Colombian'),
(50, 174, 'KM', 'COM', 'Comoros', 'Comoran, Comorian'),
(51, 178, 'CG', 'COG', 'Congo (Republic of the)', 'Congolese'),
(52, 180, 'CD', 'COD', 'Congo (Democratic Republic of the)', 'Congolese'),
(53, 184, 'CK', 'COK', 'Cook Islands', 'Cook Island'),
(54, 188, 'CR', 'CRI', 'Costa Rica', 'Costa Rican'),
(55, 384, 'CI', 'CIV', 'Côte d\'Ivoire', 'Ivorian'),
(56, 191, 'HR', 'HRV', 'Croatia', 'Croatian'),
(57, 192, 'CU', 'CUB', 'Cuba', 'Cuban'),
(58, 531, 'CW', 'CUW', 'Curaçao', 'Curaçaoan'),
(59, 196, 'CY', 'CYP', 'Cyprus', 'Cypriot'),
(60, 203, 'CZ', 'CZE', 'Czech Republic', 'Czech'),
(61, 208, 'DK', 'DNK', 'Denmark', 'Danish'),
(62, 262, 'DJ', 'DJI', 'Djibouti', 'Djiboutian'),
(63, 212, 'DM', 'DMA', 'Dominica', 'Dominican'),
(64, 214, 'DO', 'DOM', 'Dominican Republic', 'Dominican'),
(65, 218, 'EC', 'ECU', 'Ecuador', 'Ecuadorian'),
(66, 818, 'EG', 'EGY', 'Egypt', 'Egyptian'),
(67, 222, 'SV', 'SLV', 'El Salvador', 'Salvadoran'),
(68, 226, 'GQ', 'GNQ', 'Equatorial Guinea', 'Equatorial Guinean, Equatoguinean'),
(69, 232, 'ER', 'ERI', 'Eritrea', 'Eritrean'),
(70, 233, 'EE', 'EST', 'Estonia', 'Estonian'),
(71, 231, 'ET', 'ETH', 'Ethiopia', 'Ethiopian'),
(72, 238, 'FK', 'FLK', 'Falkland Islands (Malvinas)', 'Falkland Island'),
(73, 234, 'FO', 'FRO', 'Faroe Islands', 'Faroese'),
(74, 242, 'FJ', 'FJI', 'Fiji', 'Fijian'),
(75, 246, 'FI', 'FIN', 'Finland', 'Finnish'),
(76, 250, 'FR', 'FRA', 'France', 'French'),
(77, 254, 'GF', 'GUF', 'French Guiana', 'French Guianese'),
(78, 258, 'PF', 'PYF', 'French Polynesia', 'French Polynesian'),
(79, 260, 'TF', 'ATF', 'French Southern Territories', 'French Southern Territories'),
(80, 266, 'GA', 'GAB', 'Gabon', 'Gabonese'),
(81, 270, 'GM', 'GMB', 'Gambia', 'Gambian'),
(82, 268, 'GE', 'GEO', 'Georgia', 'Georgian'),
(83, 276, 'DE', 'DEU', 'Germany', 'German'),
(84, 288, 'GH', 'GHA', 'Ghana', 'Ghanaian'),
(85, 292, 'GI', 'GIB', 'Gibraltar', 'Gibraltar'),
(86, 300, 'GR', 'GRC', 'Greece', 'Greek, Hellenic'),
(87, 304, 'GL', 'GRL', 'Greenland', 'Greenlandic'),
(88, 308, 'GD', 'GRD', 'Grenada', 'Grenadian'),
(89, 312, 'GP', 'GLP', 'Guadeloupe', 'Guadeloupe'),
(90, 316, 'GU', 'GUM', 'Guam', 'Guamanian, Guambat'),
(91, 320, 'GT', 'GTM', 'Guatemala', 'Guatemalan'),
(92, 831, 'GG', 'GGY', 'Guernsey', 'Channel Island'),
(93, 324, 'GN', 'GIN', 'Guinea', 'Guinean'),
(94, 624, 'GW', 'GNB', 'Guinea-Bissau', 'Bissau-Guinean'),
(95, 328, 'GY', 'GUY', 'Guyana', 'Guyanese'),
(96, 332, 'HT', 'HTI', 'Haiti', 'Haitian'),
(97, 334, 'HM', 'HMD', 'Heard Island and McDonald Islands', 'Heard Island or McDonald Islands'),
(98, 336, 'VA', 'VAT', 'Vatican City State', 'Vatican'),
(99, 340, 'HN', 'HND', 'Honduras', 'Honduran'),
(100, 344, 'HK', 'HKG', 'Hong Kong', 'Hong Kong, Hong Kongese'),
(101, 348, 'HU', 'HUN', 'Hungary', 'Hungarian, Magyar'),
(102, 352, 'IS', 'ISL', 'Iceland', 'Icelandic'),
(103, 356, 'IN', 'IND', 'India', 'Indian'),
(104, 360, 'ID', 'IDN', 'Indonesia', 'Indonesian'),
(105, 364, 'IR', 'IRN', 'Iran', 'Iranian, Persian'),
(106, 368, 'IQ', 'IRQ', 'Iraq', 'Iraqi'),
(107, 372, 'IE', 'IRL', 'Ireland', 'Irish'),
(108, 833, 'IM', 'IMN', 'Isle of Man', 'Manx'),
(109, 376, 'IL', 'ISR', 'Israel', 'Israeli'),
(110, 380, 'IT', 'ITA', 'Italy', 'Italian'),
(111, 388, 'JM', 'JAM', 'Jamaica', 'Jamaican'),
(112, 392, 'JP', 'JPN', 'Japan', 'Japanese'),
(113, 832, 'JE', 'JEY', 'Jersey', 'Channel Island'),
(114, 400, 'JO', 'JOR', 'Jordan', 'Jordanian'),
(115, 398, 'KZ', 'KAZ', 'Kazakhstan', 'Kazakhstani, Kazakh'),
(116, 404, 'KE', 'KEN', 'Kenya', 'Kenyan'),
(117, 296, 'KI', 'KIR', 'Kiribati', 'I-Kiribati'),
(118, 408, 'KP', 'PRK', 'Korea (Democratic People\'s Republic of)', 'North Korean'),
(119, 410, 'KR', 'KOR', 'Korea (Republic of)', 'South Korean'),
(120, 414, 'KW', 'KWT', 'Kuwait', 'Kuwaiti'),
(121, 417, 'KG', 'KGZ', 'Kyrgyzstan', 'Kyrgyzstani, Kyrgyz, Kirgiz, Kirghiz'),
(122, 418, 'LA', 'LAO', 'Lao People\'s Democratic Republic', 'Lao, Laotian'),
(123, 428, 'LV', 'LVA', 'Latvia', 'Latvian'),
(124, 422, 'LB', 'LBN', 'Lebanon', 'Lebanese'),
(125, 426, 'LS', 'LSO', 'Lesotho', 'Basotho'),
(126, 430, 'LR', 'LBR', 'Liberia', 'Liberian'),
(127, 434, 'LY', 'LBY', 'Libya', 'Libyan'),
(128, 438, 'LI', 'LIE', 'Liechtenstein', 'Liechtenstein'),
(129, 440, 'LT', 'LTU', 'Lithuania', 'Lithuanian'),
(130, 442, 'LU', 'LUX', 'Luxembourg', 'Luxembourg, Luxembourgish'),
(131, 446, 'MO', 'MAC', 'Macao', 'Macanese, Chinese'),
(132, 807, 'MK', 'MKD', 'Macedonia (the former Yugoslav Republic of)', 'Macedonian'),
(133, 450, 'MG', 'MDG', 'Madagascar', 'Malagasy'),
(134, 454, 'MW', 'MWI', 'Malawi', 'Malawian'),
(135, 458, 'MY', 'MYS', 'Malaysia', 'Malaysian'),
(136, 462, 'MV', 'MDV', 'Maldives', 'Maldivian'),
(137, 466, 'ML', 'MLI', 'Mali', 'Malian, Malinese'),
(138, 470, 'MT', 'MLT', 'Malta', 'Maltese'),
(139, 584, 'MH', 'MHL', 'Marshall Islands', 'Marshallese'),
(140, 474, 'MQ', 'MTQ', 'Martinique', 'Martiniquais, Martinican'),
(141, 478, 'MR', 'MRT', 'Mauritania', 'Mauritanian'),
(142, 480, 'MU', 'MUS', 'Mauritius', 'Mauritian'),
(143, 175, 'YT', 'MYT', 'Mayotte', 'Mahoran'),
(144, 484, 'MX', 'MEX', 'Mexico', 'Mexican'),
(145, 583, 'FM', 'FSM', 'Micronesia (Federated States of)', 'Micronesian'),
(146, 498, 'MD', 'MDA', 'Moldova (Republic of)', 'Moldovan'),
(147, 492, 'MC', 'MCO', 'Monaco', 'Monégasque, Monacan'),
(148, 496, 'MN', 'MNG', 'Mongolia', 'Mongolian'),
(149, 499, 'ME', 'MNE', 'Montenegro', 'Montenegrin'),
(150, 500, 'MS', 'MSR', 'Montserrat', 'Montserratian'),
(151, 504, 'MA', 'MAR', 'Morocco', 'Moroccan'),
(152, 508, 'MZ', 'MOZ', 'Mozambique', 'Mozambican'),
(153, 104, 'MM', 'MMR', 'Myanmar', 'Burmese'),
(154, 516, 'NA', 'NAM', 'Namibia', 'Namibian'),
(155, 520, 'NR', 'NRU', 'Nauru', 'Nauruan'),
(156, 524, 'NP', 'NPL', 'Nepal', 'Nepali, Nepalese'),
(157, 528, 'NL', 'NLD', 'Netherlands', 'Dutch, Netherlandic'),
(158, 540, 'NC', 'NCL', 'New Caledonia', 'New Caledonian'),
(159, 554, 'NZ', 'NZL', 'New Zealand', 'New Zealand, NZ'),
(160, 558, 'NI', 'NIC', 'Nicaragua', 'Nicaraguan'),
(161, 562, 'NE', 'NER', 'Niger', 'Nigerien'),
(162, 566, 'NG', 'NGA', 'Nigeria', 'Nigerian'),
(163, 570, 'NU', 'NIU', 'Niue', 'Niuean'),
(164, 574, 'NF', 'NFK', 'Norfolk Island', 'Norfolk Island'),
(165, 580, 'MP', 'MNP', 'Northern Mariana Islands', 'Northern Marianan'),
(166, 578, 'NO', 'NOR', 'Norway', 'Norwegian'),
(167, 512, 'OM', 'OMN', 'Oman', 'Omani'),
(168, 586, 'PK', 'PAK', 'Pakistan', 'Pakistani'),
(169, 585, 'PW', 'PLW', 'Palau', 'Palauan'),
(170, 275, 'PS', 'PSE', 'Palestine, State of', 'Palestinian'),
(171, 591, 'PA', 'PAN', 'Panama', 'Panamanian'),
(172, 598, 'PG', 'PNG', 'Papua New Guinea', 'Papua New Guinean, Papuan'),
(173, 600, 'PY', 'PRY', 'Paraguay', 'Paraguayan'),
(174, 604, 'PE', 'PER', 'Peru', 'Peruvian'),
(175, 608, 'PH', 'PHL', 'Philippines', 'Philippine, Filipino'),
(176, 612, 'PN', 'PCN', 'Pitcairn', 'Pitcairn Island'),
(177, 616, 'PL', 'POL', 'Poland', 'Polish'),
(178, 620, 'PT', 'PRT', 'Portugal', 'Portuguese'),
(179, 630, 'PR', 'PRI', 'Puerto Rico', 'Puerto Rican'),
(180, 634, 'QA', 'QAT', 'Qatar', 'Qatari'),
(181, 638, 'RE', 'REU', 'Réunion', 'Réunionese, Réunionnais'),
(182, 642, 'RO', 'ROU', 'Romania', 'Romanian'),
(183, 643, 'RU', 'RUS', 'Russian Federation', 'Russian'),
(184, 646, 'RW', 'RWA', 'Rwanda', 'Rwandan'),
(185, 652, 'BL', 'BLM', 'Saint Barthélemy', 'Barthélemois'),
(186, 654, 'SH', 'SHN', 'Saint Helena, Ascension and Tristan da Cunha', 'Saint Helenian'),
(187, 659, 'KN', 'KNA', 'Saint Kitts and Nevis', 'Kittitian or Nevisian'),
(188, 662, 'LC', 'LCA', 'Saint Lucia', 'Saint Lucian'),
(189, 663, 'MF', 'MAF', 'Saint Martin (French part)', 'Saint-Martinoise'),
(190, 666, 'PM', 'SPM', 'Saint Pierre and Miquelon', 'Saint-Pierrais or Miquelonnais'),
(191, 670, 'VC', 'VCT', 'Saint Vincent and the Grenadines', 'Saint Vincentian, Vincentian'),
(192, 882, 'WS', 'WSM', 'Samoa', 'Samoan'),
(193, 674, 'SM', 'SMR', 'San Marino', 'Sammarinese'),
(194, 678, 'ST', 'STP', 'Sao Tome and Principe', 'São Toméan'),
(195, 682, 'SA', 'SAU', 'Saudi Arabia', 'Saudi, Saudi Arabian'),
(196, 686, 'SN', 'SEN', 'Senegal', 'Senegalese'),
(197, 688, 'RS', 'SRB', 'Serbia', 'Serbian'),
(198, 690, 'SC', 'SYC', 'Seychelles', 'Seychellois'),
(199, 694, 'SL', 'SLE', 'Sierra Leone', 'Sierra Leonean'),
(200, 702, 'SG', 'SGP', 'Singapore', 'Singaporean'),
(201, 534, 'SX', 'SXM', 'Sint Maarten (Dutch part)', 'Sint Maarten'),
(202, 703, 'SK', 'SVK', 'Slovakia', 'Slovak'),
(203, 705, 'SI', 'SVN', 'Slovenia', 'Slovenian, Slovene'),
(204, 90, 'SB', 'SLB', 'Solomon Islands', 'Solomon Island'),
(205, 706, 'SO', 'SOM', 'Somalia', 'Somali, Somalian'),
(206, 710, 'ZA', 'ZAF', 'South Africa', 'South African'),
(207, 239, 'GS', 'SGS', 'South Georgia and the South Sandwich Islands', 'South Georgia or South Sandwich Islands'),
(208, 728, 'SS', 'SSD', 'South Sudan', 'South Sudanese'),
(209, 724, 'ES', 'ESP', 'Spain', 'Spanish'),
(210, 144, 'LK', 'LKA', 'Sri Lanka', 'Sri Lankan'),
(211, 729, 'SD', 'SDN', 'Sudan', 'Sudanese'),
(212, 740, 'SR', 'SUR', 'Suriname', 'Surinamese'),
(213, 744, 'SJ', 'SJM', 'Svalbard and Jan Mayen', 'Svalbard'),
(214, 748, 'SZ', 'SWZ', 'Swaziland', 'Swazi'),
(215, 752, 'SE', 'SWE', 'Sweden', 'Swedish'),
(216, 756, 'CH', 'CHE', 'Switzerland', 'Swiss'),
(217, 760, 'SY', 'SYR', 'Syrian Arab Republic', 'Syrian'),
(218, 158, 'TW', 'TWN', 'Taiwan, Province of China', 'Chinese, Taiwanese'),
(219, 762, 'TJ', 'TJK', 'Tajikistan', 'Tajikistani'),
(220, 834, 'TZ', 'TZA', 'Tanzania, United Republic of', 'Tanzanian'),
(221, 764, 'TH', 'THA', 'Thailand', 'Thai'),
(222, 626, 'TL', 'TLS', 'Timor-Leste', 'Timorese'),
(223, 768, 'TG', 'TGO', 'Togo', 'Togolese'),
(224, 772, 'TK', 'TKL', 'Tokelau', 'Tokelauan'),
(225, 776, 'TO', 'TON', 'Tonga', 'Tongan'),
(226, 780, 'TT', 'TTO', 'Trinidad and Tobago', 'Trinidadian or Tobagonian'),
(227, 788, 'TN', 'TUN', 'Tunisia', 'Tunisian'),
(228, 792, 'TR', 'TUR', 'Turkey', 'Turkish'),
(229, 795, 'TM', 'TKM', 'Turkmenistan', 'Turkmen'),
(230, 796, 'TC', 'TCA', 'Turks and Caicos Islands', 'Turks and Caicos Island'),
(231, 798, 'TV', 'TUV', 'Tuvalu', 'Tuvaluan'),
(232, 800, 'UG', 'UGA', 'Uganda', 'Ugandan'),
(233, 804, 'UA', 'UKR', 'Ukraine', 'Ukrainian'),
(234, 784, 'AE', 'ARE', 'United Arab Emirates', 'Emirati, Emirian, Emiri'),
(235, 826, 'GB', 'GBR', 'United Kingdom of Great Britain and Northern Ireland', 'British, UK'),
(236, 581, 'UM', 'UMI', 'United States Minor Outlying Islands', 'American'),
(237, 840, 'US', 'USA', 'United States of America', 'American'),
(238, 858, 'UY', 'URY', 'Uruguay', 'Uruguayan'),
(239, 860, 'UZ', 'UZB', 'Uzbekistan', 'Uzbekistani, Uzbek'),
(240, 548, 'VU', 'VUT', 'Vanuatu', 'Ni-Vanuatu, Vanuatuan'),
(241, 862, 'VE', 'VEN', 'Venezuela (Bolivarian Republic of)', 'Venezuelan'),
(242, 704, 'VN', 'VNM', 'Vietnam', 'Vietnamese'),
(243, 92, 'VG', 'VGB', 'Virgin Islands (British)', 'British Virgin Island'),
(244, 850, 'VI', 'VIR', 'Virgin Islands (U.S.)', 'U.S. Virgin Island'),
(245, 876, 'WF', 'WLF', 'Wallis and Futuna', 'Wallis and Futuna, Wallisian or Futunan'),
(246, 732, 'EH', 'ESH', 'Western Sahara', 'Sahrawi, Sahrawian, Sahraouian'),
(247, 887, 'YE', 'YEM', 'Yemen', 'Yemeni'),
(248, 894, 'ZM', 'ZMB', 'Zambia', 'Zambian'),
(249, 716, 'ZW', 'ZWE', 'Zimbabwe', 'Zimbabwean');

-- --------------------------------------------------------

--
-- Table structure for table `document`
--

CREATE TABLE `document` (
  `documentid` bigint(20) UNSIGNED NOT NULL,
  `documentcaption` varchar(255) DEFAULT NULL,
  `filepath` varchar(255) DEFAULT NULL,
  `linkerid` bigint(20) NOT NULL,
  `linkertype` varchar(100) NOT NULL,
  `isdefault` int(11) DEFAULT 0,
  `status` int(11) DEFAULT 0,
  `adddate` datetime DEFAULT NULL,
  `updatedate` datetime DEFAULT NULL,
  `size` bigint(20) NOT NULL DEFAULT 0,
  `originalname` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `emailcms`
--

CREATE TABLE `emailcms` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `status` int(1) DEFAULT 0,
  `allowedvariable` text CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `usetemplate` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `emailcms`
--

INSERT INTO `emailcms` (`id`, `name`, `subject`, `content`, `status`, `allowedvariable`, `usetemplate`) VALUES
(1, 'Contact Us Email Content', 'Contact Us summary', '<p>Name: {name} <br /> Subject: {subject}<br/> Phone: {phone} <br /> Email: {email}<br /> Comment: {comment}</p>\r\n<p>--- Do not reply to this email ---</p>', 1, '{name}, {phone}, {email}, {subject}, {comment}', 1),
(2, 'New Order Received', 'New Order Received', '<p>{message}</p>\r\n<h3>Order Id : {orderid}</h3>\r\n<p>&nbsp;</p>\r\n<p>--- Do not reply to this email ---</p>', 1, '{message},{orderid}', 1),
(3, 'New Order Placed', 'New Order Placed', '<p>{message}</p>\r\n<p>--- Do not reply to this email ---</p>', 1, '{message}', 1),
(4, 'New User Account Verification Email', 'Verify your account with Shopping Cart', '<p>Please verify your account by clicking the following link:</p>\r\n<p>&nbsp;</p>\r\n<p><a style=\"padding: 15px; background-color: #25cabf; color: #fff; text-decoration: none;\" href=\"{login}\">Verify Account</a></p>\r\n<p>&nbsp;</p>\r\n<p>Thanks</p>\r\n<p>Support @ Shopping Cart</p>\r\n<p>&nbsp;</p>', 1, '{username}, {firstname}, {lastname}, {hash}, {login} ', 1),
(5, 'Forgot Password', 'Your reset password link for Shopping Cart', '<p>Hello {name},<br /><br />Your Shoppoing Cart password link below :</p>\r\n<p>&nbsp;</p>\r\n<p><a style=\"padding: 15px; background-color: #25cabf; color: #fff; text-decoration: none;\" href=\"{link}\">Reset Password</a></p>\r\n<p><br />Support @ Shopping Cart</p>', 1, '{email},{link}, {password}, {name},{login}\r\n', 1);

-- --------------------------------------------------------

--
-- Table structure for table `enumeration`
--

CREATE TABLE `enumeration` (
  `enumid` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(50) COLLATE utf8_bin NOT NULL,
  `ekey` varchar(50) COLLATE utf8_bin NOT NULL,
  `value` text COLLATE utf8_bin NOT NULL,
  `displayorder` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `enumeration`
--

INSERT INTO `enumeration` (`enumid`, `type`, `ekey`, `value`, `displayorder`) VALUES
(1, 'action', 'access', 'Access', 1),
(2, 'action', 'add', 'Add', 2),
(3, 'action', 'edit', 'Edit', 3),
(4, 'action', 'delete', 'Delete', 4),
(5, 'action', 'customize', 'Customize', 5),
(6, 'orderstatus', 'paid', 'Paid & Proccessing', 1),
(7, 'orderstatus', 'shipped', 'Shipped', 2),
(8, 'orderstatus', 'cancelled', 'Cancelled', 3),
(9, 'orderstatus', 'success', 'Success', 4),
(10, 'role', 'admin', 'Admin', 0),
(11, 'roletype', 'admin', 'Admin', 1),
(12, 'roletype', 'webmodule', 'Website Module', 2),
(13, 'attribute', 'size', 'Size', 1),
(14, 'attribute', 'color', 'Color', 1);

-- --------------------------------------------------------

--
-- Table structure for table `error_log`
--

CREATE TABLE `error_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `request_url` text NOT NULL,
  `http_referer` text NOT NULL,
  `proxy_ip` varchar(20) NOT NULL,
  `request_method` varchar(10) NOT NULL,
  `get_var` text NOT NULL,
  `post` text NOT NULL,
  `user_agent` text NOT NULL,
  `remote_addr` varchar(20) NOT NULL,
  `add_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `imageid` bigint(20) UNSIGNED NOT NULL,
  `imagecaption` varchar(255) DEFAULT NULL,
  `imagefile` varchar(255) NOT NULL,
  `thumbnailfile` varchar(255) DEFAULT NULL,
  `linkerid` bigint(20) NOT NULL,
  `linkertype` varchar(75) NOT NULL,
  `isdefault` int(11) DEFAULT 0,
  `displayorder` int(11) NOT NULL DEFAULT 0,
  `tag` text DEFAULT NULL,
  `settings` text DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `adddate` datetime DEFAULT NULL,
  `updatedate` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`imageid`, `imagecaption`, `imagefile`, `thumbnailfile`, `linkerid`, `linkertype`, `isdefault`, `displayorder`, `tag`, `settings`, `status`, `adddate`, `updatedate`) VALUES
(21, '', 'image5f509f438cca6.jpeg', '{\"w120.h90\":\"image5f509f438cca6.w120.h90.jpeg\",\"w136.h168\":\"image5f509f438cca6.w136.h168.jpeg\",\"w260.h280\":\"image5f509f438cca6.w260.h280.jpeg\",\"w372.h280\":\"image5f509f438cca6.w372.h280.jpeg\",\"w520.h750\":\"image5f509f438cca6.w520.h750.jpeg\"}', 2, 'product', 1, 2, 'mainproduct', '', 1, '2020-09-03 09:46:11', '2020-09-03 09:46:11'),
(12, 'Slider 1', 'image5f4a3e3422d82.jpeg', '{\"w120.h90\":\"image5f4a3e3422d82.w120.h90.jpeg\"}', 1, 'homeslider', 1, 0, '{\"title\":\"test\",\"desc\":\"test\",\"link\":\"http:\\/\\/localhost\\/tasframework\\/shoppingcart\",\"btntext\":\"Search\"}', NULL, 1, '2020-08-29 13:38:28', '2020-08-29 13:38:28'),
(11, 'Slider 2', 'image5f4a3d05930bc.jpeg', '{\"w120.h90\":\"image5f4a3d05930bc.w120.h90.jpeg\"}', 1, 'homeslider', 1, 0, '{\"title\":\"testing\",\"desc\":\"test\",\"link\":\"http:\\/\\/localhost\\/tasframework\\/shoppingcart\",\"btntext\":\"Search\"}', NULL, 1, '2020-08-29 13:33:25', '2020-08-29 13:33:25'),
(22, '', 'image5f509f56530d4.jpeg', '{\"w120.h90\":\"image5f509f56530d4.w120.h90.jpeg\",\"w136.h168\":\"image5f509f56530d4.w136.h168.jpeg\",\"w260.h280\":\"image5f509f56530d4.w260.h280.jpeg\",\"w372.h280\":\"image5f509f56530d4.w372.h280.jpeg\",\"w520.h750\":\"image5f509f56530d4.w520.h750.jpeg\"}', 1, 'product', 1, 1, 'mainproduct', '', 1, '2020-09-03 09:46:30', '2020-09-03 09:46:30'),
(20, '', 'image5f509f3f9e09f.jpeg', '{\"w120.h90\":\"image5f509f3f9e09f.w120.h90.jpeg\",\"w136.h168\":\"image5f509f3f9e09f.w136.h168.jpeg\",\"w260.h280\":\"image5f509f3f9e09f.w260.h280.jpeg\",\"w372.h280\":\"image5f509f3f9e09f.w372.h280.jpeg\",\"w520.h750\":\"image5f509f3f9e09f.w520.h750.jpeg\"}', 2, 'product', 1, 1, 'mainproduct', '', 1, '2020-09-03 09:46:07', '2020-09-03 09:46:07'),
(23, '', 'image5f509f5b10ee0.jpeg', '{\"w120.h90\":\"image5f509f5b10ee0.w120.h90.jpeg\",\"w136.h168\":\"image5f509f5b10ee0.w136.h168.jpeg\",\"w260.h280\":\"image5f509f5b10ee0.w260.h280.jpeg\",\"w372.h280\":\"image5f509f5b10ee0.w372.h280.jpeg\",\"w520.h750\":\"image5f509f5b10ee0.w520.h750.jpeg\"}', 1, 'product', 1, 2, 'mainproduct', '', 1, '2020-09-03 09:46:35', '2020-09-03 09:46:35');

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `id` int(20) NOT NULL,
  `locationtitle` varchar(50) NOT NULL,
  `locationdescription` text NOT NULL,
  `address` varchar(50) NOT NULL,
  `zipcode` int(20) NOT NULL,
  `state` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `logid` bigint(20) UNSIGNED NOT NULL,
  `eventdate` datetime NOT NULL,
  `eventlevel` varchar(30) NOT NULL,
  `message` varchar(100) NOT NULL,
  `details` text DEFAULT NULL,
  `debugtrace` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `module`
--

CREATE TABLE `module` (
  `moduleid` bigint(20) NOT NULL,
  `modulename` varchar(100) NOT NULL,
  `tags` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `displayorder` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `module`
--

INSERT INTO `module` (`moduleid`, `modulename`, `tags`, `slug`, `displayorder`) VALUES
(3, 'Brand', 'admin', 'company', 3),
(4, 'Coreadmin', 'admin', 'coreadmin', 4),
(5, 'Category', 'admin', 'category', 5),
(8, 'Ecommerce', 'admin', 'ecommerce', 8),
(9, 'Home Slider', 'admin', 'slidemanager', 9),
(12, 'Order', 'admin', 'order', 12),
(13, 'Product', 'admin', 'product', 13),
(14, 'Product Variation', 'admin', 'productvariation', 14),
(15, 'Testimonial', 'admin', 'testimonial', 15),
(17, 'User', 'admin', 'user', 17),
(18, 'Variation', 'admin', 'variation', 18),
(19, 'Customer', 'admin', 'customer', 5);

-- --------------------------------------------------------

--
-- Table structure for table `orderitem`
--

CREATE TABLE `orderitem` (
  `itemid` bigint(20) UNSIGNED NOT NULL,
  `orderid` bigint(20) NOT NULL,
  `productid` bigint(20) NOT NULL,
  `productcode` varchar(50) COLLATE utf8_bin NOT NULL,
  `productname` text COLLATE utf8_bin NOT NULL,
  `optiontag` varchar(100) COLLATE utf8_bin NOT NULL,
  `quantity` int(11) NOT NULL,
  `itemprice` float NOT NULL,
  `itemtotal` float NOT NULL,
  `adddate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `orderlog`
--

CREATE TABLE `orderlog` (
  `orderlogid` bigint(20) UNSIGNED NOT NULL,
  `orderid` bigint(20) NOT NULL,
  `logmessage` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `eventtag` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `sourcetag` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `eventtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderid` bigint(20) UNSIGNED NOT NULL,
  `userid` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `discount` double(6,2) NOT NULL,
  `discountp` double(5,2) NOT NULL,
  `tax` double(6,2) NOT NULL,
  `taxp` double(5,2) NOT NULL,
  `shippingprice` double(11,2) NOT NULL,
  `total` double(15,2) NOT NULL,
  `ordertotal` double(11,2) NOT NULL,
  `orderstatus` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `paymentstatus` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `orderdate` datetime NOT NULL,
  `tag` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `extrainfo` text COLLATE utf8mb4_bin NOT NULL,
  `adddate` datetime NOT NULL,
  `editdate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `pageid` int(11) NOT NULL,
  `content` text DEFAULT NULL,
  `page` varchar(100) DEFAULT NULL,
  `metakeyword` varchar(100) DEFAULT NULL,
  `metadescription` varchar(255) DEFAULT NULL,
  `pagetitle` varchar(250) DEFAULT NULL,
  `slug` varchar(150) DEFAULT NULL,
  `template` varchar(100) DEFAULT NULL,
  `showinmenu` int(11) NOT NULL,
  `displayorder` int(11) NOT NULL,
  `headerimage` text DEFAULT NULL,
  `contentfunction` text DEFAULT NULL,
  `sidebar` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`pageid`, `content`, `page`, `metakeyword`, `metadescription`, `pagetitle`, `slug`, `template`, `showinmenu`, `displayorder`, `headerimage`, `contentfunction`, `sidebar`) VALUES
(1, '', 'Contact Us', '', '', 'Contact Us', 'contactus', 'contact', 1, 15, NULL, 'ContactAfterContent', NULL),
(2, '<div class=\"container-404\">&nbsp;\r\n<div class=\"container-404\"><br />\r\n<h1 class=\"404\">404 <span class=\"short\">Page Not Found</span></h1>\r\n</div>\r\n</div>', '404', '', '', '404', '404', 'single', 1, 14, NULL, '', NULL),
(3, '<p>Thanks for registation.</p>\r\n<p>We send you an email to verify your account. Please check email and verify your account.</p>\r\n<p>Thanks</p>', 'Registeration Thanks', '', '', 'Registeration Thanks', 'registeration-thanks', '', 0, 0, NULL, NULL, NULL),
(4, '<p>Thanks for verifing your account.</p>', 'Account Verified', '', '', 'Account Verified', 'account-verified', '', 0, 0, NULL, NULL, NULL),
(5, '<p>Your account verification has been failed.</p>', 'Account Verification Failed', '', '', 'Account Verification Failed', 'account-verification-failed', '', 0, 0, NULL, NULL, NULL),
(6, '', 'Order Fail', '', '', 'Order Fail', 'order-fail', '', 0, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `passwordverification`
--

CREATE TABLE `passwordverification` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customerid` bigint(20) NOT NULL,
  `code` text NOT NULL,
  `adddate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `paymentid` bigint(20) NOT NULL,
  `orderid` bigint(20) NOT NULL,
  `transactionid` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `price` double(11,2) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `paymentmode` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `paymentdate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `productid` bigint(20) UNSIGNED NOT NULL,
  `productname` varchar(100) NOT NULL,
  `productslug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `shortdescription` text DEFAULT NULL,
  `brandid` bigint(20) NOT NULL,
  `singleprice` float(10,2) NOT NULL,
  `productcode` varchar(50) NOT NULL,
  `isfeatured` int(11) NOT NULL DEFAULT 0,
  `adddate` datetime DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`productid`, `productname`, `productslug`, `description`, `shortdescription`, `brandid`, `singleprice`, `productcode`, `isfeatured`, `adddate`, `editdate`, `status`) VALUES
(1, 'Sneaker - U112', 'sneaker-u112', '', '', 1, 146.00, 'U11', 0, '2020-09-01 14:53:57', '2020-09-01 14:56:24', 1),
(2, 'UP Sneakers For Men', 'up-sneakers-for-men', '', '', 1, 250.00, 'P11', 0, '2020-09-01 14:55:24', NULL, 1),
(3, 'Shoess', 'shoess', '', '', 1, 149.00, 'S-12', 0, '2020-09-03 11:48:39', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `productcategory`
--

CREATE TABLE `productcategory` (
  `productid` bigint(20) UNSIGNED NOT NULL,
  `categoryid` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `productcategory`
--

INSERT INTO `productcategory` (`productid`, `categoryid`) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 1),
(2, 2),
(3, 2);

-- --------------------------------------------------------

--
-- Stand-in structure for view `productlist`
-- (See below for the actual view)
--
CREATE TABLE `productlist` (
`productid` varchar(41)
,`mainid` bigint(20) unsigned
,`productname` mediumtext
,`productslug` varchar(255)
,`productcode` varchar(50)
,`status` int(11)
,`isfeatured` int(11)
,`price` float
,`description` mediumtext
,`shortdescription` mediumtext
,`brandid` bigint(20)
,`variationid` varchar(20)
,`adddate` datetime
,`type` varchar(11)
);

-- --------------------------------------------------------

--
-- Table structure for table `productvariation`
--

CREATE TABLE `productvariation` (
  `variationid` bigint(20) UNSIGNED NOT NULL,
  `productid` bigint(20) UNSIGNED NOT NULL,
  `productcode` varchar(50) NOT NULL,
  `singleprice` float NOT NULL,
  `adddate` datetime NOT NULL,
  `editdate` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `productvariation`
--

INSERT INTO `productvariation` (`variationid`, `productid`, `productcode`, `singleprice`, `adddate`, `editdate`, `status`) VALUES
(1, 2, 'U11-1', 270, '2020-08-29 09:59:04', NULL, 1),
(2, 1, 'P11-1', 280, '2020-08-29 09:59:21', '2020-09-01 15:07:18', 1);

-- --------------------------------------------------------

--
-- Table structure for table `productvariationoption`
--

CREATE TABLE `productvariationoption` (
  `variationid` bigint(20) UNSIGNED NOT NULL,
  `optionid` bigint(20) UNSIGNED NOT NULL,
  `attribute` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `productvariationoption`
--

INSERT INTO `productvariationoption` (`variationid`, `optionid`, `attribute`) VALUES
(1, 2, 'color'),
(1, 4, 'size'),
(2, 2, 'color'),
(2, 5, 'size');

-- --------------------------------------------------------

--
-- Table structure for table `testimonial`
--

CREATE TABLE `testimonial` (
  `testimonialid` bigint(20) UNSIGNED NOT NULL,
  `testimonialname` varchar(50) NOT NULL,
  `company` varchar(30) NOT NULL,
  `message` text NOT NULL,
  `adddate` datetime NOT NULL,
  `editdate` datetime DEFAULT NULL,
  `displayorder` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userid` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(128) DEFAULT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `userroleid` bigint(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `allowlogin` int(11) NOT NULL DEFAULT 0,
  `verifyemail` int(11) NOT NULL DEFAULT 0,
  `adddate` datetime DEFAULT NULL,
  `editdate` datetime DEFAULT NULL,
  `lastlogin` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userid`, `username`, `password`, `firstname`, `lastname`, `userroleid`, `email`, `phone`, `status`, `allowlogin`, `verifyemail`, `adddate`, `editdate`, `lastlogin`) VALUES
(1, 'admin', '$2y$10$SUg.WVaDj9BJ7647zJS9ye0.YKZ4oG3B9pGbYlK04bJAGUXJNjXTy', 'Core', 'admin', 1, 'smith@7archers.com12', '9898989898', 1, 1, 0, '2018-04-12 00:00:00', '2020-08-25 13:55:27', '2021-04-01 13:51:05');

-- --------------------------------------------------------

--
-- Table structure for table `userlog`
--

CREATE TABLE `userlog` (
  `userlogid` bigint(20) NOT NULL,
  `userid` bigint(20) NOT NULL,
  `message` text NOT NULL,
  `eventname` varchar(100) NOT NULL,
  `actionid` bigint(20) NOT NULL,
  `extrainfo` text NOT NULL,
  `eventdate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userlog`
--

INSERT INTO `userlog` (`userlogid`, `userid`, `message`, `eventname`, `actionid`, `extrainfo`, `eventdate`) VALUES
(1, 1, 'Existing User Login Success', 'User Logged in', 1, '{\"IpAddress\":\"::1\",\"BrowserInfo\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/89.0.4389.114 Safari\\/537.36\"}', '2021-04-01 10:20:16'),
(2, 1, 'Existing User Login Success', 'User Logged in', 1, '{\"IpAddress\":\"::1\",\"BrowserInfo\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/89.0.4389.114 Safari\\/537.36\"}', '2021-04-01 10:21:05');

-- --------------------------------------------------------

--
-- Table structure for table `userrole`
--

CREATE TABLE `userrole` (
  `userroleid` bigint(20) UNSIGNED NOT NULL,
  `rolename` varchar(50) NOT NULL,
  `role` varchar(20) NOT NULL,
  `permission` text CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `adddate` datetime DEFAULT NULL,
  `editdate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userrole`
--

INSERT INTO `userrole` (`userroleid`, `rolename`, `role`, `permission`, `adddate`, `editdate`) VALUES
(1, 'Admin', 'admin', '{\"attribute\":{\"access\":\"1\",\"add\":\"1\",\"edit\":\"1\",\"delete\":\"1\",\"customize\":\"1\"},\"attributeoption\":{\"access\":\"1\",\"add\":\"1\",\"edit\":\"1\",\"delete\":\"1\",\"customize\":\"1\"},\"company\":{\"access\":\"1\",\"add\":\"1\",\"edit\":\"1\",\"delete\":\"1\",\"customize\":\"1\"},\"coreadmin\":{\"access\":\"1\",\"add\":\"1\",\"edit\":\"1\",\"delete\":\"1\",\"customize\":\"1\"},\"customer\":{\"access\":\"1\",\"add\":\"1\",\"edit\":\"1\",\"delete\":\"1\",\"customize\":\"1\"},\"category\":{\"access\":\"1\",\"add\":\"1\",\"edit\":\"1\",\"delete\":\"1\",\"customize\":\"1\"},\"cms\":{\"access\":\"1\",\"add\":\"1\",\"edit\":\"1\",\"delete\":\"1\",\"customize\":\"1\"},\"docmanager\":{\"access\":\"1\",\"add\":\"1\",\"edit\":\"1\",\"delete\":\"1\",\"customize\":\"1\"},\"ecommerce\":{\"access\":\"1\",\"add\":\"1\",\"edit\":\"1\",\"delete\":\"1\",\"customize\":\"1\"},\"slidemanager\":{\"access\":\"1\",\"add\":\"1\",\"edit\":\"1\",\"delete\":\"1\",\"customize\":\"1\"},\"imagemanager\":{\"access\":\"1\",\"add\":\"1\",\"edit\":\"1\",\"delete\":\"1\",\"customize\":\"1\"},\"log\":{\"access\":\"1\",\"add\":\"1\",\"edit\":\"1\",\"delete\":\"1\",\"customize\":\"1\"},\"order\":{\"access\":\"1\",\"add\":\"1\",\"edit\":\"1\",\"delete\":\"1\",\"customize\":\"1\"},\"product\":{\"access\":\"1\",\"add\":\"1\",\"edit\":\"1\",\"delete\":\"1\",\"customize\":\"1\"},\"productvariation\":{\"access\":\"1\",\"add\":\"1\",\"edit\":\"1\",\"delete\":\"1\",\"customize\":\"1\"},\"testimonial\":{\"access\":\"1\",\"add\":\"1\",\"edit\":\"1\",\"delete\":\"1\",\"customize\":\"1\"},\"userrole\":{\"access\":\"1\",\"add\":\"1\",\"edit\":\"1\",\"delete\":\"1\",\"customize\":\"1\"},\"user\":{\"access\":\"1\",\"add\":\"1\",\"edit\":\"1\",\"delete\":\"1\",\"customize\":\"1\"},\"variation\":{\"access\":\"1\",\"add\":\"1\",\"edit\":\"1\",\"delete\":\"1\",\"customize\":\"1\"}}', '2018-08-02 08:16:38', '2020-09-04 08:01:55'),
(2, 'No Role', 'webmodule', '{}', '2020-08-29 12:34:03', NULL);

-- --------------------------------------------------------

--
-- Structure for view `productlist`
--
DROP TABLE IF EXISTS `productlist`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `productlist`  AS SELECT `p`.`productid` AS `productid`, `p`.`productid` AS `mainid`, `p`.`productname` AS `productname`, `p`.`productslug` AS `productslug`, `p`.`productcode` AS `productcode`, `p`.`status` AS `status`, `p`.`isfeatured` AS `isfeatured`, `p`.`singleprice` AS `price`, `p`.`description` AS `description`, `p`.`shortdescription` AS `shortdescription`, `p`.`brandid` AS `brandid`, '0' AS `variationid`, `p`.`adddate` AS `adddate`, 'mainproduct' AS `type` FROM `product` AS `p` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`addressid`),
  ADD KEY `commonuse` (`ownerid`,`ownertype`,`addresstype`) USING BTREE;

--
-- Indexes for table `attributeoption`
--
ALTER TABLE `attributeoption`
  ADD PRIMARY KEY (`optionid`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`itemid`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`categoryid`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`companyid`);

--
-- Indexes for table `configuration`
--
ALTER TABLE `configuration`
  ADD PRIMARY KEY (`settingid`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`countryid`),
  ADD UNIQUE KEY `alpha_2_code` (`countrycode2`),
  ADD UNIQUE KEY `alpha_3_code` (`countrycode3`);

--
-- Indexes for table `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`documentid`);

--
-- Indexes for table `emailcms`
--
ALTER TABLE `emailcms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enumeration`
--
ALTER TABLE `enumeration`
  ADD PRIMARY KEY (`enumid`);

--
-- Indexes for table `error_log`
--
ALTER TABLE `error_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`imageid`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`logid`);

--
-- Indexes for table `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`moduleid`);

--
-- Indexes for table `orderitem`
--
ALTER TABLE `orderitem`
  ADD PRIMARY KEY (`itemid`);

--
-- Indexes for table `orderlog`
--
ALTER TABLE `orderlog`
  ADD PRIMARY KEY (`orderlogid`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderid`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`pageid`);

--
-- Indexes for table `passwordverification`
--
ALTER TABLE `passwordverification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`paymentid`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`productid`);

--
-- Indexes for table `productcategory`
--
ALTER TABLE `productcategory`
  ADD PRIMARY KEY (`productid`,`categoryid`);

--
-- Indexes for table `productvariation`
--
ALTER TABLE `productvariation`
  ADD PRIMARY KEY (`variationid`);

--
-- Indexes for table `productvariationoption`
--
ALTER TABLE `productvariationoption`
  ADD PRIMARY KEY (`variationid`,`optionid`);

--
-- Indexes for table `testimonial`
--
ALTER TABLE `testimonial`
  ADD PRIMARY KEY (`testimonialid`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `userroleid` (`userroleid`);

--
-- Indexes for table `userlog`
--
ALTER TABLE `userlog`
  ADD PRIMARY KEY (`userlogid`);

--
-- Indexes for table `userrole`
--
ALTER TABLE `userrole`
  ADD PRIMARY KEY (`userroleid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `addressid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attributeoption`
--
ALTER TABLE `attributeoption`
  MODIFY `optionid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `itemid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `categoryid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `companyid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `configuration`
--
ALTER TABLE `configuration`
  MODIFY `settingid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `countryid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250;

--
-- AUTO_INCREMENT for table `document`
--
ALTER TABLE `document`
  MODIFY `documentid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emailcms`
--
ALTER TABLE `emailcms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `enumeration`
--
ALTER TABLE `enumeration`
  MODIFY `enumid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `error_log`
--
ALTER TABLE `error_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `imageid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `logid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `module`
--
ALTER TABLE `module`
  MODIFY `moduleid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `orderitem`
--
ALTER TABLE `orderitem`
  MODIFY `itemid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orderlog`
--
ALTER TABLE `orderlog`
  MODIFY `orderlogid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `pageid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `passwordverification`
--
ALTER TABLE `passwordverification`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `paymentid` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `productid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `productvariation`
--
ALTER TABLE `productvariation`
  MODIFY `variationid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `testimonial`
--
ALTER TABLE `testimonial`
  MODIFY `testimonialid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `userlog`
--
ALTER TABLE `userlog`
  MODIFY `userlogid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `userrole`
--
ALTER TABLE `userrole`
  MODIFY `userroleid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
