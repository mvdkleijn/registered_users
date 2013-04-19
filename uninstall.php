<?php

/**
 * This file is part of the "Registered Users" plugin for Wolf CMS.
 * Licensed under an MIT style license. For full details see license.txt.
 *
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @copyright Martijn van der Kleijn, 2011-2013
 * 
 */

/* Prevent direct access. */
if (!defined('IN_CMS')) { exit(); }

$sql = 'DROP TABLE IF EXISTS `' . TABLE_PREFIX . 'registered_users_temp`';
$pdo = Record::getConnection();
$pdo->exec($sql);

$sql = 'DROP TABLE IF EXISTS `' . TABLE_PREFIX . 'permission_page`';
$pdo = Record::getConnection();
$pdo->exec($sql);

$sql = 'DROP TABLE IF EXISTS `' . TABLE_PREFIX . 'registered_users_settings`';
$pdo = Record::getConnection();
$pdo->exec($sql);