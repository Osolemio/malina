
<?php
    include('../local/local.inc');
    $out=shell_exec("sudo /usr/sbin/pbase_restore.sh");
    echo str_replace("\n","<br>",$out);
    loc('script_end');


    
?>