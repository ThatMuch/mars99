/**
 * Script optimisé pour la synchronisation entre SCSS et BrowserSync
 */
const { spawn } = require('child_process');
const fs = require('fs');

// Compile le SCSS avant de démarrer BrowserSync
console.log('🔄 Compilation SCSS initiale...');

// Exécuter la compilation SCSS
const sassCompile = spawn('npm', ['run', 'build'], { stdio: 'inherit', shell: true });

sassCompile.on('close', code => {
  if (code !== 0) {
    console.error('❌ Erreur lors de la compilation SCSS initiale');
    process.exit(1);
  }

  console.log('✅ Compilation SCSS réussie !');
  console.log('🚀 Démarrage de l\'environnement de développement...');

  // Démarrer le mode watch + browsersync
  const devProcess = spawn('npm', ['run', 'start'], { stdio: 'inherit', shell: true });

  // Gérer la fin du processus
  devProcess.on('close', code => {
    console.log(`Processus terminé avec le code ${code}`);
    process.exit(code);
  });

  // Gérer les signaux SIGINT et SIGTERM
  process.on('SIGINT', () => {
    console.log('\n👋 Arrêt du serveur de développement...');
    devProcess.kill('SIGINT');
  });

  process.on('SIGTERM', () => {
    console.log('\n👋 Arrêt du serveur de développement...');
    devProcess.kill('SIGTERM');
  });
});
