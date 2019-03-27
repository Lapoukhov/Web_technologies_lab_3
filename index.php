<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>Меню</title>
</head>          
<body>
	<div>
		<?php		
		
			function check_holiday(&$delivery_date_arr, &$delivery_date)
			{
				$file_name = "holidays.txt";
				if (file_exists($file_name))
				{
					$file = fopen($file_name, "r") or die("Ошибка: не удалось открыть файл");
					//$holidays_arr = explode("\n", fread($file, filesize($file_name)));
					$holidays_arr = explode("\n", file_get_contents($file_name));
					for ($i = 0; $i < count($holidays_arr); $i++)
					{
						$holiday_date_arr = explode('-', ($holidays_arr[$i]));
						if (($delivery_date_arr[0] == $holiday_date_arr[0]) && ($delivery_date_arr[1] == $holiday_date_arr[1]))
						{
							$delivery_date = date('j.m.Y', strtotime($delivery_date. ' + 1 days'));
							$delivery_date_arr = explode('.', $delivery_date);
							echo " (дата доставки перенесена из-за выходного дня) ";
							check_holiday($delivery_date_arr, $delivery_date);    //recursion
						}
					}
					fclose($file);
				}
				else
				{
					echo "(Внимание: файл с праздниками (" . $file_name . ") не найден)  ";
				}
			}
		
			if (isset($_GET['input_date']))
			{
				$test_date = $_GET['input_date'];
				$date_arr  = explode('.', $test_date);
				if (count($date_arr) == 3)
				{
					if (checkdate((int)$date_arr[1], (int)$date_arr[0], (int)$date_arr[2]) && (preg_match("/^[0-9.]+$/i", $test_date)))      //month, day, year
					{
						$current_time = date("H:i:s");
						$time_arr  = explode(':', $current_time);
						echo "Текущее время: " . $current_time . "<br/>";
						echo "Текущая дата: " . $test_date . "<br/>";
						
						echo "Дата доставки: ";
						if ((int)$time_arr[0] <= 11)
						{
							$delivery_date = date('j.m.Y', strtotime($test_date. ' + 1 days'));
						}
						else
						{
							$delivery_date = date('j.m.Y', strtotime($test_date. ' + 2 days'));
						}
						$delivery_date_arr = explode('.', $delivery_date);
						
						//CHECK HOLIDAY	
						check_holiday($delivery_date_arr, $delivery_date);
						
						$month_arr = array('января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря');
						$index = (int)$delivery_date_arr[1] - 1;
						echo $delivery_date_arr[0] . " " . $month_arr[$index] . " " . $delivery_date_arr[2];
					}
					else
					{
						echo "Ошибка: дата не верная";
						$fon_input = "<style>.fon {background-color: red}</style>";	
						echo $fon_input;
					}
				}
				else
				{
					echo "Ошибка: введите дату в указанном формате";
					$fon_input = "<style>.fon {background-color: red}</style>";	
					echo $fon_input;
				}
			}
		?>
	</div>
	<h4>Введите дату в формате DD.MM.YYYY</h4>
	<form method="GET">
		<input class="fon" type="text" name="input_date" value="<?= (isset($_GET['input_date'])) ? strip_tags($_GET['input_date']) : '' ?>">
		<input type="submit" value="Отправить">
	</form>
</body>
</html>