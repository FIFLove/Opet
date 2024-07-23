<!--
* @author   : 김기현
* @fileName : saleread.php
* @date     : 2023.11.03. 오후 4:28
* @desc	 : 글 작성 페이지
-->

<?php
    session_start();
    include "db.php";
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    if (!isset($_SESSION["userID"])) {
        $error = "로그인 후에 구매하실 수 있습니다.";
        echo "<script>
            alert('$error');
            location.href='/login.php';</script>";
        exit;
    }

    $userID = $_SESSION["userID"];
    $user = select("SELECT * FROM users WHERE USERID = '$userID'");
    $userpoint = $user[0]['POINT'];

    $idx = $_GET["idx"];
    $item = select("SELECT * FROM item WHERE IDX = '$idx'");
    $itemcost = $item[0]["COST"];

    if($userpoint < $itemcost) {
        $error = "포인트가 부족합니다.";
        echo "<script>
            alert('$error');
            history.back();</script>";
        exit;
    } else {
        $post = select("SELECT * FROM post WHERE IDX = '$idx'");
        $salerID = $post[0]["USERID"];
        $saler = select("SELECT * FROM users WHERE USERID = '$salerID'");
        $salerpoint = $saler[0]['POINT'];
        insert("UPDATE users SET point='$userpoint'-'$itemcost' WHERE USERID = '$userID'");
        insert("UPDATE users SET point='$salerpoint'+'$itemcost' WHERE USERID = '$salerID'");
        insert("UPDATE item SET ISSOLD = 1 WHERE IDX = '$idx'");
        insert("UPDATE post SET TITLE = CONCAT('[SOLD OUT] ', TITLE) WHERE IDX = '$idx'");
        $success = "구매가 완료되었습니다!";
        echo "<script>
            alert('$success');
            history.back();</script>";
        exit;
    }
?>