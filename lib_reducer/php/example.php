#!/usr/local/bin/php
<?php
require_once("libreducer.php");

class MyReducer extends BaseReducer{
	/**
	* Parse input line and output array($key, $value).
	* By default split line as column by "\t", key = first column, value = others
	*/
	/* public function parse($line){
		list($k, $v) = parent::parse($line);
		return array($k . "k", $v . "v");
	} */
	
	public function reduce($key, $values){
		foreach($values as $value){
			echo "key: " . $key."\tvalue: " . $value . "\n";
		} 
		//echo $key."\n";		
	}
}

run(new MyReducer());