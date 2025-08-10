CREATE TABLE `apitokens` (
  `tokenid` bigint(20) NOT NULL,
  `tokenname` text NOT NULL,
  `apitoken` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `userid` bigint(20) UNSIGNED NOT NULL,
  `permissions` int(11) NOT NULL,
  `expiration` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `settings` (
  `settingid` bigint(20) UNSIGNED NOT NULL,
  `signup` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `settings` (`settingid`, `signup`) VALUES
(1, 1);

ALTER TABLE `settings`
  ADD PRIMARY KEY (`settingid`),
  ADD UNIQUE KEY `settingid` (`settingid`);

ALTER TABLE `settings`
  MODIFY `settingid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

ALTER TABLE `userinv`
  ADD `completepicurl` text DEFAULT NULL;

ALTER TABLE `apitokens`
  ADD PRIMARY KEY (`tokenid`),
  ADD KEY `fkapitokenuserid` (`userid`);

ALTER TABLE `apitokens`
  MODIFY `tokenid` bigint(20) NOT NULL AUTO_INCREMENT;
