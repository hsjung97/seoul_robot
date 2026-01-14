<?php
    $conn = mysqli_connect("localhost", "iot", "pwiot", "iotdb");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // 테이블의 모든 데이터를 삭제하고 ID 자동증가 값을 1로 초기화
    $sql = "TRUNCATE TABLE hsj"; 
    
    if (mysqli_query($conn, $sql)) {
        echo "Success";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
?>
