#!/bin/bash
cp /var/tmp/ssmtp.conf /etc/ssmtp
cp /var/tmp/revaliases /etc/ssmtp
cat /var/tmp/begin.txt /var/tmp/splitted.txt /var/tmp/end.txt > /usr/sbin/mail_compose.sh
