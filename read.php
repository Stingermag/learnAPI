<?php
$id = $_GET['stroka'];

if(is_numeric($id))//валлидация
{
	$server_name = "localhost";
	$user_name = "root";
	$password = "password";
	$db_name = "api_wallet";

	$mysqli = new mysqli($server_name,$user_name,$password,$db_name);

	
	
	$res = $mysqli->query("SELECT COUNT(*) as count FROM users WHERE ID_wallet = $id");
	
	$row = $res->fetch_object();


	if("$row->count" > 0)
	{
		$result = $mysqli->query("SELECT sumwalletrub FROM users WHERE ID_wallet = $id");
		$row = $result->fetch_object();
		$sumwalletrub = "$row->sumwalletrub";




		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://www.cbr-xml-daily.ru/daily_json.js');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		$respons = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($respons, true);

		$sumwalletusd = $sumwalletrub / $data["Valute"]["USD"]["Value"]/100;
		$sumwalletusd = round($sumwalletusd,2);


		$sumwalletrub = round($sumwalletrub/100,2);

		$arrayName = array('rub' => $sumwalletrub, 'usd' => $sumwalletusd );
		$data = json_encode($arrayName, true);


		echo $data;
	}
	else{

		$arrayName = array('rub' => "нет", 'usd' => "нет" );
		$data = json_encode($arrayName, JSON_UNESCAPED_UNICODE);


		echo $data;
	}
}