# No Update Mail

Extension WordPress qui supprime les e-mails envoyés après chaque mise à jour automatique **réussie** de WordPress, des extensions et des thèmes. Les mises à jour continuent normalement, et vous restez prévenu par e-mail quand l'une d'elles **échoue**.

Article d'accompagnement : [WordPress : comment désactiver les mails de notification de mises à jour ?](https://www.effi10.com/wordpress-comment-desactiver-les-mails-de-notification-de-mises-a-jour/)

## À quoi ça sert

Avec les mises à jour automatiques activées, WordPress envoie un e-mail à l'administrateur après chaque passage : « Votre site a été mis à jour vers WordPress x.y.z », « Des extensions ont été mises à jour automatiquement »… Sur un ou plusieurs sites, ces messages finissent par noyer ceux qui comptent vraiment. L'extension ne garde que les e-mails utiles : ceux qui signalent un problème.

| Situation | Sans l'extension | Avec l'extension |
|---|---|---|
| Mise à jour du cœur réussie | E-mail | Pas d'e-mail |
| Mise à jour du cœur échouée (ou échec critique) | E-mail | E-mail |
| Extensions ou thèmes : toutes les mises à jour réussies | E-mail | Pas d'e-mail |
| Extensions ou thèmes : au moins un échec | E-mail | E-mail (il liste aussi les mises à jour réussies du même passage) |
| Nouvelle version du cœur à installer à la main | E-mail | E-mail (non concerné) |

## Installation

1. Sur GitHub, cliquez sur **Code > Download ZIP** (ou téléchargez le ZIP d'une version dans **Releases**).
2. Dans l'administration WordPress, allez dans **Extensions > Ajouter une extension > Téléverser une extension**.
3. Choisissez le fichier ZIP, cliquez sur **Installer maintenant**, puis sur **Activer**.

Installation manuelle : décompressez le ZIP et copiez le dossier dans `wp-content/plugins/` (le fichier principal, `no-update-mail.php`, est à la racine du dossier). Le nom du dossier n'a pas d'importance ; vous pouvez le renommer `no-update-mail`.

**Mise à jour depuis la version 1.0** : l'ancienne version se trouvait dans un sous-dossier `effiNoUpdateAlert`. Désactivez-la et supprimez-la avant d'installer celle-ci, sinon ses réglages (qui coupent aussi les e-mails d'échec des extensions et des thèmes) restent actifs.

## Réglages

Aucun. L'extension agit dès son activation et ne stocke rien en base de données ; la désactiver rétablit le comportement normal de WordPress.

## Fonctionnement

L'extension s'appuie uniquement sur les filtres du cœur de WordPress (classe `WP_Automatic_Updater`) :

- `auto_core_update_send_email` : renvoie `false` pour le type `success`, et laisse passer `fail` et `critical` sans les modifier ;
- `auto_plugin_update_send_email` et `auto_theme_update_send_email` : renvoient `false` si toutes les mises à jour du passage ont réussi, et laissent l'e-mail partir dès qu'au moins une a échoué (WordPress considère une mise à jour comme réussie quand son résultat vaut exactement `true`).

Si un autre code a déjà coupé ces e-mails (valeur `false` reçue), l'extension ne les réactive pas.

Toutes les fonctions sont préfixées `effi_num_` : l'extension peut cohabiter avec l'extrait de code public `smartwp_disable_core_update_emails` sans erreur fatale. Si cet extrait est présent dans le `functions.php` de votre thème, retirez-le tout de même : ses lignes `__return_false` coupent aussi les e-mails d'échec des extensions et des thèmes.

## Limites

- Ne concerne que les mises à jour **automatiques** en arrière-plan. Les mises à jour lancées à la main depuis l'administration n'envoient pas d'e-mail de toute façon.
- L'e-mail de diagnostic que WordPress envoie sur les versions de développement (bêta, RC) n'est pas modifié.
- Quand une mise à jour du cœur est tentée lors d'un passage, WordPress n'envoie que l'e-mail du cœur et aucun e-mail pour les extensions et thèmes de ce même passage : c'est le comportement du cœur, que l'extension ne change pas.
- Nécessite WordPress 5.5.1 ou plus récent pour distinguer les succès des échecs côté extensions et thèmes. Sur une version plus ancienne, l'extension laisse ces e-mails tels quels.

## Compatibilité

- WordPress 5.5.1 ou plus récent (vérifié avec le code de WordPress 7.1).
- PHP 7.0 ou plus récent (syntaxe vérifiée avec PHP 8.2, 8.3 et 8.5).
- Multisite : activez l'extension pour tout le réseau (ou au moins sur le site principal), car c'est le site principal du réseau principal qui lance les mises à jour automatiques.

## Journal des versions

### 1.1.0
- Les e-mails d'échec des extensions et des thèmes sont désormais conservés, comme annoncé (ils étaient coupés eux aussi).
- Les e-mails d'échec du cœur gardent la valeur fixée par un éventuel autre réglage au lieu d'être forcés.
- Fichier principal placé à la racine du dépôt : le ZIP téléchargé depuis GitHub s'installe directement (il était refusé avec le message « Aucune extension valide n'a été trouvée »).
- Fonctions renommées avec le préfixe `effi_num_` (risque d'erreur fatale si l'extrait `smartwp_disable_core_update_emails` était aussi présent dans le thème).
- Suppression de la balise PHP fermante suivie d'un espace, qui envoyait un caractère parasite avant les en-têtes HTTP.
- En-tête complet (version de WordPress et de PHP requise, licence, URI de mise à jour), README en français, licence GPL v2.

### 1.0 (2022)
- Première version.

## Licence

GPL v2 ou ultérieure. Voir le fichier [LICENSE](LICENSE).

## Auteur

Cédric Girard, consultant SEO et développeur WordPress : [effi10.com](https://www.effi10.com/)
