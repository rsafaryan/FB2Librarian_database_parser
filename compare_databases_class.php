<?php
/*
   * @return generated txt file with the list of the athours which found only in one file $outName [vs.dat]
   * @param string $path [path to the folder for txt files]
   * @name1 first txt list [authors1.dat]
   * @name2 second txt list [authors1.dat]
   * @author Rafael Safaryan
*/


class Comparer {

 private $name1;			// name of first file
 private $name2;			// name of second file
 private $path;				// path from root to the folder for txt files
 private $outName;			// name of created file

// construct

 public function __construct($path, $name1, $name2, $outName) {
    	if (!$path) $path = "/";
  	$this->path = $path;
  	if (!$name1) $name1 = "authors1.dat";
  	$this->name1 = $name1;
  	if (!$name2) $name2 = "authors2.dat";
  	$this->name2 = $name2;
  	if (!$outname) $outName = "vs.dat";
  	$this->outName = $outName;
  }


// cut familynames

private function reverse_strrchr($haystack, $needle) {
  return strrpos($haystack, $needle) ? substr($haystack, 0, strrpos($haystack, $needle) +0 ) : false;
}

// read file

private function fileread($name) { 
   $filename = $this->path.$name;
   $fp = fopen($filename,"rt") or die("File $name is not exist");
   $field = explode("\n", fread($fp, filesize($filename)));
   fclose($fp);
   for ($i = 0 ; $i < count($field) ; $i++) {
	$field[$i] = $this->reverse_strrchr($field[$i], ",");
   }
   return $field;          					// array with only families
 }

//compare two arrays and remove double families

private function arraycompared() {
  $field1 = $this->fileread($this->name1);
  $field2 = $this->fileread($this->name2);
  $result = array_intersect($field1, $field2);
  $result = array_unique($result);				// remove repeated faimiles
  sort ($result);
  return $result;						// array with unique families
}

//create file with selected authors

public function createlist() {
  $fp = fopen(($this->path.$this->outName),"wt");
  $res = $this->arraycompared();
  for ($i = 0 ; $i < count($res) ; $i++) {
    $zapis = $res[$i]."\n";
    $zapis = fwrite($fp, $zapis);
   }
   fclose($fp);
}

?>
