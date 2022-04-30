<?php
    // $servername = "localhost";
    // $username = "root";
    // $password = "";
    // $dbname = "deenquiz_quizdb";

    // $conn = mysqli_connect($servername, $username, $password, $dbname);

    $con = mysqli_connect("localhost","root","", 'deenquiz_quizdb');

    // Check connection
    if ($con -> connect_errno) {
    echo "Failed to connect to MySQL: " . $con -> connect_error;
    exit();
    }
?>