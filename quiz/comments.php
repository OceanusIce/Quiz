<?php 
    include_once('db/conn.php');
    // $a=1;
    // $stmt = $conn->prepare("SELECT * FROM comments");
    // $stmt->execute();
    // $users = $stmt->fetch();
    // foreach($users as $user) 
    // {
    //     echo $user['date_time'];
    //     echo $user['rating'];
    //     echo $user['experience'];
    //     echo $user['fullname'];
    // }
?>
<?php
// $q = mysqli_query($con, "SELECT `experience`, `details`, `fullname`, `rating`, ADDDATE(`date_time`, INTERVAL '6' HOUR) as datetimer FROM `comments` ORDER BY `date_time` DESC;") or die(mysqli_error($con));
// if(mysqli_num_rows($q) > 0){
// 	//$comments="";
//     $comments=array();
//     $catigory=array();
//     $date=array();
//     $rate=array();
//     $userName=array();
//     $comments = array('catigory'=>)
// 	while($row = mysqli_fetch_array($q)){
		
// 		$comments .= "<blockquote><p style=\"font-style:italic;\">".$row['experience']."</p>".$row['details']."<small> Comment by ".$row['fullname']." [Rating: ".$row['rating']."/5] on ".$row['datetimer']."</small></blockquote>";
//         array_push($comments,$row['details']);
//         array_push($catigory,$row['experience']);
//         array_push($date,$row['datetimer']);
//         array_push($rate,$row['rating']);
//         array_push($userName,$row['fullname']);
//     }
// }else{
// 	$comments = "No Comments Yet!";
// }
?>
<?php
    // print_r($date[0]);
    //fetch table rows from mysql db
    // $sql = "SELECT * from comments";
    // $result = mysqli_query($con, $sql) or die("Error in Selecting " . mysqli_error($connection));

    // create an array
    // $emparray = array();
    // while($row =mysqli_fetch_assoc($result))
    // {
    //     $emparray[] = $row;
    // }
    // json_encode($emparray);
    // echo $emparray['2']["id"];
?>
<?php
    // variable to store number of rows per page

    $limit = 5;  

    // query to retrieve all rows from the table Countries

    $getQuery = "SELECT * FROM comments";    

    // get the result

    $result = mysqli_query($con, $getQuery);  

    $total_rows = mysqli_num_rows($result);    

    // get the required number of pages

    $total_pages = ceil ($total_rows / $limit);    

    // update the active page number

    if (!isset ($_GET['page']) ) {  

        $page_number = 1;  

    } else {  

        $page_number = $_GET['page'];  

    }

    // get the initial page number

    $initial_page = ($page_number-1) * $limit;   

    // get data of selected rows per page    

    $getQuery = "SELECT *FROM comments LIMIT " . $initial_page . ',' . $limit;  

    $result = mysqli_query($con, $getQuery);       

    //display the retrieved result on the webpage  
    $emparray = array();
    while ($row = mysqli_fetch_array($result)) {  
        //create an array
    $emparray[] = $row;
        //echo $row['id'] . ' ' . $row['details'] . '</br>';  
    }
    print_r(json_encode($emparray));
    //echo '<br>'.$emparray[0]['details'];

    // show page number with link   

    // for($page_number = 1; $page_number<= $total_pages; $page_number++) {  

    //     echo '<a href = "comments.php?page=' . $page_number . '">' . $page_number . ' </a>';  

    // }
?>