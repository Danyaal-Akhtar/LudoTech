# Référencement de Jeux de Société – Université Sorbonne Paris Nord

---

## Contexte

L’université Sorbonne Paris Nord conserve une collection d’environ 17 000 jeux de société, dont certains remontent au XIXe siècle. Une première base de données existe sous forme de fichier Excel, mais elle ne répond plus aux besoins de l’équipe en charge de la curation.

Ce projet vise à créer :
- des scripts pour nettoyer et structurer les données existantes ;
- une base de données relationnelle ;
- une application web de gestion de l’inventaire.

---

## Objectifs

- Nettoyage, validation et normalisation des données existantes ;
- Structuration de la base de données relationnelle adaptée ;
- Interface web intuitive pour la gestion, la consultation et l’évolution du catalogue ;
- Flexibilité pour extensions futures.

---

## Fonctionnalités attendues

### Nettoyage & Import des Données
- Détection des erreurs : doublons, champs manquants, valeurs incohérentes.
- Validation : contrôle de la cohérence avant import.
- Normalisation : noms, dates, formats homogènes.
- Importation : script d’ingestion du fichier Excel dans la base.

### Modèle de Données
- Entités principales : Jeux, Auteurs, Éditeurs, Catégories, Années.
- Relations multiples (un jeu peut avoir plusieurs auteurs/éditeurs).
- Intégrité des données assurée par contraintes relationnelles.

### Interface Web
- Tableau de bord : vue globale de la collection avec filtres.
- Fiches jeux détaillées.
- Recherche avancée par titres, auteurs, années, catégories…
- Ajout, modification et suppression de jeux, auteurs, éditeurs.
- Suivi des prêts et localisation physique des jeux.

### Gestion & Administration
- Gestion des utilisateurs avec rôles différenciés (administrateur, gestionnaire, utilisateur simple).
- Historique des modifications.
- Ajout de collections spécifiques.



---

## Stack Technique 

- Langage : PHP
- Base de données : MySQL
- Frontend : HTML/CSS/JS 
- Scripts : Python (pandas, openpyxl…)

---


## Références & Données d’Exemple

- Fichier Excel initial (complet et extraits anonymisés)
- Diagramme de la base de données 


---

## Membres de l'équipe 
- AKHTAR Danyaal
- GONCALVES DOS SANTOS Ilario
- MADRAZ Elies
- TRAORÉ Karim



