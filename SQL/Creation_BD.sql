-- Création des tables
CREATE TABLE Joueur(
   Numero_de_licence CHAR(10) ,
   Nom VARCHAR(50) ,
   Prenom VARCHAR(50) ,
   Date_de_naissance DATE,
   Taille DECIMAL(3,0)  ,
   Poids DECIMAL(4,1)  ,
   Statut VARCHAR(50) ,
   PRIMARY KEY(Numero_de_licence)
);

CREATE TABLE Match_Hockey(
   Id_Match_Hockey INT AUTO_INCREMENT,
   Date_Heure_match DATETIME,
   Nom_equipe_adverse VARCHAR(50) ,
   Lieu_de_rencontre VARCHAR(50) ,
   ScoreMatch VARCHAR(5) ,
   PRIMARY KEY(Id_Match_Hockey)
);

CREATE TABLE Commentaire(
   Id_Commentaire INT AUTO_INCREMENT,
   Texte VARCHAR(50) ,
   Numero_de_licence CHAR(10)  NOT NULL,
   PRIMARY KEY(Id_Commentaire),
   FOREIGN KEY(Numero_de_licence) REFERENCES Joueur(Numero_de_licence)
);

CREATE TABLE Participer(
   Numero_de_licence CHAR(10) ,
   Id_Match_Hockey INT,
   Titulaire BOOLEAN,
   Notation DECIMAL(2,1)  ,
   Poste CHAR(1) ,
   PRIMARY KEY(Numero_de_licence, Id_Match_Hockey),
   FOREIGN KEY(Numero_de_licence) REFERENCES Joueur(Numero_de_licence),
   FOREIGN KEY(Id_Match_Hockey) REFERENCES Match_Hockey(Id_Match_Hockey)
);


