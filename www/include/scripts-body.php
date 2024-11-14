<?php $scriptsBody = $Obj->scriptsBody(); ?>
<?php 
$scriptsBody = $Obj->scriptsBody(); 
if ($scriptsBody) {
    foreach($scriptsBody as $sb) {
        echo $sb['scr_body'];
    }
}
?>