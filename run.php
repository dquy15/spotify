<?php

$headers = array();
$headers[] = 'User-Agent: Spotify/8.4.98 Android/25 (ASUS_X00HD)';
$headers[] = 'Content-Type: application/x-www-form-urlencoded';
$headers[] = 'Connection: Keep-Alive';

echo color('blue',"[+]")." Spotify Account Creator - By: GidhanB.A\n";
echo color('blue',"[+]")." Butuh berapa: ";
$qty = trim(fgets(STDIN));
echo color('blue',"[+]")." Custom domain? (y/n): ";
$dom = trim(fgets(STDIN));
if ($dom == 'y') {
	echo color('blue',"[+]")." Input domain: ";
	$asu = trim(fgets(STDIN));
}
echo "\n";
for ($i=1; $i <= $qty; $i++) {
	if ($dom == 'y') {
		$base = json_decode(file_get_contents("https://wirkel.com/data.php?qty=1&domain=".$asu))->result[0];
	} else {
		$base = json_decode(file_get_contents("https://wirkel.com/data.php?qty=1"))->result[0];
	}
	$email = $base->email;
	$pass = 'sarkem123';
	$send = curl('https://spclient.wg.spotify.com:443/signup/public/v1/account/', 'iagree=true&birth_day=12&platform=Android-ARM&creation_point=client_mobile&password='.$pass.'&key=142b583129b2df829de3656f9eb484e6&birth_year=2000&email='.$email.'&gender=male&app_version=849800892&birth_month=12&password_repeat='.$pass, $headers);
	if (strpos($send[1], '"status":1')) {
		$data = json_decode($send[1]);
		$user = $data->username;
		$con = $data->country;
		echo color('green',"[$i]")." Success! - Email: $email - Username: $user - Country: $con\n";
		file_put_contents('res-spotify.txt', "$email|$pass|$con\n", FILE_APPEND);
	} else {
		die($send[1]);
	}
}

function curl($url, $post, $headers, $follow = false, $method = null)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if ($follow == true) curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		if ($method !== null) curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		if ($headers !== null) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		if ($post !== null) curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$result = curl_exec($ch);
		$header = substr($result, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
		$body = substr($result, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
		preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
		$cookies = array();
		foreach ($matches[1] as $item) {
			parse_str($item, $cookie);
			$cookies = array_merge($cookies, $cookie);
		}
		return array(
			$header,
			$body,
			$cookies
		);
	}

function color($color = "default" , $text)
	{
    	$arrayColor = array(
    		'grey' 		=> '1;30',
    		'red' 		=> '1;31',
    		'green' 	=> '1;32',
    		'yellow' 	=> '1;33',
    		'blue' 		=> '1;34',
    		'purple' 	=> '1;35',
    		'nevy' 		=> '1;36',
    		'white' 	=> '1;0',
    	);	
    	return "\033[".$arrayColor[$color]."m".$text."\033[0m";
    }
