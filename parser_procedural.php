<?php

/*
   * @return generated txt file author.dat
   * @param string $path [path to the folder for txt files]
   * @income tst file booklist.dat [from FB2 Librarian]
   * @author Rafael Safaryan
   * @recommendation - change depricated function eregi() to preg_match()
*/


function fileread($path, $name="authors.dat") { 
   $filename = $path.$name;
   $fp = fopen($filename,"rt");
   or die("File $name is not exist");
   $field = explode("\n", fread($fp, filesize($filename)));
   fclose($fp);
   return $field;           // array with lines
}

function filewrite($path, $name="authors.dat") { 
   $filename = $path.$name;
   $fp = fopen($filename,"wt");
   return $fp;
}

function fb2parser($path) {

  if (!$path) $path = "/";
  
 // read the income file
  $name = "booklist.dat";
  $field = fileread($path, $name);

  // create the list of authors in authors.dat file
  $fp = filewrite($path);
  for ($i = 0 ; $i < count($field) ; $i++) {
    $elements = explode(" - ", $field[$i]);
    $zapis = $elements[0];
    if ($i < (count($field)-1)) $zapis = $zapis."\n";
  	$zapis = fwrite($fp, $zapis);
  }
  fclose($fp);
  
  // rewrite the file with the cleaning of double/tripple/... books' authors (symbol • or ;  between athors used)
  $field = fileread($path);
  $fp = filewrite($path);
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
  
  
  // creating and printing sorted final list without doubles
  $field = fileread($path);
  sort($field);
  $fp = filewrite($path);
  for ($i = 0 ; $i < count($field) ; $i++) {
    if (strcmp($field[$i], $field[$i-1]) != 0 and substr($field[$i], -3) != "..." ) {
      $zapis = $field[$i]."\n";
      $zapis = fwrite($fp, $zapis);
      echo $field1[$i]."<br>";
    }					
  }
  fclose($fp);


}

?>
