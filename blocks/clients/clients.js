// Configuration de l'animation du slider infini
document.addEventListener("DOMContentLoaded", function () {
  const inner = document.getElementById("clients-slider-inner");
  if (!inner) return;

  // Nombre total de logos (inclut les doublons)
  const totalLogos = inner.children.length;
  // Nombre réel de logos uniques (la moitié du total puisqu'on les duplique)
  const uniqueLogos = totalLogos / 2;

  // Calcul de la largeur totale à déplacer (la largeur d'un ensemble de logos uniques)
  const logoWidth = 200; // largeur en pixels de chaque logo (définie dans le CSS)
  const slideDistance = -(logoWidth * uniqueLogos);

  // Définir la variable CSS pour la distance d'animation
  document.documentElement.style.setProperty(
    "--slider-width",
    `${slideDistance}px`
  );

  // Configuration de la vitesse d'animation basée sur le nombre de logos
  // Plus il y a de logos, plus l'animation doit être lente
  const animationDuration = Math.max(20, uniqueLogos * 3); // 3 secondes par logo, minimum 20s
  inner.style.animationDuration = `${animationDuration}s`;

  // Configuration du grid pour assurer un affichage correct
  inner.style.gridTemplateColumns = `repeat(${totalLogos}, ${logoWidth}px)`;
});
