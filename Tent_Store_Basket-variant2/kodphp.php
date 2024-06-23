<?
	$conn = mysqli_connect("хост", "логин", "пароль", "названиеБД");

	if (isset($_GET['idP'])) {
		$idP = $_GET['idP'];
		$result = mysqli_query($conn, "SELECT ID, Цена, Количество_на_складе FROM goods WHERE ID = '$idP'");
		while ($elem = mysqli_fetch_row($result)) {
			if ($elem[2] == 0) {
		   		echo $elem[0]."|На складе нет этого товара";
		   	}
		   	else{
		   		echo $elem[0]."|".$elem[1];
		   	}
		}
	}
	else if(isset($_GET['idPdop'])){
		$idPdop = $_GET['idPdop'];
		$result = mysqli_query($conn, "SELECT ID, Название, Цена, Количество_на_складе FROM goods WHERE ID = '$idPdop'");
		while ($elem = mysqli_fetch_row($result)) {
		   echo "<li class=liP id=li_in_spisok$elem[0] name='$elem[1]'>$elem[1]<br>Стоимость: <span class=price_All id=priceP$elem[0]>$elem[2]</span><br>Количество: <span id=kolP$elem[0]>1</span></li>";
		}
	}
	else if(isset($_GET['idPE']) && isset($_GET['kol'])){
		$idPE = $_GET['idPE'];
		$kol = $_GET['kol'];
		$result = mysqli_query($conn, "SELECT ID, Цена, Количество_на_складе FROM goods WHERE ID = '$idPE'");
		while ($elem = mysqli_fetch_row($result)) {
			if ($elem[2] < $kol){
				echo $elem[0]."|Указанное количество отсутствует на складе";
			}
			else{
				$price = $elem[1] * $kol;
				echo $elem[0]."|".abs($price); 
			}
		}
	}
?>
