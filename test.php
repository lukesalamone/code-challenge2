<?php
	
	class Test{
	
		private $data;
		private $header;
		private $output;
	
		function __construct($filename){
			echo "Initialized new Test class.\n";
			$this->data = [];
			
			// for post-processed data
			$this->output = [];
			
			
			// digest input file
			$temp = file($filename, FILE_IGNORE_NEW_LINES);
			$this->header = array_shift($temp);
			
			foreach($temp as $value){
				$line = explode("\t", $value);
				
				// ignore malformed input lines
				if(count($line) === 5) array_push($this->data, $line);
			}
			
			echo sprintf("Found %s lines of data in %s.\n", count($this->data), $filename);
		}
		
		// custom filter function may be specified
		function process(callable $filter){
			$this->output = array_map($filter, $this->data);
		}
		
		function sort(callable $sort_method){
			usort($this->output, $sort_method);
		}
		
		function output($filename){
			$str = $this->header . "\tTERM";
			
			foreach($this->output as $line){
				$str .= "\n" . implode("\t", $line);
			}
			
			$write = file_put_contents($filename, $str);
			
			if($write !== false){
				echo sprintf("Wrote %s bytes to %s.\n", $write, $filename);
			} else {
				echo "Error writing file.\n";
			}
		}
	
	}// end Test class
	
	// example user-defined sort
	$comparator = function($a, $b){
		if(gettype($a) !== "array" || gettype($b) !== "array") return 0;
		$values = ["M"=>1, "D"=>2, "L"=>3];
		$valA = $values[$a[3]];
		$valB = $values[$b[3]];
		
		return $valA - $valB;
	};
	
	
	// converts IDs as xxx-xx-1234,
	// removes special characters from names,
	// and adds term field
	$filter = function($line){
		if(count($line) !== 5) return;
		$id = (string)$line[0];
		$fname = $line[1];
		$lname = $line[2];
		
		// check invalid id length
		if(strlen($id) !== 9){
			echo sprintf("Invalid id length %s.\n", strlen($id));
			return;
		}
		
		$line[0] = "xxx-xx-" . substr($id, 5);
		$line[1] = preg_replace(['/[^\w \-]+/', '!\s+!'], ["", " "], $fname);
		$line[2] = preg_replace(['/[^\w \-]+/', '!\s+!'], ["", " "], $lname);
		
		// add TERM field
		$timestamp = strtotime((string)$line[4]) + 100*24*60*60;
		
		while(date("l", $timestamp) === "Saturday" || date("l", $timestamp) === "Sunday"){
			$timestamp += 24*60*60;
		}
		
		array_push($line, date("Ymd", $timestamp));
		return $line;
	};
	
	// execute the functions in the Test class to output manipulated data
	$test = new Test("test_data_in.txt");
	$test->process($filter);
	$test->sort($comparator);
	$test->output("test_data_out.txt");
	

	
?>
