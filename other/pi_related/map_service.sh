#!/bin/bash

touch /forcefsck
/usr/sbin/check_bases.sh > /var/log/checkdb.log
exit