<!--
* @author   : 김기현
* @fileName : write_ok.php
* @date     : 2023.11.03. 오후 4:28
* @desc	 : 글 작성 시 데이터베이스 응답 반환
-->

<?php
    session_start();
    include "db.php";
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    if (!isset($_SESSION["userID"])) {
        $error = "로그인 후에 글을 작성하실 수 있습니다.";
        echo "<script>
            alert('$error');
            location.href='login.php';</script>";
        exit;
    }
    // 각 변수에 write.php에서 input name값들을 저장한다
    $userID = $_SESSION["userID"];
    $result = select("SELECT POINT FROM users WHERE USERID = '$userID'");
    $point = $result[0]['POINT'];
    $title = $_POST["title"];
    $cost = $_POST["cost"];
    $detail = $_POST["detail"];
    $date = date('Y-m-d');
    if (empty($title)) {
        $error = "제목을 입력해주세요.";
    }
    else if (empty($detail)) {
        $error = "내용을 입력해주세요.";
    } else {
        $result = select("SELECT max(post.IDX) AS IDX FROM post");
        $maxIdx = $result[0]['IDX'];
        $posttype = "SALE";
        if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_FILES['image']['name'])) {
            $uploadOk = 1;
            $error = "";
            
            // 파일타입 및 확장자 체크
            $fileTypeExt = explode("/", $_FILES['image']['type']);
            $allowedExtensions = ["jpg", "jpeg", "png"];

            // 확장자 추출
            $fileExtension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);

            // 허용된 확장자인지 확인
            if (!in_array($fileExtension, $allowedExtensions)) {
                $error = $error . "죄송합니다. JPG, JPEG, PNG 파일만 허용됩니다.";
                $uploadOk = 0;
            }

            // 이미지 파일인지 확인
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if($check != false) {
                $uploadOk = 1;
            } else {
                $error = $error . "파일이 이미지가 아닙니다.";
                $uploadOk = 0;
            }

            // 파일 크기 제한 (선택사항)
            if ($_FILES["image"]["size"] > 50000) {
                $error = $error . "죄송합니다. 파일이 너무 큽니다.";
                $uploadOk = 0;
            }

            if ($uploadOk == 0) {
                $error = $error . "파일 업로드 실패.";
                echo "<script>
                alert($error);
                history.back();</script>";
            } else {
                // 데이터베이스에 이미지 정보 저장
                $imageData = file_get_contents($_FILES["image"]["tmp_name"]);
                $imageData = base64_encode($imageData);
            }
            $insertProfileQuery = "INSERT INTO post (IDX, USERID, TITLE, DETAIL, POSTDATE, HIT, POSTIMAGE, POSTTYPE) 
                VALUES ('$maxIdx'+1, '$userID', '$title', '$detail', TO_DATE('$date', 'YYYY-MM-DD'), 0, EMPTY_BLOB(), '$posttype')
                RETURNING POSTIMAGE INTO :blobData";
            if(insertImage($insertProfileQuery, $imageData)) {
                insert("INSERT INTO item(IDX, ITEMNAME, COST, ISSOLD) VALUES ('$maxIdx'+1, '$title', '$cost', 0)");
                insert("UPDATE users SET POINT = '$point'+10 WHERE USERID = '$userID'");
                echo "<script>
                    alert('글 작성이 완료되었습니다.');
                    location.href='salepage.php';</script>";
            }        
            else {
                echo "<script>
                alert('글 작성에 실패했습니다.');
                location.href='salepage.php';</script>";
            }
        } else {
            if (insert("INSERT INTO post (IDX, USERID, TITLE, DETAIL, POSTDATE, HIT, POSTTYPE) VALUES ('$maxIdx'+1, '$userID', '$title', '$detail', TO_DATE('$date', 'YYYY-MM-DD'), 0, '$posttype')")) {
                insert("INSERT INTO item(IDX, ITEMNAME, COST, ISSOLD) VALUES ('$maxIdx'+1, '$title', '$cost', 0)");
                insert("UPDATE users SET POINT = '$point'+10 WHERE USERID = '$userID'");
                echo "<script>
                alert('글 작성이 완료되었습니다.');
                location.href='salepage.php';</script>";
            }
            else {
                echo "<script>
                alert('글 작성에 실패했습니다.');
                location.href='salepage.php';</script>";
            }
        }
    }
    if(!empty($error)){
        echo "<script>
        alert($error);
        history.back();</script>";
    }
?>