<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URLs</title>
</head>
<body>
    <form action="" method="post">
        <label for="url">URL:</label><br>
        <input type="text" id="url" name="url"
        pattern="(http|https):\/\/(www\.)?[a-z]+\.[a-z]+"
        title="La URL está mal escrita">
        <br><br>
        <input type="Submit" value="Enviar">
    </form>
</body>
</html>