<!--
* @author   : 김기현
* @fileName : db.php
* @date     : 2023.11.03. 오후 4:28
* @desc	 : 글 목록 페이지
-->

<?php 
  session_start();
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
  <link rel="stylesheet" href="css/pagelist.css"/>
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
          <a href="<?php echo $signupLink; ?>" class = "logBtn"><?php echo $signupText; ?></a>
          <a href="<?php echo $loginLink; ?>" class = "logBtn"><?php echo $loginText; ?></a>
          <p class = "logBtn"><?php echo $pointText; ?></p>
      </ul>
    </div>
  </div>
  <div class="pagetop">
    <div class="postnamebox">
      <div class="postname"><strong>자유게시판</strong></div>
      <div class="postnameeng"><strong>FREE POST</strong></div>
    </div>
    <div class="rightbar"></div>
    <div class="leftbar"></div>
    <img class="postimage" src="../img/pawinhand/hero_adapt.png" alt="">
  </div>
      <a href="write.php" ><button class="write_btn"> 글쓰기 </button></a>
    <br />
    <br />
  <div class = "postlist">
  <ul class = "cf">
    <?php
      $postnum = 0;
      // board테이블에서 idx를 기준으로 내림차순해서 8개까지 표시
      // $posts = select("SELECT * FROM post WHERE postType='FREE' ORDER BY idx DESC LIMIT 0,8");
      $posts = select("SELECT * FROM (SELECT * FROM post WHERE POSTTYPE='FREE' ORDER BY IDX DESC) WHERE ROWNUM <= 8");

      // fetch_array()는 sql로 검색된 데이터들을 하나씩 반환한다. 즉 foreach문의 iterator 역할을 한다.
      foreach ($posts as $post)
      {
        //title변수에 DB에서 가져온 title을 선택
        $title=$post["TITLE"]; 
        if(strlen($title)>10)
        { 
          //title이 30을 넘어서면 ...표시
          $title=str_replace($post["TITLE"],mb_substr($post["TITLE"],0,10,"utf-8")."...",$post["TITLE"]);
        }
        $detail=$post["DETAIL"]; 
        if(strlen($detail)>30)
        { 
          $detail=str_replace($post["DETAIL"],mb_substr($post["DETAIL"],0,30,"utf-8")."...",$post["DETAIL"]);
        }
        if(is_null($post["POSTIMAGE"])) {
          $postImage = file_get_contents("img/default_profile.png");
        }
        else {
          $postImage = base64_decode($post["POSTIMAGE"]->load());
        }
        ?>
        <a href="read.php?idx=<?php echo $post["IDX"];?>">
        <li class = "postitem">
          <div class="thumbbox">
              <img src="data:image/jpeg;base64,<?php echo base64_encode($postImage); ?>" alt="Image" class="postImage"><br />
            </div>
            <div class="desc">
              <strong class="title"><?php echo $post['TITLE']?></strong>
              <p class ="detail"><?php echo $post['DETAIL']?></p>
          </div>
          <div class="imgpath">
            </div>
        </li>
        </a>
        <?php 
         $postnum++;
          if($postnum % 4 == 0) {
          ?>
          <br>
          <?php
          }
        }?>
      </ul>
      </div>
  </div>
</body>
</html>