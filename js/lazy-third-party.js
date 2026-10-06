/**
 * Charge à la demande les scripts tiers neutralisés en
 * <script type="text/plain" data-mars-lazy-src="..."> (voir inc/performance.php).
 * Déclenché à la première interaction utilisateur, avec un filet de sécurité
 * (requestIdleCallback / setTimeout) pour ne jamais priver un visiteur
 * n'interagissant pas de ces fonctionnalités.
 */
(function () {
	'use strict';

	var loaded = false;

	function loadAll() {
		if (loaded) {
			return;
		}
		loaded = true;

		document.querySelectorAll('script[data-mars-lazy-src]').forEach(function (placeholder) {
			var script = document.createElement('script');
			script.src = placeholder.getAttribute('data-mars-lazy-src');
			if (placeholder.id) {
				script.id = placeholder.id;
			}
			placeholder.parentNode.insertBefore(script, placeholder);
			placeholder.remove();
		});
	}

	['pointerdown', 'keydown', 'touchstart', 'scroll'].forEach(function (evt) {
		window.addEventListener(evt, loadAll, { once: true, passive: true });
	});

	if ('requestIdleCallback' in window) {
		requestIdleCallback(loadAll, { timeout: 5000 });
	} else {
		setTimeout(loadAll, 5000);
	}
})();
