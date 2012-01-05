SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `vvoody_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE IF NOT EXISTS `devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descr` varchar(50) NOT NULL COMMENT 'hrDeviceDescr',
  `type` varchar(50) NOT NULL COMMENT 'hrDeviceType',
  `status` int(11) NOT NULL COMMENT 'hrDeviceStatus - unknown(1), running(2), warning(3), testing(4), down(5)',
  `host_id` int(11) NOT NULL COMMENT 'hosts.id',
  `device_idx` int(11) NOT NULL COMMENT 'hrDeviceIndex',
  PRIMARY KEY (`id`),
  KEY `host_id` (`host_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `devices_log`
--

CREATE TABLE IF NOT EXISTS `devices_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) NOT NULL COMMENT 'devices.id',
  `num_errors` int(11) DEFAULT NULL COMMENT 'hrDeviceErrors',
  `stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `device_id` (`device_id`),
  KEY `stamp` (`stamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hosts`
--

CREATE TABLE IF NOT EXISTS `hosts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_name` varchar(50) NOT NULL COMMENT 'ip or host name',
  `status` char(12) NOT NULL DEFAULT 'enabled',
  `community` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip_name` (`ip_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hosts_log`
--

CREATE TABLE IF NOT EXISTS `hosts_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `host_id` int(11) NOT NULL COMMENT 'hosts.id',
  `uptime` int(11) DEFAULT NULL COMMENT 'hrSystemUptime',
  `num_users` int(11) DEFAULT NULL COMMENT 'hrSystemNumUsers',
  `max_processes` int(11) DEFAULT NULL COMMENT 'hrSystemMaxProcesses',
  `memsize` int(11) DEFAULT NULL COMMENT 'hrMemorySize',
  `num_loaded_processes` int(11) DEFAULT NULL COMMENT 'hrSystemProcesses',
  `status` int(11) NOT NULL COMMENT 'up(1), down(2)',
  `stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `host_id` (`host_id`),
  KEY `stamp` (`stamp`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `software_installed`
--

CREATE TABLE IF NOT EXISTS `software_installed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT 'hrSWInstalledName',
  `type` int(11) NOT NULL COMMENT 'hrSWInstalledType - unknown(1), operatingSystem(2), deviceDriver(3), application(4)',
  `host_id` int(11) NOT NULL COMMENT 'hosts.id',
  `last_update` datetime NOT NULL COMMENT 'hrSWInstalledDate',
  PRIMARY KEY (`id`),
  KEY `host_id` (`host_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `software_running`
--

CREATE TABLE IF NOT EXISTS `software_running` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT 'hrSWRunName',
  `type` int(11) NOT NULL COMMENT 'hrSWRunType - unknown(1), operatingSystem(2), deviceDriver(3), application(4)',
  `status` int(11) NOT NULL COMMENT 'hrSWRunStatus - running(1), runnable(2), notRunnable(3),  invalid(4)  ',
  `host_id` int(11) NOT NULL COMMENT 'hosts.id',
  PRIMARY KEY (`id`),
  KEY `host_id` (`host_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `software_running_log`
--

CREATE TABLE IF NOT EXISTS `software_running_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `software_running_id` int(11) NOT NULL COMMENT 'software_running.id',
  `cpu_used` int(11) DEFAULT NULL COMMENT 'hrSWRunPerfCPU',
  `mem_allocated` int(11) DEFAULT NULL COMMENT 'hrSWRunPerfMem',
  `stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `software_running_id` (`software_running_id`),
  KEY `stamp` (`stamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `storage`
--

CREATE TABLE IF NOT EXISTS `storage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descr` varchar(50) NOT NULL COMMENT 'hrStorageDescr',
  `type` varchar(50) NOT NULL COMMENT 'hrStorageType',
  `size` int(11) NOT NULL COMMENT 'hrStorageSize',
  `allocated_sectors` int(11) NOT NULL COMMENT 'hrStorageAllocationUnits',
  `host_id` int(11) NOT NULL COMMENT 'hosts.id',
  `storage_idx` int(11) NOT NULL COMMENT 'hrStorageIndex',
  PRIMARY KEY (`id`),
  KEY `host_id` (`host_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `storage_log`
--

CREATE TABLE IF NOT EXISTS `storage_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `storage_id` int(11) NOT NULL COMMENT 'storage.id',
  `used_capacity` int(11) DEFAULT NULL COMMENT 'hrStorageUsed',
  `allocation_failures` int(11) DEFAULT NULL COMMENT 'hrStorageAllocationFailures',
  `stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `storage_id` (`storage_id`),
  KEY `stamp` (`stamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
