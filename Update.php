<!DOCTYPE html>
<html>

<head>
  <title>Update Constructor</title>
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

  <h2 class="operations">Update Constructor Information</h2>
  <form method="POST" action="Update.php"> 
            <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
           Constructor Name: <input type="text" name="constructor_name"> <br /><br />
           Old City Name: <input type="text" name="old_city_name"> <br /><br />
           New City Name: <input type="text" name="new_city_name"> <br /><br />

            <input type="submit" value="Update" name="updateSubmit"></p>
        </form>

        
<?php
//Below code refers to oracle-test.txt provided in tutorial 7

require __DIR__ . '/#OracleFunctions.php';

function printResult($result) { //prints results from a select statement
    echo "<br><h3><font color='#2d4cb3'>Updated data from Constructor Table:</h3>";
    echo "<table class='center'>";
    echo "<tr>
      <th><font color='#2d4cb3'>Constructor Name</th>
      <th><font color='#2d4cb3'>Nationality</th>
      <th><font color='#2d4cb3'>Branch Location</th>

      </tr>";
  
    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr>
        <td><p align='center';>" . $row["0"] . "</p></td>
        <td><p align='center';>" . $row["1"] . "</p></td>
        <td><p align='center';>" . $row["2"] . "</p></td>
        </tr>"; //or just use "echo $row[0]"
    }
    echo "</table>";
  }

function handleUpdateRequest() {
  global $db_conn;

  $input_constructor = $_POST['constructor_name'];

  $old_city_name = $_POST['old_city_name'];
  $new_city_name = $_POST['new_city_name'];

  $tmp2 = executePlainSQL("SELECT constructor_name
  FROM Constructors
  WHERE city = '". $old_city_name . "'"
);

  
  if (($input_constructor==NULL) || ($$old_city_name==NULL) || ($new_city_name==NULL)){
    echo "<font color='red'><br />Make sure to fill all the blanks!</font>";
  } else if (OCI_Fetch_Array($tmp2, OCI_BOTH)[0] == NULL){
    echo "<font color='red'><br />Old city name cannot be empty.</font>";
  } 
 
  executePlainSQL(" UPDATE Constructors 
                    SET city= '". $new_city_name . "' 
                    WHERE city= '" . $old_city_name . "' ");
                    
  OCICommit($db_conn); 

}

// HANDLE ALL POST ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
function handlePOSTRequest() {
  if (connectToDB()) {

    if (array_key_exists('updateQueryRequest', $_POST)) {
      handleUpdateRequest();

      $result = executePlainSQL('SELECT * FROM Constructors
                                 ORDER BY constructor_name');
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


