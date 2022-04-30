<?php
    include_once('db/conn.php');
    session_start();

    function getHint($question_id){
	    global $con;
		//$hint = "Past Users Answered the Question as follows:\n";
        $hint = array();

		$query = mysqli_query($con, "SELECT count(answer_id) as acount FROM user_answers WHERE question_id = ".$question_id.";") or die(mysqli_error($con));
		if(mysqli_num_rows($query) > 0){
			$row_data = mysqli_fetch_array($query);
			$total = $row_data['acount'];
				$q = mysqli_query($con, "SELECT answer, count(answer_id) as acount FROM user_answers WHERE question_id = $question_id GROUP BY answer;") or die(mysqli_error($con));
					if(mysqli_num_rows($q) > 0){
                        $i = 1;
						while($row = mysqli_fetch_array($q)){
                            $hint['Percentage '.$i] = number_format(($row['acount'] / $total)* 100)."%";
                            $hint['answer'.$i] = strtoupper($row['answer']);
                            $i++;
                        }

					}else{
						$hint['result'] = "Unfortunately, no one has answered the question before!";
					}
		}
		return $hint;
	}

    //TO GET THE QUESTION, OPTION, QUESTION_ID etc
    function getQuestion(){
        global $con;
        $stmt = mysqli_query($con,"SELECT * FROM `questions` LIMIT 1");

        if(mysqli_num_rows($stmt) > 0){
            $Quiz = array();
            while ($row = mysqli_fetch_assoc($stmt)) {
                $Quiz[] = $row;
            }

        }

        return $Quiz;
    }
    $Gette = getQuestion();
    print_r(json_encode($Gette));
    //echo '<br>'.$Gette[0]['knw_area'];
    $_SESSION['title']= "Silver";
    $_SESSION['gpoint'] = 2000;

class QuizInfo{
    //THE SET TITLE FUNCTION i.e BRONZE OR SILVER OR ...etc
    function setTitleLevel(){
	    global $con;
        $title = $_SESSION['title'];
        $point = $_SESSION['gpoint'];
        echo $title.'<br>'.$point;
		mysqli_query($con, "UPDATE quiz_records SET user_title = $title, points_obtained = $point WHERE quiz_id = ".$_SESSION['quiz_id'].";") or mysqli_error($con);
		//return $_SESSION['llhint'] = 1;

	}

    function UsersScore($q_id, $points){
        global $con;
        $this->setTitleLevel();
		mysqli_query($con, "UPDATE quiz_records SET last_question_id = $q_id, last_point = $points WHERE quiz_id = ".$_SESSION['quiz_id'].";") or die(mysqli_error($con));
    }

    //IT SAVES EVERY SINGLE HISTORY OF QUESTIONS ANSWERED
    function setAnswered($user_id, $question_id, $answer, $correct){
	    global $con;
		$n = $_SESSION['gpoint'];
		mysqli_query($con, "INSERT INTO user_answers VALUES(null, ".$user_id.", ".$question_id.", '".$answer."', ".$correct.", ".$_SESSION['quiz_id'].");") or die(mysqli_error($con));
        $this->UsersScore(10, $n);
		return true;
	}

    //ONCE THE QUIZ STARTS
    function createQuiz($user_id , $knw_area) {
	    global $con;
		mysqli_query($con, "INSERT INTO quiz_records VALUES (null, ".$user_id.", '', 0, 0, 0, 0, 0, null, 0, 0, 0, 0, ".$knw_area.");") or die(mysqli_error($con));
        
		$_SESSION['quiz_id'] = mysqli_insert_id($con);
	}

    //LAST QUIZ_ID'S ID
    // function LAST_ID(){
    //     global $con;

    //     $last = "SELECT MAX(quiz_id) AS last_id FROM quiz_records";
    //     $result = mysqli_query($con, $last);
    //     $row = mysqli_fetch_array($result);
    //     return $row['last_id'];
        
    // }

    

    //ONCE THE QUIZ IS OVER
    function update_quiz_status($complete,$q_id, $points){
	    global $con;
        //INSERT THE SET TITLE FUNCTION
        $this->setTitleLevel();
		mysqli_query($con, "UPDATE quiz_records SET last_question_id = $q_id, quiz_complete = $complete, last_point = $points WHERE quiz_id = ".$_SESSION['quiz_id'].";") or die(mysqli_error($con));
        echo '<br>'.($_SESSION['quiz_id']);
        $this->endSession();
    }

    function endSession(){
        // remove all session variables
        session_unset();

        // destroy the session
        session_destroy();
    }

    //TO REGISTER THE LIFELINES USED WITH 0 OR 1
    function setLL5050($quiz_id){
		global $con;
        $stmt = mysqli_query($con,"SELECT * FROM `quiz_records` WHERE quiz_id = $quiz_id");

        //TO CHECK IF IT THE LIFE LINE BEEN PICKED OR NOT
        if(mysqli_num_rows($stmt) > 0){
            $row = mysqli_fetch_assoc($stmt);
            $_50_50 = $row['lifeline_50_50']; 
            if ($_50_50 == 1) {
                echo "<br>Sorry, you can't do that!!";
                return $_SESSION['ll5050'] = 0;
            }else{
                mysqli_query($con, "UPDATE quiz_records SET lifeline_50_50 = 1 WHERE quiz_id = ".$quiz_id.";") or die(mysqli_error($con));
                return $_SESSION['ll5050'] = 1;
            }

        }
		
	}
	
    //TO REGISTER THE LIFELINES USED WITH EITHER 0 OR 1
    function setLL1min($quiz_id){
		global $con;
        $stmt = mysqli_query($con,"SELECT * FROM `quiz_records` WHERE quiz_id = $quiz_id");

        //TO CHECK IF IT THE LIFE LINE BEEN PICKED OR NOT
        if(mysqli_num_rows($stmt) > 0){
            $row = mysqli_fetch_assoc($stmt);
            $_1min = $row['lifeline_1min'];
            if ( $_1min == 1) {
                echo "<br>Sorry, you can't do that!!";
                return $_SESSION['ll1min'] = 0;
            }else{
                mysqli_query($con, "UPDATE quiz_records SET lifeline_1min = 1 WHERE quiz_id = ".$quiz_id.";") or die(mysqli_error($con));
                return $_SESSION['ll1min'] = 1;
            }

        }
		
	}
	
    //TO REGISTER THE LIFELINES USED WITH EITHER 0 OR 1
    function setLLhint($quiz_id){
		global $con;
        $stmt = mysqli_query($con,"SELECT * FROM `quiz_records` WHERE quiz_id = $quiz_id");

        //TO CHECK IF IT THE LIFE LINE BEEN PICKED OR NOT
        if(mysqli_num_rows($stmt) > 0){
            $row = mysqli_fetch_assoc($stmt);
            $hint = $row['lifeline_hint'];
            if ($hint == 1) {
                echo "<br>Sorry, you can't do that!!";
                return $_SESSION['llhint'] = 0;
            }else{
                mysqli_query($con, "UPDATE quiz_records SET lifeline_hint = 1 WHERE quiz_id = ".$quiz_id.";") or die(mysqli_error($con));
                return $_SESSION['llhint'] = 1;
            }

        }
		
	}

}
?>
<?php
    $get = new QuizInfo;
    $points = $_SESSION['gpoint'];

    if(isset($_POST['start'])){
        echo "<br><br>New records created<br>";
        $get->createQuiz(60 , 3);
        
        echo '<br>'.($_SESSION['quiz_id']);
    }

    if(isset($_POST['next'])){
        echo "<br><br>Records submitted<br>";
        $get-> setAnswered(60, 414,"C", 1);
        echo '<br>'.($_SESSION['quiz_id']);
    }

    if(isset($_POST['end'])){
        echo "<br><br>Last records updated<br>";
        $get->update_quiz_status(1,10, $points);
    }
    if(isset($_POST['50/50'])){
        $get->setLL5050($_SESSION['quiz_id']);
        if($_SESSION['ll5050'] == 0){
            echo 'Sorry';
        }else{
            echo "<br><br>50/50<br>";
        }
        echo '<br>'.($_SESSION['quiz_id']);
    }
    if(isset($_POST['+1min'])){
        $get->setLL1min($_SESSION['quiz_id']);
        if($_SESSION['ll1min'] == 0){
            echo"Sorry";
        }else{
            echo "<br><br>+1min";
        }
        echo '<br>'.($_SESSION['quiz_id']);
    }
    if(isset($_POST['hint'])){
        $get->setLLhint($_SESSION['quiz_id']);
        if($_SESSION['llhint'] == 0 ){
            echo "Sorry";
        }else{
            print_r(json_encode(getHint(414)));
            echo "<br><br>Hint<br>";
        }
        echo '<br>'.($_SESSION['quiz_id']);
    }
?>

<html>
    <head>
        <title>Quiz</title>
    </head>
    <body>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <input type="submit" value="End Quiz" name = "end">
            <input type="submit" value="Next" name = "next">
            <input type="submit" value="Start Quiz" name = "start">
            <input type="submit" value="50/50" name = "50/50">
            <input type="submit" value="+1min" name="+1min">
            <input  type="submit" value="Hint" name="hint">
        </form>
    </body>
</html>









