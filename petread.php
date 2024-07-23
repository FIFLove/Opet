<!--
* @author   : 김기현
* @fileName : saleread.php
* @date     : 2023.11.27. 오후 10:28
* @desc	 : 동물게시판 글 열람 페이지
-->

<?php 
  session_start();
  ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
  include  "db.php"; 
  // 사용자가 로그인한 경우
  if (isset($_SESSION["userID"])) {
    $userID = $_SESSION["userID"];
    $result = select("SELECT POINT FROM users WHERE USERID = '$userID'");
    $point = $result[0]['POINT'];
    $pointText = $_SESSION["userID"] ."님의 포인트: " .$point. "point";  
    $loginText = "마이페이지"; 
    $signupText = "로그아웃";
    $loginLink = "mypage.php?after";
    $signupLink = "logout.php?after";
  } else {
    // 사용자가 로그인하지 않은 경우
    $pointText = "";
    $loginText = "로그인";
    $signupText = "회원가입";
    $loginLink = "login.php?after";
    $signupLink = "signup.php?after";
  }
?>
<!doctype html>
<head>
<meta charset="UTF-8">
<title>OPetGG</title>
<link rel="stylesheet" type="text/css" href="css/read.css" />
</head>
<body>
<!--헤더-->
<div class="header">
    <div class="menupage">
          <a href="index.php"><img src="img/logoW.png" alt="OP.GG" height="32"></a>
          <a href="index.php" class ="postselect">홈</a>
          <a href="pagelist.php" class ="postselect">자유</a>
          <a href="salepage.php" class ="postselect">판매</a>
          <a href="petpage.php" class ="postselect" style="color: orange;">동물</a>
          <a href="<?php echo $signupLink; ?>" class = "logBtn"><?php echo $signupText; ?></a>
          <a href="<?php echo $loginLink; ?>" class = "logBtn"><?php echo $loginText; ?></a>
          <p class = "logBtn"><?php echo $pointText; ?></p>
      </ul>
    </div>
    <div class="">
      <div class="loginbox">
      </div>
    </div>
  </div>
	<?php
		$idx = $_GET['idx'];
		$post = select("SELECT * FROM post WHERE IDX='$idx'");
		$hit = $post[0]['HIT'] + 1;
		insert("UPDATE post SET HIT = '$hit' WHERE IDX = '$idx'");
    $pet = select("SELECT * FROM pet WHERE IDX='$idx'");
    $habitat = $pet[0]["HABITAT"];
	?>
<!-- 글 불러오기 -->
<div class="post_box">
<button class = "canclebtn"><a href="pagelist.php">목록</a></button>
  <button class = "canclebtn"><a href="modify.php?idx=<?php echo $post[0]['IDX']; ?>">수정</a></button>
  <button class = "canclebtn"><a href="delete.php?idx=<?php echo $post[0]['IDX']; ?>">삭제</a></button>
  <button class = "canclebtn" style ="background-color: orange; width: 80px; text-align: center;"><a href="petinterest_ok.php?idx=<?php echo $post[0]['IDX']; ?>">관심</a></button>
	동물 게시판
	<h2><?php echo $post[0]['TITLE']; ?></h2>
	<div class="writer_info">
        작성자:<?php 
        $writer = select("SELECT * FROM users WHERE USERID='$userID'");
        echo $writer[0]['NICKNAME']; ?> 
		작성일:<?php echo $post[0]['POSTDATE']; ?> 
		조회수:<?php echo $post[0]['HIT']; ?>
        서식지:<?php echo $pet[0]['HABITAT']; ?>
	</div>
	<hr />
	<div id="bo_content">
        <div id="bo_content">
            <?php 
                if (!is_null($post[0]['POSTIMAGE'])) {
                $postImage = base64_decode($post[0]['POSTIMAGE']->load());
            ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($postImage); ?>" alt="Image" class="postImage"><br />
            <?php } ?>
            <?php echo $post[0]['DETAIL']; ?>
		</div>
	</div>
</div>
<div class="post_box">
  <?php echo $pet[0]['PETNAME'] ?>에게 관심을 가져주신 분들
  <div class = "postlist">
    <ul class = "cf">
      <?php
        $postnum = 0;
        // board테이블에서 idx를 기준으로 내림차순해서 8개까지 표시
        $users = select("SELECT * FROM users WHERE USERID IN (SELECT USERID FROM interest WHERE IDX = '$idx')");
        // fetch_array()는 sql로 검색된 데이터들을 하나씩 반환한다. 즉 foreach문의 iterator 역할을 한다.
        foreach ($users as $user)
        {
          $nickname = $user["NICKNAME"]; 
          if(is_null($user['PROFILEIMAGE'])) {
            $profileImage = file_get_contents("img/default_profile.png");
          }
          else {
              $profileImage = base64_decode($user['profileimage']->load());
          } ?>
          <li class = "postitem">
            <div class="thumbbox">
            <img src="data:image/jpeg;base64,<?php echo base64_encode($profileImage); ?>" alt="Image" class="postImage">
            </div>
            <div class="desc">
            <strong class="nickname"><?php echo $nickname ?></strong>
            </div>
          </li>
          <?php
          $postnum++;
          if($postnum % 10 == 0) { ?> <br /> <?php } 
        } 
      ?>
    </ul>
  </div>
</div>
</body>
</html>