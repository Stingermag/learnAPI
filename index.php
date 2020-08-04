
<!DOCTYPE html>
<html lang="en">
<head>
		<meta charset="UTF-8">
		<title> Kursach </title>
		
		<script type="text/javascript" src="jquery.js"></script>
		<script type="text/javascript">
			var xhr;
			

			function dobavlenie(text,valute)
				{
					var body = "stroka=" + text.value.trim();

					
					xhr = new XMLHttpRequest();
					xhr.open('GET' , 'read.php?'+body,true);
					xhr.onreadystatechange = onAdd;
			
					xhr.send();

				}

			function onAdd()
			{
				if(xhr.readyState == 4 && xhr.status == 200)
					{
						
						var div = document.getElementById('val');
						var div2 = document.getElementById('val2');
    			   		var data = JSON.parse(xhr.responseText);


						div.innerHTML = data.rub;
						div2.innerHTML = data.usd;
					

						
					}

			}
			function dobavlenietwo(wallet,type,sum,typeval,cause)
			{
				

					xhr = new XMLHttpRequest();
					xhr.open('POST' , 'transaction.php',true);
					xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
					xhr.send("id=" + encodeURIComponent(wallet.value.trim()) + "&type=" + encodeURIComponent(type.value.trim()) + "&sum=" + encodeURIComponent(sum.value.trim()) + "&typeval=" + encodeURIComponent(typeval.value.trim()) + "&cause=" + encodeURIComponent(cause.value.trim()));
					xhr.onreadystatechange = onChang;	



			}
			function onChang()
			{
				if(xhr.readyState == 4 && xhr.status == 200)
					{
						
						var divtwo = document.getElementById('valtwo');
		
						divtwo.innerHTML = xhr.responseText;
						
					

						
					}

			}

		</script>
</head>

<body>
<center>
<h2>Узнать баланс</h2>
Введите id кошелька<br>
	<textarea id="field"></textarea>
<br>
	<input type="button" onmousedown = "dobavlenie(document.getElementById('field'))" value="Узнать баланс"/>
	<br>
У вас <div id = 'val' ></div> рублей <br>
У вас <div id = 'val2' ></div> долларов
	

	<h2>Изменить баланс</h2>



	1 Введите id кошелька<br>
	<textarea id="wallet"></textarea><br>
	2 Выберите тип транзакции<br>
	<select id="type">
    <option value="debit">debit</option>
    <option value="credit">credit</option>
    </select><br>
	3 Введите сумму <br>
	<textarea id="sum"></textarea><br>
	4 Выберите тип валюты<br>
	<select id="typeval">
    <option value="rub">rub</option>
    <option value="usd">usd</option>
    </select><br>
	5 Причина изменения счета<br>
	<select id="cause">
    <option value="stock">stock</option>
    <option value="refund">refund</option>
    </select><br>
    <input type="button" onmousedown = "dobavlenietwo(
    	document.getElementById('wallet'),
    	document.getElementById('type'),
    	document.getElementById('sum'),
    	document.getElementById('typeval'),
    	document.getElementById('cause')
    )" value="Изменить сумму"/>
 <div id = 'valtwo' ></div><br>
	</center>
</body>
</html>