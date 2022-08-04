<!DOCTYPE html>
<html>

<head>
  <title>Insert circuits </title>
  <link rel="stylesheet" href="styles.css">
</head>

<body class = "body1">
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
    //Below code refers to oracle-test.txt provided in tutorial 7
		//this tells the system that it's no longer just parsing html; it's now parsing PHP

$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = NULL; // edit the login credentials in connectToDB()
$show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

function debugAlertMessage($message) {
   global $show_debug_alert_messages;

   if ($show_debug_alert_messages) {
    echo "<script type='text/javascript'>alert('" . $message . "');</script>";
  }
}

function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
  //echo "<br>running ".$cmdstr."<br>";
  global $db_conn, $success;

  $statement = OCIParse($db_conn, $cmdstr);
  //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

  if (!$statement) {
      echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
      $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
      echo htmlentities($e['message']);
      $success = False;
  }

  $r = OCIExecute($statement, OCI_DEFAULT);
  if (!$r) {
      echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
      $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
      echo htmlentities($e['message']);
      $success = False;
  }

return $statement;
}

function executeBoundSQL($cmdstr, $list) {
  /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
In this case you don't need to create the statement several times. Bound variables cause a statement to only be
parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
See the sample code below for how this function is used */

global $db_conn, $success;
$statement = OCIParse($db_conn, $cmdstr);

  if (!$statement) {
      //echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
      echo "<br><font color='#db2c20'><br />&nbsp;Insert failed, please make sure input type is correct.</font><br>";
      $e = OCI_Error($db_conn);
      echo htmlentities($e['message']);
      $success = False;
  }

  foreach ($list as $tuple) {
      foreach ($tuple as $bind => $val) {
          //echo $val;
          //echo "<br>".$bind."<br>";
          OCIBindByName($statement, $bind, $val);
          unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
}

      $r = OCIExecute($statement, OCI_DEFAULT);
      if (!$r) {
         //echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
          echo "<br><font color='#db2c20'><br />&nbsp;Insert failed, please make sure input type is correct.</font><br>";
          $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
          //echo htmlentities($e['message']);
          echo "<br>";
          $success = False;
      }
  }
}

function printResult($result) { //prints results from a select statement
  echo "<br><h3><font color='#2d4cb3'>Retrieved data from table Circuit:</h3>";
  echo "<table class='center'>";
  echo "<tr>
    <th><font color='#2d4cb3'>City</th>
    <th><font color='#2d4cb3'>Circuit Name</th>
    <th><font color='#2d4cb3'>Country</th>
    <th><font color='#2d4cb3'>Longitude</th>
    <th><font color='#2d4cb3'>Latitude</th>
    </tr>";

  while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
      echo "<tr>
      <td><p align='center';>" . $row["0"] . "</p></td>
      <td><p align='center';>" . $row["1"] . "</p></td>
      <td><p align='center';>" . $row["2"] . "</p></td>
      <td><p align='center';>" . $row["3"] . "</p></td>
      <td><p align='center';>" . $row["4"] . "</p></td>
      </tr>"; //or just use "echo $row[0]"
  }
  echo "</table>";
}

function connectToDB() {
  global $db_conn;

  // Your username is ora_(CWL_ID) and the password is a(student number). For example,
// ora_platypus is the username and a12345678 is the password.
  $db_conn = OCILogon("ora_kej19", "a16752370", "dbhost.students.cs.ubc.ca:1522/stu");

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

function disconnectFromDB() {
  global $db_conn;

  debugAlertMessage("Disconnect from Database");
  OCILogoff($db_conn);
}

function handleInsertRequest() {
  global $db_conn;

  //Getting the values from user and insert data into the table
  $tuple = array (
      ":bind1" => $_POST['city'],
      ":bind2" => $_POST['circuit_name'],
      ":bind3" => $_POST['country'],
      ":bind4" => $_POST['longitude'],
      ":bind5" => $_POST['latitude']
  );
  
  $alltuples = array (
      $tuple
  );

  executeBoundSQL("INSERT INTO Circuit_2 VALUES (:bind1, :bind2, :bind3, :bind4, :bind5)", $alltuples);
  OCICommit($db_conn);
}

function handleDeleteRequest() {
  global $db_conn;

  //Getting the values delete from table
  $delete_name = $_POST['circuit_name'];
  $ans = executePlainSQL("SELECT circuit_name 
                          FROM Circuit_2
                          WHERE circuit_name = '". $delete_name . "'");
  $row = oci_fetch_row($ans);
  if ($row == false) {
    echo "<font color='#db2c20'><br />&nbsp;delete failed, circuit does not exist</font><br>";
    echo "<br>";
  } 
  $row = executePlainSQL("DELETE FROM Circuit_2
                          WHERE circuit_name = '". $delete_name . "'");
  OCICommit($db_conn);
}

// HANDLE ALL POST ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
function handlePOSTRequest() {
  if (connectToDB()) {

    if (array_key_exists('deleteQueryRequest', $_POST)) {
      handleDeleteRequest();
    }
    else if (array_key_exists('insertQueryRequest', $_POST)) {
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