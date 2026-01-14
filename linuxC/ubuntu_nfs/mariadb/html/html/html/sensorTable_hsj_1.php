<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="2">
    <style>
        body { background-color: #0f172a; color: #38bdf8; font-family: 'Courier New', monospace; }
        h2 { text-shadow: 0 0 10px #38bdf8; border-bottom: 2px solid #38bdf8; display: inline-block; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: rgba(30, 41, 59, 0.5); }
        th { background: #1e293b; color: #94a3b8; padding: 12px; border: 1px solid #334155; }
        td { padding: 10px; border: 1px solid #334155; text-align: center; }
        tr:hover { background: rgba(56, 189, 248, 0.1); }
        .status-pulse { height: 10px; width: 10px; background-color: #22c55e; border-radius: 50%; display: inline-block; box-shadow: 0 0 8px #22c55e; animation: pulse 2s infinite; }
        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.3; } 100% { opacity: 1; } }
    </style>
</head>
<body>
    <h2><span class="status-pulse"></span> SYSTEM_LOG: SENSOR_DATA</h2>
    <table>
        <tr>
            <th>IDX</th><th>NAME</th><th>TIMESTAMP</th><th>ANGLE_X</th><th>COUNT</th>
        </tr>
        <?php
            $conn = mysqli_connect("localhost", "iot", "pwiot", "iotdb");
            $result = mysqli_query($conn, "SELECT id, name, date, time, a_x, count FROM hsj ORDER BY id DESC LIMIT 100");
            while($row = mysqli_fetch_array($result)) {
                echo "<tr>
                        <td>".sprintf("%04d", $row['id'])."</td>
                        <td>".$row['name']."</td>
                        <td>".$row['time']."</td>
                        <td style='color:#fbbf24'>".$row['a_x']."°</td>
                        <td style='color:#f472b6'>".$row['count']."</td>
                      </tr>";
            }
        ?>
    </table>
</body>
</html>
