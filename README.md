# Anifruits
Un jeu de combat au tour par tour développé en PHP Objet.

## Présentation

Deux personnages s’affrontent :  
- perso1 → contrôlé par le joueur  
- perso2 → contrôlé par une IA  

Le système repose entièrement sur la gestion d’état via les sessions PHP, sans modification de la base de données pendant le combat.

---

## Système de combat

Le combat est initialisé une fois que deux personnages sont sélectionnés.  
L’état est stocké dans `$_SESSION['combat']` :

- Objets personnages
- Statut du combat (`termine`)
- Journal des actions (`logs`)

Chaque tour est géré par une fonction `jouerTour()` :

1. Action du joueur (attaque, régénération, coup spécial)
2. Vérification de fin de combat
3. Action de l’IA si le combat continue
4. Mise à jour du journal

Le combat s’arrête dès qu’un personnage n’a plus de points de vie.

---

## Intelligence artificielle

L’IA choisit son action via une fonction dédiée (`actionIA()`).

Son comportement dépend de son pourcentage de vie :

- **Vie faible (< 30%)** → privilégie la régénération  
- **Vie moyenne (< 60%)** → attaque majoritairement  
- **Vie élevée** → attaque presque systématiquement  

Les décisions sont basées sur des probabilités, ce qui rend le combat moins prévisible.

---

## Coup spécial

Chaque personnage possède :

- Un nom de capacité
- Un type
- Un multiplicateur
