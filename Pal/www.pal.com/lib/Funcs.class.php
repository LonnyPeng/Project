<?php

class Funcs
{
	/**
	 * Whether or not the POST request
	 *
	 * @return boolean
	 */
	public function isPost()
	{
	    return isset($_SERVER['REQUEST_METHOD']) 
	            && 'POST' === $_SERVER['REQUEST_METHOD'];
	}

	/**
	 * Whether or not the AJAX request
	 *
	 * @return boolean
	 */
	public function isAjax()
	{
	    return (
	                isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
	                && 'XMLHttpRequest' === $_SERVER['HTTP_X_REQUESTED_WITH']
	            ) 
	            || (
	                isset($_REQUEST['X-Requested-With']) 
	                && 'XMLHttpRequest' === $_REQUEST['X-Requested-With']
	            );
	}

	/**
	 * Whether or not the JSON
	 *
	 * @param string $string
	 * @return boolean
	 */
	public function isJson($string = "")
	{
	    if (!is_string($string)) {
	        return false;
	    }

	    $result = json_decode($string);
	    if (is_object($result)) {
	        return true;
	    } else {
	        return false;
	    }
	}

	/**
	 * If string is json, to array
	 *
	 * @param string $string
	 * @return array
	 */
	public function openJson($string = '')
	{
	    if (!is_string($string)) {
	        return false;
	    }

	    if (!isJson($string)) {
	         return false;
	    }

	    return json_decode($string, true);
	}

	/**
	 * Read csv file
	 *
	 * @param string $path
	 * @return array
	 */
	public function openCsv($path = "")
	{
	    if (!file_exists($path) || is_dir($path)) {
	        return false;
	    }

	    $ext = pathinfo($path, PATHINFO_EXTENSION);
	    if (strtolower(trim($ext)) !== "csv") {
	        return false;
	    }

	    $data = array();
	    $handle = fopen($path, "r");
	    while ($re = fgetcsv($handle)) {
	        if (!implode("", $re)) {
	            break;
	        }
	        $data[] = $re;
	    }

	    return $data;
	}

	/**
	 * Read xml file
	 *
	 * @param string $path
	 * @return array
	 */
	public function openXml($path = "")
	{
	    if (!file_exists($path) || is_dir($path)) {
	        return false;
	    }

	    $ext = pathinfo($path, PATHINFO_EXTENSION);
	    if (strtolower(trim($ext)) !== "xml") {
	        return false;
	    }

	    $xml = simplexml_load_file($path);
	    if (is_object($xml)) {
	        $xml = json_decode(json_encode($xml), true);
	    }

	    return $xml;
	}

	/**
	 * Save csv file
	 *
	 * @param array $param
	 * @param string $path
	 * @return boolean
	 */
	public function saveCsv($param = array(), $path = "")
	{
	    if (file_exists($path) && !is_dir($path)) {
	        return false;
	    }

	    $pathinfo = pathinfo($path);
	    if (!isset($pathinfo['extension'])) {
	        return false;
	    }
	    if (strtolower(trim($pathinfo['extension'])) !== "csv") {
	        return false;
	    }

	    if (!makeFile($path)) {
	        return false;
	    }

	    $handle = fopen($path, 'r+');
	    foreach ($param as $row) {
	        if (!is_array($row)) {
	            continue;
	        }
	        fputcsv($handle, array_map("setText", $row));
	    }
	    fclose($handle);

	    return true;
	}

	/**
	 * Download the csv file
	 * 
	 * @param string $filename = ""
	 */
	public function csvHeader($filename = "")   
	{
	    header("Content-type:text/csv");   
	    header("Content-Disposition:attachment;filename={$filename}");   
	    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');   
	    header('Expires:0');   
	    header('Pragma:public');  
	}

	/**
	 * Encoding conversion
	 * 
	 * @param string $str = ""
	 * @return string $str
	 */
	public function setText($str = "")
	{
	    $str = trim($str);
	    $str = sprintf("%s", $str);
	    $str = iconv("UTF-8", "GBK//IGNORE", $str);
	    $str .= "\t";

	    return $str;
	}

	/**
	 * Sort $array by $sortarray
	 *
	 * @param array $array
	 * @param array $sortArray
	 * @return array
	 */
	public function sortArray($array = array(), $sortArray = array())
	{
	    uasort($array, function ($a, $b) use($sortArray) {
	        $aIndex = array_search($a, $sortArray);
	        $bIndex = array_search($b, $sortArray);
	        if ($aIndex < $bIndex) {
	            return -1;
	        } else {
	            return 1;
	        }
	    });

	    return array_values($array);
	}

	/**
	 * Create new file
	 *
	 * @param string $path
	 * @return boolean
	 */
	public function makeFile($path = "")
	{
	    $path = trim($path);
	    if (!$path) {
	        return false;
	    }

	    $path = preg_replace("/\\\\/", "/", $path);

	    $filename = substr($path, strripos($path, "/") + 1);
	    $ext = substr($filename, strripos($filename, ".") + 1);
	    if (!$ext) {
	        $filename = "";
	    }

	    $dirPathInfo = explode("/{$filename}", $path);
	    array_pop($dirPathInfo);
	    $dirPath = implode("/", $dirPathInfo);

	    if ($filename) {
	        if (is_dir($path)) {
	            return false;
	        }

	        if (file_exists($path)) {
	            return true;
	        }
	    } else {
	        if (is_dir($path)) {
	            return true;
	        }
	    }

	    // make dir
	    if (!is_dir($dirPath)) {
	        if (file_exists($dirPath)) {
	            return false;
	        }

	        if (!@mkdir($dirPath, 0777, true)) {
	            if (!is_dir($dirPath)) {
	                return false;
	            }
	        }
	    }

	    // make file
	    if ($filename) {
	        $handle = fopen($path, 'a');
	        fclose($handle);
	    }

	    if (file_exists($path)) {
	        return true;
	    } else {
	        return false;
	    }
	}

	/**
	 * Read the file directory
	 * 
	 */
	public function searchDir($path = "", &$data = array())
	{
	    if (is_dir($path)) {
	        $handle = opendir($path);
	        while ($re = readdir($handle)) {
	            if (in_array($re, array(".", ".."))) {
	                continue;
	            }
	            searchDir($path . '/' . $re, $data);
	        }
	        closedir($handle);
	    } else {
	        $data[] = $path;
	    }
	}

	/**
	 * Read the file directory
	 * 
	 * @param string $dir = ""
	 * @return array
	 */
	public function getDir($dir = "")
	{
	    $data = array();
	    searchDir($dir, $data);

	    return $data;
	}

	/**
	 * Generate  random
	 *
	 * @param int $bits
	 * @return string
	 */
	public function randId()
	{
	    $key = array(
	        mt_rand(),
	        microtime(),
	        uniqid(mt_rand(), true),
	    );
	    shuffle($key);
	    $str = strtoupper(md5(implode("", $key)));
	    
	    return implode("-", array(
	        substr($str, 0, 8),
	        substr($str, 8, 4),
	        substr($str, 12, 4),
	        substr($str, 16, 4),
	        substr($str, 20, 12),
	    ));
	}

	/**
	 * Split English characters
	 *
	 * @param string $string
	 * @return array
	 */
	public function subStrEnWord($string = "")
	{
	    $str = preg_replace("/,|\.|\?|!|\n|\r|\(|\)/", " ", $string);
	    $str = preg_replace("/[ ]{2,}/", " ", $str);

	    $str = trim($str);
	    if (!$str) {
	        return false;
	    }



	    $data = explode(' ', trim($str));
	    foreach ($data as $key => $value) {
	        if (preg_match("/’/", $value)) {
	            unset($data[$key]);
	        }
	    }

	    return $data;
	}

	/**
	 * Split Chinese characters
	 * 
	 * @param string $string
	 * @return array
	 */
	public function subStrZhWord($string = "")
	{
	    $str = preg_replace("/[0-9a-z]|,|\.|\?|!|\n|\r|’|\(|\)|[ ]/i", "", $string);

	    $str = trim($str);
	    if (!$str) {
	        return false;
	    }

	    $str = urlencode($str);
	    $data = explode("%", $str);
	    $data = array_values(array_diff($data, array("")));

	    $keyWord = "";
	    foreach ($data as $key => $value) {
	        $keyWord .= "%{$value}";

	        if (($key + 1) % 3 === 0) {
	            $data[] = urldecode($keyWord);
	            $keyWord = "";
	        }

	        unset($data[$key]);
	    }

	    return array_values($data);
	}

	/**
	 * Set the URL
	 * 
	 * @param string $url = ""
	 * @return array
	 */
	public function getUrl($url = "")
	{
	    $url = trim($url);
	    if (!$url) {
	        return false;
	    }

	    $urlInfo = explode("?", $url);
	    if (isset($urlInfo[0])) {
	        $urlInfo['url'] = $urlInfo[0];
	        unset($urlInfo[0]);
	    }

	    if (isset($urlInfo[1])) {
	        $urlInfo['params'] = array();
	        foreach (explode("&", $urlInfo[1]) as $value) {
	            if ($value) {
	                $rows = explode("=", $value);
	                $urlInfo['params'][$rows[0]] = isset($rows[1]) ? $rows[1] : '';
	            }
	        }
	        unset($urlInfo[1]);
	    }

	    return $urlInfo;
	}

	/**
	 * Dispose of legitimate URLs
	 * 
	 * @param string $url = ""
	 * @return string
	 */
	public function urlInit($url = "") 
	{
		$url = strtolower(trim($url));
	    if (!$url) {
	        return false;
	    }

		$errorUrl = array(
			"http://", 
	        "https://",
		);
		if (in_array($url, $errorUrl)) {
			return false;
		}

		if (preg_match("/^\/|^#|^javascript|^\?/i", $url)) {
			return false;
		}

		if (!preg_match("/^http:\/\/|^https:\/\//i", $url)) {
			if (preg_match("/^\/\//", $url)) {
				$url = "http:{$url}";
			} else {
				$url = "http://{$url}";
			}
		}

		if (preg_match("/\/$/", $url)) {
			$url = substr($url, 0, strlen($url) - 1);
		}
		if (preg_match('/\/#$/i', $url)) {
			$url = substr($url, 0, strlen($url) - 2);
		}

		return $url;
	}

	/**
	 * Use the curl virtual browser
	 *
	 * @param array $urlInfo = array('url' => "https://www.baidu.com/", 'params' => array('key' => 'test'), 'cookie' => 'cookie')
	 * @param string $type = 'GET|POST'
	 * @param boolean $info = false|true
	 * @return string|array
	 */
	public function curl($urlInfo, $type = "GET", $info = false) {
	    $type = strtoupper(trim($type));

	    if (isset($urlInfo['cookie'])) {
	        $cookie = $urlInfo['cookie'];
	        unset($urlInfo['cookie']);
	    }

	    if ($type == "POST") {
	        $url = $urlInfo['url'];
	        $data = $urlInfo['params'];
	    } else {
	        if (isset($urlInfo['params'])) {
	            foreach ($urlInfo['params'] as $key => $value) {
	                $urlInfo['params'][] =  "{$key}={$value}";
	                unset($urlInfo['params'][$key]);
	            }
	            $urlInfo['params'] = implode("&", $urlInfo['params']);
	        }
	        $url = implode("?", $urlInfo);
	    }
	    
	    $httpHead = array(
	        "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
	        "Cache-Control:no-cache",
	        "Connection:keep-alive",
	        "Pragma:no-cache",
	        "Upgrade-Insecure-Requests:1",
	    );
	    
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    if (isset($cookie)) {
	        curl_setopt($ch, CURLOPT_COOKIE , $cookie);
	    }
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHead);
	    curl_setopt($ch, CURLOPT_ENCODING , "gzip");
	    if ($type == "POST") {
	        curl_setopt($ch, CURLOPT_POST, 1);
	        @curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	    } else {
	        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	    }
	    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.112 Safari/537.36");
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_NOBODY, 0);
	    $result = curl_exec($ch);
	    $curlInfo = curl_getinfo($ch);
	    curl_close($ch); 
	    
	    if ($info) {
	        return $curlInfo;
	    } else {
	        return $result;
	    }
	}

	/**
	 * Regular match HTML
	 *
	 * @param string $html = ""
	 * @param string $preg = ""
	 * @param boolean $status = true|false
	 * @return string
	 */
	public function pregHtml($html = "", $preg = "", $status = true)
	{
	    $pregInit = array(
	        'clear' => "/\f|\n|\r|\t|\v/",
	        'spaces' => "/[ ]{2,}/",
	        'css' => "/<style[^>]*>.+?<\/style>/i",
	        'js' => "/<script[^>]*>.+?<\/script>/i",
	        'nojs' => "/<noscript[^>]*>.+?<\/noscript>/i",
	        'notes' => "/<!.*?>/",
	    );

	    //init
	    $html = trim($html);
	    foreach ($pregInit as $key => $value) {
	        switch ($key) {
	            case 'clear':
	                $html = preg_replace($value, "", $html);
	                break;
	            case 'spaces':
	                $html = preg_replace($value, " ", $html);
	                break;
	            default:
	                if ($status) {
	                    $src = pregHtml($html, $value, false);
	                    if (is_array($src)) {
	                        foreach ($src as $val) {
	                            $html = str_replace($val, "", $html);
	                        }
	                    } else {
	                        $html = str_replace($src, "", $html);
	                    }
	                }
	                break;
	        }
	    }

	    if (!$preg) {
	        return $html;
	    }

	    //action
	    preg_match_all($preg, $html, $pregArr);

	    if (isset($pregArr[1])) {
	        if (count($pregArr[1]) == 1) {
	            $pregArr = $pregArr[1][0];
	        } else {
	            $pregArr = $pregArr[1];
	        }
	    } else {
	        if (count($pregArr[0]) == 1) {
	            $pregArr = $pregArr[0][0];
	        } else {
	            $pregArr = $pregArr[0];
	        }
	    }

	    return is_array($pregArr) ? array_unique($pregArr) : $pregArr;
	}

	/**
	 * Get the HTML tag
	 *
	 * @param string $str = ""
	 * @return array
	 */
	public function getTags($str = "")
	{
	    $oneTag = array(
	        "meta", "link", "input", "img", "br", "hr", "param",
	    );
	    $data = $tagArr = array();

	    for($i=0;;$i++) {
	        $str = trim($str);
	        if (preg_match("/^</", $str)) {
	            if (preg_match("/^<\//", $str)) {
	                //right tag
	                $lastStatus = stripos($str, ">");
	                $rightTag = substr($str, 0, $lastStatus + 1);
	                $tag = pregHtml($rightTag, "/^<\/([a-z1-6]+)>/i");

	                foreach ($tagArr as $key => $value) {
	                    $valTag = pregHtml($value, "/^<([a-z1-6]+)[\s]?.*[\/]?>/i");
	                    if ($tag == $valTag) {
	                        $leftTag = $value;
	                        $tagKey = $key;
	                    }
	                }

	                $lastDataKey = array_search(end($data[$leftTag]), $data[$leftTag]);
	                foreach ($data[$leftTag] as $key => $value) {
	                    if (!isset($value['right'])) {
	                        $lastDataKey = $key;
	                    }
	                }

	                $data[$leftTag][$lastDataKey]['right'] = $rightTag;
	                unset($tagArr[$tagKey]);

	                $str = substr($str, $lastStatus + 1, strlen($str) - $lastStatus);
	            } else {
	                //left tag
	                $lastStatus = stripos($str, ">");
	                $leftTag = substr($str, 0, $lastStatus + 1);
	                $tag = pregHtml($leftTag, "/^<([a-z1-6]+)[\s]?.*[\/]?>/i");

	                $data[$leftTag][$i]['tags'] = $tagArr;
	                $data[$leftTag][$i]['left'] = $leftTag;
	                if (preg_match("/\/>$/", $leftTag) || in_array($tag, $oneTag)) {
	                    //no right tag
	                    $data[$leftTag][$i]['right'] = "";
	                } else {
	                    //have right tag
	                    $tagArr[] = $leftTag;
	                }

	                $str = substr($str, $lastStatus + 1, strlen($str) - $lastStatus);
	            }
	        } else {
	            //content
	            $startStatus = stripos($str, "<");
	            $content = substr($str, 0, $startStatus);
	            $lastTagKey = end($tagArr);
	            $currentData= $data[$lastTagKey];
	            $data[$lastTagKey][array_search(end($currentData), $currentData)]['content'] = $content;

	            $str = substr($str, $startStatus, strlen($str) - $startStatus);
	        }

	        if (!$str) {
	            break;
	        }
	    }

	    return $data;
	}

	/**
	 * Face++ API
	 *
	 * @param string $interfaceName = "facepp/v3/detect"
	 * @param array $params = array('url' => 'http://www.baidu.com/1.jpg' | 'img' => '@c:/1.jpg')
	 * @return array
	 */
	public function faceAPI($interfaceName = "", $params = array())
	{
	    /*
	     * url: https://console.faceplusplus.com.cn/dashboard
	     * email: xingchenyekong@163.com
	     * api_key: EkItamo3TvH2s5iFrzVDEDN6FevsbIS7
	     * api_server: N5zDXGvt0GDuKbdLnxhzi2l7Uof-LOYX
	     */

	    $interfaceArr = array(
	        "facepp/v3/detect", 
	        "imagepp/beta/detectsceneandobject",
	        "imagepp/beta/recognizetext",
	    );
	    if (!in_array($interfaceName, $interfaceArr)) {
	        $interfaceName = "facepp/v3/detect";
	    }

	    $params['api_key'] = "EkItamo3TvH2s5iFrzVDEDN6FevsbIS7";
	    $params['api_secret'] = "N5zDXGvt0GDuKbdLnxhzi2l7Uof-LOYX";

	    $urlInfo = array(
	        'url' => "https://api-cn.faceplusplus.com/{$interfaceName}",
	        'params' => $params,
	    );

	    $recult = curl($urlInfo, "POST");

	    return json_decode($recult, true);
	}

	/**
	 * Google translation
	 *
	 * @param array $tranInfo = array('tl' => 'zh-CN', 'text' => "Hello World")
	 * @return array
	 */
	public function translation($tranInfo = array('tl' => 'en', 'text' => 'Hello World'))
	{
	    if (!isset($tranInfo['tl']) || !isset($tranInfo['text'])) {
	        return false;
	    }

	    $urlInfo = array(
	        'url' => 'http://translate.google.com/translate_t',
	        'params' => array(
	            'client' => 't',
	            'sl' => 'auto',
	            'tl' => $tranInfo['tl'],
	            'ie' => 'UTF-8',
	            'text' => $tranInfo['text'],
	        ),
	    );
	    $html = curl($urlInfo);

	    $title = urldecode($tranInfo['text']);
	    $title = preg_replace("/[ ]+/", " ", $title);
	    $pregArr = array(
	        'key_1' => "/<span[\s]?title[\s]*=[\s]*[\"|']?{$title}[\"|']?[^>]*>/i",
	        'key_2' => "/<span[\s]?id[\s]*=[\s]*[\"|']?result_box[\"|']?[^>]*>/i",
	    );

	    $html = pregHtml($html);
	    $html = preg_replace("/&#39;/", "'", $html);
	    $data = getTags($html);
	    $content = "";
	    foreach ($data as $key => $value) {
	        if (preg_match($pregArr['key_1'], $key) || preg_match($pregArr['key_2'], $key)) {
	            $row = reset($value);
	            $content = isset($row['content']) ? $row['content'] : "";
	        }
	    }
	    
	    return $content;
	}

	/**
	 * Get Baidu Encyclopedia
	 *
	 * @param string $name = ""
	 * @return string
	 */
	public function baike($name = "")
	{
	    $pregArr = array(
	        'url' => "/<a[\w\s=\"]*href[\s]*=[\s]*[\"|']{1}http:\/\/www.baidu.com\/link\?url=([\S]*)[\"|']{1}[^>]*><em>{$name}<\/em>/i",
	        'baike' => "/<div[\w\s=\"]*class[\s]*=[\s]*[\"|']{1}para[\"|']{1}[^>]*>(.*?)<\/div>/i",
	    );

	    $urlInfo = array(
	        'url' => 'http://www.baidu.com/s',
	        'params' => array(
	            'wd' => urlencode($name),
	        ),
	    );
	    $html = curl($urlInfo);
	    $html = pregHtml($html, $pregArr['url']);
	    if (!isset($html[0])) {
	        return false;
	    }

	    $urlInfo = array(
	        'url' => 'http://baike.baidu.com/link',
	        'params' => array(
	            'url' => $html[0],
	        ),
	    );
	    $html = curl($urlInfo);

	    $html = pregHtml($html, $pregArr['baike']);
	    if (is_array($html)) {
	        $str = "";
	        foreach ($html as $value) {
	            $str .= strip_tags($value);
	        }

	        $html = $str;
	    }
	    $html = str_replace("&nbsp;", " ", $html);

	    return $html; 
	}

	/**
	 * Get the flag (svg)
	 *
	 * @param string $name = "ca"
	 * @return string
	 */
	public function getCountryFlag($name = "ca")
	{
	    $flagname = strtolower(trim($name));
	    $url = "http://lipis.github.io/flag-icon-css/flags/4x3/{$flagname}.svg";
	    $recult = @file_get_contents($url);

	    return $recult;
	}

	/**
	 * Get the instagram PAI
	 * 
	 * @param string $picUrl = ""
	 * @return array
	 */
	public function getInstagram($picUrl = "")
	{
	    if (isset($_COOKIE['access_token'])) {
	        $accessToken = $_COOKIE['access_token'];
	    } else {
	        $usersInfo = array(
	            'ebddev@eyebuydev' => array(
	                'client_id' => "78304140eb41434b9dfe19edfdea8ee5",
	                'redirect_uri' => "https://www.eyebuydirect.com",
	                'client_secret' => "a476a01294cd4f80b0d26a659dd3f9c3",
	                'cookie' => "mid=V4wt_QAEAAEhZ1_N0YbqPEaus9lA; fbm_124024574287414=base_domain=.instagram.com; sessionid=IGSCb72f099d36345a217d4a474734126c53d41210e31882f0cc2f75b753da002685%3AjXUvpyCmkyLdssIogCFXFDNr2Xa4c1Ws%3A%7B%22_token_ver%22%3A2%2C%22_auth_user_id%22%3A3989476732%2C%22_token%22%3A%223989476732%3AKsnNiEau58FJwuAufpIlrUnoRMM5PIok%3Ada72cfe8a0f067bcc229614a37ae8775ea6cfe4423490539641a38c15bd1f015%22%2C%22asns%22%3A%7B%22204.74.223.145%22%3A20248%2C%22time%22%3A1476168870%7D%2C%22_auth_user_backend%22%3A%22accounts.backends.CaseInsensitiveModelBackend%22%2C%22last_refreshed%22%3A1476168879.344688%2C%22_platform%22%3A4%2C%22_auth_user_hash%22%3A%22%22%7D; s_network=; fbsr_124024574287414=7KkkueXSnKfY4aDBmPVL_x5HTj9hNvGfItjtTTDrfXE.eyJhbGdvcml0aG0iOiJITUFDLVNIQTI1NiIsImNvZGUiOiJBUUF6eVRWV2gxWFlEeUxVcmp1OS1yRHlLT1ZQSWZYWGRYWjdnQWxhd0c2bHNLLVpKamg0N1Y4X2dsYXZSLWNHSTIwN1UtS0tfWkYxdEFKVU1vZVJ2ZTA1R3Bsd1h5Vnp0cWUtWUlTMXhmLWRoOXVNYzU0clFFV05KUWVjZXlSWWpMUWVtY2dHVTZGRHM0T1FUUzlURVRycmtTWDllNkFyT1liY1RGWXFmY2pjQmlCdWxHeU4wbkM1Y1JoOGdkNXJvel91aEVjWjVyWHdXX1BvS0czVTNCMTFlazRHeEJ3UHlfWGI1NHpZZ0xOM09YVUpoS1NCYTdRSFFMdHBZSTFzYlo5dEdTZVhtUlZodWF6N3JBXzFwVllMRzZTQWZuU25qV1RYeHFmc0g5ZGFRbDl0MS0ybnRVMUVYNldoTGhHSlZVVGFnVHdjcHktZWhKS0ZNSzU1T2Z2RyIsImlzc3VlZF9hdCI6MTQ3NjE2ODg4MywidXNlcl9pZCI6IjEwMDAxMDYxODc0MDU5MCJ9; ig_pr=1; ig_vw=1920; csrftoken=jhiELPGLN5dOvGGw6YF0FNCo7Ve6lEIB; ds_user_id=3989476732",
	            ),
	        );
	        $accessToken = getAccessToken($usersInfo['ebddev@eyebuydev']);
	        setcookie("access_token", $accessToken, time() + 86400, "/");
	    } 

	    $instagramUrl = "https://api.instagram.com/oembed/?url=" . $picUrl;
	    $instagramInfo = @file_get_contents($instagramUrl);
	    $instagramInfo = json_decode($instagramInfo, true);
	    $imgInfo = array(
	        'media_id' => $instagramInfo['media_id'],
	        'banner_large_file' => explode("?", $instagramInfo['thumbnail_url'])[0],
	        'banner_alt' => $instagramInfo['title'],
	        'ins_author_name' => $instagramInfo['author_name'],
	    );
	    $keys = array(
	        'likes',
	        'comments',
	    );
	    $mediaId = $imgInfo['media_id'];
	    foreach ($keys as $key) {
	        $url = "https://api.instagram.com/v1/media/{$mediaId}/{$key}?access_token={$accessToken}";
	        $recult = @file_get_contents($url);
	        $recult = json_decode($recult, true);
	        $imgInfo[$key] = count($recult['data']);
	    }

	    return $imgInfo;
	}

	/**
	 * Get instagram access token
	 * @param array $userInfo = array()
	 */
	public function getAccessToken($userInfo = array())
	{
	    /*get code*/
	    $urlInfo = array(
	        'url' => "https://api.instagram.com/oauth/authorize/",
	        'params' => array(
	            'client_id' => $userInfo['client_id'],
	            'redirect_uri' => $userInfo['redirect_uri'],
	            'response_type' => "code",
	        ),
	        'cookie' => $userInfo['cookie'],
	    );
	    $recult = curl($urlInfo, "GET", true);
	    $recultUrl = getUrl($recult['url']);
	    $code = $recultUrl['params']['code'];

	    //get access_token
	    $urlInfo = array(
	        'url' => "https://api.instagram.com/oauth/access_token",
	        'params' => array(
	            'client_id' => $userInfo['client_id'],
	            'client_secret' => $userInfo['client_secret'],
	            'grant_type' => "authorization_code",
	            'redirect_uri' => $userInfo['redirect_uri'],
	            'code' => $code,
	        ),
	    );
	    $recult = curl($urlInfo, "POST");
	    $recult = json_decode($recult, true);
	    $accessToken = $recult['access_token'];

	    return $accessToken;
	}
}