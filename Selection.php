<!DOCTYPE html>
<html>

<head>
    <title>Selectable Information</title>
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

    <h2 class="operations">Select Team Members by:</h2>
    <form method="POST" action="Selection.php">
        <input type="hidden" id="selectQueryRequest" name="selectQueryRequest">
        Nationality: <input type="text" , name="Nationality"> 
        </br>
        Born in year: <input type="text" , name="Year"> <input type="submit" value="Select" name="NBSubmit"></br>
    </form>

    <h2 class="operations">Select Constructors by:</h2>
    <form method="POST" action="Selection.php">
        <input type="hidden" id="selectQueryRequest1" name="selectQueryRequest1">
        Nationality: <input type="text" , name="C-Nationality">
        </br>
        City: <input type="text" , name="City"> <input type="submit" value="Select" name="CNSubmit"></br>
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

    function printResult($result)
    {   
        //prints results from a select statement
        echo "<br><h3><font color='#2d4cb3'>Selected data based on your input:</h3>";
        echo "<table class='center'>";
        echo "<tr>
         <th><font color='#2d4cb3'>First Name</th>
         <th><font color='#2d4cb3'>Last Name</th>
         <th><font color='#2d4cb3'>Date of Birth</th>
         <th><font color='#2d4cb3'>Nationality</th>
         <th><font color='#2d4cb3'>Constructor</th>
        </tr>";
        $hasResult = false;

        while ($row = oci_fetch_row($result)) {
            $hasResult = true;
            echo "<tr>
            <td><p align='center';>" . $row[0] . "</p></td>
            <td><p align='center';>" . $row[1] . "</p></td>
            <td><p align='center';>" . $row[2] . "</p></td>
            <td><p align='center';>" . $row[3] . "</p></td>
            <td><p align='center';>" . $row[4] . "</p></td>
            </tr>";
        }

        if ($hasResult == false) {
            echo "<tr>
            <td><p align='center'; style='color:red';>         </p></td>
            <td><p align='center'; style='color:red';>         </p></td>
            <td><p               ; style='color:red';>No Result</p></td>
            <td><p align='center'; style='color:red';>         </p></td>
            <td><p align='center'; style='color:red';>         </p></td>
            </tr>";
        }
        echo "</table>";
    }

    function printResult1($result)
    { //prints results from a select statement
        echo "<br><h3><font color='#2d4cb3'>Selected data based on your input:</h3>";
        echo "<table class='center'>";
        echo "<tr>
         <th><font color='#2d4cb3'>Name</th>
         <th><font color='#2d4cb3'>Nationality</th>
         <th><font color='#2d4cb3'>City</th>
        </tr>";
        $hasResult = false;
        while ($row = oci_fetch_row($result)) {
            $hasResult = true;
            echo "<tr>
            <td><p align='center';>" . $row[0] . "</p></td>
            <td><p align='center';>" . $row[1] . "</p></td>
            <td><p align='center';>" . $row[2] . "</p></td>
            </tr>";
        }
        if ($hasResult == false) {
            echo "<tr>
            <td><p align='center'; style='color:red';>         </p></td>
            <td><p align='center'; style='color:red';>No Result</p></td>
            <td><p align='center'; style='color:red';>         </p></td></tr>";
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

    function handleSelectRequest()
    {
        global $db_conn;
        //$entryStatus = TRUE;
        //Getting the values from user and insert data into the table
        $nationality = $_POST['Nationality'];
        $Year = $_POST['Year'];

            if(($nationality != Null) && ($Year != Null)){
            $check = executePlainSQL("SELECT * 
                                      FROM EmployTeamMembers
                                      WHERE nationality = '" . $nationality . "'
                                      AND REGEXP_LIKE(date_of_birth, '^$Year(*)')"
                               );

                printResult($check);
                
            } else if (($nationality == Null) && ($Year != Null)) {
                $check = executePlainSQL("SELECT * 
                                          FROM EmployTeamMembers
                                          WHERE REGEXP_LIKE(date_of_birth, '^$Year(*)')");
                printResult($check);

            } else if(($nationality != Null) && ($Year == Null)){
                $check = executePlainSQL("SELECT * 
                                          FROM EmployTeamMembers
                                          WHERE nationality = '" . $nationality . "'");
                printResult($check);
                
            } else {
                echo "<font color='red'><br /> Please enter a field.</font>";
            }
 

        OCICommit($db_conn);
    }

    function handleSelectRequest1()
    {
        global $db_conn;

        $cnationality = $_POST['C-Nationality'];
        $City = $_POST['City'];

        
            $check1 = executePlainSQL("SELECT * 
                                FROM Constructors
                                WHERE nationality = '" . $cnationality . "' OR city = '" . $City . "'");
            // if (mysql_num_rows($check1) == 0) {
            //     echo "<h2 style='color:red;'>Failed, does not exist. </h2>" ;
            //     echo "<br>";
            // } else {
            //     printResult($check1);
            // }

            printResult1($check1);
        
        OCICommit($db_conn);
    }

    function handlePOSTRequest()
    {
        if (connectToDB()) {

            if (array_key_exists('selectQueryRequest', $_POST)) {
                handleSelectRequest();
            } else if (array_key_exists('selectQueryRequest1', $_POST)) {
              handleSelectRequest1();
          }
            disconnectFromDB();
        }
    }

    if (isset($_POST['NBSubmit']) || isset($_POST['CNSubmit'])) {
        handlePOSTRequest();
    }

    ?>
</body>

</html>