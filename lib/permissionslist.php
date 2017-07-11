<?php

$permissionList = [
        "standard" => [
            'avatars' => 'Browse avatar library', 
            'editavatars' => 'Edit mood avatars', 
            'editprofile' => 'Edit own profile', 
            'forum' => 'View forums', 
            'usercomments' => 'Post profile comments',
            'newreply' => 'Reply to threads', 
            'newthread' => 'Create new threads', 
            'sendprivate' => 'Send private messages', 
            'upload' => 'Upload files', 
            'online' => 'View online users',
            'ranks' => 'View ranks', 
            'records' => 'See board records', 
            'thread' => 'View threads', 
            'vote' => 'Vote',
        ],
        "moderation" => [
            'deletecomments' => 'Delete profile comments', 
            'editpost' => 'Edit posts', 
            'editthread' => 'Edit threads', 
            'editmods' => 'Edit moderators assignment',
        ],
        "administration" => [
            'addranks' => 'Add new ranks', 
            'admin' => 'View administration panel', 
            'editfora' => 'Edit forum list', 
            'ipbans' => 'Manage IP bans', 
            'editsettings' => 'Edit board settings',
            'editsmilies' => 'Edit smilies list',
            'edituser' => 'Edit other users', 
            'haveCookie' => 'Eat cookies', 
            'optimize' => 'Optimize database tables',
            'pluginmanager' => 'Manage plugins',
            'recalc' => 'Recalculate statistics',
            'snooping' => 'Snoop private messages',
            'lastknownbrowsers' => 'Show last known browsers',
        ],
    ];

$bucket = 'permissionList'; include('pluginloader.php');