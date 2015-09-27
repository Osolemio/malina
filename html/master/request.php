<?php

    function file_get_content_timeout ($URL, $timeout = 1){
	$timeout = (int) $timeout;
	if ($timeout < 1) $timeout = 1;
	$Error = "Can't connect to remote URL";
	$content = '';

	if ($handler = fsockopen ($URL, $_POST['port'], $Error, $Error, $timeout)){
	    stream_set_timeout($handler,$timeout);
	$H = "GET /".$_POST['dev']." HTTP/1.1\r\n";
	$H.= "Host: $URL\r\n";
	$H.= "Connection: Close\r\n\r\n";
	fwrite($handler, $H);

	while (!feof ($handler)){
	$content.= fread ($handler, 4096);
	
	}
	
	$pos=strpos($content,'{');
	$pos1=strripos($content,'}');
	if ($pos>0) {
	    $content=substr($content,$pos,$pos1-$pos+1);

	} else $content='ERROR';

    fclose ($handler);
	return $content;
	}
    } 
    $answer=file_get_content_timeout($_POST['url'],1);
    echo $answer;

?>