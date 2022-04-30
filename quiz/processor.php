<?php
    include 'db/conn.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script>
        $(document).ready(function() {
            $('button').click(function(){
                $('#comments').load("load-answer.php")
            })
        });
    </script>
</head>
<style>
    body{
        margin: 0;
        background: #009688;
        font-family: sans-serif;
    }
    .quiz-container{
        max-width: 600px;
        min-height: 500px;
        background-color:#ffffff ;
        margin:40px auto;
        border-radius: 10px;
        padding: 30px;
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
    </style>
<body>
<div class="quiz-container">
        <div class="question-number">
            <h3>Question</h3>
        </div>
        <div class="questions">
        <?php
            $random = rand(414,438);

            $sql = "SELECT * FROM questions WHERE question_id = $random";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
                    echo "<p>";
                    echo $row['question'];
                    echo "<br>";
                    echo $row['opt_a'];
                    echo "</br>";
                }
            }else{
                echo "There are no comments";
            }
        ?>
        </div>
    </div>
</body>
</html>