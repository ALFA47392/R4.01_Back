# SportStats API - R4.01 (Back-end)

Cœur logique et serveur de données pour l'application de gestion de tournois sportifs. Ce projet constitue l'évolution du module R3.01, migrant la logique métier vers une architecture Client-Serveur robuste.

## 🏗️ Architecture du Système
Ce dépôt gère la persistance et le traitement des données. Il fonctionne de pair avec :
* **Frontend :** (R3.01 / Interface Utilisateur)
* **Auth Service :** https://github.com/ALFA47392/R4.01_Auth.git (Gestion de la sécurité)

## 🎯 Fonctionnalités Back-end
* **API RESTful :** Points d'accès pour la gestion des joueurs, équipes et matchs.
* **Logique Métier Centralisée :** Calculs de statistiques coté serveur pour garantir l'intégrité des données.
* **Gestion de Base de Données :** Postgres pour l'archivage des saisons.
* **Synchronisation :** Mise à jour en temps réel des feuilles de match.


## 📂 Organisation du Code
* `/controllers` : Logique de traitement des requêtes.
* `/models` : Schémas de données (Joueurs, Matchs, Sets).
* `/routes` : Définition des endpoints de l'API.
* `/config` : Configuration du serveur et connexions BDD.
