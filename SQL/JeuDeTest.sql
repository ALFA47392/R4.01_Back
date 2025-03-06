-- Supprimer les données existantes si elles existent
DELETE FROM Participer;
DELETE FROM Commentaire;
DELETE FROM Connexion;
DELETE FROM Match_Hockey;
DELETE FROM Joueur;

-- Insertion des données dans la table Joueur
INSERT INTO Joueur (Numero_de_licence, Nom, Prenom, Date_de_naissance, Taille, Poids, Statut) VALUES
('J123456789', 'RAGAZZINI', 'Timéo', '2005-04-07', 171, 67, 'Actif'),
('J987654321', 'AZZOLA', 'Toméo', '2001-07-18', 178, 75.3, 'Actif'), 
('J456123789', 'TIXIER', 'Thibault', '2004-09-15', 184, 65, 'Actif'),  
('J789123456', 'CODEVELLE', 'Kylian', '2001-06-06', 180, 95.2, 'Actif'),  
('J654321987', 'NEBUS', 'Eliott', '2002-05-09', 175, 80, 'Actif'), 
('J321654987', 'GENET', 'Xavier', '1981-06-21', 179, 72.5, 'Blessé'), 
('J123654789', 'MONNIER', 'Patrick', '1983-02-15', 185, 90.2, 'Blessé'),  
('J987321654', 'HUOT-MARCHAND', 'Luka', '2005-08-22', 165, 72, 'Actif'),  
('J543210987', 'CHAUVEAU', 'Nicolas', '2008-08-02', 165, 60, 'Actif'), 
('J112233445', 'TOULOUZE', 'Dorian', '1998-09-17', 190, 79, 'En équipe sup.'),
('J998877665', 'BORDES', 'Ilona', '2005-06-12', 169, 60.2, 'Actif'), 
('J667788990', 'DHAMELINCOURT', 'Victor', '2008-03-19', 164, 54.5, 'Actif'), 
('J223344556', 'PREVOST', 'Rémi', '2003-03-02', 175, 69.8, 'Actif'),
('J223344656', 'MOREL', 'Romain', '2007-09-01', 188, 74, 'Blessé'),
('J261556654', 'DESNOYER-MORCHID', 'Adam', '2008-04-08', 170, 62.6, 'Blessé'),
('J261532644','TEXIER','Julien','1998-10-20', 179, 80,'Parti'),
('J685276975', 'CAVORY', 'Louis', '2002-09-12', 168, 64, 'Actif');  

-- Insertion des données dans la table Match_Hockey
INSERT INTO Match_Hockey (Date_Heure_match, Nom_equipe_adverse, Lieu_de_rencontre, ScoreMatch) VALUES
('2024-09-21 20:00:00', 'Pamiers VIXENS', 'Toulouse', '7-3'),
('2024-09-28 20:00:00', 'Bordeaux MIGHTY WOLVES', 'Pions', '1-6'),
('2024-10-12 20:00:00', 'Colomiers CHIENS FOUS', 'Toulouse', '8-6'),
('2024-11-02 19:30:00', 'Pessac MAOHIS', 'Pessac', '5-13'),
('2024-11-09 18:00:00', 'Toulouse HOCKLINES B', 'Toulouse', '9-4'),
('2024-11-23 20:00:00', 'Anglet ARTZAK', 'Toulouse', '12-5'),
('2024-12-07 20:00:00', 'St-Orens KARIBOUS', 'St-Orens', '4-5'),
('2024-12-14 20:30:00', 'Pamiers VIXENS', 'Pamiers', '5-4'),
('2025-01-11 20:00:00', 'Bordeaux Rive-Droite', 'Toulouse', '5-9');

-- Insertion des données dans la table Commentaire
INSERT INTO Commentaire (Id_Commentaire, Texte, Numero_de_licence) VALUES
(1,'En forme', 'J123456789'),
(2,'Blessé récemment', 'J987654321'),
(3,'Très performant', 'J456123789'),
(4,'En repos pour récupération', 'J789123456'),
(5,'Bonne contribution défensive', 'J654321987'),
(6,'Blessé au dernier match', 'J321654987'),
(7,'Excellent buteur', 'J123654789'),
(8,'Travailleur acharné', 'J987321654'),
(9,'Besoin de repos', 'J543210987'),
(10,'Blessé à long terme', 'J112233445'),
(11,'Rapide et agile', 'J998877665'),
(12,'Solide défenseur', 'J667788990'),
(13,'Blessé mais en récupération', 'J223344556');


-- Insertion des participations avec les postes correctement attribués
-- Match 1
INSERT INTO Participer (Numero_de_licence, Id_Match_Hockey, Titulaire, Notation, Poste) VALUES
('J998877665', 1, 1, 7, 'D'), -- Ilona, Défenseur
('J987654321', 1, 1, 7, 'D'), -- Toméo, Défenseur
('J123456789', 1, 1, 8, 'A'), -- Timéo, Attaquant
('J654321987', 1, 1, 7, 'A'), -- Eliott, Attaquant
('J789123456', 1, 1, 8, 'A'), -- Kylian, Attaquant
('J456123789', 1, 1, 9, 'D'), -- Thibault, Défenseur
('J223344656', 1, 1, 6, 'D'), -- Romain, Défenseur
('J685276975', 1, 1, 8, 'G'), -- Louis, Gardien
('J321654987', 1, 1, 7, 'A'), -- Xavier, Attaquant
('J543210987', 1, 1, 6, 'D'); -- Nicolas, Défenseur

-- Match 2
INSERT INTO Participer (Numero_de_licence, Id_Match_Hockey, Titulaire, Notation, Poste) VALUES
('J998877665', 2, 1, 6, 'D'), -- Ilona, Défenseur
('J987654321', 2, 1, 8, 'D'), -- Toméo, Défenseur
('J123456789', 2, 1, 7, 'A'), -- Timéo, Attaquant
('J654321987', 2, 1, 8, 'A'), -- Eliott, Attaquant
('J456123789', 2, 1, 6, 'D'), -- Thibault, Défenseur
('J261556654', 2, 1, 7, 'G'), -- Adam, Gardien
('J223344656', 2, 1, 6, 'D'); -- Romain, Défenseur

-- Match 3
INSERT INTO Participer (Numero_de_licence, Id_Match_Hockey, Titulaire, Notation, Poste) VALUES
('J998877665', 3, 1, 6, 'D'), -- Ilona, Défenseur
('J987654321', 3, 1, 8, 'D'), -- Toméo, Défenseur
('J123456789', 3, 1, 9, 'A'), -- Timéo, Attaquant
('J654321987', 3, 1, 7, 'A'), -- Eliott, Attaquant
('J789123456', 3, 1, 6, 'A'), -- Kylian, Attaquant
('J456123789', 3, 1, 7, 'D'), -- Thibault, Défenseur
('J223344656', 3, 1, 6, 'D'), -- Romain, Défenseur
('J685276975', 3, 1, 8, 'G'), -- Louis, Gardien
('J112233445', 3, 1, 2, 'D'), -- Dorian, Défenseur
('J321654987', 3, 1, 9, 'A'), -- Xavier, Attaquant
('J543210987', 3, 1, 6, 'D'); -- Nicolas, Défenseur

-- Match 4
INSERT INTO Participer (Numero_de_licence, Id_Match_Hockey, Titulaire, Notation, Poste) VALUES
('J998877665', 4, 1, 7, 'D'), -- Ilona, Défenseur
('J987654321', 4, 1, 6, 'D'), -- Toméo, Défenseur
('J123456789', 4, 1, 8, 'A'), -- Timéo, Attaquant
('J654321987', 4, 1, 6, 'A'), -- Eliott, Attaquant
('J789123456', 4, 1, 9, 'A'), -- Kylian, Attaquant
('J456123789', 4, 1, 7, 'D'), -- Thibault, Défenseur
('J667788990', 4, 1, 6, 'D'), -- Victor, Défenseur
('J685276975', 4, 1, 8, 'G'), -- Louis, Gardien
('J261556654', 4, 1, 8, 'G'), -- Adam, Gardien
('J321654987', 4, 1, 8, 'A'), -- Xavier, Attaquant
('J543210987', 4, 1, 7, 'D'); -- Nicolas, Défenseur


-- Match 5
INSERT INTO Participer (Numero_de_licence, Id_Match_Hockey, Titulaire, Notation, Poste) VALUES
('J998877665', 5, 1, 7, 'D'), -- Ilona, Défenseur
('J987654321', 5, 1, 7, 'D'), -- Toméo, Défenseur
('J123456789', 5, 1, 9, 'A'), -- Timéo, Attaquant
('J654321987', 5, 1, 7, 'A'), -- Eliott, Attaquant
('J789123456', 5, 1, 9, 'A'), -- Kylian, Attaquant
('J456123789', 5, 1, 6, 'D'), -- Thibault, Défenseur
('J685276975', 5, 1, 8, 'G'), -- Louis, Gardien
('J321654987', 5, 1, 7, 'A'), -- Xavier, Attaquant
('J543210987', 5, 1, 6, 'D'), -- Nicolas, Défenseur
('J667788990', 5, 1, 8, 'D'); -- Victor, Défenseur

-- Match 6
INSERT INTO Participer (Numero_de_licence, Id_Match_Hockey, Titulaire, Notation, Poste) VALUES
('J998877665', 6, 1, 7, 'D'), -- Ilona, Défenseur
('J987654321', 6, 1, 8, 'D'), -- Toméo, Défenseur
('J123456789', 6, 1, 8, 'A'), -- Timéo, Attaquant
('J654321987', 6, 1, 7, 'A'), -- Eliott, Attaquant
('J789123456', 6, 1, 9, 'A'), -- Kylian, Attaquant
('J456123789', 6, 1, 7, 'D'), -- Thibault, Défenseur
('J261556654', 6, 1, 9, 'G'), -- Adam, Gardien
('J321654987', 6, 1, 7, 'A'), -- Xavier, Attaquant
('J543210987', 6, 1, 6, 'D'), -- Nicolas, Défenseur
('J223344556', 6, 1, 6, 'A'), -- Rémi, Attaquant
('J123654789', 6, 1, 8, 'D'), -- Patrick, Défenseur
('J667788990', 6, 1, 6.5, 'D'), -- Victor, Défenseur
('J987321654', 6, 1, 7, 'A'); -- Luka, Attaquant

-- Match 7
INSERT INTO Participer (Numero_de_licence, Id_Match_Hockey, Titulaire, Notation, Poste) VALUES
('J998877665', 7, 1, 7, 'D'), -- Ilona, Défenseur
('J987654321', 7, 1, 8, 'D'), -- Toméo, Défenseur
('J123456789', 7, 1, 8, 'A'), -- Timéo, Attaquant
('J654321987', 7, 1, 7, 'A'), -- Eliott, Attaquant
('J789123456', 7, 1, 9, 'A'), -- Kylian, Attaquant
('J456123789', 7, 1, 7, 'D'), -- Thibault, Défenseur
('J261556654', 7, 1, 9, 'G'), -- Adam, Gardien
('J321654987', 7, 1, 7, 'A'), -- Xavier, Attaquant
('J112233445', 7, 1, 2, 'D'), -- Dorian, Défenseur
('J223344556', 7, 1, 4, 'A'), -- Rémi, Attaquant
('J123654789', 7, 1, 8, 'D'), -- Patrick, Défenseur
('J987321654', 7, 1, 7, 'D'); -- Luka, Attaquant

-- Match 8
INSERT INTO Participer (Numero_de_licence, Id_Match_Hockey, Titulaire, Notation, Poste) VALUES
('J998877665', 8, 1, 7, 'D'), -- Ilona, Défenseur
('J987654321', 8, 1, 8, 'D'), -- Toméo, Défenseur
('J123456789', 8, 1, 7, 'A'), -- Timéo, Attaquant
('J654321987', 8, 1, 7, 'A'), -- Eliott, Attaquant
('J789123456', 8, 1, 9, 'A'), -- Kylian, Attaquant
('J456123789', 8, 1, 7, 'D'), -- Thibault, Défenseur
('J123654789', 8, 1, 8, 'D'), -- Patrick, Défenseur
('J543210987', 8, 1, 8, 'D'), -- Nicolas, Défenseur
('J685276975', 8, 1, 9, 'G'); -- Louis, Gardien

-- Match 9
INSERT INTO Participer (Numero_de_licence, Id_Match_Hockey, Titulaire, Notation, Poste) VALUES
('J998877665', 9, 1, 7, 'D'), -- Ilona, Défenseur
('J987654321', 9, 1, 8, 'D'), -- Toméo, Défenseur
('J123456789', 9, 1, 8, 'A'), -- Timéo, Attaquant
('J654321987', 9, 1, 5, 'A'), -- Eliott, Attaquant
('J789123456', 9, 1, 9, 'A'), -- Kylian, Attaquant
('J112233445', 9, 1, 7, 'D'), -- Dorian, Défenseur
('J456123789', 9, 1, 7, 'D'), -- Thibault, Défenseur
('J543210987', 9, 1, 7, 'D'), -- Nicolas, Défenseur
('J685276975', 9, 1, 9, 'G'); -- Louis, Gardien