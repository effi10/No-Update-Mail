<?php 
/* 
	Plugin Name: No Update Alert 
	Plugin URI: https://www.effi10.com/wordpress-comment-desactiver-les-mails-de-notification-de-mises-a-jour/ 
	Description: Disables update alerts for plugins and WordPress core (except for failure alerts if they occur) 
	Author: Cedric GIRARD 
	Version: 1.0 
	Author URI: https://www.effi10.com 
	Copyright 2022  Cedric GIRARD  (email : contact@effi10.com) 
    This program is free software; you can redistribute it and/or modify 
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation. 
    This program is distributed in the hope that it will be useful, 
    but WITHOUT ANY WARRANTY; without even the implied warranty of 
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
    GNU General Public License for more details. 
    You should have received a copy of the GNU General Public License 
    along with this program; if not, write to the Free Software 
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA 
*/ 

	// Désactiver les notifications des mises à jour des plugins 
	add_filter('auto_plugin_update_send_email', '__return_false'); 
	 
	// Désactiver les notifications de mises à jour des thèmes 
	add_filter('auto_theme_update_send_email', '__return_false');

	// Désactivation des notifications de mise à jour du Core WordPress par email 
	// "Votre site a bien été mis à jour..." SAUF en cas d'échec 
	add_filter( 'auto_core_update_send_email', 'smartwp_disable_core_update_emails', 10, 4 ); 
	 
	function smartwp_disable_core_update_emails( $send, $type, $core_update, $result ) { 
	  if ( !empty($type) && $type == 'success' ) { 
		// Succès : on désactive la notification 
		return false; 
	  } 
	  // Notification envoyée 
	  return true; 
	} 

?> 