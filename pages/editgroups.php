<?php
//  AcmlmBoard XD - Administration hub page
//  Access: administrators


AssertForbidden("admin");

if($loguser['powerlevel'] < 3)
    Kill(__("You're not an administrator. There is nothing for you here."));

if(!isset($_GET['id']))
    Kill('Invalid group ID');

$title = __("Edit permissions");

$crumbs = new PipeMenu();
$crumbs->add(new PipeMenuLinkEntry(__("Admin"), "admin"));
$crumbs->add(new PipeMenuLinkEntry(__("Edit permissions"), "editpermissions"));
makeBreadcrumbs($crumbs);


$group = (int)$_GET['id'];

$rGroup = Query('SELECT * FROM {groups} WHERE id={0}', $group);

if(!NumRows($rGroup)) 
    Kill('Group ID not specified');

$rGroup = Fetch($rGroup);

if($rGroup['id'] >= $loguser['powerlevel'])
    Kill('You may only edit groups whose ID is lower than yours');

if(isset($_POST['submit']) && $_POST['token'] == $loguser['token']) {
    
    unset($_POST['submit']);
    unset($_POST['token']);
    
    $permissions = Array();
    
    foreach ($_POST as $perm){
        $permissions[] = htmlspecialchars($perm);
    }
    
    $implodedperms = implode(' ', $permissions);
    
    Query('UPDATE {groups} SET permissions={0} WHERE id={1}', $implodedperms, $group);
    Alert('Done.');
}

$permsarray = explode(' ', $rGroup['permissions']);

echo '<form action="'.actionLink('editpermissions', $group).'" method="post">';
echo '<table class="outline margin width50"> <tbody> <tr class="header1"><th>Standard permissions</th></tr>';

//there should be a method that works without three separate foreaches, but blarg.

    foreach($permissionList['standard'] as $permname => $text) {
        
        $checked = in_array($permname, $permsarray) ? 'checked="checked"' : ''; 
        
        echo'
            <tr colspan="2" class="cell1">
    			<td>
					<input type="checkbox" name="permissions'.rand().'" value="'.$permname.'" '.$checked.' />&nbsp;&bull;&nbsp;'.htmlspecialchars($text).'
				</td>
			</tr>
        ';
    }
    
    echo '<tr class="header0"><th>Moderation permissions</th></tr>';
    
    foreach($permissionList['moderation'] as $permname => $text) {
        
        $checked = in_array($permname, $permsarray) ? 'checked="checked"' : ''; 
        
        echo'
            <tr class="cell1">
        		<td>
					<input type="checkbox" name="permissions'.rand().'" value="'.$permname.'" '.$checked.' />&nbsp;&bull;&nbsp;'.htmlspecialchars($text).'
				</td>
			</tr>
        ';
    }
    
    echo '<tr class="header0"><th>Administration permissions</th></tr>';
    
    foreach($permissionList['administration'] as $permname => $text) {
        
        $checked = in_array($permname, $permsarray) ? 'checked="checked"' : ''; 
        
        echo'
            <tr class="cell1">
            	<td>
					<input type="checkbox" name="permissions'.rand().'" value="'.$permname.'" '.$checked.' />&nbsp;&bull;&nbsp;'.htmlspecialchars($text).'
				</td>
			</tr>
        ';
    }
    
echo '</tbody></table>';

echo '<table class="outline margin width50"> <tbody> <tr class="header1"><th></th></tr>
    <tr><td>
    <input type="submit" value="Submit" name="submit" /> <input type="hidden" name="token" value="'.$loguser['token'].'" />
   </td></tr> </tbody> </table>';
  echo '</form>';
?>
