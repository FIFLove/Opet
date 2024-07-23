<!--
* @author   : 김기현
* @fileName : petinterest_ok.php
* @date     : 2023.11.27. 오후 10:07
* @desc	 : 동물 관심 등록 시 데이터베이스 응답 반환
-->

<?php
    session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    include "db.php";
    if (!isset($_SESSION["userID"])) {
        $error = "로그인 후에 관심을 등록할 수 있습니다.";
        echo "<script>
            alert('$error');
            location.href='login.php';</script>";
        exit;
    }
    
    $userID = $_SESSION["userID"];
    $idx = $_GET["idx"];
    if (insert("INSERT INTO interest(USERID, IDX) VALUES ('$userID', '$idx')")) {
        echo "<script>
        alert('관심등록했습니다!');
        history.back();</script>";
    }
    else {
        echo "<script>
        alert('이미 관심 등록을 하셨습니다!');
        history.back();</script>";
    }
        
    if(!empty($error)){
        echo "<script>
        alert($error);
        history.back();</script>";
    }
?>