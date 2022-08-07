<!DOCTYPE html>
<html>

<head>
    <title>Aggregation+Nest+Division</title>
    
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

    <h2 class="operations">Here are some extra info if you want to know:</h2>
    <form method="POST" action="Remaining.php"> 
        <input type="hidden" id="divisionQueryRequest" name="divisionQueryRequest">
        The safety car driver who have driven every safety car: 
        <input type="submit" value="Who is it?" name="divisionSubmit"></p>
    </form>

    <form method="POST" action="Remaining.php"> 
        <input type="hidden" id="aggregationQueryRequest" name="aggregationQueryRequest">
        Find out the MAXIMUM amount of sponsorship cost: 
        <input type="submit" value="How much?" name="aggSubmit"></p>
    </form>

    <form method="POST" action="Remaining.php"> 
        <input type="hidden" id="awhavingQueryRequest" name="awhavingQueryRequest">
        Things happen during races, what happened to the driver who didn't complete the race? 
        <input type="submit" value="Find" name="awhSubmit"></p>
    </form>

    <?php
     require __DIR__ . '/#OracleFunctions.php';

    function printResultDV($result)
    { while ($row = oci_fetch_row($result)) {
            echo "<h3><font color='#2d4cb3'>The safety driver is:</h3>";
            echo "<p style = 'color:#2d4cb3'>" . $row[0] . "</p>";
        }
    }

    function printResultAG($result)
    { while ($row = oci_fetch_row($result)) {
            echo "<h3><font color='#2d4cb3'> Max sponsorship cost:</h3>";
            echo "<p style = 'color:#2d4cb3'> $" . $row[0] . " million</p>";
        }
    }

    function printResultAWH($result)
    { while ($row = oci_fetch_row($result)) {
            echo "<p style = 'color:#2d4cb3'>" . $row[0] . " driver failed the race due to ". $row[1] . "</p>";
        }
    }

    function handleDivisionRequest()
    {
        global $db_conn;
        
        $result = executePlainSQL(
            "SELECT safetycardriver_name
             FROM SafetyCarDriver A
             WHERE NOT EXISTS ((SELECT dsc.safetycar_name
                                FROM DriveSafetyCars dsc)
                                MINUS (SELECT safetycar_name 
                                               FROM SafetyCarDriver, DriveSafetyCars
                                               WHERE safetycar_driver = A.safetycardriver_name))");
        printResultDV($result);
        OCICommit($db_conn);
    }

    function handleAggregationRequest()
    {
        global $db_conn;
        $result = executePlainSQL("SELECT MAX(s.amount)
                                   FROM Sponsorship s");

        printResultAG($result);
        OCICommit($db_conn);
    }

    function handleAWHRequest()
    {
        global $db_conn;

        $result = executePlainSQL("SELECT COUNT(*), h.race_status
                                   FROM Participate p, HaveResults2 h
                                   WHERE p.race_date = h.race_date
                                   GROUP BY h.race_status
                                   HAVING h.race_status != 'completed'");
        
        printResultAWH($result);
        OCICommit($db_conn);
    }

    
    function handlePOSTRequest()
    {
        if (connectToDB()) {

            if (array_key_exists('divisionQueryRequest', $_POST)) {
                handleDivisionRequest();
            } else if (array_key_exists('aggregationQueryRequest', $_POST)) {
                handleAggregationRequest();
            } else if (array_key_exists('awhavingQueryRequest', $_POST)) {
                handleAWHRequest();}

            disconnectFromDB();
        }
    }

    if (isset($_POST['divisionSubmit']) || isset($_POST['aggSubmit']) || isset($_POST['awhSubmit'])) {
        handlePOSTRequest();
    }
    ?>

</body>

</html>