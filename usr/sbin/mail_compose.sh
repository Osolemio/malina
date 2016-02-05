#!/bin/bash

out=(`mysql -NBu root -pmicroart map -e "SELECT * from data WHERE number=(SELECT MAX(number) from data)"`)

declare -A data_fields
declare -A alias
declare -A min
declare -A max


#here you have to define fields to check, min & max values, their aliases for mail, your email, items number.

#================= Values from mysql DB===================
#_Uacc
data_fields[1]=${out[5]}
#_UOUTmed
data_fields[2]=${out[15]}
#_MODE
data_fields[3]=${out[3]}
#_Temp_Grad0
data_fields[4]=${out[22]}

#=============  Aliases or text============================
alias[1]="Acc voltage "
alias[2]="Output AC "
alias[3]="Mode "
alias[4]="Acc temp "


#=============== min & max values ========================
min[1]=44.0
min[2]=200

# MODE=2, NO INPUT AC
#if min=max, alert will be on equal. Otherwise >=max or <=min
# you may use decimals because we use perl to compare 

min[3]=2
min[4]=3

max[1]=59.0
max[2]=250
max[3]=2
max[4]=30

#====================== 
# Number of items
index=4

#====================== Where we want to send email to =========================
mail_recipient="12345@mail.ru"

#============================== script body ===================================

for ((i=1; i<=$index; i++)) do

  result=(`perl -e "if(${data_fields[$i]}<${max[$i]} || ${data_fields[$i]}>${min[$i]}){print 1;}else{print 0;}"`)

if test $result -eq 0; then

 if test ! -e "/var/map/temp_email_$i"; then
    cur_t=`date`
    echo " " >> /var/map/temp_email_$i
    echo ${alias[$i]} >> /var/map/temp_email_$i
    echo "value: ${data_fields[$i]} $cur_t" >> /var/map/temp_email_$i
    tmp_name=`date +%s`
    /usr/bin/mail -s "Notification from MAP" $mail_recipient < /var/map/temp_email_$i
 fi
 fi

 if test $result -eq 1; then

 if test -e "/var/map/temp_email_$i"; then
    cur_t=`date`
    rm /var/map/temp_email_$i
    echo " " >> /var/map/temp_email_ok_$i
    echo ${alias[$i]} >> /var/map/temp_email_ok_$i
    echo "value: ${alias[$i]} is in range. Value=${data_fields[$i]} $cur_t" >> /var/map/temp_email_ok_$i
    tmp_name=`date +%s`
    /usr/bin/mail -s "Notification from MAP" $mail_recipient < /var/map/temp_email_ok_$i
    rm /var/map/temp_email_ok_$i
 fi
 fi

done
