<?php
include 'db.php'; // db.php 파일을 포함합니다.

// SQL 쿼리를 통해 BLOB 불러오기
$sql = "SELECT IMAGE FROM Q";
$result = selectWithBlob($sql);

if ($row = oci_fetch_assoc($result)) {
    // 결과에서 BLOB 데이터 가져오기
    $imageBlob = $row['IMAGE']->load();

    // BLOB를 이미지로 변환하여 출력
    header('Content-Type: image/jpeg');
    echo $imageBlob;
} else {
    echo "No image found";
}

oci_free_statement($result);
oci_close($connect);

function selectWithBlob($sql) {
    global $connect;

    $stid = oci_parse($connect, $sql);
    oci_execute($stid);
    return $stid;
}
?>
