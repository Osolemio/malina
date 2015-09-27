<?php
 $uploaddir = '/var/update/';
 $recipient = 'osolemio@live.ru';
 $name=$uploaddir."update.tar";
 $gpg='/usr/bin/gpg';
 $pos=strpos($_FILES['update']['name'],'update');

 if ($pos===false) 
  {
    echo "Filename is not valid<br>";
    exit(-1);
 }

 $target = $uploaddir . "update.sig";
   unlink($name);
   if (move_uploaded_file($_FILES['update']['tmp_name'], $target)) 
  {
      echo "File name is ok, the file has been successfully uploaded.<br>";
	$command="sudo /usr/bin/gpg -o /var/update/update.tar -r osolemio@live.ru --decrypt /var/update/update.sig";
        exec($command,$shell,$return);
	if ($return!=0) 
	    {
	    echo "system error<br>";
	    exit(-1);
	    }
	if (file_exists($name)) {
	    unlink($target);
           echo "File has been decrypted ok. You may reboot to start update.<br>";
	    }
	else 
	    {
	    echo "File is corrupted or wrong<br>";
	    unlink($target);
	    exit(-1);
	    }
   } else {
          echo "File uploading failed.<br>";
   }


?>