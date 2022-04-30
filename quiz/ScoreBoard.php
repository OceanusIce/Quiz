<?php 
    include_once('db/conn.php');

    // function getWeekStart(){
        
    //     global $con;
    //     $q = mysqli_query($con, "SELECT max(`week_start`) as last_start FROM `tbl_weeks`;");
    //     $row_num = mysqli_num_rows($q);
        
    //         return $data['last_start'];
    //     }if{
    //         return 0;
    //     }
    // }
class ScoreBoard{
    function getKnowledgeArea($knw_area){
        $area = '';
        switch ($knw_area) {
            case 0:
                $area = "All";
                break;
            case 1:
                $area = "Contemporary Issues";
                break;
            case 2:
                $area = "Hadith";
                break;
            case 3:
                $area = "Fiqh";
                break;
            case 4:
                $area = "Qur'an";
                break;
            case 5:
                $area = "Tauheed";
                break;
            case 6:
                $area = "Seerah";
                break;
            default:
                $area = 'nill';
                break;
        }
        
        return $area;
    }

    // USERS LAST FIVE ATTMPTS
    function getUserQuiz($user_id){
		global $con;
		$query = mysqli_query($con, "SELECT ADDDATE(`quiz_records`.`date-time`, INTERVAL '1' HOUR) as date_time, `user_title`, `points_obtained`, `lifeline_50_50`, `lifeline_1min`, `lifeline_hint`, `knw_area` FROM quiz_records WHERE user_id = $user_id AND quiz_complete = 1 ORDER BY   `quiz_records`.`date-time` DESC LIMIT 5;") or die(mysqli_error($con));
		
		if(mysqli_num_rows($query) > 0){
            $last = array();

            while($row = mysqli_fetch_array($query)){
                            //$ll = "";
                if($row['lifeline_50_50'] == "1") $last['lifeline']= "[50/50] ";
                if($row['lifeline_1min'] == "1") $last['lifeline']= "[+1 Min] ";
                if($row['lifeline_hint'] == "1") $last['lifeline']= "[Hint] ";

                if ($last == "") $last['lifeline'] = "None!";

                $last['date'] = $row['date_time'];
                $last['knw_area'] = $this->getKnowledgeArea($row['knw_area']);
                $last['points'] = number_format($row['points_obtained']);

            }
        }else{
            $last['result'] = "No Quiz Record!";
        }
		
		return $last;

	}

    //USER'S HISTORY
    function c_score($userid){
	    global $con;
		//Cummulative score of a user
		
			$q = mysqli_query($con, "SELECT SUM(`last_point`) as n1, count(`quiz_id`) as n2 FROM `quiz_records` WHERE `user_id` = ".$userid.";") or die(mysqli_error($con));
        if(mysqli_num_rows($q)>0){
            $data = mysqli_fetch_array($q);
            $qn = $data['n1'];
            $qn2 = $data['n2'];

            $data = array();

            $data['TotalTimesPlayed'] = $qn2;
            $data['TotalScoresGotten'] = number_format($qn);
            return $data;
        }else{
            return "Subhanallah! We cant get your stats now, have you played a quiz? :)";
        }
	}

    //TOP SCORER OF THE DAY
    function top_score_today(){
        global $con;
        //echo 1;
	  $q =  
        // mysqli_query($con, "SELECT sum(qr.points_obtained) as points, up.fb_user_id as fb, u.fullname as fname FROM `quiz_records` as qr, users as u, user_providers as up WHERE qr.user_id  = u.user_id AND up.user_id  = u.user_id and DATE(qr.`date-time`) = DATE(NOW()) GROUP BY fname ORDER BY points DESC LIMIT 1;") or die(mysqli_error($con));
	  
	  mysqli_query($con, "SELECT sum(qr.points_obtained) as points,  u.fullname as fname FROM `quiz_records` as qr, users as u WHERE qr.user_id  = u.user_id AND DATE(qr.`date-time`) = DATE(NOW()) GROUP BY fname ORDER BY points DESC LIMIT 1;") or die(mysqli_error($con));
            if(mysqli_num_rows($q) > 0){
            
              $data = array();
                      
              $row = mysqli_fetch_array($q);
                $data['FullName'] = $row['fname'];
                $data['points'] = number_format($row['points']);

            }else{
             $data['result'] = "No scores yet!";
            }

	return $data;
	
	
	}
    //ALL THE QUIZ HISTORY OF NUMBER OF USERS, NUMBER OF TIMES PLAYED, AVERAGE PLAYS PERDAY AND THE TOTAL AMOUNT OF POINT ALL THE USERS HAD SCORED
    function quizStats(){
	    global $con;
		$q = mysqli_query($con, "SELECT COUNT(`user_id`) as n FROM `users`;") or die(mysqli_error($con));
		 if(mysqli_num_rows($q)>0){
            $data = mysqli_fetch_array($q);
            $n = $data['n'];
		}
		 
		 $q = mysqli_query($con, "SELECT COUNT(`quiz_id`) as n1, SUM(`last_point`) as n2 FROM `quiz_records`;") or die(mysqli_error($con));
		 if(mysqli_num_rows($q)>0){
            $data = mysqli_fetch_array($q);
            $qn = $data['n1'];
            $qn2 = $data['n2'];
		}
		 
		$avg = floor($qn/$n);
		 
        $stats = array();
        $stats['TotalUsers'] = number_format($n);
        $stats['TimesPlayed'] = number_format($qn);
        $stats['AveregePlayed'] = $avg;
        $stats['TotalUsersScores'] = number_format($qn2);

        return $stats;
        //return $msg;
	}

    //TOP SCORER
    function top_cummulative(){
	    global $con;
            $q = mysqli_query($con, "SELECT sum(qr.points_obtained) as points,  u.fullname as fname FROM `quiz_records` as qr, users as u WHERE qr.user_id  = u.user_id GROUP BY fname ORDER BY points DESC LIMIT 1;") or die(mysqli_error($con));
            $row = mysqli_fetch_array($q);
            
            $topRanker = array();
            $topRanker['Fullname'] = $row['fname'];
            $topRanker['TotalScore'] = number_format($row['points']);
            return $topRanker;
            
        }

    //IT SHOWS THE TOP SCORES OF THAT DAY
    function scoreboard_today(){
        $limit = 10;
	    global $con;

        $q = mysqli_query($con, "SELECT sum(qr.points_obtained) as points, u.fullname as fname
            FROM `quiz_records` as qr, users as u
            WHERE
            qr.user_id = u.user_id AND
            DATE(qr.`date-time`) = DATE(NOW())
            GROUP BY fname
            ORDER BY points DESC
            LIMIT $limit;") or die(mysqli_error($con));
            
            if(mysqli_num_rows($q) > 0){
                $today = array();
                while ($row = mysqli_fetch_assoc($q)) {
                    $today[] = $row;
                }
            }else{
                $today['result'] = "No scores yet!";
            }

	    return $today;
	}

    //IT SHOWS THE TOP SCORES OF DIFFERENT CATEGORIES FFOR THE DAY
    function scoreboard_cummulative($knw_area = false, $net = FALSE){
        global $con;
        $limit = 10;
        if($knw_area === false){
            $q = mysqli_query($con, "SELECT sum(qr.points_obtained) as points,  u.fullname as fname FROM `quiz_records` as qr, users as u WHERE qr.user_id  = u.user_id GROUP BY fname ORDER BY points DESC LIMIT ".$limit.";") or die(mysqli_error($con));
        }  else {
            $q = mysqli_query($con, "SELECT sum(qr.points_obtained) as points,  u.fullname as fname FROM `quiz_records` as qr, users as u WHERE qr.user_id  = u.user_id AND knw_area = ".$knw_area." GROUP BY fname ORDER BY points DESC LIMIT ".$limit.";") or die(mysqli_error($con));
        }
        if(mysqli_num_rows($q) > 0){
            $cat = array();
            while ($row = mysqli_fetch_assoc($q)) {
                $cat[] = $row;
            }
        }else{
            $cat['result'] = "No scores yet!";
        }

    return $cat;


    }

    // IT SHOWS THE TOP SCORE FOR THE WEEK
    function week_cummulative(){
        global $con;
        $limit = 10;
        // $time_now = Date("Y-m-d H:i:s",time()+3600);
        // $last_friday = Date("Y-m-d H:i:s", getWeekStart());
        //echo $time_now;
        // $q = mysqli_query($con, "SELECT sum(qr.points_obtained) as points, up.fb_user_id as fb, u.fullname as fname FROM `quiz_records` as qr, users as u, user_providers as up WHERE qr.`date-time` >= '".$last_friday."' AND qr.`date-time` <= '".$time_now."' AND qr.user_id  = u.user_id AND up.user_id  = u.user_id GROUP BY fname ORDER BY points DESC LIMIT 15;") or die(mysqli_error($con));

        $q =mysqli_query($con, "SELECT sum(qr.points_obtained) as points,  u.fullname as fname FROM `quiz_records` as qr, users as u WHERE qr.user_id  = u.user_id AND DATE(qr.`date-time`) >= DATE_SUB(CURDATE(),INTERVAL 7 DAY) GROUP BY fname ORDER BY points DESC LIMIT $limit;") or die(mysqli_error($con));

        //echo "Weekly Cummulatve Top Scorers starting ".Date("l, Y-m-d H:i:s", getWeekStart());
        if(mysqli_num_rows($q) > 0){
            $Week = array();
                while ($row = mysqli_fetch_assoc($q)) {
                    $week[] = $row;
                }
            }else{
                $week['Result'] = "No scores yet!";
            }

        return $week;
    }
}
    $scoreBoard = new ScoreBoard;
    $show = $scoreBoard->getUserQuiz(60);
    $History = $scoreBoard->quizStats();
    $todayTopRanker = $scoreBoard->top_score_today();
    $topRanker = $scoreBoard->top_cummulative();

    print_r(json_encode($show));
    //echo $show['lifeline']. '<br>'
    print_r(json_encode($todayTopRanker));
    //echo $todayTopRanker['FullName'].'<br>';
    print_r(json_encode($History));
    print_r(json_encode($topRanker));


    if (!isset($_GET['meth'])){
        $cat = $scoreBoard->scoreboard_cummulative();
        print_r(json_encode($cat));
    }else{
        $value = $_GET['meth'];
        if($value== 'TodayBoard'){
            $Today = $scoreBoard->scoreboard_today();
            print_r(json_encode($Today));

        }else{
            if($value== 'WeekBoard'){
                $Week = $scoreBoard->week_cummulative();
                print_r(json_encode($Week));

            }else{
               if($value=="All"){
                   $cat = $scoreBoard->scoreboard_cummulative(0);
                   print_r(json_encode($cat));

               }else{
                    if($value=="ContemIssue"){
                        $cat = $scoreBoard->scoreboard_cummulative(1);
                        print_r(json_encode($cat));

                    }else{
                        if($value == "Hadith"){
                            $cat = $scoreBoard->scoreboard_cummulative(2);
                            print_r(json_encode($cat));
                        }else{
                            if($value == "Fiqh"){
                                $cat = $scoreBoard->scoreboard_cummulative(3);
                                print_r(json_encode($cat));
                            }else{
                                if($value == "Qur'an"){
                                    $cat = $scoreBoard->scoreboard_cummulative(4);
                                    print_r(json_encode($cat));
                                }else{
                                    if($value == "Tauheed"){
                                        $cat = $scoreBoard->scoreboard_cummulative(5);
                                        print_r(json_encode($cat));
                                    }else{
                                        echo "Url does not exist!";
                                    }
                                }
                            }
                        }
                    }
               }
            }
        }
    }
    
    // }
?>
<?php
// $qz3 = new Quiz();
 
?>