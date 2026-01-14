<?php
    $conn = mysqli_connect("localhost", "iot", "pwiot", "iotdb");
    $result = mysqli_query($conn, "SELECT time, a_x, count FROM hsj ORDER BY id DESC LIMIT 20");
    
    $header = array('Time', 'AngleX', 'Count'); 
    $tempRows = array(); 

    if ($result && mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_array($result)) {
            array_unshift($tempRows, array(
                (string)$row['time'], 
                (int)$row['a_x'], 
                (int)$row['count']
            ));
        }
    } else {
        $tempRows[] = array(date("H:i:s"), 0, 0);
    }
    
    $finalData = array_merge(array($header), $tempRows);
    mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="5">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <style>
        body { background: #0f172a; color: #f8fafc; margin: 0; padding: 20px; font-family: sans-serif; }
        
        /* 버튼 디자인 */
        .btn-group { margin-bottom: 20px; display: flex; gap: 10px; }
        button { 
            padding: 12px 25px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;
            transition: 0.2s; text-transform: uppercase; font-size: 13px;
        }
        .btn-start { background: #10b981; color: white; box-shadow: 0 4px 0 #059669; }
        .btn-stop { background: #f59e0b; color: white; box-shadow: 0 4px 0 #d97706; }
        .btn-delete { background: #ef4444; color: white; box-shadow: 0 4px 0 #dc2626; }
        button:active { transform: translateY(3px); box-shadow: none; }
        
        #chart_div { border: 1px solid #334155; border-radius: 8px; background: #1e293b; padding: 15px; height: 450px; }
    </style>
    <script>
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var rawData = <?php echo json_encode($finalData); ?>;
            var data = google.visualization.arrayToDataTable(rawData);

            var options = {
                backgroundColor: 'transparent',
                title: 'REAL-TIME SYSTEM MONITOR',
                titleTextStyle: {color: '#38bdf8', fontSize: 18},
                
                // [수정] X축의 모든 선 제거
                hAxis: { 
                    textStyle: {color: '#64748b'}, 
                    gridlines: {color: 'transparent'},
                    baselineColor: 'transparent'
                },

                // [수정] Y축 설정 (왼쪽/오른쪽 모든 선 제거)
                vAxes: {
                    0: { // 왼쪽 축 (AngleX)
                        title: 'Angle X',
                        titleTextStyle: {color: '#38bdf8'},
                        textStyle: {color: '#64748b'},
                        gridlines: {color: 'transparent'},
                        minorGridlines: {color: 'transparent'},
                        baselineColor: 'transparent',
                        viewWindow: { min: 0, max :100 }
                    },
                    1: { // 오른쪽 축 (Count)
                        title: 'Count',
                        titleTextStyle: {color: '#f472b6'},
                        textStyle: {color: '#64748b'},
                        gridlines: {color: 'transparent'},
                        minorGridlines: {color: 'transparent'},
                        baselineColor: 'transparent',
                        viewWindow: { min: 0, max: 10 },
                        ticks: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                    }
                },
               bar : { groupWidth:'70%'},
 
                // [중요] 기본 형태는 선(line)으로 하되, 특정 시리즈만 막대(bars)로 설정
                seriesType: 'line', 
                series: {
                    0: { targetAxisIndex: 0, curveType: 'function', lineWidth: 5 }, // AngleX: 곡선
                    1: { targetAxisIndex: 1, type: 'bars' }          // Count: 막대 그래프
                },

                colors: ['#38bdf8', '#f472b6'],
                legend: { position: 'bottom', textStyle: {color: '#94a3b8'} },
                chartArea: { width: '80%', height: '70%' }
            };

            // [수정] LineChart 대신 ComboChart를 사용해야 막대와 선을 혼합할 수 있습니다.
            var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
        // [기능 1 & 2] 아두이노 제어 (10 / OFF)
        function sendCommand(cmd) {
            fetch('client_send.php?cmd=' + cmd)
                .then(response => response.text())
                .then(res => {
                    console.log("Response:", res);
                    alert("명령 전송 완료: " + cmd);
                });
        }
// [기능 3] DB 데이터 전체 삭제 및 그래프 초기화
        function deleteData() {
            if(confirm("정말로 DB의 모든 데이터를 삭제하시겠습니까?\n(블루투스 연결 유지를 위해 새로고침 없이 초기화합니다)")) {
                fetch('db_delete.php')
                    .then(response => response.text())
                    .then(res => {
                        alert("데이터베이스와 그래프가 초기화되었습니다.");
                        
                        // 1. 그래프 데이터를 빈 값으로 생성
                        var header = ['Time', 'AngleX', 'Count'];
                        var emptyRow = [new Date().toLocaleTimeString().split(' ')[1], 0, 0]; // 현재시간, 0, 0
                        var data = google.visualization.arrayToDataTable([header, emptyRow]);
                        
                        // 2. 이미 정의된 options를 사용하여 그래프만 다시 그림
                        // drawChart()를 다시 실행하거나 직접 그립니다.
                        var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
                        
                        // 기존에 사용하던 options 변수를 그대로 사용합니다.
                        // (options 변수가 drawChart 함수 안에 있다면 밖으로 빼두는 것이 좋습니다)
                        chart.draw(data, options); 
                    })
                    .catch(err => console.error("Error:", err));
            }
        }
    </script>
</head>
<body>
    <div class="btn-group">
        <button class="btn-start" onclick="sendCommand('3')">Sensor Start (10)</button>
        <button class="btn-stop" onclick="sendCommand('OFF')">Sensor Stop (OFF)</button>
        <button class="btn-delete" onclick="deleteData()">Clear Database</button>
    </div>
    <div id="chart_div"></div>
</body>
</html>
