/*
 * mapd.c
 * Release 2.03b
 *  Created on: April 6 2015 
 *      Author: Osolemio
 */  
 
#include <fcntl.h>
#include <unistd.h>
#include <termios.h>
#include <stdio.h>
#include <stdlib.h>
#include <syslog.h>
#include <string.h>
#include <mysql/mysql.h>
#include <time.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <errno.h>
#include <signal.h>
#include <sys/ipc.h>
#include <sys/shm.h>


#define VOLTAGE 48
#define DAEMON_NAME "MAP Reader daemon"
#define SHARED_MEMORY_KEY  1998
#define SHARED_MEMORY_KEY_BMS  1997
#define SHARED_MEMORY_SIZE 100
#define SHM_CREATE 1
#define PORT_MAP "/dev/ttyUSB0"
#define BAUDRATE B19200
#define size_of_buffer 560
#define to_read 0x72
#define to_write 0x77
#define map 1
#define mppt 2
#define true 1;
#define false 0;
#define PID_FILE "/var/run/mapd.pid" // not used now


//========  BMS THRESHOLDS =================

#define bms_high_u 3.9
#define bms_low_u 2.7
#define bms_high_t 40
#define bms_low_t 0


//======================================

unsigned char Buffer[size_of_buffer];
unsigned char sum;


struct timeval tv1,tv2,dtv;


void time_start() { gettimeofday(&tv1, &timezone); }

long time_stop()

{ gettimeofday(&tv2, &timezone);

  dtv.tv_sec= tv2.tv_sec -tv1.tv_sec;
  
    dtv.tv_usec=tv2.tv_usec-tv1.tv_usec;
    
      if(dtv.tv_usec<0) { dtv.tv_sec--; dtv.tv_usec+=1000000; }
      
        return dtv.tv_sec*1000+dtv.tv_usec/1000;
        
        }
//-------------------------------------------------------------------------



int put_char(int fd, unsigned char a)
 {
 	unsigned char a1;
	unsigned int h=0;
      
	 if (write(fd,&a,1)!=1)  return -1;
	 if (read(fd,&a1,1)!=1) return -1;
	 
	 
	 if (a!=a1) {do {read(fd,&a1,1);} while (a!=a1 && h++<20);} // tuning to the stream if there are omitten bytes 
	 if (a!=a1) 
          {  
	    return -1;
	  }
    return 0;

 }


void code_DB (int fd, unsigned char a)
{
  if (a == '\n')
    
    {
      sum += 0xDB;
      if (put_char (fd, 0xDB) != 0)
	return;
      sum += 0xDC;
      if (put_char (fd, 0xDC) != 0)
	return;
    }
  
  else if (a == 0xDB)
    
    {
      sum += 0xDB;
      if (put_char (fd, 0xDB) != 0)
	return;
      sum += 0xDD;
      if (put_char (fd, 0xDD) != 0)
	return;
    }
  
  else
    {
      sum += a;
      put_char (fd, a);
      return;
    }
 }

void send_command (unsigned char command, int fd, unsigned short addr,
	      unsigned short page) 
{
  unsigned char a[4];
  short i;
  sum = 0;
  a[0] = command;
  a[1] = page;
  a[2] = addr >> 8;
  a[3] = addr & 0xFF;
  for (i = 0; i < 4; i++)
    code_DB (fd, a[i]);		//
   if (command == to_write)
    for (i = 0; i <= page; i++)
      code_DB (fd, Buffer[i]);
    sum = 0xff - sum;
  sum++;
  if (put_char (fd, sum) != 0)
    return;
   if (sum != '\n')
    put_char (fd, '\n');
}

 void decode_answer (int i)

{
  int c, c1;
  for (c = 1; c <= i; c++)
    {
      if (Buffer[c] == 0xDB && Buffer[c + 1] == 0xDC)
	{
	  Buffer[c] = 0x0A;--i;
	  for (c1 = c; c1 < (i-1); c1++)
	    Buffer[c1 + 1] = Buffer[c1 + 2];
	}
      else if (Buffer[c] == 0xDB && Buffer[c + 1] == 0xDD)
	{
	  Buffer[c] = 0xDB;--i;
	  for (c1 = c; c1 < (i-1); c1++)
	    Buffer[c1 + 1] = Buffer[c1 + 2];
	}
    }

}


int read_answer (int fd) 
 {
  int i = 0, c = 0;
  unsigned char sum_r = 0;
   if (read (fd, &Buffer, 1) != 1)
    return -1;
  
  do
    
    {
      if (write (fd, &Buffer[i], 1) != 1)
	return -1;
      if (read (fd, &Buffer[++i], 1) != 1)
	return -1;
    }
  while (Buffer[i] != 0x0A && i < 560);
  if (write (fd, &Buffer[i], 1) != 1)
    return -1;
  if (Buffer[0] == 0x65)
    {
      return -1;
    }
  if (Buffer[0] == 0x6f)
    {
      for (c = 0; c < (i - 1); c++)
	sum_r += Buffer[c];
      sum_r = 0xff - sum_r;
      sum_r++;
      if ((sum_r != 0x0A) && (sum_r != Buffer[i - 1]))
	return -1;
      if ((sum_r == 0x0A) && (sum_r != Buffer[i]))
	return -1;
      decode_answer (i);
      return 0;
    }
   ;
  return -1;
 }


 int open_port (char dev)
{
   int fd;
   if (dev == map)
    
    {
      fd = open (PORT_MAP, O_RDWR, O_NOCTTY);
      if (fd < 0)
		{
	syslog(LOG_ERR,"Can't open serial port");
		return -1;
		}
	return fd;
    }
  
return -1;
}



static void signal_hdl(int sig, siginfo_t *siginfo, void *context)
  {
      struct tm *newtime;
      char *sig_desc;

        struct tm tim;
        time_t ltime;

	sig_desc=malloc(100);
	sig_desc=strsignal(sig);
	
	
          switch(sig)
          {
                  case SIGTERM:
                  case SIGINT:
                  case SIGQUIT:
                  case SIGHUP:
 		   time (&ltime);
    		 newtime = localtime (&ltime);
			syslog(LOG_NOTICE, "stopped by terminate signal at: %s",asctime(newtime));
                          closelog();
                          unlink(PID_FILE);
                          system("sync");
                          exit(0);
                          break;
                  default:
                          syslog(LOG_NOTICE,"got signal %d. ignore...",sig);
                          break;
          }
  }

  int read_eeprom(unsigned char *eeprom, int fd, MYSQL mysql)
    {
    int i;
    char query[255];
        
     send_command(to_read,fd,0,0xFF);
//       tcdrain(fd);
        if (read_answer(fd) == 0) 
         {
          for (i=0;i<256;i++) eeprom[i]=Buffer[i+1];
          
         }
         else 
         {
         
         syslog(LOG_ERR, "Error read MAP settings"); return -1;
         
         }
    
      send_command(to_read,fd,0x100,0xFF);
//       tcdrain(fd);
        if (read_answer(fd) == 0) 
         {
         for (i=0;i<256;i++) eeprom[i+0x100]=Buffer[i+1];
         }
         else 
         {
         
         syslog(LOG_ERR, "Error read MAP settings"); return -1;
         
         }
         
         sprintf(query,"TRUNCATE TABLE settings");
         
         if (mysql_query (&mysql, query))
        	    {
        	      syslog(LOG_ERR,"Error truncate table in MySQL. Table settings\n");
        	      return -1;
        	    }
	 for (i=0;i<0x200;i++) {
	 
	  sprintf(query, "INSERT INTO settings VALUES('%d','%d',NULL)",i,eeprom[i]);
	  if (mysql_query (&mysql, query))
        	    {
        	      syslog(LOG_ERR,"Error adding data in MySQL. Table settings\n");
        	      return -1;
        	    }
	       
	 }
    
    return 0;
    } 


    unsigned char real_mode(unsigned char mode, unsigned char net_alg, unsigned char flag_eco, unsigned char NetUpEco, unsigned char NetUpLoad, unsigned char UNET)
	
	    {
	    
	
/*

    M_OFF          =0 – МАП выключен и нет сети на входе
    M_OFFNET       =1 – МАП выключен но есть сеть на входе (значение напряжения сети выводится в ЖКИ)
    M_ON           =2 – МАП включен (происходит генерация 220В от АКБ, нет сети на входе.  
    M_ONNET        =3 – МАП включен и транслирует сеть (есть сеть на входе).
    M_ONCHARGE 	   =4 – МАП включен, транслирует сеть и одновременно заряжает АКБ.

    ------------ my extentions------------------------
    10 - принудительная генерация
    11 - тарифная сеть. максимальный тариф. принудительная генерация
    12 - тарифная сеть. минимальный тариф
    13 - трансляция + эко-подкачка
    14 - трансляция + продажа в сеть
    15 - ожидание внешнего заряда
    16 - тарифная сеть. трансляция+эко-подкачка
    17 - тарифная сеть. трансляция+продажа в сеть

    
    
    


*/	    

	if (mode<2 && mode>3) return mode;

      if (NetUpEco==0) //ECO forced gen or Tarifs
	 {

	if (mode==2) 
	    {
		if (UNET>100)
		 {
		    if (net_alg==2) return 10;else
		    if (net_alg==3 && (flag_eco&2)==0) return 11; else
		    if (net_alg==3 && (flag_eco&2)>0) return 12;

		 } else return 2;

	    }

	}

      if (NetUpEco==1) // Eco pumping
	{

	 if (mode==3)
	    {
		if (net_alg==2 && (flag_eco&1)>0) return 15;else
		if (net_alg==2 && (flag_eco&1)==0) return 13; else
		if (net_alg==3 && (flag_eco&2)>0) return 12; else
		if (net_alg==3 && (flag_eco&2)==0) return 16;
	    }

	}

	if (NetUpEco==2) // Sell to network
	{

	 if (mode==3)
	    {
		if (net_alg==2 && (flag_eco&1)>0) return 15;else
		if (net_alg==2 && (flag_eco&1)==0) return 14; else
		if (net_alg==3 && (flag_eco&2)>0) return 12; else
		if (net_alg==3 && (flag_eco&2)==0) return 17;
	    }

	}
    
	

	return mode;


    }



//-----------------------------------------------------------MAIN ---------------------------------------------------------

  int main(int argc, char* argv[])

  {

	struct map_bat
	{
	unsigned char battery_id;
	unsigned long int timestamp;
	float current;
	char tbat;
	float Ubat;
	float Imppt; 
	};
	
	struct map_bat *batmon;

	int shm, shm_cache;

	char  *cache_str;


	unsigned int tty_error_counter=0;
	long delay_var;
        int status;
        int pid;
        int i,i1;
	float bms_alert=bms_high_u;
	unsigned char num_maps;

        unsigned char v, map_status_old=255;
	unsigned int offset;
	int write_flag=0;

        char query[355];
        char dateStr[9], timeStr[9];
        float I_acc, I_mppt;



        MYSQL mysql;
        MYSQL_ROW row;
        MYSQL_RES *res;

	unsigned int num_fields;
	unsigned int num_rows;


        struct tm *newtime;

        struct tm tim;
        time_t ltime;
	
	struct sigaction my_signal;
 	bzero(&my_signal, sizeof(my_signal));
	
	unsigned long int HH,MM,LL;
	
	unsigned char eeprom[512];
	
        struct map_info
        {
          unsigned char _MODE;	
          unsigned char _Status_Char;	
          float _Uacc;		
          unsigned int _Iacc;	
          unsigned int _PLoad;
          unsigned char _F_Acc_Over;
          unsigned char _F_Net_Over;
          unsigned int _UNET;
          unsigned char _INET;
          unsigned int _PNET;
          unsigned char _TFNET;
          unsigned char _ThFMAP;
          unsigned int _UOUTmed;
          unsigned char _TFNET_Limit;
          unsigned int _UNET_Limit;
          unsigned char _RSErrSis;
          unsigned char _RSErrJobM;
          unsigned char _RSErrJob;
          unsigned char _RSWarning;
          signed char _Temp_Grad0;
          signed char _Temp_Grad2;
          float _INET_16_4;
          float _IAcc_med_A_u16;
          unsigned char _Temp_off;
          unsigned long int _E_NET;
          unsigned long int _E_ACC;
          unsigned long int _E_ACC_CHARGE;
          float _Uacc_optim;
	  float _I_acc_avg;
	  float _I_mppt_avg;
	  unsigned char _I2C_err;
	  char _Temp_Grad1;
	  unsigned char _Relay1;
	  unsigned char _Relay2;
	  unsigned char _Flag_ECO;
	  unsigned char _RSErrDop;
      };

      struct map_info map_data;
      
      struct bms_struct 
      {
      unsigned char cell_number;
      float v;
      float i;
      char t;
      
      };

      struct bms_struct *bms;
      
      
       	  setlogmask(LOG_UPTO(LOG_NOTICE));
          openlog(DAEMON_NAME, LOG_CONS | LOG_NDELAY | LOG_PERROR | LOG_PID, LOG_USER);

          syslog(LOG_NOTICE, "Entering Daemon");

          	  mysql.reconnect = true;
                  mysql_init (&mysql);
                  if (mysql_real_connect
                      (&mysql, "localhost", "monitor", "energy", "map", 0, NULL, 0) == NULL)
                    {
                      syslog (LOG_ERR, "Can't connect to MySql database");
                      return -1;
                    }
        pid = fork();
      if (pid == -1)
      {
          syslog(LOG_ERR,"Error: Start Daemon failed (%s)\n");

          return -1;
      }
      else if (!pid)
      {
          umask(0);
          setsid();
          chdir("/");

          close(STDIN_FILENO);
          close(STDOUT_FILENO);
          close(STDERR_FILENO);

          //------------------------------------��������� ���� ��� --------------------------------
            int fd = open_port (map);

          if (fd < 0)  
          {
          syslog(LOG_NOTICE, "Error open device");
          return 1;
        	}
          if (!isatty (fd))   
          {
          syslog(LOG_NOTICE, "Error 2");
           return 2;
	}	
          struct termios port_cfg;

          if (tcgetattr (fd, &port_cfg) < 0)
	{
	 syslog(LOG_NOTICE, "Error 3");
              return 3;
}

// --------------settings for different systems---------------------
          //    port_cfg.c_cflag |= (CLOCAL | CREAD);
          //    port_cfg.c_cflag &= ~PARENB;
          //    port_cfg.c_cflag &= ~CSTOPB;
          //    port_cfg.c_cflag &= ~CSIZE;
          //    port_cfg.c_cflag |= CS8;
          
          //    port_cfg.c_iflag |= (INPCK | ISTRIP);
          //    port_cfg.c_iflag &= ~(IXON | IXOFF | IXANY);
          //    port_cfg.c_oflag &= ~OPOST;
          //    port_cfg.c_cflag &= ~CRTSCTS;
            
            cfmakeraw (&port_cfg); // All other settings must be after this line
//            port_cfg.c_lflag &= ~(ICANON | ECHO | ECHOE | ISIG | ECHOKE | ECHOCTL);
              port_cfg.c_cc[VMIN] = 0;
              port_cfg.c_cc[VTIME] = 1;
            
            cfsetospeed (&port_cfg, BAUDRATE);

          cfsetispeed (&port_cfg, BAUDRATE);

          if (tcsetattr (fd, TCSAFLUSH, &port_cfg) < 0)
		{
          
          syslog(LOG_NOTICE, "Error entering Daemon. Port setattr");
          return 4;
          
          
          }
//----------------------- signals handler ------------------------------
	 my_signal.sa_sigaction=&signal_hdl;
	 my_signal.sa_flags=SA_SIGINFO;
	    if ((sigaction(SIGTERM, &my_signal, NULL) &&
	        sigaction(SIGINT, &my_signal, NULL) &&
	        sigaction(SIGQUIT, &my_signal, NULL) &&
		sigaction(SIGHUP, &my_signal, NULL))<0)
		
		{
		 syslog(LOG_ERR, "sigaction");
		 return 1;
		 }

//================ READ ALL MAP SETTINGS ======================

	if (read_eeprom(eeprom, fd, mysql)!=0) return -1;
	
//=========== if bms exists we create table bms in memory for transaction  & shared memory segmant for monitor==================	
	         
         if (eeprom[0x156]==3 || eeprom[0x156]==1) 
         {
          sprintf(query,"CREATE TABLE IF NOT EXISTS bms (`cell_number` tinyint(3) unsigned not null, `U` decimal(3,2) unsigned not null, `I` decimal(3,2) unsigned not null, `t` tinyint(3) not null) ENGINE=MEMORY DEFAULT CHARSET=utf8");
         
         if (mysql_query (&mysql, query))
        	    {
        	      syslog(LOG_ERR,"Error crete table `bms` in memory\n");
        	      return -1;
        	    }
	 
                }
	
	int shm_bms;
	shm_bms=shmget(SHARED_MEMORY_KEY_BMS, 1024, 0644 | IPC_CREAT);
	if (shm_bms==-1)
	    {
	    syslog(LOG_ERR,"Unable to create shared memory segment.");
	    return -1;
	    }



	bms = shmat(shm_bms,(void *)0,0);
	 if (bms == (void *)(-1)) 
		    {
	    syslog(LOG_ERR,"Unable get pointer to shared memory segment.");
	    return -1;
	    }

//--------- create table "eeprom_result----------------------------------

sprintf(query,"CREATE TABLE IF NOT EXISTS eeprom_result (`offset` tinyint(3) unsigned not null, `result` tinyint(3) unsigned not null) ENGINE=MEMORY DEFAULT CHARSET=utf8");
         
         if (mysql_query (&mysql, query))
        	    {
        	      syslog(LOG_ERR,"Error crete table `bms` in memory\n");
        	      return -1;
        	    }
	 
                
//---------------------create named pipe-----------------------------------------

    int fd_fifo;
     char char_fifo[4];
    unlink("/var/map/to_map");
	if ((mkfifo("/var/map/to_map",O_RDWR))==-1)
	    {
		syslog(LOG_ERR,"Error create named pipe in /var/map/");
		exit(-1);
	    }
	fd_fifo=open("/var/map/to_map",O_RDONLY | O_NONBLOCK);
	if (fd_fifo==-1)
	    {
		syslog(LOG_ERR,"Error open named pipe /var/map/to_map");
		exit(-1);
	    }

	if (fchmod(fd_fifo,666)==-1 || fchown(fd_fifo,1000,1000)==-1)
	    {
		syslog(LOG_ERR,"Error change attributes on pipe /var/map/to_map");
		exit(-1);
	    }
//-----------------Create shared memory segments------------

	shm=shmget(SHARED_MEMORY_KEY, 1024, 0644 | IPC_CREAT);
	if (shm==-1)
	    {
	    syslog(LOG_ERR,"Unable to create shared memory segment.");
	    return -1;
	    }



	batmon = shmat(shm,(void *)0,0);
	 if (batmon == (void *)(-1)) 
		    {
	    syslog(LOG_ERR,"Unable get pointer to shared memory segment.");
	    return -1;
	    }


	shm_cache=shmget(2015, 1024, 0644 | IPC_CREAT);
	if (shm_cache==-1)
	    {
	    syslog(LOG_ERR,"Unable to create shared memory segment for cache.");
	    return -1;
	    }



	cache_str = shmat(shm_cache,(char *)0,0);
	 if (cache_str == (char *)(-1)) 
		    {
	    syslog(LOG_ERR,"Unable get pointer to char to shared memory segment for cache.");
	    return -1;
	    }




//-------------------ALERT THRESHOLDS-------------------------------------------
        
        if (eeprom[0x180]==4) bms_alert=4.1;
	if (eeprom[0x180]==5) bms_alert=3.9;
	 batmon->battery_id=1;


	 map_data._Relay1=0;
	 map_data._Relay2=0;

//-------------------- main cycle -------------------------------

          do
            {

//----------------- checking wether data to MAP exist------------

   if (write_flag==0) { // - if the're no pending query
    
    if (read(fd_fifo,char_fifo,4)==4 && char_fifo[0]==0xFF) {

	offset=char_fifo[2]*256+char_fifo[1];

	write_flag=1;

	}	
    }

//----------------------trying to write. if fails, keep on for next iteration--------------------------------------

	if (write_flag==1) 
	    {
		if (offset<0x138 || (char_fifo[3]>=eeprom[offset+8] && char_fifo[3]<=eeprom[offset+16]))
		{
		if (offset==0 || (offset>=0x102 && offset<=0x1B7)) 
		    {
		    syslog(LOG_NOTICE,"Read from pipe - offset %4X value %2X",offset, char_fifo[3]);
		    Buffer[0]=3;
		    send_command(to_write,fd,0x0,0x0);
		    if (read_answer(fd)==0) 
			{
			Buffer[0]=char_fifo[3];
//----------------------------------------------------------------------------
			if (offset==0x156 && Buffer[0]>0) //exception for earlier MAP FW releases. Hope will be removed in future 
			    {
			    offset=0x21;
			    Buffer[0]+=5;
			    }
			if (offset==0x156 && Buffer[0]==0) //exception for earlier MAP FW releases. Hope will be removed in future 
			    {
			    offset=0x21;
			    Buffer[0]=2;
			    }
//---------------------------exception for Vdd-------------------------------------------------
			if (offset==0x1B5 && Buffer[0]==6) //exception for earlier MAP FW releases. Hope will be removed in future 
			    {
			    offset=0xFE;
			    Buffer[0]=(eeprom[0xFE]==0)?1:0;
			    }
//-------------------------------------------------------------------------------------

			send_command(to_write,fd,offset,0x0);

			    if (offset==0x21) offset=0x156; // restore value. exception. is to remove in future
			    if (offset==0xFE) offset=0x1B5; // restore value
			
			if (read_answer(fd)==0)
			    {
			    Buffer[0]=7;
			    send_command(to_write,fd,0x0,0x0);
			    if (read_answer(fd)==0)
				{
				syslog(LOG_NOTICE,"Written to eeprom: offset %4X value %2X",offset, char_fifo[3]);
				sprintf(query, "INSERT INTO eeprom_result VALUES('%d','%d')",offset, 0);
				if (mysql_query (&mysql, query))
        				{
        	    	    	     syslog(LOG_ERR,"Error adding data in MySQL -> eeprom write status. Table bms\n");
				        }
				    write_flag=0;

				}

			    }    

			}

		    } else {syslog(LOG_ERR,"Value to write in eeprom is not allowed. offset=%4X, value=%2X",offset, char_fifo[3]); write_flag=0;}
		}
		else {syslog(LOG_ERR,"Value to write in eeprom is not allowed. offset=%4X, value=%2X",offset, char_fifo[3]); write_flag=0;}

	    }

	

	send_command (to_read, fd, 0x585,1);
	
	 if (read_answer(fd) == 0) 
	    {
	    map_data._Flag_ECO=Buffer[1];

	    map_data._Relay1=Buffer[2];
	    map_data._Relay2=Buffer[2];

	    }
	     else map_data._Flag_ECO=255;
	    bzero(Buffer,sizeof(Buffer));


    	send_command (to_read, fd, 0x400, 0xFF);
//              tcdrain (fd);

        if (read_answer (fd) == 0)
        	{

        map_data._MODE = Buffer[0x400 - 0x3FF];

	num_maps=(eeprom[0x155]==255)?1:(eeprom[0x155]+1);




	map_data._UNET = Buffer[0x422 - 0x3FF];
        map_data._UNET += 100;


// change MAP mode to my extensions
	
	map_data._MODE=real_mode(map_data._MODE, eeprom[0x16B], map_data._Flag_ECO, eeprom[0x13C], eeprom[0x13B], map_data._UNET);


        map_data._Status_Char = Buffer[0x402 - 0x3FF];

        map_data._Uacc =
        	    (Buffer[0x405 - 0x3FF] * 256 + Buffer[0x406 - 0x3FF]);
        	  map_data._Uacc = map_data._Uacc / 10;
        	  map_data._Iacc = Buffer[0x408 - 0x3FF];
        	  map_data._Iacc *= 2;

        map_data._PLoad = Buffer[0x409 - 0x3FF];
        	  map_data._PLoad *= 100;

        map_data._F_Acc_Over = Buffer[0x41C - 0x3FF];

        map_data._F_Net_Over = Buffer[0x41D - 0x3FF];


        map_data._INET = Buffer[0x423 - 0x3FF];

        map_data._PNET = Buffer[0x424 - 0x3FF];
        	  map_data._PNET *= 100;

        map_data._TFNET = Buffer[0x425 - 0x3FF];
        	  //map_data._TFNET = 6250 / map_data._TFNET;

        map_data._ThFMAP = Buffer[0x426 - 0x3FF];
        	  //map_data._ThFMAP = 6250 / map_data._ThFMAP;

        map_data._UOUTmed = Buffer[0x427 - 0x3FF];
        	  if (map_data._UOUTmed > 0)
        	    map_data._UOUTmed += 100;

        map_data._TFNET_Limit = Buffer[0x428 - 0x3FF];
        	  //if (map_data._TFNET_Limit!=0) map_data._TFNET_Limit= 2500 / map_data._TFNET_Limit;

        map_data._UNET_Limit = Buffer[0x429 - 0x3FF];
        	  map_data._UNET_Limit += 100;

        map_data._RSErrSis = Buffer[0x42A - 0x3FF];
        	  map_data._RSErrJobM = Buffer[0x42B - 0x3FF];

        map_data._RSErrJob = Buffer[0x42C - 0x3FF];

        map_data._RSWarning = Buffer[0x2E];

        map_data._Temp_Grad0 = Buffer[0x2F] - 50;
	map_data._Temp_Grad1 = Buffer[0x30] - 50;

        map_data._Temp_Grad2 = Buffer[0x430 - 0x3FF] - 50;

        	  
        	    if (map_data._INET < 16)  map_data._INET_16_4 =(float)Buffer[0x32] / 16;
        	     else map_data._INET_16_4 =(float)Buffer[0x32] / 4;

        map_data._IAcc_med_A_u16 = (float)Buffer[0x34]*16+(float)Buffer[0x33] / 16;

        map_data._Temp_off = Buffer[0x43C - 0x3FF];
        map_data._E_NET =
	    (Buffer[0x50] * 65536 + Buffer[0x4F] * 256 + Buffer[0x4E]);
	  map_data._E_ACC =
	    (Buffer[0x53] * 65536 + Buffer[0x52] * 256 + Buffer[0x51]);
	  map_data._E_ACC_CHARGE =
	    (Buffer[0x56] * 65536 + Buffer[0x55] * 256 + Buffer[0x54]);
	 map_data._I2C_err = Buffer[0x45A-0x3FF];
	 map_data._RSErrDop= Buffer[0x447-0x3FF];
	 map_data._Relay1=0;
	 map_data._Relay2=0;


//----------------- adding BMS data --------------------------------

            if (eeprom[0x156]==3 || eeprom[0x156]==1) {
              i1=0; bzero(bms,32*sizeof(struct bms_struct));
              int limit=1<<(eeprom[0x06]+3); //calculate #of memory cells to read
	       sprintf(query,"TRUNCATE TABLE bms");
                  if (!mysql_query (&mysql, query))
		    {    	
                     for (i=0;i<limit;i+=2) {
	                bms[i1].cell_number=i1+1;
        	        bms[i1].v=((float)Buffer[0x81+i]+(float)Buffer[0x81+i+1]*256)/100;
        	        
            		bms[i1].i=(float)Buffer[0xE1+i1]*bms[i1].v/100;
            		if (Buffer[0xC1+i1]==255) bms[i1].t=127; else bms[i1].t=Buffer[0xC1+i1]-50; 

    			sprintf(query, "INSERT INTO bms VALUES('%d','%.2f','%.2f','%i')",bms[i1].cell_number,bms[i1].v,bms[i1].i,bms[i1].t);
			    if (mysql_query (&mysql, query))
        				{
        	    	    	     syslog(LOG_ERR,"Error adding data in MySQL. Table bms\n");
				        }
            		i1++;
            		}
            		
//-------------------------- checking alerts --------------------------------

			unsigned char flag=0;

		        for (i=0;i<i1;i++)
		         {
			    if ((bms[i].t>=bms_high_t && bms[i].t!=127) || (bms[i].t<=bms_low_t) || (bms[i].v<=bms_low_u) || (bms[i].v>=bms_alert && bms[i].v<9.9)) 
			    {flag=1; break;}
			 }
			    time (&ltime);
        		    newtime = localtime (&ltime);
        		    tim = *newtime;    
			if (flag==1)
			for (i=0;i<i1;i++)			    
			  {
			        
	        	  sprintf (query,"INSERT INTO bms_alert VALUES (NULL,'%d-%d-%d','%d:%d:%d','%d','%.2f','%.2f','%i')",
	        	          tim.tm_year + 1900, tim.tm_mon + 1, tim.tm_mday,
        			  tim.tm_hour, tim.tm_min, tim.tm_sec,
	        	          bms[i].cell_number,bms[i].v,bms[i].i,bms[i].t);
				if (mysql_query (&mysql, query))
        				{
        	    	    	     syslog(LOG_ERR,"Error adding data in MySQL. Table bms\n");
        	    	    	     break;
				        }
			    
			    }

                	    }
            	else 
            		{
        	      syslog(LOG_ERR,"Error truncate table bms in MySQL.\n");
            		}
	            
            
            
            }

//---------------------------Checking EEPROM change-------------------------

	if (Buffer[0x04]&5>0)
	  {
	  if (read_eeprom(eeprom,fd,mysql)==0)
	    {
	    Buffer[0]=3;
	    send_command(to_write,fd,0x0,0x0);
	    if (read_answer(fd)==0) 
	    {
	    Buffer[0]=0;
	    send_command(to_write,fd,0x403,0x0);
	    read_answer(fd);
	    }
	   }
	  }    
          
//------------------------Read average currents -----------------
          map_data._I_acc_avg=0;
          map_data._I_mppt_avg=0;
          I_mppt=0;


      if (eeprom[0x156]==2 || eeprom[0x156]==3) {
   
          
           send_command (to_read, fd, 0x432, 0x1);
//              tcdrain (fd);
              bzero(Buffer,10);
              if (read_answer(fd)!=0) I_acc=map_data._IAcc_med_A_u16; else
                 I_acc=(float)Buffer[0x2]*16+(float)Buffer[0x1] / 16;
                 map_data._I_acc_avg=(I_acc+map_data._IAcc_med_A_u16)/2;
	    
	    send_command (to_read, fd, 0x530, (eeprom[0x157]*2)-1);
//              tcdrain (fd);
              bzero(Buffer,70);

              if (read_answer(fd)!=0) I_mppt=0; else
               for (i1=1;i1<=eeprom[0x157];i1++)
		I_mppt+=(((float)Buffer[2*i1]*256+(float)Buffer[(2*i1)-1]) / 16);

		if (I_mppt<655) map_data._I_mppt_avg+=I_mppt; // value checking
	      I_mppt=0;
	   }
//----------------------------------------------------------------	
        
        	
	
	

         map_data._Uacc_optim=0;//reserved
        	  time (&ltime);
        	  newtime = localtime (&ltime);
        	  tim = *newtime;
//------------- updating data in memory segment

		sprintf (cache_str,
        		   "{\"time\":\"%02d:%02d:%02d\",\"_MODE\":\"%d\",\"_Status_Char\":\"%d\",\"_Uacc\":\"%.1f\",\"_Iacc\":\"%i\",\"_PLoad\":\"%i\",\"_F_Acc_Over\":\"%d\",\"_F_Net_Over\":\"%d\",\"_UNET\":\"%i\",\"_INET\":\"%d\",\"_PNET\":\"%i\",\"_TFNET\":\"%d\",\"_ThFMAP\":\"%d\",\"_UOUTmed\":\"%i\",\"_TFNET_Limit\":\"%d\",\"_UNET_Limit\":\"%i\",\"_RSErrSis\":\"%d\",\"_RSErrJobM\":\"%d\",\"_RSErrJob\":\"%d\",\"_RSWarning\":\"%d\",\"_Temp_Grad0\":\"%d\",\"_Temp_Grad2\":\"%d\",\"_INET_16_4\":\"%.1f\",\"_IAcc_med_A_u16\":\"%.1f\",\"Temp_off\":\"%d\",\"_E_NET\":\"%d\",\"_E_ACC\":\"%d\",\"_E_ACC_CHARGE\":\"%d\",\"_Uacc_optim\":\"%.1f\",\"_I_acc_avg\":\"%.1f\",\"_I_mppt_avg\":\"%.1f\",\"_I2C_Err\":\"%d\",\"_Temp_Grad1\":\"%d\",\"_Relay1\":\"%d\",\"_Relay2\":\"%d\",\"_Flag_ECO\":\"%d\",\"_RSErrDop\":\"%d\"}",
        		   tim.tm_hour, tim.tm_min, tim.tm_sec, map_data._MODE,
        		   map_data._Status_Char, map_data._Uacc, map_data._Iacc,
        		   map_data._PLoad, map_data._F_Acc_Over,
        		   map_data._F_Net_Over, map_data._UNET, map_data._INET,
        		   map_data._PNET, map_data._TFNET, map_data._ThFMAP,
        		   map_data._UOUTmed, map_data._TFNET_Limit,
        		   map_data._UNET_Limit, map_data._RSErrSis,
        		   map_data._RSErrJobM, map_data._RSErrJob,
        		   map_data._RSWarning, map_data._Temp_Grad0,
        		   map_data._Temp_Grad2, map_data._INET_16_4,
        		   map_data._IAcc_med_A_u16, map_data._Temp_off,
        		   map_data._E_NET, map_data._E_ACC, map_data._E_ACC_CHARGE,
        		   map_data._Uacc_optim, map_data._I_acc_avg, map_data._I_mppt_avg, map_data._I2C_err,
			   map_data._Temp_Grad1, map_data._Relay1, map_data._Relay2, map_data._Flag_ECO, map_data._RSErrDop);
 
//---------------adding DB record


        	  sprintf (query,
        		   "INSERT INTO data VALUES (NULL,'%d-%d-%d','%d:%d:%d','%d','%d','%.1f','%i','%i','%d','%d','%i','%d','%i','%d','%d','%i','%d','%i','%d','%d','%d','%d','%d','%d','%.1f','%.1f','%d','%d','%d','%d','%.1f','%.1f','%.1f','%d','%d','%d','%d','%d')",
        		   tim.tm_year + 1900, tim.tm_mon + 1, tim.tm_mday,
        		   tim.tm_hour, tim.tm_min, tim.tm_sec, map_data._MODE,
        		   map_data._Status_Char, map_data._Uacc, map_data._Iacc,
        		   map_data._PLoad, map_data._F_Acc_Over,
        		   map_data._F_Net_Over, map_data._UNET, map_data._INET,
        		   map_data._PNET, map_data._TFNET, map_data._ThFMAP,
        		   map_data._UOUTmed, map_data._TFNET_Limit,
        		   map_data._UNET_Limit, map_data._RSErrSis,
        		   map_data._RSErrJobM, map_data._RSErrJob,
        		   map_data._RSWarning, map_data._Temp_Grad0,
        		   map_data._Temp_Grad2, map_data._INET_16_4,
        		   map_data._IAcc_med_A_u16, map_data._Temp_off,
        		   map_data._E_NET, map_data._E_ACC, map_data._E_ACC_CHARGE,
        		   map_data._Uacc_optim, map_data._I_acc_avg, map_data._I_mppt_avg, map_data._I2C_err,
			   map_data._Temp_Grad1, map_data._Relay1, map_data._Relay2, map_data._Flag_ECO);

        	  if (mysql_query (&mysql, query))
        	    {
        	      syslog(LOG_ERR,"Error adding in MySQL\n"); return -1;
        	    }
//-------------checking for errors and updating error table

	    if ((map_data._RSErrSis>0 || map_data._RSErrJobM>0 || map_data._RSErrJob>0 || map_data._RSWarning>0 || map_data._I2C_err>0 || map_data._RSErrDop>0))
		    {
		  sprintf (query,
        		   "INSERT INTO map_errors VALUES (NULL,'%d-%d-%d','%d:%d:%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d')",
        		   tim.tm_year + 1900, tim.tm_mon + 1, tim.tm_mday,
        		   tim.tm_hour, tim.tm_min, tim.tm_sec,
			   map_data._RSErrSis,
        		   map_data._RSErrJobM, map_data._RSErrJob,
        		   map_data._RSWarning, map_data._I2C_err,
			   map_data._RSErrDop,map_data._F_Acc_Over, map_data._F_Net_Over,
			   map_data._TFNET_Limit, map_data._UNET_Limit);

        	  if (mysql_query (&mysql, query))
        	    {
        	      syslog(LOG_ERR,"Error adding in MySQL - map_errors table\n"); return -1;
        	    }

		    } //if errors
//---------------------------------------------------------
		 batmon->timestamp=ltime;
		 batmon->current=(map_data._MODE==4)?map_data._IAcc_med_A_u16:(0-map_data._IAcc_med_A_u16);
		 batmon->current*=num_maps;
		 batmon->tbat=map_data._Temp_Grad0;
		 batmon->Ubat=map_data._Uacc;
		 batmon->Imppt=map_data._I_mppt_avg;
		tty_error_counter=0;
		usleep(800000);
	        }
              else 
              {
//		time_stop();
//		syslog(LOG_ERR,"Cycle failed %d ms",time_stop());
        	tcflush (fd, TCIOFLUSH);
		if (tty_error_counter++==10) { syslog(LOG_ERR,"Constant tty read error (10 times)"); tty_error_counter=0;}
		}

//------------------- adjusting MAP time at 00:00:00 localtime----------------

	if (tim.tm_min==0 && tim.tm_hour==0 && tim.tm_sec<2)
           {
	    Buffer[0]=3;
	    send_command(to_write,fd,0x0,0x0);
	    if (read_answer(fd)==0) 
	    {
	    Buffer[0]=0;
	    send_command(to_write,fd,0x1B6,0x0);
	    read_answer(fd);
	    }
	    Buffer[0]=3;
	    send_command(to_write,fd,0x0,0x0);
	    if (read_answer(fd)==0) 
	    {
	    Buffer[0]=0;
	    send_command(to_write,fd,0x44B,0x0);
	    read_answer(fd);
	    }
          }	        
          
//------------------------------------------------------------          
          bzero (Buffer, size_of_buffer);
         
        

            }
          while (1);

          close (fd);
          mysql_close (&mysql);

	  closelog();
          return 0;
      }
      else
      {
     return 0;
      }
  } // That's all ;)

