<?php
$id = $_POST['id'];
$type = $_POST['type'];
$sum = $_POST['sum'];
$typeval = $_POST['typeval'];
$cause = $_POST['cause'];

if(is_numeric($id) && is_numeric($sum))
{
	if(!strcasecmp($type, "debit") || !strcasecmp($type, "credit"))
	{
		if(!strcasecmp($typeval, "usd") || !strcasecmp($typeval, "rub"))
		{
			if(!strcasecmp($cause, "stock") || !strcasecmp($cause, "refund"))
			{		

				$server_name = "localhost";
				$user_name = "root";
				$password = "password";
				$db_name = "api_wallet";

				$mysqli = new mysqli($server_name,$user_name,$password,$db_name);

				$res = $mysqli->query("SELECT sumwalletrub FROM users WHERE ID_wallet = $id");
				
				$row = $res->fetch_object();
				$row->sumwalletrub;

				////////////////
				if(!strcasecmp($typeval, "usd"))
				{
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, 'http://www.cbr-xml-daily.ru/daily_json.js');//апи центробанка
					curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
					$respons = curl_exec($ch);
					curl_close($ch);
					$data = json_decode($respons, true);

					$koef = $data["Valute"]["USD"]["Value"];

					if(!strcasecmp($type, "credit"))
						$lastsum = "$row->sumwalletrub" + $sum*$koef*100;
					else
						$lastsum = "$row->sumwalletrub" - $sum*$koef*100;
					$result = $mysqli->query("UPDATE `users` SET `sumwalletrub`= $lastsum WHERE `ID`= $id");
				}
				//////////////////

			
				if(!strcasecmp($typeval, "rub"))
				{
					if(!strcasecmp($type, "credit"))
						$lastsum = "$row->sumwalletrub" + $sum*100;
					else
						$lastsum = "$row->sumwalletrub" - $sum*100;
					$result = $mysqli->query("UPDATE `users` SET `sumwalletrub`= $lastsum WHERE `ID`= $id");
				}

				////////////////
				$today = date("Y-m-d H:i:s");  

				$result = $mysqli->prepare("INSERT INTO `transaction`(`ID_wallet`, `type`, `sum`, `typemoney`, `cause`, `date`) VALUES (?,?,?,?,?,?)"); 
				$result->bind_param('isisss', $id,$type,$sum,$typeval,$cause,$today);
				$result->execute();
				echo "Успешно выполнено";


			}					
		}		
	}
}


	

