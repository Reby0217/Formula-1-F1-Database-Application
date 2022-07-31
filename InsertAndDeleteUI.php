<!DOCTYPE html>
<html>
    <head>
        <title>
            <meta charset = "UTF-8">
            Insert circuits
        </title>
    </head>

    <body>

    <h2 style="color:Tomato;">Adding new Circuits</h3>
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
        Delete Circuit Name: <input type="text" name="circuit_name"> <br /><br />
        <input type="submit" value="Delete" name="deleteSubmit"></p>

        

</html>