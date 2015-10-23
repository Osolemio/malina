!#/bin/bash
echo "Start recreating /dev/sda3 ext2 partition"

echo "Stop all MAP services"
rm /var/map/.map
rm /var/map/.mppt
rm /var/map/.bmon
sleep 20

echo "Mysql stopping"
/etc/init.d/mysql stop
echo "Unmounting /dev/ext2"
echo "Recreating /dev/ext2"
umount /bases
fsck.ext2 -f /dev/sda3

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
echo "Done. Making ext2 on /dev/sda3"
mkfs.ext2 /dev/sda3

fsck.ext2 -f /dev/sda3

echo "mounting /bases"
mount -a
echo "copying empty DB files"
cp -rp /home/pi/db_backup/mysql /bases
sync
echo "mysql start"
/etc/init.d/mysql start
echo "done"
