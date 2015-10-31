/*
 * mpptd.c
 *  for EP-Tracer. RS232
 *  Release 1.01b
 *  Created on: October 6. 2015.
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


#define DAEMON_NAME "MPPT-EP Reader daemon"
 
 #define PORT_MPPT "/dev/ttyUSB1"
 #define PORT_MPPT_ALONE "/dev/ttyUSB0"
 #define BAUDRATE B115200
 #define ID 0x0016
 #define read_command 0xA0    
 #define size_of_buffer 560
 #define map 1
 #define mppt 2
 #define mppt_a 3
 #define true 1
 #define false 0
 #define SHARED_MEMORY_KEY 1999
 


unsigned char Buffer[size_of_buffer];

unsigned char sum;

struct timeval tv1,tv2,dtv;

unsigned char Buffer_read[13]={0xEB,0x90,0xEB,0x90,0xEB,0x90,0x00,0x00,0xA0,0x00,0x00,0x00,0x7F};

unsigned char crctbl_l[256]={
0x00,0x21,0x42,0x63,0x84,0xa5,0xc6,0xe7,
0x08,0x29,0x4a,0x6b,0x8c,0xad,0xce,0xef,
0x31,0x10,0x73,0x52,0xb5,0x94,0xf7,0xd6,
0x39,0x18,0x7b,0x5a,0xbd,0x9c,0xff,0xde,
0x62,0x43,0x20,0x01,0xe6,0xc7,0xa4,0x85,
0x6a,0x4b,0x28,0x09,0xee,0xcf,0xac,0x8d,
0x53,0x72,0x11,0x30,0xd7,0xf6,0x95,0xb4,
0x5b,0x7a,0x19,0x38,0xdf,0xfe,0x9d,0xbc,
0xc4,0xe5,0x86,0xa7,0x40,0x61,0x02,0x23,
0xcc,0xed,0x8e,0xaf,0x48,0x69,0x0a,0x2b,
0xf5,0xd4,0xb7,0x96,0x71,0x50,0x33,0x12,
0xfd,0xdc,0xbf,0x9e,0x79,0x58,0x3b,0x1a,
0xa6,0x87,0xe4,0xc5,0x22,0x03,0x60,0x41,
0xae,0x8f,0xec,0xcd,0x2a,0x0b,0x68,0x49,
0x97,0xb6,0xd5,0xf4,0x13,0x32,0x51,0x70,
0x9f,0xbe,0xdd,0xfc,0x1b,0x3a,0x59,0x78,
0x88,0xa9,0xca,0xeb,0x0c,0x2d,0x4e,0x6f,
0x80,0xa1,0xc2,0xe3,0x04,0x25,0x46,0x67,
0xb9,0x98,0xfb,0xda,0x3d,0x1c,0x7f,0x5e,
0xb1,0x90,0xf3,0xd2,0x35,0x14,0x77,0x56,
0xea,0xcb,0xa8,0x89,0x6e,0x4f,0x2c,0x0d,
0xe2,0xc3,0xa0,0x81,0x66,0x47,0x24,0x05,
0xdb,0xfa,0x99,0xb8,0x5f,0x7e,0x1d,0x3c,
0xd3,0xf2,0x91,0xb0,0x57,0x76,0x15,0x34,
0x4c,0x6d,0x0e,0x2f,0xc8,0xe9,0x8a,0xab,
0x44,0x65,0x06,0x27,0xc0,0xe1,0x82,0xa3,
0x7d,0x5c,0x3f,0x1e,0xf9,0xd8,0xbb,0x9a,
0x75,0x54,0x37,0x16,0xf1,0xd0,0xb3,0x92,
0x2e,0x0f,0x6c,0x4d,0xaa,0x8b,0xe8,0xc9,
0x26,0x07,0x64,0x45,0xa2,0x83,0xe0,0xc1,
0x1f,0x3e,0x5d,0x7c,0x9b,0xba,0xd9,0xf8,
0x17,0x36,0x55,0x74,0x93,0xb2,0xd1,0xf0};

unsigned char crctbl_h[256]={
0x00,0x10,0x20,0x30,0x40,0x50,0x60,0x70,
0x81,0x91,0xa1,0xb1,0xc1,0xd1,0xe1,0xf1,
0x12,0x02,0x32,0x22,0x52,0x42,0x72,0x62,
0x93,0x83,0xb3,0xa3,0xd3,0xc3,0xf3,0xe3,
0x24,0x34,0x04,0x14,0x64,0x74,0x44,0x54,
0xa5,0xb5,0x85,0x95,0xe5,0xf5,0xc5,0xd5,
0x36,0x26,0x16,0x06,0x76,0x66,0x56,0x46,
0xb7,0xa7,0x97,0x87,0xf7,0xe7,0xd7,0xc7,
0x48,0x58,0x68,0x78,0x08,0x18,0x28,0x38,
0xc9,0xd9,0xe9,0xf9,0x89,0x99,0xa9,0xb9,
0x5a,0x4a,0x7a,0x6a,0x1a,0x0a,0x3a,0x2a,
0xdb,0xcb,0xfb,0xeb,0x9b,0x8b,0xbb,0xab,
0x6c,0x7c,0x4c,0x5c,0x2c,0x3c,0x0c,0x1c,
0xed,0xfd,0xcd,0xdd,0xad,0xbd,0x8d,0x9d,
0x7e,0x6e,0x5e,0x4e,0x3e,0x2e,0x1e,0x0e,
0xff,0xef,0xdf,0xcf,0xbf,0xaf,0x9f,0x8f,
0x91,0x81,0xb1,0xa1,0xd1,0xc1,0xf1,0xe1,
0x10,0x00,0x30,0x20,0x50,0x40,0x70,0x60,
0x83,0x93,0xa3,0xb3,0xc3,0xd3,0xe3,0xf3,
0x02,0x12,0x22,0x32,0x42,0x52,0x62,0x72,
0xb5,0xa5,0x95,0x85,0xf5,0xe5,0xd5,0xc5,
0x34,0x24,0x14,0x04,0x74,0x64,0x54,0x44,
0xa7,0xb7,0x87,0x97,0xe7,0xf7,0xc7,0xd7,
0x26,0x36,0x06,0x16,0x66,0x76,0x46,0x56,
0xd9,0xc9,0xf9,0xe9,0x99,0x89,0xb9,0xa9,
0x58,0x48,0x78,0x68,0x18,0x08,0x38,0x28,
0xcb,0xdb,0xeb,0xfb,0x8b,0x9b,0xab,0xbb,
0x4a,0x5a,0x6a,0x7a,0x0a,0x1a,0x2a,0x3a,
0xfd,0xed,0xdd,0xcd,0xbd,0xad,0x9d,0x8d,
0x7c,0x6c,0x5c,0x4c,0x3c,0x2c,0x1c,0x0c,
0xef,0xff,0xcf,0xdf,0xaf,0xbf,0x8f,0x9f,
0x6e,0x7e,0x4e,0x5e,0x2e,0x3e,0x0e,0x1e};

unsigned int CRC(unsigned char *buff, unsigned char  crc_datanumber)
{
    unsigned int accum;
    unsigned char i,cl,ch,j;
    ch = 0;
    cl = 0;
    accum = 0;
    for(i=0;i<crc_datanumber;i++)
    {
        j = (*buff++)^ch;
        ch = crctbl_h[j]^cl; 
        cl = crctbl_l[j];

    }
    accum = ch;
    accum <<= 8;
    accum |= cl;
    return(accum);
}



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
	 if (write(fd,&a,1)!=1) return -1;
	 
    return 0;

 }


 int send_read(int fd)
  {
   int i;
    unsigned int cs;
    Buffer_read[6]=(unsigned char)ID;
    Buffer_read[7]=(unsigned char)(ID>>8);;

    cs=CRC(&Buffer_read[6],4);
    Buffer_read[10]=(unsigned char)(cs>>8);
    Buffer_read[11]=(unsigned char)(cs);
    i=write(fd,Buffer_read,13);
    if (i<13) return -1; return 1;
    }

  char validate_answer(void)
	{
	    int i;
	    unsigned char check_buffer[10]={0xEB,0x90,0xEB,0x90,0xEB,0x90,0,0,0xA0,0x2E};
	    check_buffer[6]=(unsigned char)ID;
	    check_buffer[7]=(unsigned char)(ID>>8);
	    for (i=0;i++;i<10)
		if (Buffer[i]!=check_buffer[i]) return -1;
	    return 1;
    }

 int open_port(char dev)
    {
	 int fd;
	 if (dev==mppt)
	 	 {
	 		 fd = open(PORT_MPPT, O_RDWR | O_NOCTTY);
	 		 if (fd<0)
			    {
	 		 	syslog(LOG_ERR,"Can't open serial port\n"); return -1;}
	 		  else
	 		 {
	 		 fcntl(fd,F_SETFL,0);
	 		 return fd;
	 		 }
	 	 }
	
	if (dev==mppt_a)
	 	 {
	 		 fd = open(PORT_MPPT_ALONE, O_RDWR | O_NOCTTY);
	 		 if (fd<0)
			    {
	 		 	syslog(LOG_ERR,"Can't open serial port\n"); return -1;}
	 		  else
	 		 {
	 		 fcntl(fd,F_SETFL,0);
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
	
	int shm;



	long delay_var;
	int status;
	int pid;	  
	int i,j;
	unsigned int cs;
	char query[355];
	char mode[4]={'F','B','E','-'};
   
   
   
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

           cfmakeraw(&port_cfg);

         cfsetospeed(&port_cfg, BAUDRATE);
         cfsetispeed(&port_cfg, BAUDRATE);

//----------- all other port settings must be here <------------------         
         port_cfg.c_cc[VMIN] =0;
         port_cfg.c_cc[VTIME] =10;
    
         
         
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

//-----------------Create shared memory segment------------

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

//-------------------- main cycle -------------------------------
   
	do {
   
	bzero(Buffer,size_of_buffer);
	do { tcflush(fd_mppt,TCIFLUSH);} while (send_read(fd_mppt)!=1);
         tcdrain(fd_mppt);usleep(20000);
	j=read(fd_mppt,Buffer,size_of_buffer);
		if (j>50 && validate_answer()) {
	    if ((Buffer[j-2]+Buffer[j-3]*256)==CRC(&Buffer[6],j-9))
		{
	    		mppt_data.Vc_PV=(float)(Buffer[18]+Buffer[19]*256)/100;
			mppt_data.V_Bat=(float)(Buffer[10]+Buffer[11]*256)/100;
			mppt_data.P_PV=(float)(Buffer[50]+Buffer[51]*256);
			mppt_data.Ic_PV=(float)(mppt_data.Vc_PV==0)?0:mppt_data.P_PV/mppt_data.Vc_PV;
			mppt_data.P_Out=(float)(Buffer[50]+Buffer[51]*256);
			mppt_data.P_Load=(float)(Buffer[50]+Buffer[51]*256);
			mppt_data.P_curr=(float)(Buffer[50]+Buffer[51]*256);
			mppt_data.I_Ch=(float)((float)(Buffer[12]+Buffer[13]*256)/100+(float)(Buffer[14]+Buffer[15]*256)/100+(float)(Buffer[16]+Buffer[17]*256)/100);
			mppt_data.I_Out=mppt_data.I_Ch;
			mppt_data.Temp_Int=(float)(Buffer[24]+Buffer[25]*256)/100-40;
			mppt_data.Temp_Bat=(float)(Buffer[26]+Buffer[27]*256)/100-40;
			mppt_data.Pwr_kW=(float)((long)Buffer[52]+(long)Buffer[53]*0xFF+(long)Buffer[54]*0xFFFF+(long)Buffer[55]*0xFFFFFF)/100000;
			mppt_data.Sign_C0=0;
			mppt_data.Sign_C1=0;
			mppt_data.I_EXTS0=0;
			mppt_data.I_EXTS1=0;
			mppt_data.P_EXTS0=0;
			mppt_data.P_EXTS1=0;
			mppt_data.Relay_C=0;
			mppt_data.RSErrSis=0;
			mppt_data.Mode=mode[Buffer[30]];
			mppt_data.Sign='-';
			mppt_data.MPP='-';
			mppt_data.windspeed=65535;

	    /* Get the time in seconds */
	     time(&ltime);
            /* Convert it to the structure tm */
             newtime = localtime(&ltime);
	     tim=*newtime;
     sprintf(query, "INSERT INTO mppt VALUES (NULL,'%d-%d-%d', '%d:%d:%d','%.1f','%.1f','%.1f','%d','%d','%d','%d','%.1f','%.1f','%d','%d','%.3f','%d','%d','%d','%d','%d','%d','%d','%d','%c','%c','%c','%d')",
	tim.tm_year+1900,tim.tm_mon+1,tim.tm_mday,tim.tm_hour,tim.tm_min,
	tim.tm_sec,mppt_data.Vc_PV,mppt_data.Ic_PV,mppt_data.V_Bat,
	mppt_data.P_PV,mppt_data.P_Out,mppt_data.P_Load,mppt_data.P_curr,
	mppt_data.I_Ch,mppt_data.I_Out,mppt_data.Temp_Int,mppt_data.Temp_Bat,
	mppt_data.Pwr_kW,mppt_data.Sign_C0,mppt_data.Sign_C1,mppt_data.I_EXTS0,
	mppt_data.I_EXTS1,mppt_data.P_EXTS0,mppt_data.P_EXTS1,mppt_data.Relay_C,
	mppt_data.RSErrSis,mppt_data.Mode, mppt_data.Sign,mppt_data.MPP,mppt_data.windspeed);

	
	if (mysql_query(&mysql,query)) { syslog(LOG_ERR,"\nError adding in MySQL\n"); return -1;}
	
//	syslog(LOG_ERR,"%s",query);    
	     time(&ltime);
	     batmon->battery_id=1;
	     batmon->timestamp=ltime;
	     batmon->current1=(mppt_data.Sign_C0==1)?(0-mppt_data.I_EXTS0):mppt_data.I_EXTS0;
	     batmon->current2=(mppt_data.Sign_C1==1)?(0-mppt_data.I_EXTS1):mppt_data.I_EXTS1;
	     batmon->current_ch=mppt_data.I_Ch;
	     batmon->tbat=mppt_data.Temp_Bat;
	     batmon->Ubat=mppt_data.V_Bat;  
 
          

	   usleep(900000);

	} //if j>0
       } else // if CRC
	tcflush(fd_mppt,TCIFLUSH);
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


