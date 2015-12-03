<?php

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

// Default values
$courses = array(
  array("name"=>"Projekti", "op"=>"20" , "wload"=>"200"),
  array("name"=>"Algoritmit", "op"=>"2" , "wload"=>"40"),
  array("name"=>"Videot", "op"=>"5" , "wload"=>"20"),
  array("name"=>"Keikka", "op"=>"1" , "wload"=>"10"),
  array("name"=>"Ohjelmointi", "op"=>"9" , "wload"=>"90"),
  array("name"=>"Lopputyö", "op"=>"17" , "wload"=>"100"),
);

// Default amount
$time = 200;

// Reset message
$resetmsg = '<p style="float: right;">Form has defaut values!</p>';

if (!($_GET['enabled'][0] == NULL)) {
  // Get depth of array
  $e = array_keys($_GET['name']);
  $getvalue = array();
  $i = 0;
  foreach ($e as $key) {
    $getvalue[] = array("name"=>$_GET['name'][$i], "op"=>$_GET['op'][$i] , "wload"=>$_GET['time'][$i]);
    $i++;
  }
  /* Override default values*/
  $courses = $getvalue;

  /* Get the limit */
  $time = $_GET['limit'][0];

  /* Hide reset message */
  $resetmsg = '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>My work load</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <link rel="stylesheet" href="http://getbootstrap.com/examples/sticky-footer/sticky-footer.css">
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
</head>
<body>
<!-- Begin page content -->
<div class="container">
  <div class="page-header">
    <h1>My Work load optimator</h1>
  </div>

  <p>This application is used to solve the knapsack problem and create a UI for it. The source for the function is copied from here: <a target="_blank" href="http://rosettacode.org/wiki/Knapsack_problem/0-1">http://rosettacode.org/wiki/Knapsack_problem/0-1</a></p>
  <p>I/0 Selects if you want include the row to the results array. Rest of the fields are editable.</p>
  <p>Enjoy your clicking!</p>

  <?php
  echo "<b>Initial infomation:</b>";
  echo "<form id='myform' method='GET' action='index.php'>";
  echo "<table id='mytable'>";
  echo "<tr><th>I/0</th><th>Course</th><th>Credits</th><th>Time</th></tr>";

  // Counter reset
  $i = 0;
  // Print courses array
  foreach($courses as $key) {
    $i++;
    echo "<tr><td><input class='row-". $i ."' type='checkbox' checked='checked' name='enabled[]'></td>
            <td><input class='field row-". $i ."' name='name[]' type='text' value='".$key['name']."' /></td>
            <td><input class='field row-". $i ."' name='op[]'  type='number' value='".$key['op']."' /></td>
            <td><input class='field row-". $i ."' name='time[]' type='number' value='".$key['wload']."' />
            </td></tr>";
  }
  echo "</table>";
  echo "<span>Time limit:</span>";
  echo "<input class='' name='limit[]' type='number' value='".$time."' />";
  echo "<br>";
  echo "<br>";
  echo "<input type='submit' value='Optimize'>";
  echo "<button id='add-row' onclick='addrow();' type='button'>+ Add row +</button>";

  /* Reset  handling*/
  if(empty($resetmsg)) {
    echo "<a class='reset' href='index.php'>Reset values</a>";
  } else {
    echo $resetmsg;
  }
  echo "</form><hr>";
  echo '<br>';

  //values are submitted
  if (!empty($_GET)){

    # Function arguments
    # course names
    $items4 = $_GET['name'];
    # $w = weight of item (time)
    $w4 = $_GET['time'];
    # $v = value of item (op)
    $v4 = $_GET['op'];
    # $i = index
    $i = sizeof($v4);
    # $aW = Available Weight
    $aW = $_GET['limit'][0];
    ## Initialize $m = Memo items array
    $numcalls = 0; $m = array(); $pickedItems = array();

    ## Solve
    list ($m4,$pickedItems) = knapSolveFast2($w4, $v4, $i -1, $aW,$m,$pickedItems);

    if(isset($pickedItems)){
      //Correct answer
      echo "<b>Optimized Courses selection:</b><br>";
      echo "<table id='results'>";
      echo "<tr><th>Course</th><th>Credits</th><th>Time</th></tr>";
      $totalVal = $totalWt = 0;
      foreach($pickedItems as $key) {
        $totalVal += $v4[$key];
        $totalWt += $w4[$key];
        echo "<tr><td>".$items4[$key]."</td><td>".$v4[$key]."</td><td>".$w4[$key]."</td></tr>";
      }
      echo "<tr><td><b>Total</b></td><td>$totalVal</td><td>$totalWt</td></tr>";
      echo "</table><hr>";
    }
  }
  ?>
</div>

<footer class="footer">
  <div class="container">
    <p class="text-muted">My work load optimator project by Onni Aaltonen. Source availble at <a href="https://github.com/onnia/Workload-Optimization" target="_blank" >Github</a>.</p>
  </div>
</footer>
<script src="js/custom.js"></script>
</body>
</html>
