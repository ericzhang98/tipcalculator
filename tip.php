<!DOCTYPE html>

<html lang="en-US">

<head>
  <title>Tip Calculator</title>
  <!--link rel="stylesheet"
  href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"-->
</head>

<body style="background-color:gray">

<?php

$amount = NULL; //bill amount
$percentage = 10; //default percentage at 10%
$customPercentage = NULL; //custom percentage amount
$amountError = false; //bill amount error flag for bad input
$customError = false; //custom percentage error flag for bad input
//assume no error for input unless we get an error when processing

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
  $amount = $_POST["amount"];
  $percentage = $_POST["percentage"];
  $customPercentage = $_POST["customPercentage"];
  //set amount error if needed
  processAmount($amount);
  //set custom error if needed
  processCustomPercentage($customPercentage);
}

//error flagging for bill 
function processAmount($data) {
  if (isset($data) && !is_numeric($data) && !is_int($data) &&
        !is_float($data)) {
    $GLOBALS["amountError"] = true;
  }
  if ($data <= 0) {
    $GLOBALS["amountError"] = true;
  }
}

//error flagging for custom percentage
function processCustomPercentage($data) {
  //we only want to set customError if custom button is checked
  if ($GLOBALS["percentage"] == -1) {
    if (isset($data) && !is_numeric($data) && !is_int($data) &&
          !is_float($data)) {
      $GLOBALS["customError"] = true;
    }
    if ($data <= 0) {
      $GLOBALS["customError"] = true;
    }
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

  <p> Bill subtotal: $ <input type="text" name="amount" 
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
  echo '> Custom percentage';
  echo '<input type="text" name="customPercentage" value="', $customPercentage, '">%';
  if ($customError) {
    echo '<p style="color:red"> *Please enter a valid tip percentage</p>';
  }
  ?>
  <p> <button type="submit"> Submit </button> </p>

</form>

<?php
if (isset($amount) && !$amountError && !$customError) {
  echo "<h3>Result</h3>";
  echo "<p>Tip amount: $", calculateTip(), "<br>";
  echo "Total: $", $amount + calculateTip(), "</p>";

  if ($amount >= 1000) {
    echo "<p>Damn, that's a pretty hefty bill. Consider making a donation to ";
    echo "<a href='https://donate.doctorswithoutborders.org/'>a charity</a></p>";
  }
}
?>

</div>

</body>


</html>
