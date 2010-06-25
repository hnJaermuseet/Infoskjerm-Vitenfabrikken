-- phpMyAdmin SQL Dump
-- version 2.11.3deb1ubuntu1.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 26, 2009 at 11:08 AM
-- Server version: 5.0.51
-- PHP Version: 5.2.4-2ubuntu5.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `vf-infoskjerm`
--

-- --------------------------------------------------------

--
-- Table structure for table `brukere`
--

CREATE TABLE IF NOT EXISTS `brukere` (
  `bruker_id` int(11) NOT NULL auto_increment,
  `bruker_navn` varchar(255) NOT NULL default '',
  `bruker_epost` varchar(255) NOT NULL default '',
  `bruker_passord_md5` varchar(255) NOT NULL default '',
  `bruker_tid_reg` int(11) NOT NULL default '0',
  `bruker_tid_sistinnlogg` int(11) NOT NULL default '0',
  PRIMARY KEY  (`bruker_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `skjermer`
--

CREATE TABLE IF NOT EXISTS `skjermer` (
  `skjerm_id` int(11) NOT NULL auto_increment,
  `skjerm_navn` varchar(255) character set latin1 NOT NULL,
  `skjerm_defaultslide_heading` varchar(255) collate utf8_swedish_ci NOT NULL,
  `skjerm_defaultslide` text character set latin1 NOT NULL,
  `skjerm_slidenr` int(11) NOT NULL,
  PRIMARY KEY  (`skjerm_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `slides`
--

CREATE TABLE IF NOT EXISTS `slides` (
  `slide_id` int(11) NOT NULL auto_increment,
  `skjerm_id` varchar(255) NOT NULL,
  `slide_navn` varchar(255) NOT NULL,
  `slide_nr` varchar(255) NOT NULL,
  `slide_fra` int(11) NOT NULL,
  `slide_til` int(11) NOT NULL,
  `slide_pri` int(11) NOT NULL,
  `slide_innhold_heading` varchar(255) NOT NULL,
  `slide_innhold` text NOT NULL,
  PRIMARY KEY  (`slide_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `slides_connect`
--

CREATE TABLE IF NOT EXISTS `slides_connect` (
  `slide_id` int(11) NOT NULL,
  `skjerm_id` int(11) NOT NULL,
  `slide_nr` int(11) NOT NULL,
  `slide_fra` int(11) NOT NULL,
  `slide_til` int(11) NOT NULL,
  `slide_pri` int(11) NOT NULL,
  PRIMARY KEY  (`slide_id`,`skjerm_id`,`slide_nr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

