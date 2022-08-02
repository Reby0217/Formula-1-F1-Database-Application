<!DOCTYPE html>
<html>

<head>
  <title>
    Insert circuits
  </title>
  <link rel="stylesheet" href="styles.css">
</head>

<body>
  <header class="page-header">
    <h1>Formula Database Application </h1>
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
</body>
</form>
<h2 class="operations">Delete Circuit</h2>
<form method="POST" action="InsertAndDeleteUI.php">
  <input type="hidden" id="deleteQueryRequest" name="deleteQueryRequest">
  Circuit Name: <input type="text" name="circuit_name"> <br /><br />
  <input type="submit" value="Delete" name="deleteSubmit"></p>

</html>