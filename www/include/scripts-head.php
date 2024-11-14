<?php 
$scriptsHead = $Obj->scriptsHead(); 
if ($scriptsHead) {
    foreach($scriptsHead as $sh) {
        echo $sh['scr_head'];
    }
}
?>