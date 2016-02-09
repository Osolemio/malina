#!/bin/bash

#remove old smsd stats. Older than 7 days
find /var/log/smstools/smsd_stats -type f -mtime +7 -print0 | xargs -0 rm -f
find /var/log/smstools -type f -mtime +7 -print0 | xargs -0 rm -f


#remove old log files
find /var/log -type f -mtime +7 -print0 | xargs -0 rm -f
find /var/log/nginx -type f -mtime +7 -print0 | xargs -0 rm -f



#remove failed emails
rm /root/dead.letter
