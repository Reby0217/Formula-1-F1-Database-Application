<!DOCTYPE html>
<html>

<head>
    <title>Search Race Location and Date</title>
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

    <h2 class="operations">Enter race name to get it's location and date:</h2>
    <form method="POST" action="Join.php">
        <input type="hidden" id="joinQueryRequest" name="joinQueryRequest">
        Race Name: <input type="text" , name="RaceName"> <input type="submit" value="Find" name="Submit"></br>
    </form>

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
    { //prints results from a select statement
        echo "<br><h3><font color='#2d4cb3'>Races location and date:</h3>";
        echo "<table class='center'>";
        echo "<tr>
        <th><font color='#2d4cb3'>Location</th>
        <th><font color='#2d4cb3'>Date</th>
        </tr>";
        $hasResult = false;

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            $hasResult = true;

            echo "<tr>
            <td><p align='center';>" . $row[0] . "</p></td>
            <td><p align='center';>" . $row[1] . "</p></td>
            </tr>";
        }

        if ($hasResult == false) {
            echo "<tr><td><p align='center'; style='color:red';>No Race</p></td>
            <td><p align='center'; style='color:red';>Found</p></td>

            </tr>";
        }
        
        echo "</table>";

    }

    function connectToDB()
    {
        global $db_conn;
        //change to your cwl and password
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

    function disconnectFromDB()
    {
        global $db_conn;

        debugAlertMessage("Disconnect from Database");
        OCILogoff($db_conn);
    }

    function handleJoinRequest()
    {
        global $db_conn;
        //$entryStatus = TRUE;
        //Getting the values from user and insert data into the table
        $racename = $_POST['RaceName'];

        $try = ("SELECT c.city, r.race_date
                 FROM RacesTakePlace r, Circuit_2 c
                 WHERE r.circuit_name = c.circuit_name 
                 AND r.race_name = '" . $racename . "'
                ");

                     
        $check = executePlainSQL($try);
        printResult($check);

        // if ($check != false) {
        //     printResult($check);
        // }
     
        OCICommit($db_conn);
    }


    function handlePOSTRequest()
    {
        if (connectToDB()) {

            if (array_key_exists('joinQueryRequest', $_POST)) {
                handleJoinRequest();
            }
            disconnectFromDB();
        }
    }

    if (isset($_POST['Submit'])) {
        handlePOSTRequest();
    }

    ?>
</body>

</html>