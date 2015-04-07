SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `ch5_socialii`
--

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created`, `updated`) VALUES
(1, 'Regular User', 1394920186, 1394920186);

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `new_email`, `password`, `name`, `activated`, `role_id`, `created`, `updated`) VALUES
(1, 'user1@example.com', NULL, '$2y$13$SjAJFTNr/EFKSuf7Y7UZgu8ViyySLQsICBt/PryluxqfwIP9j9ox2', 'User 1', 1, 1, 1394920423, 1394920423),
(2, 'user2@example.com', NULL, '$2y$13$0jZE/pPTct.8dg6a8wA5COAEDxBBDatQSb6drui/h.VAUvhr6Af8C', 'User 2', 1, 1, 1395009793, 1395009793);

--
-- Dumping data for table `followers`
--

INSERT INTO `followers` (`id`, `follower_id`, `followee_id`, `created`) VALUES
(1, 1, 2, NULL),
(2, 2, 1, NULL);
