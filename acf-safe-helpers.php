<?php

/**
 * Fonction utilitaire pour vérifier ACF avant get_field()
 */

function safe_get_field($field, $post_id = false)
{
	if (!function_exists('get_field')) {
		return false;
	}
	return get_field($field, $post_id);
}

/**
 * Version avec fallback par défaut
 */
function safe_get_field_with_default($field, $post_id = false, $default = '')
{
	if (!function_exists('get_field')) {
		return $default;
	}
	$value = get_field($field, $post_id);
	return $value !== false ? $value : $default;
}

// ✅ UTILISER dans les templates principaux :
// $subtitle = safe_get_field('subtitle') ?: '';

// ❌ NE PAS utiliser dans les blocs ACF :
// Les blocs ACF ne s'exécutent QUE si ACF est activé

// ❌ NE PAS utiliser dans les fichiers déjà protégés :
// if (function_exists('acf_register_block_type')) { ... }
