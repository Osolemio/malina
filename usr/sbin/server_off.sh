#!/bin/bash

#--- this script is called by stop_gen.sh if DOD or Ah threshold is over
# add a line for each server
# ssh -t rebootuser@'server_ip' 'sudo shutdown -h now'
# or any other command you like
# public key ~/.ssh/id_rsa.pub must be copied to ~/.ssh/authorized_keys on each server you want to shut down
#
    if test ! -e "/var/map/.lock_server";
	then

# ssh -t rebootuser@'server_ip' 'sudo shutdown -h now'




	fi