#!/bin/bash

# Script pour la compilation SCSS avec Dart Sass
# Ce script permet de compiler les fichiers SCSS avec Dart Sass
# et de nettoyer les fichiers CSS générés.

# Couleurs pour les messages
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Afficher un message d'aide
function show_help {
  echo -e "${BLUE}Script de compilation SCSS pour Mars${NC}"
  echo ""
  echo -e "Usage: $0 [option]"
  echo ""
  echo "Options:"
  echo "  watch    - Compile les fichiers SCSS et surveille les modifications"
  echo "  build    - Compile les fichiers SCSS une seule fois (production)"
  echo "  dev      - Compile les fichiers SCSS une seule fois (développement)"
  echo "  clean    - Nettoie les fichiers CSS générés"
  echo "  help     - Affiche ce message d'aide"
  echo ""
}

# Fonction pour vérifier si npm est installé
function check_npm {
  if ! command -v npm &> /dev/null; then
    echo -e "${RED}Erreur: npm n'est pas installé. Veuillez l'installer avant de continuer.${NC}"
    exit 1
  fi
}

# Fonction pour vérifier si les dépendances sont installées
function check_dependencies {
  if [ ! -d "node_modules" ]; then
    echo -e "${YELLOW}Installation des dépendances...${NC}"
    npm install
  fi
}

# Afficher un message de début
echo -e "${BLUE}======================================${NC}"
echo -e "${BLUE}  Script de compilation SCSS pour${NC}"
echo -e "${BLUE}         Mars${NC}"
echo -e "${BLUE}======================================${NC}"

# Vérifier si npm est installé
check_npm

# Vérifier si les dépendances sont installées
check_dependencies

# Traiter les arguments
case "$1" in
  watch)
    echo -e "${YELLOW}Compilation et surveillance des fichiers SCSS...${NC}"
    npm run watch
    ;;
  build)
    echo -e "${YELLOW}Compilation des fichiers SCSS pour la production...${NC}"
    npm run build
    echo -e "${GREEN}Compilation terminée !${NC}"
    ;;
  dev)
    echo -e "${YELLOW}Compilation des fichiers SCSS pour le développement...${NC}"
    npm run build:dev
    echo -e "${GREEN}Compilation terminée !${NC}"
    ;;
  clean)
    echo -e "${YELLOW}Nettoyage des fichiers CSS générés...${NC}"
    npm run clean:css
    ;;
  help|*)
    show_help
    ;;
esac

echo -e "${BLUE}======================================${NC}"
echo -e "${GREEN}Terminé !${NC}"
