<html>
<head>
    <meta charset="UTF-8">
    <title>Covid Data</title>
</head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
<body>
<form action="/" method="post">
    Value: <input type="text" name="value"><br>
    <label for="column">Column:</label>
    <select name="column" id="column">
        <?php

        require_once("vendor/autoload.php");

        foreach (\App\CountryCovidDataRow::getPropertyNames() as $propertyName)
        {
            echo "<option value=\"$propertyName\">$propertyName</option>";
        }
        ?>
    </select><br>
    <input type="submit" value="Search">
</form>
<?php

use App\CovidTable;

$table = new CovidTable();
$table->load();
if (isset($_POST["value"]) && $_POST["value"] !== "" && isset($_POST["column"]))
{
    $table->print($table->search($_POST["value"], $_POST["column"]));
}
else
{
    $table->print($table->getData());
}
?>
</body>
</html>
