// start 21/11/2564
//พัฒนาโดย อาจารย์โซฟีร์ หะยียูโซ๊ะ
// พัฒนา ผ่าน โปรแกรม staclib;
//111234567889
<?php 
	/*Get Data From POST Http Request*/
	$datas = file_get_contents('php://input');
	/*Decode Json From LINE Data Body*/
	$deCode = json_decode($datas,true);


	
	// function เขียน logfiles
	file_put_contents('log.txt', file_get_contents('php://input') . PHP_EOL, FILE_APPEND);

	$replyToken = $deCode['events'][0]['replyToken'];
	$userId = $deCode['events'][0]['source']['userId'];
	$text = $deCode['events'][0]['message']['text'];

	
	$messages = [];
	$messages['replyToken'] = $replyToken;
	$messages['messages'][0] = getFormatTextMessage("หวัดดีคะ Miss นมแม่ยินดีให้บริการ1");
	$messages['messages'][0] = getFormatTextMessage("หวัดดีคะ Heroku ");


	// เชื่อมต่อกับ ฐานข้อมูล อย่างไร ตรงนี้ 
	// ทดสอบ
	// ทดสบการเชื่อมต่อ
  // เว็บ webhood อย่างไร


	$encodeJson = json_encode($messages);

	$LINEDatas['url'] = "https://api.line.me/v2/bot/message/reply";
  	$LINEDatas['token'] = "0AUPsNcsGyj4cXXxV/R/Oj+PRQx6m1UIuQ/Cd8+BFfsqn+WoBeUIhSvWtK1fos4n1I+AUFesQPB4mZk/beV2tv8+8zQgZ0cT7MnVUvrDC4bJVEA+Hb5E+80CdKzclPpIIaWDPxEXaP7DEOlq/i7TjAdB04t89/1O/w1cDnyilFU=";
	// การกำหนดในส่วนตัวแปรนี้เราจะใช้เก็บ Token อ้างอิงจากโปรแกรม Line

  	$results = sentMessage($encodeJson,$LINEDatas);

	/*Return HTTP Request 200*/
	http_response_code(200);

	function getFormatTextMessage($text)
	{
		$datas = [];
		$datas['type'] = 'text';
		$datas['text'] = $text;

		return $datas;
	}

	function sentMessage($encodeJson,$datas)
	{
		$datasReturn = [];
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $datas['url'],
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => $encodeJson,
		  CURLOPT_HTTPHEADER => array(
		    "authorization: Bearer ".$datas['token'],
		    "cache-control: no-cache",
		    "content-type: application/json; charset=UTF-8",
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		    $datasReturn['result'] = 'E';
		    $datasReturn['message'] = $err;
		} else {
		    if($response == "{}"){
			$datasReturn['result'] = 'S';
			$datasReturn['message'] = 'Success';
		    }else{
			$datasReturn['result'] = 'E';
			$datasReturn['message'] = $response;
		    }
		}

		return $datasReturn;
	}
?>
