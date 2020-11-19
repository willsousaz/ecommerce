<?php

use \Ytech\Page; 

$app->get('/',function(){

	$page = new Page();

	$page->setTpl("index"); 

});


 ?>