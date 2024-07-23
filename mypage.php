<!--
* @author   : 김기현
* @fileName : mypage.php
* @date     : 2023.11.19. 오후 10:39
* @desc	 : 마이페이지 반환
-->

<?php
    session_start();
    include "db.php";
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    if (!isset($_SESSION["userID"])) {
        $error = "로그인 후에 마이페이지에 접근하실 수 있습니다.";
        echo "<script>
                alert('$error');
                location.href='login.php';</script>";
        exit;
    }

    $userID = $_SESSION["userID"];
    $user = select("SELECT * FROM users WHERE USERID = '$userID'");
    $nickname = $user[0]['NICKNAME'];
    $statemsg = $user[0]['STATEMESSAGE'];
    $password = $user[0]['PASSWORD'];
    $point = $user[0]['POINT'];
    if(is_null($user[0]['PROFILEIMAGE'])) {
        $profileimage = file_get_contents("img/default_profile.png");
    }
    else {
        $profileimage = base64_decode($user[0]['PROFILEIMAGE']->load());
    }
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>OPetGG</title>
    <link rel="stylesheet" href="css/mypage.css?after" />
</head>
<body>
   <!--헤더-->
  <div class="header">
    <div class="menupage">
          <a href="index.php"><img src="img/logoW.png" alt="OP.GG" height="32"></a>
          <a href="index.php" class ="postselect">홈</a>
          <a href="pagelist.php" class ="postselect">자유</a>
          <a href="salepage.php" class ="postselect">판매</a>
          <a href="petpage.php" class ="postselect">동물</a>
      </ul>
    </div>
  </div>
<div class="profile_box">
    <div class="profile_box_1">
        <div class="profile_box_2">
            <img src="data:image/jpeg;base64,<?php echo base64_encode($profileimage); ?>" alt="Image" class="profile_image"><br />
        </div>
            <div class="nickname"><?php echo $nickname ?><br /></div>
            <form action="statemsgchange_ok.php" method="post">
            <textarea style="resize: none;" type ="text" name="statemsg" class = "statemsg" placeholder="상태메세지를 입력해주세요." maxlength="50" required rows = "1" onkeypress="check_enter()"><?php echo $statemsg; ?></textarea> <hr/>
            </form>
            <div class="profileimg_change_box">
                <form action="profilechange_ok.php" method="post" enctype="multipart/form-data">
                프로필 이미지 변경</br>
                <input type="file" name="image" id="image" accept="image/*" style ="margin-top: 5px">
                <br>
                <input type="submit" value="업로드" style ="margin-top: 5px">
            </form>
            보유중인 포인트 : <?php echo $point ?>point
            </div>
    </div>
    <div class="profile_box_3">
    <div class="phara1"><?php echo $nickname?>님이 작성한 게시글</div>
      <div class="postlist">
        <ul class = "cf">
          <?php
            $postnum = 0;
            // board테이블에서 idx를 기준으로 내림차순해서 8개까지 표시
            $posts = select("SELECT * FROM (SELECT * FROM post WHERE USERID = '$userID' ORDER BY IDX DESC ) WHERE ROWNUM <= 4");
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
              if(strlen($title)>30)
              { 
                $detail=str_replace($post["DETAIL"],mb_substr($post["DETAIL"],0,30,"utf-8")."...",$post["DETAIL"]);
              }
              if(is_null($post["POSTIMAGE"])) {
                $postImage = file_get_contents("img/default_profile.png");
              }
              else {
                $postImage = base64_decode($post['POSTIMAGE']->load());
              }?>
              <a href="read.php?idx=<?php echo $post["IDX"];?>">
                <li class = "postitem">
                  <div class="thumbbox">
                    <!--img class ="postImage" src = "/img/background2.jpg"-->
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
              <?php $postnum++; if($postnum % 4 == 0) { ?> <br>
              <?php } 
            }
          ?>
        </ul>
      </div>
    <div class="phara1"><?php echo $nickname?>님이 관심등록한 동물</div>
    <div class="postlist">
      <ul class = "cf">
        <?php
          $postnum = 0;
          $posts = select("SELECT * FROM (SELECT * FROM post WHERE IDX IN (SELECT IDX FROM interest WHERE USERID = '$userID') ORDER BY IDX DESC ) WHERE ROWNUM <= 4");
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
            if(strlen($title)>30)
            { 
              $detail=str_replace($post["DETAIL"],mb_substr($post["DETAIL"],0,30,"utf-8")."...",$post["DETAIL"]);
            }
            if($post['POSTIMAGE']=="") {
                $postImage = file_get_contents("img/default_profile.png");
            }
            else {
              $postImage = base64_decode($post['POSTIMAGE']->load());
            }
            ?>
            <a href="read.php?idx=<?php echo $post["IDX"];?>">
            <li class = "postitem">
              <div class="thumbbox">
                <!--img class ="postImage" src = "/img/background2.jpg"-->
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
            <?php $postnum++; if($postnum % 4 == 0) { ?> <br> <?php }
        }?>
    </ul>
  </div>
  </div>
</div>
</body>
</html>
