<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Chat :: login</title>
    <style>
        body{
            background-color: #000;
            color: greenyellow;
            font-family: "Courier new";
        }
        .error{
            color: red;
        }
        #form{
            display: block;
            width:200px;
            margin: 0 auto;
            margin-top:25%;
            transform: translateY(-50%);
        }
        #form > form > *{
            font-size: 20px;
            display: block;
            width: 100%;
            padding:15px;
            background-color: #000;
            color: greenyellow;
            border:1px solid greenyellow;
            margin-bottom: 3px;
            font-size: 20px;
        }
        #form > form > *[data-lastpass-icon-root]{
            border:none;
        }
        
    </style>
</head>
<body>
    <div id="form">
        <?php if(isset($_GET['error'])): ?>
            <div class="error"><?php echo $_GET['error'] ?></div>
        <?php endif ?>
        <form action="index.php" method="POST">
            <input type="text" name="username" placeholder="username" autocomplete="false" />
            <input type="password" name="password" placeholder="password" autocomplete="false" />
            <input type="submit" name="login" value="Accedi" />
        </form>
    </div>
</body>
</html>