<?php
class ValuesIterator implements Iterator{
	private $last_key;
	private $current_key;
	private $current_value;
	private $is_newline;
	private $is_eof;
	
	public function __construct($reducer){
		$this->last_key = "\t";
		$this->current_key = "\t";
		$this->is_newline = false;
		$this->is_eof = false;
		$this->reducer = $reducer;
	}
	public function next_key(){
		if(!$this->is_newline){	
			do{
				$this->next();
			}while(!$this->is_eof && $this->last_key === $this->current_key);
			$this->is_newline = false;
		}
		return !$this->is_eof;
	}
	
	
	
	public function rewind() { 
		$this->last_key = $this->current_key;
		$this->is_newline = false;
	}

    public function current() {  
		return $this->current_value;
	}

    public function key() { 
		return $this->current_key;
	}

    public function next() {  
		do{
			$in = fgets(STDIN);
			if($in === false) {
				$this->is_eof = true;
				return;
			}
			$in = trim($in);
		}while($in === "");
		list($key, $value) = $this->reducer->parse($in);
		$this->last_key = $this->current_key;
		$this->current_key = $key;
		$this->current_value = $value;
		//echo $this->last_key . ":" . $this->current_key ."\n";
	}		

    public function valid() { 
		if($this->last_key !== "\t" && $this->last_key !== $this->current_key){		
			$this->is_newline = true;
			return false;
		}
		return !$this->is_eof;
		//return $this->is_valid;
	} 
}

abstract class BaseReducer{
	public function setup(){}
	public abstract function reduce($key, $values);
	public function cleanup(){}
	public function parse($line){
		return explode("\t", $line, 2);
	}
}

function run(BaseReducer $reducer){
	$values = new ValuesIterator($reducer);
	while(!feof(STDIN)){
		if(!$values->next_key()) break;
		$reducer->reduce($values->key(), $values);
	}

}

