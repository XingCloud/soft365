<?php

function get_ip() {
	$v = '';
	$v = (! empty ( $_SERVER ['REMOTE_ADDR'] )) ? $_SERVER ['REMOTE_ADDR'] : ((! empty ( $_SERVER ['HTTP_X_FORWARDED_FOR'] )) ? $_SERVER ['HTTP_X_FORWARDED_FOR'] : @getenv ( 'REMOTE_ADDR' ));
	if (isset ( $_SERVER ['HTTP_CLIENT_IP'] ))
		$v = $_SERVER ['HTTP_CLIENT_IP'];
	return htmlspecialchars ( $v, ENT_QUOTES );
}

/**
 * 
 * @param string $string
 * @return array $dateArr array('start'=>'2012-11-11', end=>'2012-11-14')
 */
function parseDateString($string){
	$dateArr = array();
	switch ($string){
		case 'today':{
			$date = date('Y-m-d');
			$dateArr['start'] = $date;
			break;
		}
		case 'yestorday':{
			$yestorday = date('Y-m-d', strtotime('-1 day'));
			$dateArr['start'] = $dateArr['end'] = $yestorday;
			break;
		}
		case 'last2':{
			$yestorday = date('Y-m-d', strtotime('-1 day'));
			$dateArr['start'] = $yestorday;
			break;
		}
		case 'last7':{
			$date = date('Y-m-d', strtotime('-6 day'));
			$dateArr['start'] = $date;
			break;
		}
		case 'thisweek':{
			$date = date('Y-m-d', strtotime('-1 week Monday'));
			$dateArr['start'] = $date;
			break;
		}
		case 'last2week':{
			$date = date('Y-m-d', strtotime('-2 week Monday'));
			$dateArr['start'] = $date;
			break;
		}
		case 'thismonth':{
			$date = date('Y-m-d', strtotime('first day of this month'));
			$dateArr['start'] = $date;
			break;
		}
		case 'last2month':{
			$date = date('Y-m-d', strtotime('first day of -1 month'));
			$dateArr['start'] = $date;
			break;
		}
		case 'lifetime':{
			$dateArr['start'] = '2012-11-11';
			break;
		}
		default:{
			if(substr($string, 0, 6)==='custom' && strpos($string, '|')){
				list($type, $dateArr['start'], $dateArr['end']) = explode('|', $string);
			}
			break;
		}
	}
	return $dateArr;
}
function listdir($path = '', &$name = array() )
{
  $path = $path == ''? dirname(__FILE__) : $path;
  $lists = scandir($path);
 
  if(!empty($lists))
  {
      foreach($lists as $f)
      {
	      if(is_dir($path.'/'.$f) && $f != ".." && $f != ".")
	      {
	          listdir($path.'/'.$f, $name);
	      }
	      elseif($f != ".." && $f != ".")
	      {
	          $name[] = $path.'/'.$f;
	      }
      }
  }
  return $name;
}
function recurse_copy($src,$dst) {
	$dir = opendir($src);
	if(!is_dir($dst)){
		mkdir($dst, 0777, true);
	}
	while(false !== ( $file = readdir($dir)) ) {
		if (( $file != '.' ) && ( $file != '..' )) {
			if ( is_dir($src . '/' . $file) ) {
				recurse_copy($src . '/' . $file,$dst . '/' . $file);
			}
			else {
				copy($src . '/' . $file,$dst . '/' . $file);
			}
		}
	}
	closedir($dir);
}
function recurse_rm($dir) {
	foreach(glob($dir . '/*') as $file) {
		if(is_dir($file))
			recurse_rm($file);
		else
			unlink($file);
	}
	rmdir($dir);
}
// 提示下载
function noticeDown($filePath, $noticeFileName){
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename='.str_replace(' ', '', $noticeFileName));
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header("Cache-Control: no-cache, must-revalidate");
	header('Pragma: public');
	//header('Content-Length: ' . filesize($file));
	ob_clean();
	flush();
	readfile($filePath);
	exit;
}


/**
 *'zh_CN' => '中国',
 *'es_AR'=>'阿根廷',
 *'en_AU'=>'澳大利亚',
 *'pt_BR'=>'巴西',
 *'it_IT'=>'意大利',
 *'es_MX'=>'墨西哥',
 *'pl_PL'=>'波兰',
 *'es_ES'=>'西班牙',
 *'zh_TW'=>'台湾',
 *'th_TH'=>'泰国',
 *'tr_TR'=>'土耳其',
 *'en_US'=>'美国',
 *'vi_VN'=>'越南',
 */
function i18n_locale(){
	return array(
			"all",
			"en_us",    
			"zh_cn",
			"es_ar",
			"en_au",
			"pt_br",
			"it_it",
			"es_mx",
			"pl_pl",
			"es_es",
			"zh_tw",
			"th_th",
			"tr_tr",
			"vi_vn"
			);
}
//
function set_locale($locale){
	$langs = explode(",",C("LANG_LIST"));
	if ($locale && in_array($locale, $langs)){
		$LANG_SET = $locale;
	}else{
		$LANG_SET = "zh_cn";
	}
	if (is_file(LANG_PATH.$LANG_SET.'/common.php')){
		L(include LANG_PATH.$LANG_SET.'/common.php');
	}
}
// 自定义base64 DECODE方法
function cust_base64_decode($code) {
	$decode = '';
	if (! empty ( $code )) {
		$head = substr ( $code, 0, 2 );
		$tail = substr ( $code, - 2 );
		$origin = substr ( $code, 2 );
		$origin = substr ( $origin, 0, strlen ( $origin ) - 2 );
		$tmp = str_split ( $origin );
		$first = $head [0] - 1;
		$second = $head [1] - 1;
		$last = $tail [1] - 1;
		$second_last = $tail [0] - 1;
		$tmp [$first] = NULL;
		$tmp [$second] = NULL;
		$reverses = array_reverse ( $tmp );
		$reverses [$last] = NULL;
		$reverses [$second_last] = NULL;
		$reverses = array_reverse ( $reverses );
// 		dump($reverses);
		$decode = base64_decode ( join ( '', $reverses ) );
	}
	return $decode;
}

// 自定义的base64 encode方法
function cust_base64_encode($code) {
	$encode = base64_encode ( $code );
	$first = rand_num ();
	$second = rand_num2 ();
	if ($second == $first) {
		$second = rand_num2 ();
	}
	$tmp = str_insert ( $encode, $first, "e" );
	$tmp = str_insert ( $tmp, $second, "l" );
	$last_str = strrev ( $tmp );
	$last = rand_num2 ();
	$second_last = rand_num ();
	if ($second_last == $last) {
		$second_last = rand_num ();
	}
	$result = str_insert ( $last_str, $second_last, "e" );
	$result = str_insert ( $result, $last, "x" );
	$result = strrev ( $result );
	return $first . $second . $result . $second_last . $last;
}
function str_insert($str, $i, $substr) {
	$i = $i - 1;
	for($j = 0; $j < $i; $j ++) {
		$startstr .= $str [$j];
	}
	for($j = $i; $j < strlen ( $str ); $j ++) {
		$laststr .= $str [$j];
	}
	$str = ($startstr . $substr . $laststr);
	return $str;
}

//
function rand_num() {
	return rand ( 1, 4 );
}
function rand_num2() {
	return rand ( 6, 9 );
}
//
function getSystem() {
	$sys = $_SERVER ['HTTP_USER_AGENT'];
	if (stripos ( $sys, "Android" )) {
		$os = "Android";
	} elseif (stripos ( $sys, "NT 6.1" )) {
		$os = "Windows 7";
	} elseif (stripos ( $sys, "NT 6.2" )) {
		$os = "Windows 8";
	} elseif (stripos ( $sys, "NT 6.0" )) {
		$os = "Windows Vista";
	} elseif (stripos ( $sys, "NT 5.1" )) {
		$os = "Windows XP";
	} elseif (stripos ( $sys, "NT 5.2" )) {
		$os = "Windows Server 2003";
	} elseif (stripos ( $sys, "NT 5" )) {
		$os = "Windows 2000";
	} elseif (stripos ( $sys, "NT 4.9" )) {
		$os = "Windows ME";
	} elseif (stripos ( $sys, "NT 4" )) {
		$os = "Windows NT 4.0";
	} elseif (stripos ( $sys, "98" )) {
		$os = "Windows 98";
	} elseif (stripos ( $sys, "95" )) {
		$os = "Windows 95";
	} elseif (stripos ( $sys, "Mac" )) {
		$os = "Mac";
	} elseif (stripos ( $sys, "Linux" )) {
		$os = "Linux";
	} elseif (stripos ( $sys, "Unix" )) {
		$os = "Unix";
	} elseif (stripos ( $sys, "FreeBSD" )) {
		$os = "FreeBSD";
	} elseif (stripos ( $sys, "SunOS" )) {
		$os = "SunOS";
	} elseif (stripos ( $sys, "BeOS" )) {
		$os = "BeOS";
	} elseif (stripos ( $sys, "OS/2" )) {
		$os = "OS/2";
	} elseif (stripos ( $sys, "PC" )) {
		$os = "Macintosh";
	} elseif (stripos ( $sys, "AIX" )) {
		$os = "AIX";
	} else {
		$os = "unknow";
	}
	return $os;
}
// Browser Name
function getBrowser() {
	$Agent = $_SERVER ['HTTP_USER_AGENT'];
	$browseragent = ""; // 浏览器
	$browserversion = ""; // 浏览器的版本
	if (ereg ( 'MSIE ([0-9]{1,2}.[0-9]{1,2})', $Agent, $version )) {
		$browserversion = $version [1];
		$browseragent = "Internet Explorer";
	} else if (ereg ( 'Opera/([0-9]{1,2}.[0-9]{1,2})', $Agent, $version )) {
		$browserversion = $version [1];
		$browseragent = "Opera";
	} else if (ereg ( 'Firefox/([0-9.]{1,5})', $Agent, $version )) {
		$browserversion = $version [1];
		$browseragent = "Firefox";
	} else if (ereg ( 'Chrome/([0-9.]{1,3})', $Agent, $version )) {
		$browserversion = $version [1];
		$browseragent = "Chrome";
	} else if (ereg ( 'Safari/([0-9.]{1,3})', $Agent, $version )) {
		$browseragent = "Safari";
		$browserversion = "";
	} else {
		$browserversion = "";
		$browseragent = "Unknown";
	}
	return $browseragent . " " . $browserversion;
}

// First Language
function getLanguage() {
	$result = '';
	$lang = $_SERVER ['HTTP_ACCEPT_LANGUAGE'];
	if ($lang) {
		$arrays = explode ( ";", $lang );
		if ($arrays && count ( $arrays ) > 1) {
			$tmp = $arrays [0];
			$arr = explode ( ",", $tmp );
			if ($arr && count ( $arr ) > 1) {
				$result = $arr [0];
			} else {
				$result = $arrays [0];
			}
		} else {
			$result = substr ( $lang, - 5 );
		}
	}
	return $result;
}

//copy 原来的图片到新的地址（仅限于更新了）
function change_img_path($name,$image_url,$locale,$dest_locale){
    $realpath = pathinfo($image_url);
	$origin_file = APP_PATH."images/$name/$locale/".$realpath["basename"];
	$dest_path = APP_PATH."images/$name/$dest_locale/";
	$dest_file = $dest_path.$realpath["basename"];	
	if (! file_exists ( $dest_file )) {
		mkdir ( $dest_path, 0777, TRUE );
	}
	 if(copy($origin_file,$dest_file)){
	 	return unlink($origin_file);
	}
}
