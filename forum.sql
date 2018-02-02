-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Ven 02 Février 2018 à 07:00
-- Version du serveur :  5.7.14
-- Version de PHP :  7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `forum`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `categories`
--

INSERT INTO `categories` (`id`, `category`) VALUES
(1, 'C++'),
(2, 'PHP'),
(3, 'Node js'),
(4, 'Javascript'),
(5, 'MySQL'),
(6, 'Symfony'),
(7, 'Angular'),
(8, 'Ada'),
(9, 'Fortran'),
(10, '.net');

-- --------------------------------------------------------

--
-- Structure de la table `grants`
--

CREATE TABLE `grants` (
  `id` int(11) NOT NULL,
  `grant_name` varchar(255) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `grants`
--

INSERT INTO `grants` (`id`, `grant_name`) VALUES
(1, 'CAN_CREATE_TOPIC'),
(2, 'CAN_DELETE_TOPIC'),
(3, 'CAN_POST_ON_TOPIC'),
(4, 'CAN_EDIT_POST'),
(5, 'CAN_DELETE_POST'),
(6, 'CAN_DELETE_ALL_POST'),
(7, 'CAN_CREATE_CATEGORY'),
(8, 'CAN_CLOSE_TOPIC');

-- --------------------------------------------------------

--
-- Structure de la table `link_role_grant`
--

CREATE TABLE `link_role_grant` (
  `id_role` int(11) NOT NULL,
  `id_grant` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `link_role_grant`
--

INSERT INTO `link_role_grant` (`id_role`, `id_grant`) VALUES
(1, 1),
(2, 1),
(3, 1),
(3, 2),
(1, 3),
(2, 3),
(3, 3),
(1, 4),
(2, 4),
(3, 4),
(1, 5),
(3, 5),
(2, 6),
(3, 6),
(3, 7),
(2, 8),
(3, 8);

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `id_topic` int(11) NOT NULL,
  `id_category` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `post` text NOT NULL,
  `post_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `posts`
--

INSERT INTO `posts` (`id`, `id_topic`, `id_category`, `id_user`, `post`, `post_date`) VALUES
(8, 14, 2, 3, 'Difficile de\r\ns\'y faire', '2018-01-25 10:48:43'),
(9, 15, 3, 3, 'Je ne sais\r\npas ...', '2018-01-25 11:05:43'),
(11, 18, 2, 3, 'Quoi ?', '2018-01-25 19:10:01'),
(46, 15, 3, 3, 'moi non plus', '2018-01-28 14:15:53'),
(47, 15, 3, 3, '???', '2018-01-28 14:16:02'),
(51, 21, 2, 4, 'Cool', '2018-01-29 10:38:22'),
(52, 22, 5, 4, 'truc', '2018-01-29 12:38:56'),
(84, 22, 5, 4, 'dddd', '2018-01-29 20:39:35'),
(85, 22, 5, 4, 'eeee', '2018-01-29 20:39:53'),
(86, 22, 5, 4, 'ffff', '2018-01-29 20:40:47'),
(87, 22, 5, 4, 'gggg', '2018-01-29 20:40:50'),
(89, 22, 5, 3, 'yyy', '2018-01-29 21:03:06'),
(92, 21, 2, 3, 'aaa', '2018-01-30 06:42:07'),
(98, 25, 7, 4, '????????????', '2018-02-01 21:13:03'),
(99, 26, 1, 4, 'Si si', '2018-02-01 21:13:45'),
(100, 26, 1, 3, 'Non non', '2018-02-01 21:14:29'),
(101, 21, 2, 3, 'bbb', '2018-02-01 21:14:50'),
(102, 21, 2, 3, 'ccc', '2018-02-01 21:14:54');

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(255) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `roles`
--

INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'USER'),
(2, 'MODERATOR'),
(3, 'ADMIN');

-- --------------------------------------------------------

--
-- Structure de la table `topics`
--

CREATE TABLE `topics` (
  `id` int(11) NOT NULL,
  `id_category` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `topic` varchar(255) NOT NULL,
  `nb_posts` int(11) NOT NULL,
  `last_msg_date` datetime NOT NULL,
  `topic_closed` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `topics`
--

INSERT INTO `topics` (`id`, `id_category`, `id_user`, `topic`, `nb_posts`, `last_msg_date`, `topic_closed`) VALUES
(14, 2, 3, 'Langage sans type !', 1, '2018-01-25 10:48:43', 0),
(15, 3, 3, 'Node js qu\'est-ce ?', 3, '2018-01-28 14:16:02', 0),
(18, 2, 3, 'PHP c\'est fou ce que cela peut faire causer en bien ou en mal mais cela fait parler', 1, '2018-01-25 19:10:01', 0),
(21, 2, 4, 'PHP cool', 4, '2018-02-01 21:14:55', 0),
(22, 5, 4, 'truc', 6, '2018-01-29 20:50:11', 0),
(25, 7, 4, 'Pour quoi faire ?', 1, '2018-02-01 21:13:03', 0),
(26, 1, 4, 'Langage difficile', 2, '2018-02-01 21:14:29', 0);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `id_role` int(11) NOT NULL,
  `pseudo` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `e_mail` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `id_role`, `pseudo`, `password`, `e_mail`) VALUES
(3, 1, 'Jojo', '8c7bf89b1f5b26c642b231279cf7db68fce25492', 'jo@free.fr'),
(4, 3, 'toto', '8c7bf89b1f5b26c642b231279cf7db68fce25492', 'toto@free.fr'),
(5, 2, 'maxou', '8c7bf89b1f5b26c642b231279cf7db68fce25492', 'max@free.fr'),
(6, 1, 'bobby', '8c7bf89b1f5b26c642b231279cf7db68fce25492', 'bob@free.fr'),
(7, 1, 'Raymond', '8c7bf89b1f5b26c642b231279cf7db68fce25492', 'ray@free.fr');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `grants`
--
ALTER TABLE `grants`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `link_role_grant`
--
ALTER TABLE `link_role_grant`
  ADD PRIMARY KEY (`id_role`,`id_grant`),
  ADD KEY `id_grant` (`id_grant`);

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_topic` (`id_topic`),
  ADD KEY `id_category` (`id_category`),
  ADD KEY `id_user` (`id_user`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_category` (`id_category`),
  ADD KEY `id_user` (`id_user`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_role` (`id_role`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT pour la table `grants`
--
ALTER TABLE `grants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;
--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `topics`
--
ALTER TABLE `topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `link_role_grant`
--
ALTER TABLE `link_role_grant`
  ADD CONSTRAINT `link_role_grant_ibfk_1` FOREIGN KEY (`id_grant`) REFERENCES `grants` (`id`),
  ADD CONSTRAINT `link_role_grant_ibfk_2` FOREIGN KEY (`id_role`) REFERENCES `roles` (`id`);

--
-- Contraintes pour la table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`id_topic`) REFERENCES `topics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`id_category`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `posts_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `topics`
--
ALTER TABLE `topics`
  ADD CONSTRAINT `topics_ibfk_1` FOREIGN KEY (`id_category`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `topics_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `roles` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
