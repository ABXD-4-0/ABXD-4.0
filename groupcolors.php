<?php

// What is this for? Basically, it makes the CSS classes for username colors but handled that colors can be changed through the database and allow the creation of new groups.
// I decided to make this because i don't like the idea of hardcoding the colors so this will keep light themes fine ( i guess)
// this should be put somewhere else but it throws an error if i place it in /css

include('lib/common.php');

$qGroups = Query('SELECT * from groups');

header('Content-type: text/css');

while($group = Fetch($qGroups)) {
    $idbit = $group['id'] >= 0 ? $group['id'] : 'x';
    
    echo '
.nc0'.$idbit.' { color: '.$group['color_male'].'; } .nc1'.$idbit.' { color: '.$group['color_female'].'; } .nc2'.$idbit.' { color: '.$group['color_unspec'].'; }
    ';
 }
 
?>