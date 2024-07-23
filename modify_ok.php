<!--
* @author   : 김기현
* @fileName : modify_ok.php
* @date     : 2023.11.19. 오후 8:25
* @desc	 : 글 수정 시 데이터베이스 응답 반환
-->

<?php
    session_start();
    include "db.php";
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $idx = $_GET['idx'];
    $result = select("SELECT USERID FROM post WHERE IDX = '$idx'");
    $writerID = $result[0]['USERID'];
     
    $result = select("SELECT POSTIMAGE FROM post WHERE IDX = '$idx'");

    if ((!isset($_SESSION["userID"]) || $_SESSION["userID"] != $writerID)) {
        $error = "글 작성자만이 글을 수정할 수 있습니다.";
        echo "<script>
            alert('$error');
            history.back();</script>";
        exit;
    }

    $userID = $_SESSION["userID"];
    $title = $_POST["title"];
    $detail = $_POST["detail"];
    $date = date('Y-m-d');
    if (empty($title)) {
        $error = "제목을 입력해주세요.";
    }
    else if (empty($detail)) {
        $error = "내용을 입력해주세요.";
    } else {
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
            $insertProfileQuery = "UPDATE post SET TITLE='$title', DETAIL='$detail', POSTDATE=TO_DATE('$date', 'YYYY-MM-DD'),
                                        POSTIMAGE=EMPTY_BLOB() WHERE IDX='$idx')
            RETURNING POSTIMAGE INTO :blobData";
            if(insertImage($insertProfileQuery, $imageData)) {
                echo "<script>
                    alert('글 수정이 완료되었습니다.');
                    location.href='pagelist.php';</script>";
            }        
            else {
                echo "<script>
                alert('글 수정에 실패했습니다.');</script>";
            }
        }
        else { echo "alert('이미지 없이 글을 수정');"; 
            if(insert("UPDATE post SET TITLE='$title', DETAIL='$detail', POSTDATE=TO_DATE('$date', 'YYYY-MM-DD') WHERE IDX='$idx'"))  {
                    echo "<script>
                        alert('글 수정이 완료되었습니다.');
                        location.href='pagelist.php';</script>";
            }        
            else {
                    echo "<script>
                    alert('글 수정에 실패했습니다.');
                    location.href='pagelist.php';</script>";
            }
        }
    }
    if(!empty($error)){
            echo "<script>
            alert($error);
            history.back();</script>";
    }
?>