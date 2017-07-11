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
	
	$group_permissions = Fetch(Query('SELECT permissions FROM {groups} WHERE id={0}', $loguser['powerlevel']));
	if(!isset($perms))
		$perms = explode(' ', $group_permissions['permissions']);
	//print_r($perms);
	if(!isset($forbidden))
		$forbidden = explode(" ", $loguser['forbiddens']);
	$caught = 0;
	if(in_array($to, $forbidden) || !in_array($to, $perms))
		$caught = 1;
	else
	{
		$specific = $to."[".$specifically."]";
		if(in_array($specific, $forbidden))
			$caught = 2;
	}

	if($caught)
	{
		$not = __("Non sei autorizzato ad eseguire questa azione");
		$bucket = "forbiddens"; include("./lib/pluginloader.php");
		Kill(format($not), __("Accesso negato"));
	}
}

function IsAllowed($to, $specifically = 0)
{
	global $loguser, $forbidden, $perms;
	
	$group = Fetch(Query('SELECT permissions FROM {groups} WHERE id={0}', $loguser['powerlevel']));
	if(!isset($perms))
		$perms = explode(' ', $group['permissions']);
	if(!isset($forbidden))
		$forbidden = explode(" ", $loguser['forbiddens']);

	if(in_array($to, $forbidden) || !in_array($to, $perms)) {
		return FALSE;
		}
	else
	{
		$specific = $to."[".$specifically."]";
		if(in_array($specific, $forbidden))
			return FALSE;
	}
	return TRUE;
}
