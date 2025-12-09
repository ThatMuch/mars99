# Bloc Metrics - Fonctionnalité d'Expansion

## Fonctionnalités implémentées

### Interaction principal

- **Clic sur le bouton `+`** : Étend la carte métrique et cache les autres
- **Re-clic sur le bouton `+`** : Ferme la carte étendue et restaure toutes les cartes
- **Clic en dehors** : Ferme automatiquement la carte étendue
- **Touche Escape** : Ferme la carte étendue

### Animations et transitions

- **Transition fluide** de 0.6s avec courbe de Bézier personnalisée
- **Animation du bouton** : rotation de 45° quand étendu (transforme + en ×)
- **Effet de glissement** : les cartes cachées glissent vers la gauche
- **Animation du contenu** : apparition en fondu avec mouvement vertical

### Responsive Design

- **Desktop** : cartes se cachent en glissant horizontalement
- **Mobile** : cartes se cachent en glissant verticalement
- **Flexbox adaptatif** : la carte étendue prend toute la largeur disponible

## Structure CSS

### Classes principales

- `.metrics-grid` : conteneur principal avec transitions
- `.metric-card` : carte individuelle avec états
- `.metric-card.expanded` : carte étendue (prend 100% de largeur)
- `.metric-card.hidden` : cartes cachées (opacité 0, transform)
- `.metric-content` : contenu additionnel (visible uniquement quand étendu)

### Transitions

- **Transform** : `translateX()` et `translateY()` pour les mouvements
- **Opacity** : fade in/out pour les cartes cachées
- **Max-height** : expansion/contraction du contenu
- **Cubic-bezier** : courbe d'animation fluide `(0.4, 0, 0.2, 1)`

## JavaScript

### Fonctions principales

- `expandCard(cardToExpand)` : étend une carte spécifique
- `collapseAllCards()` : ferme toutes les cartes
- Event listeners pour clics, touche Escape, et clics extérieurs

### Gestion d'état

- Variable `expandedCard` : suit quelle carte est actuellement étendue
- Classes CSS ajoutées/supprimées dynamiquement
- Support de plusieurs blocs metrics sur la même page

## Utilisation

1. **Configuration ACF** : Ajoutez du contenu dans le champ `content` de chaque métrique
2. **Affichage** : Le contenu apparaît uniquement quand la carte est étendue
3. **Navigation** : Une seule carte peut être étendue à la fois
4. **Accessibilité** : Support clavier (Escape) et navigation intuitive

## Personnalisation

### Durée des animations

```css
transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
```

### Hauteur maximale du contenu

```css
.metric-card.expanded .metric-content {
  max-height: 300px; /* Ajustable selon vos besoins */
}
```

### Couleurs et effets

- Bouton : couleur orange `#FF8E38`
- Ombres : mix de `rgb(44 93 133)` avec transparence
- Background étendu : `#eef7fe`
