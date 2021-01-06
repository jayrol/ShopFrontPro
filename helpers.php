<?php

/**
 * Print array in a readable format
 * @param array $array the array that needs to be displayed
 */
function display_array($array){
	echo "<pre>";
	print_r($array);
	echo "</pre>";
}

/**
 * var_dump array in a readable format
 * @param array $array the array that needs to be displayed
 */
function display_dump($array){
	echo "<pre>";
	var_dump($array);
	echo "</pre>";
}

?>