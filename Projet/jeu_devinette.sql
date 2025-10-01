-- 1.Create the Database | Créer la Base de données

CREATE DATABASE IF NOT EXISTS jeu_devinette;


-- 2.Access the Database | Accéder à la Base de données

USE jeu_devinette;


-- 3.Create the Table | Créer la Tables

CREATE TABLE IF NOT EXISTS compte_utilisateur(

id INT(5) PRIMARY KEY AUTO_INCREMENT,

nom_utilisateur VARCHAR(50) NOT NULL UNIQUE,

mot_de_passe VARCHAR(150) NOT NULL

)CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;



