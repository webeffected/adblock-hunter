<?php
//Данные для подключения к БД
$host = "localhost"; //Имя хоста БД
$username = "user"; //Пользователь БД
$password = "pass"; //Пароль БД
$db_name = "database"; //Имя БД

//Соединяемся с БД
mysql_connect($host, $username, $password)or die('Не удалось соединиться с БД: ' . mysql_error());
mysql_select_db($db_name)or die('Не удалось выбрать базу данных!');

//Мера безопасности - пропускаем в переменной только цифры от 0 до 9
$ab =  preg_replace("/[^0-9-]/","",$_GET['ab']);

//Если нет адблока
if ($ab==0) {
	mysql_query("UPDATE adblock SET adblock_no = adblock_no + 1") or die(' Не удалось записать в лог Адблока 0: ' . mysql_error());
}

//Если есть адблок
elseif ($ab==1) {
	mysql_query("UPDATE adblock SET adblock_yes = adblock_yes + 1") or die(' Не удалось записать в лог Адблока 1: ' . mysql_error());
}

//Чтобы посмотреть статистику открываем write.php?b=775 (775 можно сменить на любое другое число)
elseif ($ab==775) {
	//Читаем данные из базы
	$ab_result = mysql_query("SELECT adblock_yes,adblock_no FROM adblock") or die(' Не удалось прочитать БД: ' . mysql_error());
	$ab_row = mysql_fetch_array($ab_result);	
	
	//Считаем проценты с адблоком
	//Всего получено просмотров
	$ab_total = $ab_row['adblock_yes'] + $ab_row['adblock_no'];
	//Из них с адблоком в процентах
	$ab_percent = $ab_row['adblock_yes']/$ab_total*100;
	
	//Выводим информацию в таблицу
	?>
	<table>
		<tr>
			<th>С адблоком</th>
			<th>Без адблока</th>
			<th>Процент с адблоком</th>
		</tr>
		<tr>
			<td><?php echo $ab_row['adblock_yes'] ?></td>
			<td><?php echo $ab_row['adblock_no'] ?></td>
			<td><?php echo number_format($ab_percent, 2, '.', ' ') ?>%</td>
		</tr>
	</table>
<?
}

//Чтобы очистить данные из таблицы открываем write.php?ab=100000 (100000 можно сменить на любое другое число)
elseif ($ab==100000) {
	mysql_query("UPDATE adblock SET adblock_yes = 0, adblock_no = 0") or die(' Не удалось очистить данные: ' . mysql_error());
	echo 'Все данные удалены';
}

//Если какая-то умная задница пытается передать что-то плохое через $_GET параметр, шлём его лесом
else {
	echo '<!-- И не надейся! -->';	
}
?>