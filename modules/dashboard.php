<?php
  include 'config1.php';
	
	$todayYMD = date("Y-m-d");
	$today = date("d/m/Y");
	$todayQuery = date("d-m-Y");
	$todayTimestamp = strtotime($today);
	$userId = $_SESSION['uid'];
?>

<div class="panel panel-warning">
  <div class="panel-heading">
   

<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Today's Attendance</h3>
  </div>
  <div class="panel-body">
		<?php
			
		
			$query_subject = "SELECT subject.name, subject.id from subject INNER JOIN user_subject WHERE user_subject.id = subject.id AND user_subject.uid = {$_SESSION['uid']}  ORDER BY subject.name";
			$sub=$conn->query($query_subject);
			$rsub=$sub->fetchAll(PDO::FETCH_ASSOC);
			$today = date("d/m/Y");
			$todayQuery = date("d-m-Y");
			$todayDBQuery = strtotime(date("Y-m-d"));
			$noOfSubject = count($rsub);
			
			for($i = 0; $i<$noOfSubject; $i++) {
				$subId = $rsub[$i]['id'];
				$sql = "SELECT sid, ispresent FROM attendance WHERE id=$subId AND date=$todayDBQuery AND uid=$userId";
				$stmt = $conn->prepare($sql); 
				$stmt->execute();
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
				if(!empty($result)){
					print "<p><a href='index.php?subject=" . $subId . "&date=" . $todayQuery ."'>Class <strong>" . $rsub[$i]['name'] ."</strong> of <strong>Today's</strong> (" . $today .")</a> <span class='label label-success'>Attendance Recoreded</span> </p>";
				}
				else {
					print "<p><a href='index.php?subject=" . $subId . "&date=" . $todayQuery ."'>Class <strong>" . $rsub[$i]['name'] ."</strong> of <strong>Today's</strong> (" . $today .")</a> <span class='label label-warning'>Mark Attendance Now!</span></p>";
				}
			}
		?>
  </div>
</div>

<div class="panel panel-success">
  <div class="panel-heading">
    <h3 class="panel-title">You have</h3>
  </div>
  <div class="panel-body">
    <p><i class="fa fa-book"></i> <a href="index.php?page=studentinfo"> <strong><?php print $noOfSubject; ?></strong>  Branches </a></p>
		<?php
			$studentQuery = "SELECT COUNT(DISTINCT sid) as student_count FROM `user_subject` INNER JOIN student_subject WHERE user_subject.id = student_subject.id AND user_subject.uid = $userId";
			$stmtStudent = $conn->prepare($studentQuery); 
			$stmtStudent->execute();
			$resultStudent = $stmtStudent->fetchAll(PDO::FETCH_ASSOC); 
		?>
		
		<?php if(!empty($resultStudent)) : ?>
			<p><i class="fa fa-users"></i> <a href="index.php?page=studentinfo"><strong><?php print $resultStudent[0]['student_count'] ?></strong> Students</a></p>
		<?php else: ?>
			<p><i class="fa fa-users"></i> No Students assigned to you!</p>
		<?php endif; ?>
  </div>
</div>

