<?php
if(validation_errors())
{
	echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">×</button>';
	echo validation_errors();
	echo '</div>';
}?>

