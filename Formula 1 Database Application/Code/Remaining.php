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

    <?php
     require __DIR__ . '/#OracleFunctions.php';

    function printResult($result)
    { //prints results from a select statement
        while ($row = oci_fetch_row($result)) {
            echo "<tr>
            <td><p style = 'color:#2d4cb3'>" . $row[0] . "</p></td>
            </tr>";
        }
        echo "</table>";
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
        printResult($result);

            
        
     
        OCICommit($db_conn);
    }

    
    function handlePOSTRequest()
    {
        if (connectToDB()) {

            if (array_key_exists('divisionQueryRequest', $_POST)) {
                handleDivisionRequest();}


            disconnectFromDB();
        }
    }

    if (isset($_POST['divisionSubmit'])) {
        handlePOSTRequest();
    }

    ?>
</body>

</html>