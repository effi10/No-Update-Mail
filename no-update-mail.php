<?php
/**
 * Plugin Name:       No Update Mail
 * Plugin URI:        https://github.com/effi10/No-Update-Mail
 * Description:       Supprime les e-mails envoyés après les mises à jour automatiques réussies de WordPress, des extensions et des thèmes. Les e-mails d'échec sont conservés.
 * Version:           1.1.0
 * Requires at least: 5.5.1
 * Requires PHP:      7.0
 * Author:            Cédric Girard
 * Author URI:        https://www.effi10.com/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       no-update-mail
 * Update URI:        https://github.com/effi10/No-Update-Mail
 *
 * @package No_Update_Mail
 */

/*
 * Copyright 2022-2026 Cédric Girard (https://www.effi10.com/)
 *
 * Ce programme est un logiciel libre : vous pouvez le redistribuer et/ou le
 * modifier selon les termes de la licence publique générale GNU (GPL), telle
 * que publiée par la Free Software Foundation, version 2 ou (à votre choix)
 * toute version ultérieure. Il est distribué SANS AUCUNE GARANTIE ; voir le
 * fichier LICENSE pour plus de détails.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Cœur de WordPress : coupe uniquement l'e-mail de succès.
 *
 * Le filtre `auto_core_update_send_email` reçoit le type d'e-mail : 'success',
 * 'fail' ou 'critical' (l'e-mail 'manual', « une nouvelle version est
 * disponible », ne passe pas par ce filtre). Les e-mails d'échec et d'échec
 * critique gardent la valeur reçue, ce qui respecte un éventuel autre réglage.
 *
 * @param bool   $send Envoyer ou non l'e-mail. Vrai par défaut.
 * @param string $type Type d'e-mail : 'success', 'fail' ou 'critical'.
 * @return bool
 */
function effi_num_core_update_send_email( $send, $type = '' ) {
	if ( 'success' === $type ) {
		return false;
	}

	return $send;
}
add_filter( 'auto_core_update_send_email', 'effi_num_core_update_send_email', 10, 2 );

/**
 * Indique si au moins une mise à jour de la liste a échoué.
 *
 * Même règle que le cœur (WP_Automatic_Updater::after_plugin_theme_update()) :
 * une mise à jour n'est réussie que si sa propriété `result` vaut exactement true.
 *
 * @param array $update_results Résultats des mises à jour (objets avec item, result, name, messages).
 * @return bool
 */
function effi_num_has_failed_update( $update_results ) {
	if ( ! is_array( $update_results ) ) {
		return false;
	}

	foreach ( $update_results as $update_result ) {
		if ( ! is_object( $update_result ) || ! isset( $update_result->result ) || true !== $update_result->result ) {
			return true;
		}
	}

	return false;
}

/**
 * Extensions et thèmes : garde l'e-mail seulement si une mise à jour a échoué.
 *
 * Branché sur `auto_plugin_update_send_email` et `auto_theme_update_send_email`,
 * qui reçoivent depuis WordPress 5.5.1 la liste des résultats en second
 * paramètre. En cas d'échec, WordPress envoie son e-mail habituel, qui liste
 * aussi les mises à jour réussies du même passage.
 *
 * @param bool       $enabled        Envoyer ou non l'e-mail. Vrai par défaut.
 * @param array|null $update_results Résultats des mises à jour d'extensions ou de thèmes.
 * @return bool
 */
function effi_num_plugin_theme_update_send_email( $enabled, $update_results = null ) {
	// Un autre réglage a déjà coupé ces e-mails, ou WordPress ne fournit pas les résultats (avant 5.5.1).
	if ( ! $enabled || ! is_array( $update_results ) ) {
		return $enabled;
	}

	return effi_num_has_failed_update( $update_results );
}
add_filter( 'auto_plugin_update_send_email', 'effi_num_plugin_theme_update_send_email', 10, 2 );
add_filter( 'auto_theme_update_send_email', 'effi_num_plugin_theme_update_send_email', 10, 2 );
