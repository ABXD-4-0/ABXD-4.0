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
	global $loguser, $forbidden, $perms;
	
	$group = Fetch(Query('SELECT permissions FROM {groups} WHERE id={0}', $loguser['powerlevel']));
	if(!isset($perms))
		$perms = $group['permissions'];
	if(!isset($forbidden))
		$forbidden = $loguser['forbiddens'];
    
	$caught = 0;
    
    $caught = stripos($perms, $to) ? 0 : 1;
    $caught = stripos($forbidden, $to) ? 1 : 0;
    
	if($specifically > 0)
	{
		$specific = $to."[".$specifically."]";
		$caught = strpos($perms, $specific) ? 0 : 2;
        $caught = strpos($forbidden, $specific) ? 2 : 0;
	}

	if($caught)
	{
		$not = __("You are not allowed to perform this action");
		$bucket = "forbiddens"; include("./lib/pluginloader.php");
		Kill(format($not), __("Access denied"));
	}
}

function IsAllowed($to, $specifically = 0)
{
	global $loguser, $forbidden, $perms;
	
	$group = Fetch(Query('SELECT permissions FROM {groups} WHERE id={0}', $loguser['powerlevel']));
	if(!isset($perms))
    	$perms = $group['permissions'];
	if(!isset($forbidden))
		$forbidden = $loguser['forbiddens'];
    
    $candoit = FALSE;
    $candoit = stripos($perms, $to) ? TRUE : FALSE;
    $candoit = stripos($forbidden, $to) ? FALSE : TRUE;
    
    if($specifically > 0) {
		$specific = $to."[".$specifically."]";
		$candoit = stripos($perms, $specific) ? TRUE : FALSE;
        $candoit = stripos($forbidden, $to) ? FALSE : TRUE;
    }

	return $candoit;
}
