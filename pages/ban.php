<?php
//BAN MORONCOWBELL BAN BAN

//checking stuff

if ($loguser['powerlevel'] < 2)
    Kill(__("Mass-destruction weapons aren't for you."));
	
if (isset($_POST['subm']) && $loguser['token'] != $_POST['key'])
	Kill(__("No."));

$uid = (int)$_GET["id"];

$user = fetch(query("select * from {users} where id=".$uid));

if(!$user)
	Kill("User ID not specified");

if($user['powerlevel'] > 0)
	Kill("Cannot ban a staff member"); 

if($user['powerlevel'] < 0)
    Kill('User already banned');

if (isset($_POST['subm']))
    $reason = htmlspecialchars($_POST['reason']);

$timeStamp = strtotime($_POST['duration']);
	if($timeStamp === "")
		Alert(__("Invalid time given. Try again."));
	if($timeStamp === "permanent")
		$timeStamp == 0;

    Query('UPDATE {users} set title={0}, tempbanpl=0, tempbantime={1}, powerlevel=-1 WHERE id={2}', $reason, $duration, $uid);
    redirectAction("profile", $uid)
?>

<form action="<?php echo actionLink("ban", $uid); ?>" method="post">
<table class="outline margin form">
		<tr class="header1">
			<th colspan="2">Ban <?php echo userLink($user);?></th>
		<tr class="cell1">
			<td class="cell2 center">
				Duration (type "permanent" if permanent)
			</td>
			<td>
				<input type="text" name="duration" />
			</td>
		</tr>
		<tr class="cell0">
			<td class="cell2 center">
				Reason
			</td>
			<td>
				<input type="text" name="reason" />
			</td>
		</tr>
		<tr class="cell2">
			<td>&nbsp;</td>
			<td>
				<input type="submit" name="subm" value="Ban user" />
				<input type="hidden" name="key" value="<?php echo $loguser['token']; ?>" />
			</td>
		</tr>
	</table>
	</form>
