<!DOCTYPE html>
<html>

<head>
  <title>Update Sponsorship</title>
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

  <h2 class="operations">Update Sponsorship Information</h2>
  <form method="POST" action="Update.php"> 
            <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
            Old Sponsorship Name: <input type="text" name="old_sponsorship_name"> <br /><br />
            New Sponsorship Name: <input type="text" name="new_sponsorship_name"> <br /><br />

            <input type="submit" value="Update" name="updateSubmit"></p>
        </form>

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

function printResult($result) { //prints results from a select statement
    echo "<br><h3><font color='#2d4cb3'>Updated data from Sponsorship Table:</h3>";
    echo "<table class='center'>";
    echo "<tr>
      <th><font color='#2d4cb3'>Sponsorship Name</th>

      </tr>";
  
    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr>
        <td><p align='center';>" . $row["0"] . "</p></td>
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


function handleUpdateRequest() {
  global $db_conn;

  $old_sponsorship_name = $_POST['old_sponsorship_name'];
  $new_sponsorship_name = $_POST['new_sponsorship_name'];

  $tmp = executePlainSQL("SELECT sponsorship_name
                          FROM Sponsorship
                          WHERE sponsorship_name = '". $old_sponsorship_name . "'"

);
  
  if (OCI_Fetch_Array($tmp, OCI_BOTH)[0] == NULL){
    echo "<font color='red'><br />&nbsp; Invalid Sponsorship name. Please enter a valid one.</font>";
  } 


  // you need the wrap the old name and new name values with single quotations

  executePlainSQL(" UPDATE Sponsorship 
                    SET sponsorship_name= '". $new_sponsorship_name . "' 
                    WHERE sponsorship_name= '" . $old_sponsorship_name . "' ");
                    

                

      
  OCICommit($db_conn); 
}



// HANDLE ALL POST ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
function handlePOSTRequest() {
  if (connectToDB()) {

    if (array_key_exists('updateQueryRequest', $_POST)) {
      handleUpdateRequest();

      $result = executePlainSQL('SELECT * FROM Sponsorship');
      printResult($result);

      disconnectFromDB();
  }
}
}

if (isset($_POST['updateSubmit'])) {
  handlePOSTRequest();
}

?>
  </body>
</html>


