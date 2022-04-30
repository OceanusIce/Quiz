<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <style>
        body{
            display:flex;
            justify-content:center;
            height: 97vh;
            align-items: center;
            background:darkblue;
        }
        .main{
            border-radius:10px;
            text-align:center;
            background:lightblue;
        }
        .header, .text, .enter{
            padding:0.5rem 3rem;
        }
        .btn{
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            background-color: #009688;
            font-size: 16px;
            color:#ffffff;
            border: none;
            display: inline-block;
            margin: 15px 0 20px;
        }
        .header{
            border-bottom: black solid 2px;
        }
        h1{
            font-family:sans-serif;
        }
        .text{
            font-family:Arial, Helvetica, sans-serif;
        }
        a{
            text-decoration:none;
        }
    </style>
</head>
<body>
    <div class="main">
        <div class="header">
            <h1>Quiz<h2>
        </div>
        <div class="text">
            To gain success one must walk a thousand miles and it all starts with a single step.
        </div><br>
        <div class="enter">
            <a class="social-btn btn btn-icon btn-block text-left " disabled><span><img src="https://img.icons8.com/color/48/000000/google-logo.png" class="img-fluid mr-1" width="25"></span> Sign up with Google</a><br>
            <a href="processor.php" class="social-btn btn btn-icon btn-block text-left ">Enter</a>
        </div>
    </div>
</body>
</html>