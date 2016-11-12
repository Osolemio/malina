#!/bin/bash

start_line=` grep -n '#data start#' /usr/sbin/mail_compose.sh | cut -d ":" -f1`
end_line=` grep -n '#data end#' /usr/sbin/mail_compose.sh | cut -d ":" -f1`

sed -n ${start_line},${end_line}p /usr/sbin/mail_compose.sh | grep -v '^#\|^$' > /var/tmp/splitted.txt
sed -n 1,${start_line}p /usr/sbin/mail_compose.sh > /var/tmp/begin.txt
sed -n ${end_line},`wc -l /usr/sbin/mail_compose.sh | cut -d " " -f1`p /usr/sbin/mail_compose.sh > /var/tmp/end.txt
grep -v '^#\|^$' /etc/ssmtp/ssmtp.conf > /var/tmp/ssmtp.conf
grep -v '^#\|^$' /etc/ssmtp/revaliases > /var/tmp/revaliases

