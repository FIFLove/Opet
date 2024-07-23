<!--
* @author   : 김기현
* @fileName : modify.php
* @date     : 2023.11.23. 오후 10:13
* @desc	 : 글 삭제 페이지
-->

<?php 
    session_start();
    include  "db.php"; 
    $idx = $_GET['idx'];
    $writer = select("SELECT USERID FROM post WHERE IDX = '$idx'");
    $writerID = $writer[0]['USERID'];
    if ((!isset($_SESSION["userID"]) || $_SESSION["userID"] != $writerID)) {
        $error = "글 작성자 만이 삭제하실 수 있습니다.";
        $ee = $userID;
        echo "<script>
        alert('$error'.'$userID'.'$$writerID');
            history.back();</script>";
        exit;
    }
	insert("DELETE FROM post WHERE IDX='$idx'");
    insert("DELETE FROM item WHERE IDX='$idx'");
	echo "<script>
        alert('글 삭제가 완료되었습니다.');
        location.href='salepage.php';</script>";
?>