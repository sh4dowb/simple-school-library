<?php
function timeleft($realseconds){
    $seconds = abs($realseconds);
    if($seconds < 600)
       $text = "less than 1 hour";
    elseif($seconds < 86400)
       $text = floor($seconds / 3600) . " hour(s)";
    elseif($seconds < 86400 * 30)
       $text = floor($seconds / 86400) . " day(s)";
    elseif($seconds < 86400 * 30 * 7)
       $text = floor($seconds / 86400 * 30) . " week(s)";
    elseif($seconds < 86400 * 30 * 7 * 4)
       $text = floor($seconds / 86400 * 30 * 7) . " month(s)";
    if($realseconds < 0)
        $text .= " late";
    else
        $text .= " left";
    return $text;
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Library</title>

    <!-- Bootstrap core CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/1.10.18/css/jquery.dataTables.min.css" rel="stylesheet" crossorigin="anonymous">
  </head>

  <body>

    <header>
      <!-- Fixed navbar -->
      <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="index.php">Library</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item<?=(basename($_SERVER['SCRIPT_FILENAME']) == "index.php" ? " active" : "")?>">
              <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item<?=(basename($_SERVER['SCRIPT_FILENAME']) == "books.php" ? " active" : "")?>">
              <a class="nav-link" href="books.php">Books</a>
            </li>
            <li class="nav-item<?=(basename($_SERVER['SCRIPT_FILENAME']) == "members.php" ? " active" : "")?>">
              <a class="nav-link" href="members.php">Students</a>
            </li>
            <li class="nav-item<?=(basename($_SERVER['SCRIPT_FILENAME']) == "borrowers.php" ? " active" : "")?>">
              <a class="nav-link" href="borrowers.php">Borrows</a>
            </li>
            <li class="nav-item<?=(basename($_SERVER['SCRIPT_FILENAME']) == "oldborrowers.php" ? " active" : "")?>">
              <a class="nav-link" href="oldborrowers.php">Borrows (history)</a>
            </li>
            <li class="nav-item<?=(basename($_SERVER['SCRIPT_FILENAME']) == "statistics.php" ? " active" : "")?>">
              <a class="nav-link" href="statistics.php">Statistics</a>
            </li>
          </ul>
        </div>
      </nav>
    </header>
