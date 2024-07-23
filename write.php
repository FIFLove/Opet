<!--
* @author   : 김기현
* @fileName : db.php
* @date     : 2023.11.03. 오후 4:28
* @desc	 : 글 작성 페이지
-->

<?php 
    session_start();
    include "db.php";
    if (!isset($_SESSION["userID"])) {
        $error = "로그인 후에 글을 작성하실 수 있습니다.";
        echo "<script>
            alert('$error');
            location.href='login.php';</script>";
        exit;
    }
?>
<!doctype html>
<head>
<meta charset="UTF-8">
<title>글쓰기</title>
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
                <form action="write_ok.php" method="post" enctype="multipart/form-data">
                <div class="board_box_2">
                        <input type = "text" class = "title" placeholder="자유게시판" disabled>
                    </div>
                    <div class="board_box_2">
                        <input type ="text" name="title" class = "title" placeholder="제목을 입력해주세요." maxlength="30" required><hr/>
                        <textarea style="resize: none;" type ="text" name="detail" class = "detail" placeholder="내용을 입력해주세요." maxlength="5000" required ></textarea><br/>
                    </div>
                    <div class="board_box_2">
                        <button class = "imgbtn"><img src="img/imageimg.png" class = "imgimg" style="margin-top:5px"/></button>
                        <input type="file" name="image" id="image" accept="image/*"><p class ="imgText">  </p>
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