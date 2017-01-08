<?php

//Functions from old permissions system.
//I'm putting them here so we know what we have to rewrite/nuke ~Dirbaio


function CanMod($userid, $fid)
{
	global $loguser;
	// Private messages. You cannot moderate them
	if (!$fid)
		return false;
	if($loguser['powerlevel'] > 1)
		return true;
	if($loguser['powerlevel'] == 1)
	{
		$rMods = Query("select * from {forummods} where forum={0} and user={1}", $fid, $userid);
		if(NumRows($rMods))
			return true;
	}
	return false;
}






function AssertForbidden($to, $specifically = 0)
{
	global $loguser, $forbidden;
	if(!isset($forbidden))
		$forbidden = explode(" ", $loguser['forbiddens']);
	$caught = 0;
	if(in_array($to, $forbidden))
		$caught = 1;
	else
	{
		$specific = $to."[".$specifically."]";
		if(in_array($specific, $forbidden))
			$caught = 2;
	}

	if($caught)
	{
		$not = __("You are not allowed to {0}.");
		$messages = array
		(
			"addranks" => __("add new ranks"),
			"deletecomments" => __("delete usercomments"),
			"editcats" => __("edit the forum categories"),
			"editfora" => __("edit the forum list"),
			"ipbans" => __("edit the IP ban list"),
			"editmods" => __("edit Local Moderator assignments"),
			"editavatars" => __("edit your mood avatars"),
			"editpora" => __("edit the PoRA box"),
			"editpost" => __("edit posts"),
			"editprofile" => __("edit your profile"),
			"editsettings" => __("edit the board settings"),
			"editsmilies" => __("edit the smiley list"),
			"editthread" => __("edit threads"),
			"edituser" => __("edit users"),
			"haveCookie" => __("have a cookie"),
			"usercomments" => __("post usercomments"),
			"newreply" => __("reply to threads"),
			"newthread" => __("start new threads"),
			"optimize" => __("optimize the tables"),
			"purgeRevs" => __("purge old revisions"),
			"recalc" => __("recalculate the board counters"),
			"sendprivate" => __("send private messages"),
			"snooping" => __("view other users' private messages"),
			"upload" => __("upload files"),
			"admin" => __("see the admin room"),
			"avatars" => __("see the avatar library"),
			"lastknownbrowsers" => __("see the Last Known Browser table"),
			"online" => __("see who's online"),
			"ranks" => __("see the rank lists"), //dumb, but it might block postcount++ idiots
			"records" => __("see the top scores and DB usage"),
			"thread" => __("read threads"),
			"vote" => __("vote"),
		);
		$messages2 = array
		(
			"viewForum" => __("see this forum"),
			"viewThread" => __("read this thread"),
			"makeReply" => __("reply in this thread"),
			"edituser" => __("edit this user"),
		);
		$bucket = "forbiddens"; include("./lib/pluginloader.php");
		if($caught == 2 && array_key_exists($to, $messages2))
			Kill(format($not, $messages2[$to]), __("Permission denied"));
		Kill(format($not, $messages[$to]), __("Permission denied"));
	}
}

function IsAllowed($to, $specifically = 0)
{
	global $loguser, $forbidden;
	if(!isset($forbidden))
		$forbidden = explode(" ", $loguser['forbiddens']);
	if(in_array($to, $forbidden))
		return FALSE;
	else
	{
		$specific = $to."[".$specifically."]";
		if(in_array($specific, $forbidden))
			return FALSE;
	}
	return TRUE;
}

/*
//Improved permissions system ~Nina
$groups = array();
$rGroups = query("SELECT * FROM {usergroups}");
while ($group = fetch($rGroups))
{
	$groups[] = $group;
	$groups[$grup['id']]['permissions'] = unserialize($group['permissions']);
}

//Do nothing for guests.
if (isset($loguserid) && isset($loguser['group']))
{
	$rPermissions = query("SELECT * FROM {userpermissions} WHERE uid={0}", $loguserid);
	$permissions = fetch($rPermissions);
	$permissions['permissions'] = unserialize($permissions['permissions']);
	if (is_array($groups[$loguser['group']]['permissions']))
		$loguser['permissions'] = array_merge($groups[$loguser['group']]['permissions'], $permissions); //$permissions overrides the group permissions here.
	if ($loguser['powerlevel'] == 4) $loguser['group'] == "root"; //Just in case.
}

//Returns false for guests no matter what. Returns if the user is allowed to do something otherwise.
//Additionally always returns true if the user's powerlevel is root.
function checkAllowed($p)
{
	global $loguser, $loguserid;
	if (!$loguserid) return false;
	elseif ($loguser['group'] == "root" || $loguser['powerlevel'] == 4) return true;
	elseif (strpos('.', $p))
	{
		$nodes = explode(".", $p);
		$r = $loguser['permissions'];
		foreach ($nodes as $n)
			$r = $r[$node];
		return $r;
	}
	else return $loguser['permissions'][$p];
}

*/