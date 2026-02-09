/**
 * Configuration BrowserSync pour le thème WordPress Mars
 *
 * Ce fichier configure BrowserSync pour le rechargement automatique du navigateur
 * lorsque des fichiers sont modifiés dans le thème
 */

const browserSync = require('browser-sync').create();
const fs = require('fs');

// Récupérez l'URL locale de votre site WordPress à partir du fichier wp-config.php
// ou modifiez directement l'URL ci-dessous selon votre environnement local
const localDomain = "localhost:10118"; // URL de votre site local WordPress

// Configuration de BrowserSync
browserSync.init(
  {
    proxy: localDomain,
    files: [
      // Surveille les fichiers CSS compilés
      './**/*.css',
      './*.css.map',
      // Surveille les fichiers PHP du thème
      './**/*.php',
      // Surveille les fichiers JavaScript
      './js/**/*.js',
      // Surveille les images
      './images/**/*',
      // Surveille également les fichiers SCSS pour déclencher un rechargement
      // lorsqu'ils sont modifiés (après la compilation CSS)
      './scss/**/*.scss'
    ],
    // Ouvre automatiquement le navigateur
    open: true,
    // Notifie les changements dans le navigateur
    notify: true,
    // Permet l'injection CSS sans rechargement complet (quand possible)
    injectChanges: true,
    // Retarde le rechargement pour s'assurer que la compilation SCSS est terminée
    reloadDelay: 300,
    // Debounce pour éviter les rechargements multiples
    reloadDebounce: 500,
    // Surveille les changements toutes les 100ms
    watchOptions: {
      ignoreInitial: true,
      ignored: '.git',
      interval: 100,
      awaitWriteFinish: {
        stabilityThreshold: 200,
        pollInterval: 100
      }
    },
    // Utilise HTTPS si votre site local utilise SSL
    // https: true,
  },
  function(err, bs) {
    if (err) {
      console.error('⚠️ Erreur lors de l\'initialisation de BrowserSync:', err);
      return;
    }

    // Affiche un message de confirmation dans la console une fois que BrowserSync est prêt
    console.log('🚀 BrowserSync est en cours d\'exécution. Votre site est disponible sur:');
    console.log('📱 Local: ' + bs.options.getIn(['urls', 'local']));
    console.log('🌐 External: ' + bs.options.getIn(['urls', 'external']));
    console.log('⚙️ UI: ' + bs.options.getIn(['urls', 'ui']));
  }
);
