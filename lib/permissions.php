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

/* A step more for a flexible permissions system
WARNING: USE THIS BRANCH ONLY FOR TESTING PURPOSE, YOU MAY ACCIDENTALLY GIVE BANNED USERS THE BANHAMMER OR SOMETHING
todo: make it on the database and allow plugins to use their garbage here*/

if ($loguser['powerlevel'] < 0)
    $loguser['forbiddens'] = "addranks deletecomments editcats editfora ipbans editmods editavatars editpora editpost editprofile editsettings editsmilies editthread edituser haveCookie usercomments newreply newthread optimize purgeRevs recalc snooping upload admin avatars lastknownbrowsers makeReply";
else if ($loguser['powerlevel'] >= 0 && $loguser['powerlevel'] < 3 && $loguserid)
    $loguser['forbiddens'] = "addranks editcats editfora ipbans editmods editpora editpost editsettings editsmilies editthread edituser optimize purgeRevs recalc snooping admin lastknownbrowsers";
else if ($loguser['powerlevel'] >= 3)
    $loguser['forbiddens'] = " ";
    
//$loguser['forbiddens'] = Query("SELECT forbiddens FROM gperms WHERE id = {0}", $loguser['id']); //wonderful query but this needs to return a string -.-

else if (!$loguserid)
    $loguser['forbiddens'] = "addranks deletecomments editcats editfora ipbans editmods editavatars editpora editpost editprofile editsettings editsmilies editthread edituser haveCookie usercomments newreply newthread optimize purgeRevs recalc snooping upload admin avatars lastknownbrowsers makeReply";

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
		$not = __("You are not allowed to perform this action.");
		/*$messages = array //i don't want to use specific messages, so i'm keeping this commented because it would actually be useful.
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
		);*/
		$bucket = "forbiddens"; include("./lib/pluginloader.php");
		if($caught == 2 && array_key_exists($to, $messages2))
			Kill(format($not), __("Permission denied"));
		Kill(format($not), __("Permission denied"));
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