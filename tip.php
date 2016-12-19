<!DOCTYPE html>

<html lang="en-US">

<head>
  <title>Tip Calculator</title>
</head>

<body style="background-color:gray">

<?php

$amount = NULL; //bill amount
$percentage = 10; //default percentage at 10%
$customPercentage = NULL; //custom percentage amount
$split = 1; //default split at 1
//error flags
//assume no error for input unless we get an error when processing
$amountError = false; 
$customError = false; 
$splitError = false;

function calculateTip() {
  //if -1, then use customPercentage
  if ($GLOBALS["percentage"] == -1) {
    return $GLOBALS["amount"] * $GLOBALS["customPercentage"] / 100;
  }

  //otherwise just return regular amount
  return $GLOBALS["amount"] * $GLOBALS["percentage"] / 100;
}

//handle form post request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $amount = str_replace(",", "", $_POST["amount"]);
  $percentage = $_POST["percentage"];
  $customPercentage = $_POST["customPercentage"];
  $split = str_replace(",", "", $_POST["split"]);
  //set amount, custom, and split error if needed
  processAmount($amount);
  processCustomPercentage($customPercentage);
  processSplit($split);
}

//error flagging for bill 
function processAmount($data) {
  if ((isset($data) && !is_numeric($data)) || $data <= 0) {
    $GLOBALS["amountError"] = true;
  }
}

//error flagging for custom percentage
function processCustomPercentage($data) {
  //we only want to set customError if custom button is checked
  if ($GLOBALS["percentage"] == -1) {
    if ((isset($data) && !is_numeric($data)) || $data <= 0) {
      $GLOBALS["customError"] = true;
    }
  }
}

//error flagging for split
function processSplit($data) {
  //check if it's greater than 0 and is an int
  if ((isset($data) && !is_numeric($data)) || $data <= 0 || 
      (floatval($data) != intval($data))) {
    $GLOBALS["splitError"] = true;
  }
}

?>

<div style="text-align:center; background-color:yellow; 
margin:50px 30%; padding-top:20px; padding-bottom:30px; 
border-style:solid; border-width: 3px; border-color:green">

<h1> Tip Calculator </h1>
<br>

<form method="post" action="<?php 
  echo htmlspecialchars($_SERVER["PHP_SELF"]);
?>">

  <p> Bill subtotal: $ <input type="text" name="amount" style="width:50px"
  value="<?php echo $amount; ?>"> 
  <?php
  if ($amountError) {
    echo '<p style="color:red"> *Please enter a valid bill</p>';
  }
  ?>
  </p>

  <p>Tip percentage:</p>

  <?php 
  //show the radio buttons for 10, 15, 20%
  for ($i = 10; $i <= 20; $i+=5) {
    echo '<input type="radio" name="percentage" value="', $i, '"';
    if ($i == $percentage) {
      echo "checked";
    }
    echo '> ', $i, '%';
  }

  //show the custom tip radio button
  echo "<br>";
  echo '<input type="radio" name="percentage" value="-1"';
  if ($percentage == -1) {
   echo "checked";
  } 
  echo '> Custom percentage: ';
  echo '<input type="text" name="customPercentage" style="width:30px" value="', $customPercentage, '">%';
  if ($customError) {
    echo '<p style="color:red"> *Please enter a valid tip percentage</p>';
  }

  //show the split option
  echo '<p>Number of people: ';
  echo '<input type="text" name="split" style="width:30px" value="', $split, '">';
  if ($splitError) {
    echo '<p style="color:red"> *Please enter a valid number of people</p>';
  }


  ?>
  <p> <button type="submit"> Submit </button> </p>

</form>

<?php
//SHOW END TIP RESULT
if (isset($amount) && !$amountError && !$customError && !$splitError) {
  echo "<h3>Result</h3>";
  echo "<p>Tip amount: $", calculateTip(), "<br>";
  echo "Total: $", $amount + calculateTip(), "</p>";

  //show split tip results if more than 1 person splitting
  if (!$splitError && $split > 1) {
    $splitTip = ceil(calculateTip()/$split*100)/100;
    $splitTotal = ceil(($amount + calculateTip())/$split*100)/100;
    echo "<h3>Splitting</h3>";
    echo "<p>Split tip amount: $", $splitTip, "<br>";
    echo "Split total: $", $splitTotal, "</p>";
  }

  if ($amount >= 1000) {
    echo "<p>Damn, that's a pretty hefty bill. Consider making a donation to ";
    echo "<a href='https://donate.doctorswithoutborders.org/'>a charity</a></p>";
  }
}
?>

</div>

</body>


</html>
