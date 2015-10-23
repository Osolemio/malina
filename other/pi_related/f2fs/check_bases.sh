#!/bin/bash
echo "Start checking database partition"
/etc/init.d/mysql stop
umount /bases
fsck.f2fs -f /dev/sda4
mount -a
/etc/init.d/mysql start
