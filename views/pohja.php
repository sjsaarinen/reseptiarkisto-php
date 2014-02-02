<!DOCTYPE html>
<html>
    <head>
        <title>Reseptiarkisto: <?php echo $data->title ?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/bootstrap-theme.css" rel="stylesheet">
        <link href="css/main.css" rel="stylesheet">
    </head>
    <body>
        <div id="content">
            <?php if (!empty($data->virhe)): ?>
                <div class="alert alert-danger"><?php echo $data->virhe; ?></div>
            <?php endif; ?>
            <?php require $sivu; ?>
        </div>
    </body>
</html>