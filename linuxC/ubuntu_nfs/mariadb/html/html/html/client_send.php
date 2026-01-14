<?php
// 1. 명령어 받기 (기본값 OFF)
$cmd = isset($_GET['cmd']) ? $_GET['cmd'] : 'OFF';
$host = "127.0.0.1";
$port = 5000;

// 2. 소켓 생성
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if (!$socket) die("Socket Create Error");

// 3. 서버 접속
if (@socket_connect($socket, $host, $port)) {

    // [인증] 터미널에서 사람이 치는 것과 똑같이 \n 하나만 붙입니다.
    $login_msg = "[HSJ_WEB:PASSWD]\n";
    socket_write($socket, $login_msg, strlen($login_msg));

    // 인증 처리 시간 대기
    usleep(200000); 

    // [명령] \n 을 붙여서 전송
    $msg = "[HSJ_BTARD]GETSENSOR@$cmd\n"; // 여기도 \n만
    socket_write($socket, $msg, strlen($msg));

    echo "SUCCESS: Sent command '$cmd' to HSJ_BTARD<br>";
    echo "Message: " . htmlspecialchars($msg);

} else {
    echo "FAILED: Connection Error (C-Server 가 꺼져있을 수 있습니다)";
}

socket_close($socket);
?>
