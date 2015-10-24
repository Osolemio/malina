!#/bin/bash
echo "Start recreating /dev/sda4 f2fs partition"

echo "Stop all MAP services"
rm /var/map/.map
rm /var/map/.mppt
rm /var/map/.bmon
sleep 20

echo "Mysql stopping"
/etc/init.d/mysql stop
echo "Unmounting /dev/sda4"
echo "Recreating /dev/sda4"
umount /dev/sda4

fsck.f2fs -f /dev/sda4

  fdisk /dev/sda <<EOF
p
d
4
n
p



p
w
q
EOF
echo "Done. Making f2fs on /dev/sda4"
partprobe
umount /dev/sda4
mkfs.f2fs /dev/sda4

fsck.f2fs -f /dev/sda4

echo "mounting /bases"
mount -a
echo "copying empty DB files"
cp -rp /home/pi/db_backup/mysql /bases
sync
echo "mysql start"
/etc/init.d/mysql start
echo "done"
