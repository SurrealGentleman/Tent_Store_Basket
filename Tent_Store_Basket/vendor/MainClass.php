<?
	class MainClass
	{
		protected $conn;
		function __construct()
		{
			$this->conn = new PDO("mysql:host=хост; dbname=названиеБД", "логин", "пароль");
		}

		function print_category ()
		{
			$result = $this->conn->query("SELECT DISTINCT Категория FROM goods");
			foreach ($result as $value) {
				echo $value[0].'#';
			}
		}

		function print_goods($category)
		{
			$result = $this->conn->query("SELECT ID, Название, Производитель, Цена, Описание FROM goods WHERE Категория='$category'");
			foreach ($result as $value) {
				echo $value[0].'~'.$value[1].'~'.$value[2].'~'.$value[3].'~'.$value[4].'#';
			}
		}

		function print_box($box)
		{
			$result = $this->conn->query("SELECT ID, Название, Цена FROM goods WHERE ID='$box'");
			foreach ($result as $value) {
				echo $value[0].'~'.$value[1].'~'.$value[2];
			}
		}

		function print_price_all($name_product, $count_product)
		{
			$result = $this->conn->query("SELECT Цена, Количество_на_складе FROM goods WHERE Название='$name_product'");
			foreach ($result as $value) {
				if ($count_product > $value[1]) {
					echo "error~".$name_product."~Указанное количество отсутствует на складе.";
				}
				else{
					$price_all = $value[0] * abs($count_product);
					echo 'ok~'.$name_product.'~'.$price_all;
				}
				
			}
		}

		function print_val($name)
		{
			$result = $this->conn->query("SELECT Количество_на_складе FROM goods WHERE Название='$name'");
			foreach ($result as $value) {
				if ($value[0] == 0) {
					echo $name.'~0';
				}
				else{
					echo $name.'~1';
				}
			}
		}

		function _destruct()
		{
			$this->conn->close();
		}
	}
?>
