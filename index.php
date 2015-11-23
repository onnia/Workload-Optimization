<?php session_start();
						
			#########################################################
# 0-1 Knapsack Problem Solve with memoization optimize and index returns
# $w = weight of item
# $v = value of item
# $i = index
# $aW = Available Weight
# $m = Memo items array
# PHP Translation from Python, Memoization,
# and index return functionality added by Brian Berneker
#
#It works uncorrectly! For examle if $aw=4. Max value is true, but no "Array Indices" and its parameters are displayed
#
#########################################################

function knapSolveFast2($w, $v, $i, $aW, &$m, &$pickedItems) {
 
	global $numcalls;
	$numcalls ++;
	// echo "Called with i=$i, aW=$aW<br>";
 
	// Return memo if we have one
	if (isset($m[$i][$aW])) {
		return array( $m[$i][$aW], $m['picked'][$i][$aW] );
	} else {
 
		// At end of decision branch
		if ($i == 0) {
			if ($w[$i] <= $aW) { // Will this item fit?
				$m[$i][$aW] = $v[$i]; // Memo this item
				$m['picked'][$i][$aW] = array($i); // and the picked item
				return array($v[$i],array($i)); // Return the value of this item and add it to the picked list
 
			} else {
				// Won't fit
				$m[$i][$aW] = 0; // Memo zero
				$m['picked'][$i][$aW] = array(); // and a blank array entry...
				return array(0,array()); // Return nothing
			}
		}	
 
		// Not at end of decision branch..
		// Get the result of the next branch (without this one)
		list ($without_i,$without_PI) = knapSolveFast2($w, $v, $i-1, $aW,$m,$pickedItems);
 
		if ($w[$i] > $aW) { // Does it return too many?
 
			$m[$i][$aW] = $without_i; // Memo without including this one
			$m['picked'][$i][$aW] = array(); // and a blank array entry...
			return array($without_i,array()); // and return it
 
		} else {
 
			// Get the result of the next branch (WITH this one picked, so available weight is reduced)
			list ($with_i,$with_PI) = knapSolveFast2($w, $v, ($i-1), ($aW - $w[$i]),$m,$pickedItems);
			$with_i += $v[$i];  // ..and add the value of this one..
 
			// Get the greater of WITH or WITHOUT
			if ($with_i > $without_i) {
				$res = $with_i;
				$picked = $with_PI;
				array_push($picked,$i);
			} else {
				$res = $without_i;
				$picked = $without_PI;
			}
 
			$m[$i][$aW] = $res; // Store it in the memo
			$m['picked'][$i][$aW] = $picked; // and store the picked item
			return array ($res,$picked); // and then return it
		}	
	}
}

if (!isset($_SESSION['courses'])){
// Unset all of the session variables.
// orginal data
$courses = array(
				array("name"=>"Projekti", "op"=>"20" , "wload"=>"200"),
				array("name"=>"Algoritmit", "op"=>"2" , "wload"=>"40"),
				array("name"=>"Videot", "op"=>"5" , "wload"=>"20"),
				array("name"=>"Keikka", "op"=>"1" , "wload"=>"10"),
				array("name"=>"Ohjelmointi", "op"=>"9" , "wload"=>"90"),
				array("name"=>"Lopputyö", "op"=>"17" , "wload"=>"100"),
			);
			
			$_SESSION = $courses;
}

//var_dump($_SESSION);

 $courses = $_SESSION; 

 for($i = 0; $i < count($courses); $i++) { 
	$items4[] = $courses[$i]['name']; 
	$v4[] = $courses[$i]['op']; 
	$w4[] = $courses[$i]['wload']; 
 }
// time amout 
$time = 200; 
//$items4 = array("Projekti","Algoritmit","Videot","Keikka","Ohjelmointi", "Lopputyö");
//$w4 = array(200,40,20,10,90,100);
//$v4 = array(20,2,5,1,9,17);
 
## Initialize
$numcalls = 0; $m = array(); $pickedItems = array();
 
# Funtion arguments
# $w = weight of item
# $v = value of item
# $i = index
# $aW = Available Weight
# $m = Memo items array
 
## Solve
list ($m4,$pickedItems) = knapSolveFast2($w4, $v4, sizeof($v4) -1, $time,$m,$pickedItems);
 ?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <title>My work load</title>

  <!-- Bootstrap -->
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
  <!-- Optional theme -->
  <link rel="stylesheet" href="http://getbootstrap.com/examples/sticky-footer/sticky-footer.css">

  <!-- Latest compiled and minified JavaScript -->
  <!-- <script src="js/jquery-2.1.4.min.js"></script> -->
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script src="js/jquery.cookie.js" type="text/javascript"></script>
</head>
<body>
<!-- Begin page content -->
<div class="container">
  <div class="page-header">
    <h1>My Work load optimator</h1>
  </div>

  <?php
  # Input values
  echo "<b>Alkuperäinen data:</b>";
  echo "<form <input type='submit' value='Submit'>";
  echo "<table border cellspacing=0>";
  echo "<tr><td>Item</td><td>OP</td><td>Time</td></tr>";

  foreach($courses as $key) {
    echo "<tr><td><input name='name' type='text' value='".$key[name]."' /></td><td><input name='op'  type='text' value='".$key[op]."' /></td><td><input name='time' type='text' value='".$key['wload']."' /></td></tr>";
  }

  echo "</table>";
  echo "<br>";

  echo "<input type='submit' value='Submit'>";
  echo "</form><hr>";

  // TODO: Solve how to get all params from url
  foreach($_GET as $key => $value){
    echo $key . " : " . $value . "<br />\r\n";
  }

  //Correct anwser
  echo "<b>Valitut kurssit:</b><br>";
  echo "<table border cellspacing=0>";
  echo "<tr><td>Kurssi</td><td>OP</td><td>Time</td></tr>";
  $totalVal = $totalWt = 0;
  foreach($pickedItems as $key) {
    $totalVal += $v4[$key];
    $totalWt += $w4[$key];
    echo "<tr><td>".$items4[$key]."</td><td>".$v4[$key]."</td><td>".$w4[$key]."</td></tr>";
  }
  echo "<tr><td align=right><b>Yhteensä</b></td><td>$totalVal</td><td>$totalWt</td></tr>";
  echo "</table><hr>";

  ?>

  </div>
</div>

<footer class="footer">
  <div class="container">
    <p class="text-muted">My work load optimator</p>
  </div>
</footer>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>-->
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script src="custom.js"></script>
</body>

</body>
</html>
