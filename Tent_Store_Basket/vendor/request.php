<?
	require_once('MainClass.php');
	if (isset($_GET['category'])) {
		$show_goods = new MainClass();
		$goods = $show_goods->print_goods($_GET['category']);
		echo $goods;
	}
	else if(isset($_GET['check'])){
		$show_box = new MainClass();
		$box = $show_box->print_box($_GET['check']);
		echo $box;
	}
	else if(isset($_GET['name_product']) && isset($_GET['count_product'])){
		$show_price_all = new MainClass();
		$price_all = $show_price_all->print_price_all($_GET['name_product'], $_GET['count_product']);
		echo $price_all;
	}
	else if(isset($_GET['val_int'])){
		$show_val = new MainClass();
		$val = $show_val->print_val($_GET['val_int']);
		echo $val;
	}
	else{
		$view_object = new MainClass();
		$views = $view_object->print_category();
		echo $views;
	}
?>

