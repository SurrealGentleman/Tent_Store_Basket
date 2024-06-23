<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Магазин палаток</title>
</head>
<style>
	.korzina{
		position: absolute;
		right: 30px;
		top: 30px;
	}
</style>
<body>
	<h1>Магазин Палаток</h1>
	<div id="korzina" class="korzina">
		<ul id="korzina_spisok"></ul>
		<span id="price_in_korzina"></span>
	</div>
	<div>
		<?php
			$conn = mysqli_connect("хост", "логин", "пароль", "названиеБД");
			$result = mysqli_query($conn, "SELECT ID, Название, Производитель, Категория, Цена FROM goods");
			while ($elem = mysqli_fetch_row($result)) {
			    echo "<div>$elem[1]<br>$elem[2]<br>$elem[3]<br>$elem[4] руб.<input class='checkBox' type='checkbox' id='$elem[0]' onchange='zapros($elem[0])'></div><div id='div_for_num$elem[0]'></div><br><div id=error_for_product$elem[0]></div>";
			}
		?>
	</div>
</body>
</html>
<script>
	var xhr = new XMLHttpRequest();
	function zapros(znach_id) {
	  	var masclass = document.getElementsByClassName('checkBox');
	  	if (masclass[znach_id-1].checked === true) {
	    	var nameserver = "kodphp.php?idP="+znach_id;
			xhr.open("GET", nameserver, true);
	        xhr.onreadystatechange = createNUM;
	        xhr.send();
	  	}
	  	else{
	  		if (document.getElementById('inputNUM'+znach_id)) {
	  			document.getElementById('inputNUM'+znach_id).remove();
	  		}
	  		if (document.getElementById('li_in_spisok'+znach_id)) {
	  			document.getElementById('li_in_spisok'+znach_id).remove();
	  		}
	  		if (document.getElementById('span_for_error'+znach_id)) {
	  			document.getElementById('span_for_error'+znach_id).innerHTML = '';
	  		}
	  		if (document.getElementById('span2_for_error'+znach_id)) {
	  			document.getElementById('span2_for_error'+znach_id).remove();
	  		}
			schet();
	  	}
	}
	function createNUM() {
		if (xhr.readyState == 4) {
	        if (xhr.status == 200) {
	        	var result = xhr.responseText;
	        	var massiv = result.split('|');
	        	if (massiv[1] == "На складе нет этого товара") {
	        		document.getElementById('div_for_num'+massiv[0]).innerHTML+='<span id=span_for_error'+massiv[0]+'>'+massiv[1]+'</span>';
	        	}
	        	else{
	        		document.getElementById('div_for_num'+massiv[0]).innerHTML+='<input id=inputNUM'+massiv[0]+' type="number" value="1" min="0" oninput="zapros3('+massiv[0]+')">';
	        		zapros2(massiv[0]);
	        	}
	        }
	    }
	}
	function zapros2(znach1_id) {
		var nameserver = "kodphp.php?idPdop="+znach1_id;
		xhr.open("GET", nameserver, true);
	    xhr.onreadystatechange = append_korzina;
	    xhr.send();
	}
	function append_korzina() {
		if (xhr.readyState == 4) {
	        if (xhr.status == 200) {
	        	var result = xhr.responseText;
	        	document.getElementById('korzina_spisok').innerHTML+=result
	        	schet();
	        }
	    }
	}
	function zapros3(znach2_id) {
		if (document.getElementById('inputNUM'+znach2_id).value != '') {
			document.getElementById('li_in_spisok'+znach2_id).style.display = 'block';
			var nameserver = "kodphp.php?idPE="+znach2_id+"&kol="+document.getElementById('inputNUM'+znach2_id).value;
			xhr.open("GET", nameserver, true);
		    xhr.onreadystatechange = createprice;
		    xhr.send(null);
		}
		else{
		    document.getElementById('error_for_product'+znach2_id).innerHTML= '<span id=span2_for_error'+znach2_id+'>Введите данные</span>';
		    document.getElementById('li_in_spisok'+znach2_id).style.display = 'none';
		    document.getElementById('priceP'+znach2_id).innerHTML='';
		    schet();	
		}
	}
	function createprice() {
		if (xhr.readyState == 4) {
	        if (xhr.status == 200) {
	        	var result = xhr.responseText;
	        	var massiv = result.split('|');
	        	if (massiv[1] == 'Указанное количество отсутствует на складе') {
	        		document.getElementById('error_for_product'+massiv[0]).innerHTML= '<span id=span2_for_error'+massiv[0]+'>Указанное количество отсутствует на складе</span>';
	        		document.getElementById('priceP'+massiv[0]).innerHTML='';
	        		document.getElementById('li_in_spisok'+massiv[0]).style.display = 'none';
	        		schet();
	        	}
	        	else{
	        		if (document.getElementById('span2_for_error'+massiv[0])) {
			  			document.getElementById('span2_for_error'+massiv[0]).remove();
			  		}
			  		document.getElementById('li_in_spisok'+massiv[0]).style.display = 'block';
			  		document.getElementById('priceP'+massiv[0]).innerHTML = massiv[1];
			  		document.getElementById('kolP'+massiv[0]).innerHTML = Math.abs(Number(document.getElementById('inputNUM'+massiv[0]).value));
	        		schet();
	        	}
	        }
	    }
	}
	function schet() {
		if (document.querySelectorAll(".liP").length == 0) {
			document.getElementById('price_in_korzina').innerHTML = '';
		}
		var summa = 0;
		var nodes = document.getElementsByClassName('price_All');
		for (var i = 0; i < nodes.length; i++) {
			summa += Number(nodes[i].innerHTML);
			document.getElementById('price_in_korzina').innerHTML=summa;	
		}
	}
</script>
