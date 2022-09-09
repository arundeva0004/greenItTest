<?php

class Home {

	public function __construct() {
		echo "Hello World";
	}
}

function createHome(){
	$home = new Home();
}

createHome();