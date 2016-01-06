/*
 * mpptd.c
 *
 *  Release 2.03b
 *  Created on: April 6. 2015.
 *      Author: Osolemio
 */
 
#include <fcntl.h>
#include <unistd.h>
#include <termios.h>
#include <stdio.h>
#include <stdlib.h>
#include <syslog.h>
#include <time.h>
#include <string.h>
#include <./mysql/mysql.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <errno.h>
#include <signal.h>
#include <sys/ipc.h>
#include <sys/shm.h>


#define DAEMON_NAME "MPPT Reader daemon"
 
 #define PORT_MPPT "/dev/ttyUSB1"
 #define PORT_MPPT_ALONE "/dev/ttyUSB0"
 #define BAUDRATE B19200
 #define size_of_buffer 560
 #define to_read 0x72
 #define to_write 0x77
 #define map 1
 #define mppt 2
 #define mppt_a 3
 #define true 1
 #define false 0
 #define SHARED_MEMORY_KEY 1999
 


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
	unsigned char a1,h=0;
      
	 if (write(fd,&a,1)!=1)  return -1;
	 if (read(fd,&a1,1)!=1) return -1;
	 
	 
	 if (a!=a1) {do {read(fd,&a1,1);} while (a!=a1 && h++<20);} // tuning to stream, if there are omitten bytes 
	 if (a!=a1) {
	  return -1;}
    return 0;

 }

 void code_DB(int fd, unsigned char a)
 {
  if(a=='\n')
   {
    sum+=0xDB; if (put_char(fd, 0xDB)!=0) return; 
    sum+=0xDC; if (put_char(fd, 0xDC)!=0) return;}
    
    else if(a==0xDB)
    
      {
      sum+=0xDB; if (put_char(fd, 0xDB)!=0) return;
       sum+=0xDD; if (put_char(fd, 0xDD)!=0) return;}
       
     else 
        {
        sum+=a; put_char(fd, a);
        return;
        }
    }


 void send_command(unsigned char command, int fd, unsigned short addr, unsigned short page)
  {

  unsigned char a[4];
  short i;
    sum=0; a[0]=command; a[1]=page; a[2]=addr>>8; a[3]=addr&0xFF;
    for(i=0;i<4;i++) code_DB(fd, a[i]);
    
   if (command==to_write)
	   for (i=0;i<=page;i++) code_DB(fd, Buffer[i]);

   sum=0xff-sum; sum++; if (put_char(fd, sum)!=0) return;
   if (sum!='\n') put_char(fd, '\n');

  }

    void decode_answer(int i)
	{
	int c, c1;
	    for (c=1; c<=i; c++)
	    {
	     if (Buffer[c]==0xDB && Buffer[c+1]==0xDC)
	        {
	         Buffer[c]=0x0A;i--;
	          for (c1=c; c1<(i-1); c1++) Buffer[c1+1]=Buffer[c1+2];
	        } else 
	     if (Buffer[c]==0xDB && Buffer[c+1]==0xDD)
	        {
	         Buffer[c]=0xDB;i--;
	          for (c1=c; c1<(i-1); c1++) Buffer[c1+1]=Buffer[c1+2];
	        }
            }
	
	}

 int read_answer(int fd)
 {
 
   int i=0,c=0;
   unsigned char sum_r=0;
	 
	 if (read(fd, &Buffer, 1) !=1) return -1;

		 do 
		 {
		 if (write(fd,&Buffer[i],1) !=1 ) return -1;
		 if (read(fd, &Buffer[++i], 1) !=1) return -1;
	         } while ((Buffer[i]!=0x0A) && (i<256));
	         
	          if (write(fd,&Buffer[i],1)!=1) return -1;
		 
		 if (Buffer[0]==0x65) 
		 {
		  return -1;
		 }
		 
        if (Buffer[0]==0x6f) 
        {
        for (c=0;c<(i-1);c++) sum_r+=Buffer[c];sum_r=0xff-sum_r; sum_r++;
        if ((sum_r!=0x0A) && (sum_r!=Buffer[i-1])) {
        return -1;}
        if ((sum_r==0x0A) && (sum_r!=Buffer[i])) {
        return -1;}
        decode_answer(i);
        
        return 0;
        }

  return -1;
 }

 int open_port(char dev)
    {
	 int fd;
	 if (dev==mppt)
	 	 {
	 		 fd = open(PORT_MPPT, O_RDWR, O_NOCTTY);
	 		 if (fd<0)
			    {
	 		 	syslog(LOG_ERR,"Can't open serial port\n"); return -1;}
	 		  else
	 		 {
	 		 //fcntl(fd,F_SETFL,0);
	 		 return fd;
	 		 }
	 	 }
	
	if (dev==mppt_a)
	 	 {
	 		 fd = open(PORT_MPPT_ALONE, O_RDWR, O_NOCTTY);
	 		 if (fd<0)
			    {
	 		 	syslog(LOG_ERR,"Can't open serial port\n"); return -1;}
	 		  else
	 		 {
	 		 //fcntl(fd,F_SETFL,0);
	 		 return fd;
	 		 }
	 	 }
	    
	  return -1;
    }



void signal_hdl(int sig, siginfo_t *siginfo, void *context)
  {
      struct tm *newtime;

        struct tm tim;
        time_t ltime;



          switch(sig)
          {
                  case SIGTERM:
                  case SIGINT:
                  case SIGQUIT:
		  case SIGHUP:
			time (&ltime);
            		 newtime = localtime (&ltime);
        		tim =*newtime;

                          syslog(LOG_NOTICE,"stopped on signal at %s",asctime(newtime));
                          closelog();
                          exit(0);
                          break;
                  default:
                          syslog(LOG_NOTICE,"got signal %d. ignore...",sig);
                          break;
          }
  }

    int file_exists(char * filename)
    {
   return (access(filename, 0) == 0);
    }



//-----------------------------MAIN------------------------------------


  int main(int argc, char* argv[])
  {
   
    	struct mppt_bat
	{
	unsigned char battery_id;
	unsigned long int timestamp;
	float current1;
	float current2;
	float current_ch;
	char tbat;
	float Ubat;
	};
	
	struct mppt_bat *batmon;
	
	int shm, shm_cache;
	char *cache_str;


	long delay_var;
	int status;
	int pid;	  
	int i;
	char query[355];
   
   
   
   MYSQL mysql;
   MYSQL_ROW row;
   MYSQL_RES *res;
 
  
  struct termios port_cfg; 
   
  struct tm *newtime;
  
  struct tm tim;
  time_t ltime;

	struct sigaction my_signal;

	bzero(&my_signal, sizeof(my_signal)); 
   
   struct mppt_info {
	   float		Vc_PV;
	   float		Ic_PV;
	   float		V_Bat;
	   unsigned	int	P_PV;
	   unsigned	int	P_Out;
	   unsigned	int	P_Load;
	   unsigned	int	P_curr ;
	   float		I_Ch;
	   float		I_Out;
	   signed  char		Temp_Int;
	   signed  char		Temp_Bat;
           float	        Pwr_kW;
	   unsigned	char 	Sign_C0;
	   unsigned	char 	Sign_C1;
	   unsigned	int	I_EXTS0;
	   unsigned	int	I_EXTS1;
	   unsigned	int	P_EXTS0;
	   unsigned	int	P_EXTS1;
	   unsigned 	char 	Relay_C;
	   unsigned	char	RSErrSis;
	   char		 	Mode;
	   char 		Sign;
	   char			MPP;
	   unsigned 	int	windspeed;
   };

   struct mppt_info mppt_data;

		setlogmask(LOG_UPTO(LOG_NOTICE));
        openlog(DAEMON_NAME, LOG_CONS | LOG_NDELAY | LOG_PERROR | LOG_PID, LOG_USER);

        syslog(LOG_NOTICE, "Entering Daemon");



	mysql.reconnect=true;
	mysql_init(&mysql);  
	if (mysql_real_connect(&mysql,"localhost","monitor","energy","map",0,NULL,0)==NULL)
    {
    syslog(LOG_ERR, "Can't connect to MySql database"); return -1;
    }

	pid = fork();
      if (pid == -1)
      {
          syslog(LOG_INFO,"Error: Start Daemon failed (%s)\n");

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

  //-------------------------Open MPPT port-----------------------------------
     
     int fd_mppt = open_port(mppt);
        if (fd_mppt<0) 
		{
		if (file_exists("/var/map/.map"))
			return 1;
		fd_mppt = open_port(mppt_a);
		  if (fd_mppt<0)
			return 1;
		}
        if (!isatty(fd_mppt))
           return 2;

        if (tcgetattr(fd_mppt, &port_cfg)<0)
          return 3;

  //------ other different settings -----------------
  
    //     port_cfg.c_cflag |= (CLOCAL | CREAD);
    //     port_cfg.c_cflag &= ~PARENB;
    //     port_cfg.c_cflag &= ~CSTOPB;
    //     port_cfg.c_cflag &= ~CSIZE;
    //     port_cfg.c_cflag |= CS8;
    //     port_cfg.c_lflag &= ~(ICANON | ECHO | ECHOE | ISIG);
    //     port_cfg.c_iflag |= (INPCK | ISTRIP);
    //     port_cfg.c_iflag &= ~(IXON | IXOFF | IXANY);
    //     port_cfg.c_oflag &= ~OPOST;
    //     port_cfg.c_cflag &= ~CRTSCTS;
         cfmakeraw(&port_cfg);

//----------- all other port settings must be here <------------------         
         port_cfg.c_cc[VMIN] =0;
         port_cfg.c_cc[VTIME] =1;
         
         
         cfsetospeed(&port_cfg, BAUDRATE);
         cfsetispeed(&port_cfg, BAUDRATE);

          if (tcsetattr(fd_mppt, TCSAFLUSH, &port_cfg)<0)
         return 4;

//----------------------- signals handler ------------------------------
	 my_signal.sa_sigaction=&signal_hdl;
	 my_signal.sa_flags=SA_SIGINFO;
	    if (sigaction((SIGTERM | SIGHUP | SIGINT), &my_signal, NULL) < 0)
		{
		 syslog(LOG_ERR, "sigaction");
		 return 1;
		 }

//-----------------Create shared memory segments------------

	shm=shmget(SHARED_MEMORY_KEY, 1024, 0644 | IPC_CREAT);
	if (shm==-1)
	    {
	    syslog(LOG_ERR,"Unable to create shared memory segment.");
	    return -1;
	    }

// map segmant into daemon's address space-----------------


	batmon = shmat(shm,(void *)0,0);
	 if (batmon == (void *)(-1)) 
		    {
	    syslog(LOG_ERR,"Unable get pointer to shared memory segment.");
	    return -1;
	    }

	     batmon->battery_id=1; //default value


	shm_cache=shmget(2016, 1024, 0644 | IPC_CREAT);
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












//-------------------- main cycle -------------------------------
   do {
    
     send_command(to_read, fd_mppt, 0x4B2, 0xE1);
          if (read_answer(fd_mppt)==0)
           {
        	  	   mppt_data.Mode=Buffer[1];
        	  	   mppt_data.Sign=Buffer[0xB9];
        	  	   mppt_data.MPP=Buffer[0xB8];
        	  	   unsigned int CC=(Buffer[0x19]*256+Buffer[0x18]); mppt_data.Vc_PV=(float)CC/100;
        	  	   CC=(Buffer[0x1F]*256+Buffer[0x1E]); mppt_data.Ic_PV=(float)CC/100;
        	  	   CC=(Buffer[0x25]*256+Buffer[0x24]);mppt_data.V_Bat=(float)CC/100;
        	  	   mppt_data.P_PV=(Buffer[0x2B]*256+Buffer[0x2A]);
        	  	   mppt_data.P_Out=(Buffer[0x2D]*256+Buffer[0x2C]);
        	  	   mppt_data.P_Load=(Buffer[0x35]*256+Buffer[0x34]);
        	  	   mppt_data.P_curr=(Buffer[0x39]*256+Buffer[0x38]);
        	  	   CC=(Buffer[0x3B]*256+Buffer[0x3A]);
        	  	   mppt_data.I_Ch=(float)CC/100;
        	  	   CC=(Buffer[0x3F]*256+Buffer[0x3E]);mppt_data.I_Out=(float)CC/100;
        	  	   mppt_data.Temp_Int=Buffer[0x5C];
        	  	   mppt_data.Temp_Bat=Buffer[0x5D];
        	  	   mppt_data.Pwr_kW=(Buffer[0xE1]*256+Buffer[0xE0])+((float)Buffer[0xDF]*256+(float)Buffer[0xDE])/1000;
        	  	   
		bzero(Buffer,size_of_buffer);	

//----------------------READ BLOCK 2-------------------

	 send_command(to_read, fd_mppt, 0x5A6, 0x59);
          if (read_answer(fd_mppt)==0)
               {
			   mppt_data.Relay_C=Buffer[0x2E];
        	  	   mppt_data.RSErrSis=Buffer[1];
			   mppt_data.Sign_C0=Buffer[0x4F];
        	  	   mppt_data.Sign_C1=Buffer[0x50];
        	  	   mppt_data.I_EXTS0=(Buffer[0x52]*256+Buffer[0x51]);
        	  	   mppt_data.I_EXTS1=(Buffer[0x54]*256+Buffer[0x53]);
        	  	   mppt_data.P_EXTS0=(Buffer[0x56]*256+Buffer[0x55]);
        	  	   mppt_data.P_EXTS1=(Buffer[0x58]*256+Buffer[0x57]);
        	  	   mppt_data.windspeed=(Buffer[0x44]*256+Buffer[0x43]);

          
	    /* Get the time in seconds */
	     time(&ltime);
            /* Convert it to the structure tm */
             newtime = localtime(&ltime);
	     tim=*newtime;

    sprintf(cache_str, "{\"time\":\"%02d:%02d:%02d\",\"Vc_PV\":\"%.1f\",\"Ic_PV\":\"%.1f\",\"V_Bat\":\"%.1f\",\"P_PV\":\"%d\",\"P_Out\":\"%d\",\"P_Load\":\"%d\",\"P_curr\":\"%d\",\"I_Ch\":\"%.1f\",\"I_Out\":\"%.1f\",\"Temp_Int\":\"%d\",\"Temp_Bat\":\"%d\",\"Pwr_kW\":\"%.3f\",\"Sign_C0\":\"%d\",\"Sign_C1\":\"%d\",\"I_EXTS0\":\"%d\",\"I_EXTS1\":\"%d\",\"P_EXTS0\":\"%d\",\"P_EXTS1\":\"%d\",\"Relay_C\":\"%d\",\"RSErrSis\":\"%d\",\"Mode\":\"%c\",\"Sign\":\"%c\",\"MPP\":\"%c\",\"windspeed\":\"%d\"}",
	tim.tm_hour,tim.tm_min,
	tim.tm_sec,mppt_data.Vc_PV,mppt_data.Ic_PV,mppt_data.V_Bat,
	mppt_data.P_PV,mppt_data.P_Out,mppt_data.P_Load,mppt_data.P_curr,
	mppt_data.I_Ch,mppt_data.I_Out,mppt_data.Temp_Int,mppt_data.Temp_Bat,
	mppt_data.Pwr_kW,mppt_data.Sign_C0,mppt_data.Sign_C1,mppt_data.I_EXTS0,
	mppt_data.I_EXTS1,mppt_data.P_EXTS0,mppt_data.P_EXTS1,mppt_data.Relay_C,
	mppt_data.RSErrSis,mppt_data.Mode, mppt_data.Sign,mppt_data.MPP,mppt_data.windspeed);



     sprintf(query, "INSERT INTO mppt VALUES (NULL,'%d-%d-%d', '%d:%d:%d','%.1f','%.1f','%.1f','%d','%d','%d','%d','%.1f','%.1f','%d','%d','%.3f','%d','%d','%d','%d','%d','%d','%d','%d','%c','%c','%c','%d')",
	tim.tm_year+1900,tim.tm_mon+1,tim.tm_mday,tim.tm_hour,tim.tm_min,
	tim.tm_sec,mppt_data.Vc_PV,mppt_data.Ic_PV,mppt_data.V_Bat,
	mppt_data.P_PV,mppt_data.P_Out,mppt_data.P_Load,mppt_data.P_curr,
	mppt_data.I_Ch,mppt_data.I_Out,mppt_data.Temp_Int,mppt_data.Temp_Bat,
	mppt_data.Pwr_kW,mppt_data.Sign_C0,mppt_data.Sign_C1,mppt_data.I_EXTS0,
	mppt_data.I_EXTS1,mppt_data.P_EXTS0,mppt_data.P_EXTS1,mppt_data.Relay_C,
	mppt_data.RSErrSis,mppt_data.Mode, mppt_data.Sign,mppt_data.MPP,mppt_data.windspeed);
        
	if (mysql_query(&mysql,query)) { syslog(LOG_ERR,"\nError adding in MySQL\n"); return -1;}

//-----------Adding error field if there is one
    if (mppt_data.RSErrSis>0)
    {
     sprintf(query, "INSERT INTO mppt_errors VALUES (NULL,'%d-%d-%d','%d:%d:%d','%d')",
	tim.tm_year+1900,tim.tm_mon+1,tim.tm_mday,tim.tm_hour,tim.tm_min,
	tim.tm_sec,mppt_data.RSErrSis);
        
	if (mysql_query(&mysql,query)) { syslog(LOG_ERR,"\nError adding in MySQL - mppt_errors\n"); return -1;}
    }
	    
	     batmon->battery_id=1;
	     batmon->timestamp=ltime;
	     batmon->current1=(mppt_data.Sign_C0==1)?(0-mppt_data.I_EXTS0):mppt_data.I_EXTS0;
	     batmon->current2=(mppt_data.Sign_C1==1)?(0-mppt_data.I_EXTS1):mppt_data.I_EXTS1;
	     batmon->current_ch=mppt_data.I_Ch;
	     batmon->tbat=mppt_data.Temp_Bat;
	     batmon->Ubat=mppt_data.V_Bat;    
         } 
	   usleep(800000);
    		}
          else {
//          syslog(LOG_ERR,"Error reading from the device. Cycle was %d ms", time_stop());
         tcflush(fd_mppt,TCIFLUSH);}
	

	bzero(Buffer,size_of_buffer);          
	
//----------------- drop kWh counter everyday at 23:30 ----------------

	if (tim.tm_hour==23 && tim.tm_min==50) 
	{
	Buffer[0]=3;
	send_command(to_write,fd_mppt,0,0);
	if (read_answer(fd_mppt)==0) {
	bzero(Buffer,size_of_buffer);
	send_command(to_write,fd_mppt,0x583,3);
	read_answer(fd_mppt);
	}
	}

	
    } while (1);

 close(fd_mppt);
 mysql_close(&mysql);
closelog(); 
 return 0;
 
 }
      else
      {
     return 0;
      }
  }


