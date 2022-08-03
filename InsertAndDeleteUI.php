<!DOCTYPE html>
<html>

<head>
  <title>Insert circuits </title>
  <link rel="stylesheet" href="styles.css">
</head>

<body>
  <header class="page-header">
    <h1>Formula 1 Database Application </h1>
  </header>
  <h2 class="operations">Adding new Circuits</h2>
  <form method="POST" action="InsertAndDeleteUI.php">
    <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
    Circuit Name: <input type="text" name="circuit_name"> <br /><br />
    <p>Leave below entries empty if you do not have detailed information.</p>
    City: <input type="text" name="city"> <br /><br />
    Country: <input type="text" name="country"> <br /><br />
    Longitude: <input type="real" name="longitude"> <br /><br />
    Latitude: <input type="real" name="latitude"> <br /><br />
    <input type="submit" value="Insert" name="insertSubmit"></p>

  </form>

<h2 class="operations">Delete Circuit</h2>
<form method="POST" action="InsertAndDeleteUI.php">
  <input type="hidden" id="deleteQueryRequest" name="deleteQueryRequest">
  Delete Circuit Name: <input type="text" name="circuit_name"> <br /><br />
  <input type="submit" value="Delete" name="deleteSubmit"></p>
</form>

<?php
    //Below code refers to oracle-test.txt provided in tutorial 7
		//this tells the system that it's no longer just parsing html; it's now parsing PHP

$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = NULL; // edit the login credentials in connectToDB()
$show_debug_alert_messages = True; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

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

function printResult($result) { //prints results from a select statement
  echo "<br>Retrieved data from table Circuit_2:<br>";
  echo "<table>";
  echo "<tr>
    <th>Circuit Name</th>
    <th>City</th>
    <th>Country</th>
    <th>Longitude</th>
    <th>Latitude</th>
    </tr>";

  while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
      echo "<tr>
      <td>" . $row["Circuit Name"] . "</td>
      <td>" . $row["City"] . "</td>
      <td>" . $row["Country"] . "</td>
      <td>" . $row["Longitude"] . "</td>
      <td>" . $row["Latitude"] . "</td>
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
      ":bind1" => $_POST['Circuit Name'],
      ":bind2" => $_POST['City'],
      ":bind3" => $_POST['Country'],
      ":bind4" => $_POST['Longitude'],
      ":bind5" => $_POST['Latitude']
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
    echo "<br> <p style:font=larger>Delete failed: No such circuit found.</p></br>";
  } 
  $row = executePlainSQL("DELETE FROM Circuit_2
                          WHERE circuit_name = '". $delete_name . "'");
  OCICommit($db_conn);
}




// HANDLE ALL POST ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
function handlePOSTRequest() {
  if (connectToDB()) {
     if (array_key_exists('insertQueryRequest', $_POST)) {
          handleInsertRequest();
      } else if (array_key_exists('deleteQueryRequest', $_POST)){
          handleDeleteRequest();
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