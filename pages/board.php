<?php

if($loguserid && isset($_GET['action']) && $_GET['action'] == "markallread")
{
	Query("REPLACE INTO {threadsread} (id,thread,date) SELECT {0}, {threads}.id, {1} FROM {threads}", $loguserid, time());
	redirectAction("board");
}

$links = new PipeMenu();
if ($loguserid)
	$links->add(new PipeMenuLinkEntry(__("Mark all forums read"), "board", 0, "action=markallread", "ok"));

makeLinks($links);
makeBreadcrumbs(new PipeMenu());

if(!$mobileLayout)
{
	$statData = Fetch(Query("SELECT
		(SELECT COUNT(*) FROM {threads}) AS numThreads,
		(SELECT COUNT(*) FROM {posts}) AS numPosts,
		(SELECT COUNT(*) FROM {users}) AS numUsers,
		(select count(*) from {posts} where date > {0}) AS newToday,
		(select count(*) from {posts} where date > {1}) AS newLastHour,
		(select count(*) from {users} where lastposttime > {2}) AS numActive",
		 time() - 86400, time() - 3600, time() - 2592000));

	$stats = Format(__("{0} and {1} total"), Plural($statData["numThreads"], __("thread")), Plural($statData["numPosts"], __("post")));
	$stats .= "<br />".format(__("{0} today, {1} last hour"), Plural($statData["newToday"], __("new post")), $statData["newLastHour"]);

	$percent = $statData["numUsers"] ? ceil((100 / $statData["numUsers"]) * $statData["numActive"]) : 0;
	$lastUser = Query("select u.(_userfields) from {users} u order by u.regdate desc limit 1");
	if(numRows($lastUser))
	{
		$lastUser = getDataPrefix(Fetch($lastUser), "u_");
		$last = format(__("{0}, {1} active ({2}%)"), Plural($statData["numUsers"], __("registered user")), $statData["numActive"], $percent)."<br />".format(__("Newest: {0}"), UserLink($lastUser));
	}
	else
		$last = __("No registered users")."<br />&nbsp;";


	write(
	"
		<table class=\"outline margin width100\" style=\"overflow: auto;\">
			<tr class=\"cell2 center\" style=\"overflow: auto;\">
			<td>
				<div style=\"float: left; width: 25%;\">&nbsp;<br />&nbsp;</div>
				<div style=\"float: right; width: 25%;\">{1}</div>
				<div class=\"center\">
					{0}
				</div>
			</td>
			</tr>
		</table>
	",	$stats, $last);
}

function announcement() {
    global $loguserid;
	
	$anncforum = Settings::get('announcementsForum');
	if ($anncforum > 0) {
		$annc = Fetch(Query('SELECT * FROM {threads} where forum={0} ORDER BY date DESC LIMIT 1 ', $anncforum));
								
		if ($annc) {
			
			//this is incredibly stupid but required, ugh
			$poster = Fetch(Query('SELECT * FROM {users} where id='.$annc['user']));
			
			$annc['new'] = '';
			if ((!$loguserid && $annc['anncdate'] > (time()-900)) ||
				($loguserid && $annc['anncdate'] > $annc['readdate']))
				$annc['new'] = "<div class=\"statusIcon new\"></div>";
			
			$annc['poll'] = ($annc['poll'] ? "<img src=\"".resourceLink('img/poll.png')."\" alt=\"Poll\"/> " : '');
			$posterlink = UserLink($poster);
			$annc['link'] = MakeThreadLink($annc);
			$annc['date'] = formatdate($annc['date']);
		}
    ?>
    <table class="outline margin anncbar">
    	<tr class="header1">
			<th colspan="2">
				Announcement
			</th>
		</tr>
		<tr class="cell1">
			<td class="cell2 newMarker">
				<?php echo $annc['new']; ?>
			</td>
			<td>
				<?php echo $annc['poll'], $annc['link'], " &mdash; Posted by ", $posterlink, " on ", $annc['date']; ?>
			</td>
		</tr>
</table> <?php
	}
}

printRefreshCode();
if (Settings::Get['announcementsMode'] == 1) {
announcement();
}
makeForumListing(0);

?>
