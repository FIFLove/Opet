<!--
* @author   : 김기현
* @fileName : modify.php
* @date     : 2023.11.19. 오후 8:19
* @desc	 : 자유 게시판 글 수정 페이지
-->

<?php 
    session_start();
    include "db.php"; 
    $idx = $_GET['idx'];
    $result = select("SELECT USERID FROM post WHERE IDX = '$idx'");
    $writerID = $result[0]['USERID'];
    if ((!isset($_SESSION["userID"]) || $_SESSION["userID"] != $writerID)) {
        $error = "글 작성자만이 글을 수정할 수 있습니다.";
        echo "<script>
            alert('$error');
            history.back();</script>";
        exit;
    }
    
	$post = select("SELECT * FROM post WHERE IDX='$idx'");
?>

<!doctype html>
<head>
<meta charset="UTF-8">
<title>글수정</title>
<link rel="stylesheet" type="text/css" href="css/write.css" />
</head>
<body>
    <!--헤더-->
    <div class="header">
    <div class="menupage">
          <a href="index.php"><img src="img/logoW.png" alt="OP.GG" height="32"></a>
          <a href="index.php" class ="postselect">홈</a>
          <a href="pagelist.php" class ="postselect" style="color: orange;">자유</a>
          <a href="salepage.php" class ="postselect">판매</a>
          <a href="petpage.php" class ="postselect">동물</a>
      </ul>
    </div>
    <div class="">
      <div class="loginbox">
      </div>
    </div>
    </div>
    <div class="board_area">
        <div class="board_box_1">
                <form action="modify_ok.php?idx=<?php echo $idx?>" method="post" enctype="multipart/form-data">
                <div class="board_box_2">
                        <input type = "text" class = "title" placeholder="자유게시판" disabled>
                    </div>
                    <div class="board_box_2">
                        <input type ="text" name="title" class = "title" placeholder="제목을 입력해주세요." maxlength="30" value="<?php echo $post[0]['TITLE']?>" required><hr/>
                        <textarea style="resize: none;" type ="text" name="detail" class = "detail" placeholder="내용을 입력해주세요." maxlength="5000" required ><?php echo $post[0]["DETAIL"]?></textarea><br/>
                    </div>
                    <div class="board_box_2">
                        <button class = "imgbtn"><img src="img/imageimg.png" class = "imgimg" style="margin-top:5px"/></button>
                        <input type="file" name="image" id="image" accept="image/*" value="<?php echo $post[0]['POSTIMAGE']?>"><p class ="imgText">  </p>
                    </div>
                    <div class="btn_box submit_box">
                        <button class = "canclebtn"><a href="pagelist.php">취소</a></button>
                        <button type="submit" class = "submitbtn">작성 완료</button>
                    </div>
                </form>
        </div>
    </div>
    </body>
    </html>