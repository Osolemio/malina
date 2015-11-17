#!/bin/sh

cp /var/map/sms_tmp.conf /etc/smsd.conf
chown 0:0 /etc/smsd.conf
chmod 644 /etc/smsd.conf
rm /var/map/sms_tmp.conf
