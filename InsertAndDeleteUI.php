<!DOCTYPE html>
<html>
    <head>
        <title>
            <meta charset = "UTF-8">
            Insert circuits
        </title>
    </head>

    <body>

    <h2>Adding new Circuits</h2> 
  <! h2 {
        color: red;
        width:500px;
        border: 1px solid black;
    }
    >
    <form method="POST" action="InsertAndDeleteUI.php"> 
        <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
        Circuit Name: <input type="text" name="circuit_name"> <br /><br />
        <p>Leave below entries empty if you do not have detailed information.</p>
        City: <input type="text" name="city"> <br /><br />
        Country: <input type="text" name="country"> <br /><br />
        Longitude: <input type="real" name="longitude"> <br /><br />
        Latitude: <input type="real" name="latitude"> <br /><br />

        <input type="submit" value="Insert" name="insertSubmit"></p>

    </body>
    </form>

    <h2>Delete Circuit</h2>
    <form method="POST" action="InsertAndDeleteUI.php">
        <input type="hidden" id="deleteQueryRequest" name="deleteQueryRequest">
        Delete Circuit Name: <input type="text" name="deletecircuit_name"> <br /><br />

        

</html>