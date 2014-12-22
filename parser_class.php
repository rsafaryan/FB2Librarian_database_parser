<?php

/*
   * @return generated txt file [author.dat]
   * @param string $path [path to the folder for txt files]
   * @income txt file booklist.dat [from FB2 Librarian]
   * @author Rafael Safaryan
   * @recommendation - change depricated function eregi() to preg_match()
*/


class Parser {

  private $path;			// path from root to the folder for txt files
  private $inName;			// name of database file
  private $outName;			// name of created file


  // set variables
 
  public function __construct($path, $inName, $outName) {
    	if (!$path) $path = "/";
  	$this->path = $path;
  	if (!$inName) $inName = "booklist.dat";
  	$this->inName = $inName;
  	if (!$outName) $outName = "authors.dat";
  	$this->outName = $outName;
  }
 
 
 // read-convert txt database-file to array by line {could be abstract class itself for the future solutions}

 private function fileread($name) { 
   $filename = $this->path.$name;
   $fp = fopen($filename,"rt") or die("File $name is not exist");
   $field = explode("\n", fread($fp, filesize($filename)));
   fclose($fp);
   return $field;          					// array with lines
 }


 // create ean empty txt database-file for writing in it

 private function filewrite() {
   $filename = $this->path.$this->outName;
   $fp = fopen($filename,"wt");
   return $fp;
 }


 // create the list of all authors

 private function listCreate() {
   $field = $this->fileread($this->inName);				// array from txt database
   $fp = $this->filewrite();						// create an empty file
   for ($i = 0 ; $i < count($field) ; $i++) {
     $elements = explode(" - ", $field[$i]);
     $zapis = $elements[0];
     if ($i < (count($field)-1)) $zapis = $zapis."\n";
     $zapis = fwrite($fp, $zapis);
   }
   fclose($fp);
 }

 // rewrite the file with the cleaning of double/tripple/... books' authors (symbol • or ;  between athors used)

 private function listClean() {
   $field = $this->fileread($this->outName);				// array from txt database
   $fp = $this->filewrite();						// create an empty file
   for ($i = 0 ; $i < count($field) ; $i++) {
     if(!eregi(';',$field[$i]) or !eregi('•',$field[$i])) {
       $zapis = $field[$i]."\n";
       $zapis = fwrite($fp, $zapis);
      } else {
         $dubl = explode("; ", $field[$i]);
	 for ($k = 0; $k < count($dubl); $k++) {
  	    $zapis = $dubl[$k]."\n";
	    $zapis = fwrite($fp, $zapis);
	  }
      }
    }
    fclose($fp);
  }


  // creating sorted list without doubles

  private function doublesClean() {
    $field = $this->fileread($this->outName);				// array from txt database
    sort($field);
    $fp = $this->filewrite();						// create an empty file
    for ($i = 0 ; $i < count($field) ; $i++) {
       if (strcmp($field[$i], $field[$i-1]) != 0 and substr($field[$i], -3) != "..." ) {
          $zapis = $field[$i]."\n";
          $zapis = fwrite($fp, $zapis);
       }					
    }
    fclose($fp);
  }

  

// collaboration method of creating finnlized file

  private function finalList() {
    $this->listCreate();
    $this->listClean();
    $this->doublesClean();	
  }
 

 // print outgoing file
   
 public function printlList() {
    $field = $this->fileread($this->outName);				// array from txt database
    for ($i = 0 ; $i < count($field) ; $i++) echo $field[$i]."<br>";
 }
 
  
?>
