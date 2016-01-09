<?php
    $strFile = rawurldecode(@$_GET['url']);
	$strFile_array = explode('.' , $strFile);
    $strFileExt = end($strFile_array);

    if($strFileExt == 'jpg' or $strFileExt == 'jpeg') {
        header('Content-Type: image/jpeg');
    } elseif($strFileExt == 'png') {
        header('Content-Type: image/png');
    } elseif($strFileExt == 'gif') {
        header('Content-Type: image/gif');
    } else {
        die('not supported');
    }
	
    if($strFile != ''){
        $cache_expire = 60*60*24*365;
        header("Pragma: public");
        header("Cache-Control: maxage=". $cache_expire);
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $cache_expire).' GMT');
        echo file_get_contents($strFile);
    }

    exit;
?>


