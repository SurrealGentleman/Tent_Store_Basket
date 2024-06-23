function createXmlHttpRequestObject(){
	let xHttp;
	xHttp = new XMLHttpRequest();
	if (!xHttp) {
		alert("Ошибка при попытке создания объекта XMLHttpRequest");
	}
	else{
		return xHttp;
	}
}
let xHttp = createXmlHttpRequestObject();

function btn_category() {
    let serverAddress = "../vendor/request.php";
    if (xHttp) {
        try{
            xHttp.open("GET", serverAddress, true);
            xHttp.onreadystatechange = createMENU;
            xHttp.send(null);
        }
        catch(e){
            alert("Невозможно :\n"+e.toString());
        }
    }
}

function createMENU() {
    if (xHttp.readyState == 4) {
        if (xHttp.status == 200) {
        	try{
            	let response = xHttp.responseText;
        		let array_categories = response.split("#");
				for (let i = 0; i < array_categories.length-1; i++) {
					document.getElementById("menu").innerHTML += "<li><button id='"+i+"' value='"+array_categories[i]+"' onclick='display_goods("+i+")' class='btn'>" + array_categories[i] + "</button></li>";
				}
            }
            catch(e){
                alert("Ошибка при обработке данных сервера:\n"+e.toString());
            }
        }
        else{
            alert(xHttp.statusText);
        }
    }
}

function display_goods(i){
	if(i == '0'){
		document.getElementById('0').style.borderBottom='3px solid black';
		document.getElementById('1').style.borderBottom='0px';
		document.getElementById('category').style.display = "block";
		document.getElementById('sec_cart').style.display = "none";
		document.getElementById('inp').style.border='0px';
	}
	else if (i == '1') {
		document.getElementById('1').style.borderBottom='3px solid black';
		document.getElementById('0').style.borderBottom='0px';
		document.getElementById('category').style.display = "block";
		document.getElementById('sec_cart').style.display = "none";
		document.getElementById('inp').style.border='0px';
	}
	document.getElementById("category").innerHTML = '';
	let serverAddress = "../vendor/request.php?category="+encodeURIComponent(document.getElementsByClassName('btn')[i].value);
	document.getElementById("category").innerHTML = "<h3>"+document.getElementsByClassName('btn')[i].value+"</h3>";
	if (xHttp) {
        try{
            xHttp.open("GET", serverAddress, true);
            xHttp.onreadystatechange = create_goods;
            xHttp.send(null);
        }
        catch(e){
            alert("Невозможно :\n"+e.toString());
        }
    }
}

function create_goods() {
	if (xHttp.readyState == 4) {
        if (xHttp.status == 200) {
        	try{
        		let array_goods_pieces = new Array();
            	let response = xHttp.responseText;
        		let array_goods = response.split("#");
				for (let i = 0; i < array_goods.length-1; i++) {
					let arr = array_goods[i].split("~");
					document.getElementById("category").innerHTML += `
					<div>
						<p class='name'>`+arr[1]+`</p>
						<p class='manufacturer'>`+arr[2]+`</p>
						<p class='price'>`+arr[3]+` руб.</p>
						<p class='description'>`+arr[4]+`</p>
						<p> <input class='check' type='checkbox' data-price-check='`+arr[3]+`' data-count='1' name='`+arr[1]+`' value='ID`+arr[0]+`'></p>
					</div>`;
				}
				fun();
            }
            catch(e){
                alert("Ошибка при обработке данных сервера:\n"+e.toString());
            }
        }
        else{
            alert(xHttp.statusText);
        }
    }
}


function fun() {

	let arr = $('.check');
	let cart_sch = $('#inp');
	let cart_elem = $('#cart');

	$.each(arr, function() {
		let x = $(this).attr('value');
		if ($('#'+x).length > 0) {
			$(this).prop('checked', true);
		}
	});

	$.each(arr, function() {
		$(this).bind('click', function() {
			if ($(this).is(':checked')) {
				let cur = $(cart_sch).attr('value');
				let arcart = cur.split(' ');
				let id = Number(arcart[1])+1;
				let tt = 'Корзина ' + id;
				let tcheck = 'check_'+id;
				let scheck = 'SPANcheck_'+id;
				cart_sch.attr('value', tt);
				cart_elem.append(`
					<li id='`+$(this).attr('value')+`' class='price_li' name='`+$(this).attr('name')+`'>
						<span class='SPANcheck'>`+$(this).attr('name')+ ` - ` +$(this).attr('data-price-check')+`</span>
						руб.
						<input class='input_count' type='number' id=`+tcheck+` data-price-num='`+$(this).attr('data-price-check')+`' data-count-num='`+$(this).attr('data-count')+`' data-name='`+$(this).attr('name')+`' oninput='formula(`+tcheck+`)' value='1' min=0>
					</li>
					`);
			}
			else{
				let cur = $(cart_sch).attr('value');
				let arcart = cur.split(' ');
				let id = Number(arcart[1])-1;
				let tt = 'Корзина ' + id;
				cart_sch.attr('value', tt);
				let y = $(this).attr('value');
				$('li[id*='+y+']').remove();
			}
		})
	})
}

function cart_btn() {
	document.getElementById('category').style.display = "none";
	document.getElementById('sec_cart').style.display = "block";
	document.getElementById('inp').style.border='2px solid black';
	document.getElementById('0').style.borderBottom='0px';
	document.getElementById('1').style.borderBottom='0px';
	value_int();
	price_all();
	
}

function value_int(){
	$.each($('.price_li'), function() {
		let name = $(this).attr('name');
		let serverAddress = "../vendor/request.php?val_int="+name;
		if (xHttp) {
	        try{
	            xHttp.open("GET", serverAddress, true);
	            xHttp.onreadystatechange = createVAL;
	            xHttp.send(null);
	        }
	        catch(e){
	            alert("Невозможно :\n"+e.toString());
	        }
	    }
	});
	
}
function createVAL() {
	if (xHttp.readyState == 4) {
        if (xHttp.status == 200) {
        	try{
            	let response = xHttp.responseText;
            	let arr = response.split('~');
            	$.each($('.input_count'), function(){
            		if ($(this).attr('data-name') == arr[0]) {
            			if (arr[1] == 0) {
            				$(this).attr('data-price-num', 0);
            				$(this).attr('data-count-num', 0);
            				$(this).attr('value', 0);
            				price_all();
            			}
            		}
            	});
            	if (arr[1] == 0) {
	            	$.each($('.SPANcheck'), function(){
	            		let arr_NP = $(this).html().split(' - ');
	        			if (arr_NP[0] == arr[0]) {
	        				$(this).html(arr[0]+' - 0');
	        				price_all();
	        			}
	            	});
	            }
            }
            catch(e){
                alert("Ошибка4 при обработке данных сервера:\n"+e.toString());
            }
        }
        else{
            alert(xHttp.statusText);
        }
    }
}


function formula(tcheck) {
	let serverAddress = "../vendor/request.php?name_product="+encodeURIComponent($(tcheck).attr('data-name'))+"&count_product="+encodeURIComponent($(tcheck).val());
	if (xHttp) {
        try{
            xHttp.open("GET", serverAddress, true);
            xHttp.onreadystatechange = createFormula;
            xHttp.send(null);
        }
        catch(e){
            alert("Невозможно :\n"+e.toString());
        }
    }
}

function createFormula() {
	if (xHttp.readyState == 4) {
        if (xHttp.status == 200) {
        	try{
            	let response = xHttp.responseText;
            	let arr_NallP = response.split('~');
            	if (arr_NallP[0] == "error") {
            		$.each($('.price_li'), function(){
            			let name = $(this).attr('name');
            			if (name == arr_NallP[1] && $(this).has('span.error').length == 0) {
            				$(this).append('<span class=error name="'+arr_NallP[1]+'">'+arr_NallP[2]+'</span>');
            			}
            		})
            		$.each($('.SPANcheck'), function(){
            			let arr_NP = $(this).html().split(' - ');
            			if (arr_NP[0] == arr_NallP[1]) {
            				$(this).html(arr_NallP[1]+' - 0');
            				price_all()
            			}
            		})
            	}
            	else{	
            		$.each($('.SPANcheck'), function(){
            			let arr_NP = $(this).html().split(' - ');
            			if (arr_NP[0] == arr_NallP[1]) {
            				$(this).html(arr_NallP[1]+' - '+arr_NallP[2]);
            				price_all()
            			}
            		})
            		$.each($('.error'), function(){
    					if ($(this).attr('name') == arr_NallP[1]) {
    						$(this).remove();
    					}
    				})
            	}
            }
            catch(e){
                alert("Ошибка4 при обработке данных сервера:\n"+e.toString());
            }
        }
        else{
            alert(xHttp.statusText);
        }
    }
}
	
function price_all() {
	if ($('#cart').is('li') == false) {
		$('#pp').attr('value', 0);
	}
	let price_all = 0
	$.each($('.SPANcheck'), function() {
		let arr_NP = $(this).html().split(' - ');

		price_all += Number(arr_NP[1]);
		$('#pp').attr('value', price_all);
	});
}