INSERT INTO `{$dbpref}categories` VALUES(1, 'Staff', 0);
INSERT INTO `{$dbpref}categories` VALUES(2, 'General', 1);
INSERT INTO `{$dbpref}categories` VALUES(3, 'Janitorial Services', 2);

INSERT INTO `{$dbpref}forums` (`id`, `title`, `description`, `catid`, `minpower`, `minpowerthread`, `minpowerreply`) VALUES(1, 'Admin room', 'Staff discussion forum', 1, 1, 1, 1);
INSERT INTO `{$dbpref}forums` (`id`, `title`, `description`, `catid`) VALUES(2, 'General chat', 'Talk about serious stuff', 2);
INSERT INTO `{$dbpref}forums` (`id`, `title`, `description`, `catid`, `minpowerthread`, `minpowerreply`) VALUES(3, 'Trash', 'Trashed threads go here.', 3, 3, 3);
INSERT INTO `{$dbpref}forums` (`id`, `title`, `description`, `catid`, `minpower`, `minpowerthread`, `minpowerreply`) VALUES(4, 'Hidden trash', 'Only visible to admins. Deleted threads go here.', 3, 3, 3, 3);

INSERT INTO `{$dbpref}settings` (`plugin`, `name`, `value`) VALUES('main', 'trashForum', 3);
INSERT INTO `{$dbpref}settings` (`plugin`, `name`, `value`) VALUES('main', 'hiddenTrashForum', 4);

--TODO: add system group

INSERT INTO `{$dbpref}groups` (`id`, `name`, `color_male`, `color_female`, `color_unspec`, `default`, `permissions`) VALUES
(4, 'Root',  '#5555FF', '#FF5588', '#FF55FF', 0, 'addranks admin deletecomments editcats editfora ipbans editmods editavatars editpora editpost editprofile editsettings editsmilies editthread edituser forum haveCookie usercomments newreply newthread optimize pluginmanager recalc sendprivate snooping upload admin avatars lastknownbrowsers online ranks records thread userbadges vote '),
(3, 'Admin', '#FFEA95', '#C53A9E', '#F0C413', 0, 'admin addranks deletecomments editcats editfora ipbans editmods editavatars editpora editpost editprofile editsettings editsmilies editthread edituser forum haveCookie lastknownbrowsers usercomments newreply newthread optimize pluginmanager recalc sendprivate snooping upload admin avatars lastknownbrowsers online ranks records thread userbadges vote '),
(2, 'Full mod', '#AFFABE', '#C762F2', '#47B53C', 0, 'avatars editavatars editprofile forum usercomments newreply newthread sendprivate upload online ranks records thread vote editpost editthread haveCookie'),
(1, 'Local mod', '#D8E8FE', '#FFB3F3', '#EEB9BA', 0, 'avatars editavatars editprofile forum usercomments newreply newthread sendprivate upload online ranks records thread vote haveCookie'),
(0, 'Normal', '#97ACEF', '#F185C9', '#7C60B0', 0, 'editavatars editprofile forum haveCookie usercomments newreply newthread sendprivate upload online ranks records thread vote '),
(-1, 'Banned',  '#888888', '#888888', '#888888', 0, 'forum newreply sendprivate online ranks records thread vote');