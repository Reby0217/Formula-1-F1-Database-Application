<!DOCTYPE html>
<html>

<head>
  <title>Insert circuits </title>
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

  <h2 class="operations">Adding new Circuits</h2>
  <form method="POST" action="InsertAndDeleteUI.php">
    <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
    Circuit Name: <input type="text" name="circuit_name"> <br /><br />
    <p>Leave below entries empty if you do not have detailed information.</p>
    City: <input type="text" name="city"> Country: <input type="text" name="country"> <br /><br />
    Longitude: <input type="real" name="longitude"> Latitude: <input type="real" name="latitude"> <br /><br />
    <input type="submit" value="Insert" name="insertSubmit"></p>

  </form>

  <h2 class="operations">Delete Circuit</h2>
  <form method="POST" action="InsertAndDeleteUI.php">
    <input type="hidden" id="deleteQueryRequest" name="deleteQueryRequest">
    Delete Circuit Name: <input type="text" name="circuit_name"> <br /><br />
    <input type="submit" value="Delete" name="deleteSubmit"></p>
  </form>

  <br>

  <?php


  $success = True; 
  $db_conn = NULL; 
  $show_debug_alert_messages = False; 

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

  function executeBoundSQL($cmdstr, $list)
  {
    global $db_conn, $success;
    $statement = OCIParse($db_conn, $cmdstr);

    if (!$statement) {

      echo "<h2 style='color:red;'> Insert failed, please make sure input type is correct. </h2>" ;
      $e = OCI_Error($db_conn);
      echo htmlentities($e['message']);
      $success = False;
    }

    foreach ($list as $tuple) {
      foreach ($tuple as $bind => $val) {
        OCIBindByName($statement, $bind, $val);
        unset($val); 
      }

      $r = OCIExecute($statement, OCI_DEFAULT);
      if (!$r) {
        echo "<h2 style='color:red;'> Insert failed, please make sure input type is correct. </h2>" ;
        $e = OCI_Error($statement); 
        echo "<br>";
        $success = False;
      }
    }
  }

  function printResult($result)
  { //prints results from a select statement
    echo "<br><h3><font color='#2d4cb3'>Retrieved data from table Circuit:</h3>";
    echo "<table class='center'>";
    echo "<tr>
    <th><font color='#2d4cb3'>City</th>
    <th><font color='#2d4cb3'>Circuit Name</th>
    <th><font color='#2d4cb3'>Country</th>
    <th><font color='#2d4cb3'>Longitude</th>
    <th><font color='#2d4cb3'>Latitude</th>
    </tr>";

    while ($row = oci_fetch_row($result)) {
      echo "<tr>
      <td><p align='center';>" . $row[0] . "</p></td>
      <td><p align='center';>" . $row[1] . "</p></td>
      <td><p align='center';>" . $row[2] . "</p></td>
      <td><p align='center';>" . $row[3] . "</p></td>
      <td><p align='center';>" . $row[4] . "</p></td>
      </tr>"; 
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

  function handleInsertRequest()
  {
    global $db_conn;
    $entryStatus = TRUE;

    //Getting the values from user and insert data into the table
    $tuple = array(
      ":city" => $_POST['city'],
      ":circuit_name" => $_POST['circuit_name'],
      ":country" => $_POST['country'],
      ":longitude" => $_POST['longitude'],
      ":latitude" => $_POST['latitude']
    );

    $circuitTuples = array(
      $tuple
    );

    if (in_array('', $tuple, true)) {
      $entryStatus = FALSE;
      $missingArray = array();
      foreach ($tuple as $bind => $value) {
        if (empty($value)) {
            array_push($missingArray, $bind);
        }
    }
    $result = preg_replace('/[^a-zA-Z0-9_ -]/s','',$missingArray);
      echo "<h2 style='color:red;'> Please fill " .implode("','",$result)."'<br> </h2>" ;;
    
  }

    if ($entryStatus) {
      executeBoundSQL("INSERT INTO Circuit_2 VALUES (:city, :circuit_name, :country, :longitude, :latitude)", $circuitTuples);
      OCICommit($db_conn);
    }
  }

  function handleDeleteRequest()
  {
    global $db_conn;

    //Getting the values delete from table
    $delete_name = $_POST['circuit_name'];
    $ans = executePlainSQL("SELECT circuit_name 
                          FROM Circuit_2
                          WHERE circuit_name = '" . $delete_name . "'");
    $row = oci_fetch_row($ans);
    if ($row == false) {
      echo "<h2 style='color:red;'> Delete failed, circuit does not exist. </h2>" ;
      echo "<br>";
    }
    $row = executePlainSQL("DELETE FROM Circuit_2
                          WHERE circuit_name = '" . $delete_name . "'");
    OCICommit($db_conn);
  }


  function handlePOSTRequest()
  {
    if (connectToDB()) {

      if (array_key_exists('deleteQueryRequest', $_POST)) {
        handleDeleteRequest();
      } else if (array_key_exists('insertQueryRequest', $_POST)) {
        handleInsertRequest();
      }
      $result = executePlainSQL('SELECT * FROM Circuit_2');
      printResult($result);
      disconnectFromDB();
    }
  }

  if (isset($_POST['deleteSubmit']) || isset($_POST['insertSubmit'])) {

    handlePOSTRequest();
  }

  ?>
</body>

</html>