<!--
* @author   : 김기현
* @fileName : db.php
* @date     : 2023.11.13. 오후 4:28
* @desc	 : 메인 페이지
-->

<?php
session_start();
include  "db.php"; 
// 사용자가 로그인한 경우
if (isset($_SESSION["userID"])) {
  $welcomeText = "환영합니다, " . $_SESSION["userID"] . "님!";
  $loginText = "마이페이지";
  $signupText = "로그아웃";
  $loginLink = "mypage.php?after";
  $signupLink = "logout.php?after";
  $gotologinText = "환영합니다! 첫 글을 작성해주세요!";
  $gotologinLink = "pagelist.php?after";
} else {
  // 사용자가 로그인하지 않은 경우
  $welcomeText = "";
  $loginText = "로그인";
  $signupText = "회원가입";
  $loginLink = "login.php?after";
  $signupLink = "signup.php?after";
  $gotologinText = "로그인 후 첫 글을 작성하러 가보세요!";
  $gotologinLink = "login.php?after";
}
?>
<!DOCTYPE html>
<head>
  <meta charset="UTF-8">
  <title>OPetGG</title>
  <link rel="stylesheet" href="css/mainpage.css?after" /> <!--?after를 경로에 붙여서 캐시에서 css 파일을 가져오는것을 방지-->
</head>
<body style="overflow-x: hidden">
<div class="backgroundImage2"></div>
  <!--헤더-->
  <div class="header">
    <div class="contect">
      Contact us <strong>watergon@naver.com</strong> 
    </div>
  </div>
  <div class="header">
    <div class="logo">
        <a href="index.php"><img src="img/logoO.png" alt="OP.GG" height="32"></a>
    </div>
    <div class="header">
      <div class="loginbox">
        <h4><?php echo $welcomeText; ?></h4>
        <a href="<?php echo $loginLink; ?>" class = "logBtn"><?php echo $loginText; ?></a>
        <a href="<?php echo $signupLink; ?>" class = "logBtn"><?php echo $signupText; ?></a>
      </div>
    </div>
  </div>
  <div class="welcome">
    <h1>회원가입이 완료되었습니다.</h1>
    <h1>저희와 함께해주셔서 감사합니다.</h1>
    <br>
    <br>
    <a href="<?php echo $loginLink; ?>"><?php echo $gotologinText; ?></a>
  </div>
</body>
</html>