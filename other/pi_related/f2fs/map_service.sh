#!/bin/bash

touch /forcefsck
/usr/sbin/check_bases.sh > /var/log/checkdb.log
mount -o remount,rw,commit=600 /dev/sda2
exit