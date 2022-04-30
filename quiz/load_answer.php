<?php
    include 'db/conn.php';
?>
<?php
    $random = rand(414,438);

    $sql = "SELECT * FROM questions WHERE question_id = $random";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            echo "<p>";
            echo $row['answers'];
            echo "<br>";
            echo $row['explanation'];
            echo "</br>";
        }
    }else{
        echo "There are no comments";
    }
?>