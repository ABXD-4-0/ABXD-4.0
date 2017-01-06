<?php
//  AcmlmBoard XD - User account registration page
//  Access: any, but meant for guests.
$crumbs = new PipeMenu();
$crumbs->add(new PipeMenuLinkEntry(__("Register"), "register"));
makeBreadcrumbs($crumbs);
$title = __("Register");

if($loguserid)
    Kill(__("You already have an account"));

if(Settings::get("registration"))
    Kill(__("Account registrations are disabled"));

#StopForumSpam protection goes here

function validateSex($sex)
{
	if($sex == 0) return 0;
	if($sex == 1) return 1;
	if($sex == 2) return 2;
	return 2;
}
if(isset($_POST['name']))
{
	$name = trim($_POST['name']);
	$cname = str_replace(" ","", strtolower($name));
	$rUsers = Query("select name, displayname from {users}");
	while($user = Fetch($rUsers))
	{
		$uname = trim(str_replace(" ", "", strtolower($user['name'])));
		if($uname == $cname)
			break;
		$uname = trim(str_replace(" ", "", strtolower($user['displayname'])));
		if($uname == $cname)
			break;
	}
	$ipKnown = FetchResult("select COUNT(*) from {users} where lastip={0}", $_SERVER['REMOTE_ADDR']);
	//This makes testing faster.
	if($_SERVER['REMOTE_ADDR'] == "127.0.0.1")
		$ipKnown = 0;
	if($uname == $cname)
		$err = __("This user name is already taken. Please choose another.");
	else if($name == "" || $cname == "")
		$err = __("The user name must not be empty. Please choose one.");
    else if (Settings::get("forcemail") && !$email)
        $err = __("You must specify an e-mail address");
	else if(strpos($name, ";") !== false)
		$err = __("The user name cannot contain semicolons.");
	elseif($ipKnown >= 3)
		$err = __("You already have an account.");
	else if ($_POST['pass'] !== $_POST['pass2'])
		$err = __("The passwords you entered don't match.");
    else if(strlen($_POST['pass']) < 8)
		$err = __("Your password must be at least eight characters long.");
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		$err = __("Please enter a valid e-mail address");
    
	/*if(!$err) #Enable this only if you're using the StopForumSpam addition
	{
		$reasons = array();
		if(IsTorExitPoint()) {
			$reasons[] = 'tor';
		}
		$s = new StopForumSpam($stopForumSpamKey);
		if($s->is_spammer(array('email' => $_POST['email'], 'ip' => $_SERVER['REMOTE_ADDR'] ))) {
			$reasons[] = 'sfs';
		}
		if(count($reasons)) {
			$reason = implode(',', $reasons);
			$bucket = "regfail"; include("lib/pluginloader.php");
			$err = 'An unknown error occured, please try again.';
		}
	}*/
	if($err)
	{
		Alert($err);
	}
	else
	{
		$newsalt = Shake();
		$sha = doHash($_POST['pass'].$salt.$newsalt);
		$sex = validateSex($_POST["sex"]);
		$rUsers = Query("insert into {users} (name, password, pss, regdate, lastactivity, lastip, email, sex, theme) values ({0}, {1}, {2}, {3}, {3}, {4}, {5}, {6}, {7})", $_POST['name'], $sha, $newsalt, time(), $_SERVER['REMOTE_ADDR'], $_POST['email'], $sex, Settings::get("defaultTheme"));
		$uid = insertId();
		if($uid == 1)
			Query("update {users} set powerlevel = 4 where id = 1");
		logAction('register', array('user' => $uid));
		$user = Fetch(Query("select * from {users} where id={0}", $uid));
		$user["rawpass"] = $_POST["pass"];
		$bucket = "newuser"; include("lib/pluginloader.php");
		$sessionID = Shake();
		setcookie("logsession", $sessionID, 0, $boardroot, "", false, true);
		Query("INSERT INTO {sessions} (id, user, autoexpire) VALUES ({0}, {1}, {2})", doHash($sessionID.$salt), $user["id"], 0);
		redirectAction("board");
	}
}
$sexes = array(__("Male"), __("Female"), __("N/A"));
$name = "";
if(isset($_POST["name"]))
	$name = htmlspecialchars($_POST["name"]);
$email = "";
if(isset($_POST["email"]))
	$email = htmlspecialchars($_POST["email"]);
$sex = 2;
if(isset($_POST["sex"]))
	$sex = validateSex($_POST["sex"]);
echo "
<script src=\"".resourceLink('js/zxcvbn.js')."\"></script>
<form action=\"".actionLink("register")."\" method=\"post\">
	<table class=\"outline margin width50\">
		<tr class=\"header0\">
			<th colspan=\"2\">
				".__("Register")."
			</th>
		</tr>
		<tr>
			<td class=\"cell2\">
				<label for=\"un\">".__("User name")."</label>
			</td>
			<td class=\"cell0\">
				<input type=\"text\" id=\"un\" name=\"name\" value=\"$name\" maxlength=\"20\" style=\"width: 98%;\"  class=\"required\" />
			</td>
		</tr>
		<tr>
			<td class=\"cell2\">
				<label for=\"pw\">".__("Password")."</label>
			</td>
			<td class=\"cell1\">
				<input type=\"password\" id=\"pw\" name=\"pass\" size=\"13\" maxlength=\"32\" class=\"required\" /> / ".__("Repeat:")." <input type=\"password\" id=\"pw2\" name=\"pass2\" size=\"13\" maxlength=\"32\" class=\"required\" />
			</td>
		</tr>
		<tr>
			<td class=\"cell2\">
				<label for=\"email\">".__("Email address")."</label>
			</td>
			<td class=\"cell0\">
				<input type=\"email\" id=\"email\" name=\"email\" value=\"$email\" style=\"width: 98%;\" maxlength=\"60\" />
			</td>
		</tr>
		<tr>
			<td class=\"cell2\">
				".__("Sex")."
			</td>
			<td class=\"cell1\">
				".MakeOptions("sex",$sex,$sexes)."
			</td>
		</tr>";
echo "
		<tr class=\"cell2\">
			<td></td>
			<td>
				<input type=\"submit\" name=\"action\" value=\"".__("Register")."\"/>
			</td>
		</tr>
		<tr>
			<td colspan=\"2\" class=\"cell0 smallFonts\">
				".__("Specifying an email address is not exactly a hard requirement, but it will allow you to reset your password should you forget it. By default, your email is not shown.")."
			</td>
		</tr>
	</table>
</form>";
function MakeOptions($fieldName, $checkedIndex, $choicesList)
{
	$checks[$checkedIndex] = " checked=\"checked\"";
	foreach($choicesList as $key=>$val)
		$result .= format("
					<label>
						<input type=\"radio\" name=\"{1}\" value=\"{0}\"{2} />
						{3}
					</label>", $key, $fieldName, $checks[$key], $val);
	return $result;
}