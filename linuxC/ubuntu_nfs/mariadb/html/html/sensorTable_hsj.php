<!DOCTYPE html>
<html>
<head>
	<meta charset = "UTF-8">
	<meta http-equiv = "refresh" content = "30">
	<style type = "text/css">
		.spec{
			text-align:center;
		}
		.con{
			text-align:left;
		}
		</style>
</head>

<body>
	<h1 align = "center">iot Database</h1>
	<div class = "spec">
		# <b>The sensor value description</b>
		<br></br>
	</div>

	<table border = '1' style = "width = 30%" align = "center">
	<tr align = "center">
		<th>ID</th>
		<th>NAME</th>
		<th>DATE</th>
		<th>TIME</th>
		<th>ILLU</th>
		<th>A_X</th>
		<th>MAX_ANGLE</th>
	</tr>

	<?php
		$conn = mysqli_connect("localhost", "iot", "pwiot");
		mysqli_select_db($conn, "iotdb");
		$result = mysqli_query($conn, "select * from hsj");
		while($row = mysqli_fetch_array($result))
		{
			echo "<tr align = center>";
			echo '<th>'.$row['id'].'</td>';
			echo '<th>'.$row['name'].'</td>';
			echo '<th>'.$row['date'].'</td>';
			echo '<th>'.$row['time'].'</td>';
			echo '<th>'.$row['a_x'].'</td>';
			echo '<th>',$row['max_angle'].'</td>';
			echo "</tr>";

		}
		mysqli_close($conn);
	?>
	</table>
</body>
</html>
