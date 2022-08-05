<!DOCTYPE html>
<html>

<head>
  <title>Races Info</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body class="body1">
  <header class="page-header">
    <h1>Formula 1 Database Application </h1>
  </header>
  <p text-align='left'>Return to Mainpage:
    <a href="https://www.students.cs.ubc.ca/~douxinyi/m4/MainUI.php">
      <button>Back</button>
    </a>
  </p>

  <h2 class="operations">Browse races information by:</h2>
  <form method="POST" action="Projection.php">
    <input type="hidden" id="ProjectQueryRequest" name="ProjectQueryRequest">
    <input type="checkbox" name= "raceInfo[]"  value="race_date" > Race Date <br/>
    <input type="checkbox" name= "raceInfo[]"  value="race_name" > Race Name <br/>
    <input type="checkbox" name= "raceInfo[]" value="round_number" > Round Numbers <br/>
    <input type="checkbox" name= "raceInfo[]" value="lap_numbers"> Lap Numbers <br/>
    <input type="checkbox" name= "raceInfo[]" value="circuit_name"> Circuit Name <br/>
    <br>

    <input type="submit" value="Project" name="ProjectSubmit">
</form>



  <br>

  <?php


  $success = True; 
  $db_conn = NULL; 
  $show_debug_alert_messages = False; 
  $raceInfo = array();

  function debugAlertMessage($message)
  {
    global $show_debug_alert_messages;

    if ($show_debug_alert_messages) {
      echo "<script type='text/javascript'>alert('" . $message . "');</script>";
    }
  }

  function executePlainSQL($cmdstr)
  {
    global $db_conn, $success;

    $statement = OCIParse($db_conn, $cmdstr);

    if (!$statement) {
      echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
      $e = OCI_Error($db_conn); 
      echo htmlentities($e['message']);
      $success = False;
    }

    $r = OCIExecute($statement, OCI_DEFAULT);
    if (!$r) {
      echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
      $e = oci_error($statement); 
      echo htmlentities($e['message']);
      $success = False;
    }

    return $statement;
  }


  function printResult($result)
  { //prints results: Projection Table
    global $raceInfo;
    echo "<br><h3><font color='#2d4cb3'>Projected Race Record :</h3>";
    echo "<table class='center'>";
    
    foreach($raceInfo as $title){
      echo "<th><p align='center';color='#2d4cb3';>"
     . $title . "</p></th>";
    }

    while ($array = oci_fetch_row($result)) {
      echo "<tr>";
      foreach($array as $row){
      echo "<td><p align='center';>"
      . $row . "</p></td>";
    }
      echo "</tr>"; 
    }
    echo "</table>";
  }

  function connectToDB()
  {
    global $db_conn;
    //change to your cwl and password
    $db_conn = OCILogon("ora_qdkaiyu", "a36591923", "dbhost.students.cs.ubc.ca:1522/stu");
    if ($db_conn) {
      debugAlertMessage("Database is Connected");
      return true;
    } else {
      debugAlertMessage("Cannot connect to Database");
      $e = OCI_Error(); // For OCILogon errors pass no handle
      echo htmlentities($e['message']);
      return false;
    }
  }

  function disconnectFromDB()
  {
    global $db_conn;

    debugAlertMessage("Disconnect from Database");
    OCILogoff($db_conn);
  }

  function handleProjectRequest()
  {
    global $db_conn;

    if(isset($_POST['raceInfo'])){
      global $raceInfo;
      $raceInfo = $_POST['raceInfo'];
      $selectStatement = "SELECT ";
      for($i=0; $i<count($raceInfo); $i++){
       //echo "<th>&nbsp;". $raceInfo[$i] . "&nbsp;</th>";
       $selectStatement = $selectStatement. $raceInfo[$i] .", ";
      }
  
      $selectStatement = substr($selectStatement, 0, -2);
      $selectStatement = $selectStatement ." FROM RacesTakePlace";
    
      $result = executePlainSQL($selectStatement);
      printResult($result);
      
  }
      OCICommit($db_conn);
    
  }


  function handlePOSTRequest()
  {
    if (connectToDB()) {

      if (array_key_exists('ProjectQueryRequest', $_POST)) {
        handleProjectRequest();
      } 

      disconnectFromDB();
    }
  }


  if (isset($_POST['ProjectSubmit'])) {
    handlePOSTRequest();
  }

  ?>
</body>

</html>