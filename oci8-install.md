kasun@kasun-HP-Pavilion-g6-Notebook-PC:/etc/php/7.2/cli$ php --version
PHP 7.2.19-0ubuntu0.18.04.2 (cli) (built: Aug 12 2019 19:34:28) ( NTS )
Copyright (c) 1997-2018 The PHP Group
Zend Engine v3.2.0, Copyright (c) 1998-2018 Zend Technologies
    with Zend OPcache v7.2.19-0ubuntu0.18.04.2, Copyright (c) 1999-2018, by Zend Technologies
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/etc/php/7.2/cli$ sudo apt-get install php-pear php7.2-dev build-ssential unzip libaio1
Reading package lists... Done
Building dependency tree       
Reading state information... Done
E: Unable to locate package build-ssential
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/etc/php/7.2/cli$ sudo apt-get install php-pear php7.2-dev build-essential unzip libaio1
Reading package lists... Done
Building dependency tree       
Reading state information... Done
build-essential is already the newest version (12.4ubuntu1).
unzip is already the newest version (6.0-21ubuntu1).
libaio1 is already the newest version (0.3.110-5ubuntu0.1).
libaio1 set to manually installed.
The following additional packages will be installed:
  pkg-php-tools shtool
Suggested packages:
  dh-php dh-make
The following NEW packages will be installed:
  php-pear php7.2-dev pkg-php-tools shtool
0 upgraded, 4 newly installed, 0 to remove and 10 not upgraded.
Need to get 988 kB of archives.
After this operation, 8,399 kB of additional disk space will be used.
Do you want to continue? [Y/n] y
Get:1 http://lk.archive.ubuntu.com/ubuntu bionic-updates/main amd64 php-pear all 1:1.10.5+submodules+notgz-1ubuntu1.18.04.1 [284 kB]
Get:2 http://lk.archive.ubuntu.com/ubuntu bionic/main amd64 shtool all 2.0.8-9 [121 kB]
Get:3 http://lk.archive.ubuntu.com/ubuntu bionic-updates/main amd64 php7.2-dev amd64 7.2.19-0ubuntu0.18.04.2 [555 kB]
Get:4 http://lk.archive.ubuntu.com/ubuntu bionic/main amd64 pkg-php-tools all 1.35ubuntu1 [27.8 kB]
Fetched 988 kB in 2s (504 kB/s)         
Selecting previously unselected package php-pear.
(Reading database ... 221723 files and directories currently installed.)
Preparing to unpack .../php-pear_1%3a1.10.5+submodules+notgz-1ubuntu1.18.04.1_all.deb ...
Unpacking php-pear (1:1.10.5+submodules+notgz-1ubuntu1.18.04.1) ...
Selecting previously unselected package shtool.
Preparing to unpack .../shtool_2.0.8-9_all.deb ...
Unpacking shtool (2.0.8-9) ...
Selecting previously unselected package php7.2-dev.
Preparing to unpack .../php7.2-dev_7.2.19-0ubuntu0.18.04.2_amd64.deb ...
Unpacking php7.2-dev (7.2.19-0ubuntu0.18.04.2) ...
Selecting previously unselected package pkg-php-tools.
Preparing to unpack .../pkg-php-tools_1.35ubuntu1_all.deb ...
Unpacking pkg-php-tools (1.35ubuntu1) ...
Setting up shtool (2.0.8-9) ...
Setting up php-pear (1:1.10.5+submodules+notgz-1ubuntu1.18.04.1) ...
Processing triggers for man-db (2.8.3-2ubuntu0.1) ...
Setting up php7.2-dev (7.2.19-0ubuntu0.18.04.2) ...
update-alternatives: using /usr/bin/php-config7.2 to provide /usr/bin/php-config (php-config) in auto mode
update-alternatives: using /usr/bin/phpize7.2 to provide /usr/bin/phpize (phpize) in auto mode
Setting up pkg-php-tools (1.35ubuntu1) ...
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/etc/php/7.2/cli$ mkdir /opt/oracle
mkdir: cannot create directory ‘/opt/oracle’: Permission denied
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/etc/php/7.2/cli$ sudo mkdir /opt/oracle
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/etc/php/7.2/cli$ cd ..
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/etc/php/7.2$ cd ..
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/etc/php$ cd ..
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/etc$ cd ..
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/$ ls
bin   cdrom  etc   initrd.img      lib    lib64       media  opt   root  sbin  srv       sys  usr  vmlinuz
boot  dev    home  initrd.img.old  lib32  lost+found  mnt    proc  run   snap  swapfile  tmp  var  vmlinuz.old
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/$ cd home/
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/home$ ls
kasun  lost+found
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/home$ cd  kasun
kasun@kasun-HP-Pavilion-g6-Notebook-PC:~$ cd kasun
bash: cd: kasun: No such file or directory
kasun@kasun-HP-Pavilion-g6-Notebook-PC:~$ ls
'~'                  Desktop     Downloads          Music      Public        SITS   Templates
'Android Projects'   Documents   examples.desktop   Pictures   rtlwifi_new   snap   Videos
kasun@kasun-HP-Pavilion-g6-Notebook-PC:~$ cd do
bash: cd: do: No such file or directory
kasun@kasun-HP-Pavilion-g6-Notebook-PC:~$ cd Do
Documents/ Downloads/ 
kasun@kasun-HP-Pavilion-g6-Notebook-PC:~$ cd Downloads/
kasun@kasun-HP-Pavilion-g6-Notebook-PC:~/Downloads$ ls
 201212-ug-php-oracle-1884760.pdf                       'IVR work flow 300604.jpg'
'Call Connectivity Diagram.jpg'                         'IVR work flow 303050.jpg'
'Complaint Data to SITS.xlsx'                           'IVR work flow Merchant.jpg'
'Hierrachy for Branches & Depts.docx'                   'IVR work flow Premium.jpg'
'instantclient-basic-linux.x64-18.5.0.0.0dbru (1).zip'  'Unuhuma 2 - Tehan Perera (Aradhana nethagin gena).mp4'
 instantclient-basic-linux.x64-18.5.0.0.0dbru.zip        xhamster.com_10309842_north_east_indian_couple_480p.mp4
 instantclient-sdk-linux.x64-12.2.0.1.0.zip              xhamster.com_12165487_muslim_girl_homemade_nude_self_480p.mp4
 instantclient-sdk-linux.x64-18.5.0.0.0dbru.zip
kasun@kasun-HP-Pavilion-g6-Notebook-PC:~/Downloads$ sudo rm instantclient-basic-linux.x64-18.5.0.0.0dbru (1).zip
bash: syntax error near unexpected token `('
kasun@kasun-HP-Pavilion-g6-Notebook-PC:~/Downloads$ sudo mv instantclient-* /opt/oracle/
kasun@kasun-HP-Pavilion-g6-Notebook-PC:~/Downloads$ cd /opt/oracle/
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/opt/oracle$ unzip instantclient-basic-linux.x64-18.5.0.0.0dbru.zip 
Archive:  instantclient-basic-linux.x64-18.5.0.0.0dbru.zip
checkdir error:  cannot create instantclient_18_5
                 Permission denied
                 unable to process instantclient_18_5/adrci.
checkdir error:  cannot create instantclient_18_5
                 Permission denied
                 unable to process instantclient_18_5/BASIC_LICENSE.
checkdir error:  cannot create instantclient_18_5
                 Permission denied
                 unable to process instantclient_18_5/BASIC_README.
checkdir error:  cannot create instantclient_18_5
                 Permission denied
                 unable to process instantclient_18_5/genezi.
checkdir error:  cannot create instantclient_18_5
                 Permission denied
                 unable to process instantclient_18_5/libclntshcore.so.18.1.
checkdir error:  cannot create instantclient_18_5
                 Permission denied
                 unable to process instantclient_18_5/libclntsh.so.
checkdir error:  cannot create instantclient_18_5
                 Permission denied
                 unable to process instantclient_18_5/libclntsh.so.18.1.
checkdir error:  cannot create instantclient_18_5
                 Permission denied
                 unable to process instantclient_18_5/libipc1.so.
checkdir error:  cannot create instantclient_18_5
                 Permission denied
                 unable to process instantclient_18_5/libmql1.so.
checkdir error:  cannot create instantclient_18_5
                 Permission denied
                 unable to process instantclient_18_5/libnnz18.so.
checkdir error:  cannot create instantclient_18_5
                 Permission denied
                 unable to process instantclient_18_5/libocci.so.
checkdir error:  cannot create instantclient_18_5
                 Permission denied
                 unable to process instantclient_18_5/libocci.so.18.1.
checkdir error:  cannot create instantclient_18_5
                 Permission denied
                 unable to process instantclient_18_5/libociei.so.
checkdir error:  cannot create instantclient_18_5
                 Permission denied
                 unable to process instantclient_18_5/libocijdbc18.so.
checkdir error:  cannot create instantclient_18_5
                 Permission denied
                 unable to process instantclient_18_5/libons.so.
checkdir error:  cannot create instantclient_18_5
                 Permission denied
                 unable to process instantclient_18_5/liboramysql18.so.
checkdir error:  cannot create instantclient_18_5
                 Permission denied
                 unable to process instantclient_18_5/network/.
checkdir error:  cannot create instantclient_18_5
                 Permission denied
                 unable to process instantclient_18_5/ojdbc8.jar.
checkdir error:  cannot create instantclient_18_5
                 Permission denied
                 unable to process instantclient_18_5/ucp.jar.
checkdir error:  cannot create instantclient_18_5
                 Permission denied
                 unable to process instantclient_18_5/uidrvci.
checkdir error:  cannot create instantclient_18_5
                 Permission denied
                 unable to process instantclient_18_5/xstreams.jar.
checkdir error:  cannot create instantclient_18_5
                 Permission denied
                 unable to process instantclient_18_5/network/admin/.
checkdir error:  cannot create instantclient_18_5
                 Permission denied
                 unable to process instantclient_18_5/network/admin/README.
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/opt/oracle$ sudo unzip instantclient-basic-linux.x64-18.5.0.0.0dbru.zip 
Archive:  instantclient-basic-linux.x64-18.5.0.0.0dbru.zip
  inflating: instantclient_18_5/adrci  
  inflating: instantclient_18_5/BASIC_LICENSE  
  inflating: instantclient_18_5/BASIC_README  
  inflating: instantclient_18_5/genezi  
  inflating: instantclient_18_5/libclntshcore.so.18.1  
    linking: instantclient_18_5/libclntsh.so  -> libclntsh.so.18.1 
  inflating: instantclient_18_5/libclntsh.so.18.1  
  inflating: instantclient_18_5/libipc1.so  
  inflating: instantclient_18_5/libmql1.so  
  inflating: instantclient_18_5/libnnz18.so  
    linking: instantclient_18_5/libocci.so  -> libocci.so.18.1 
  inflating: instantclient_18_5/libocci.so.18.1  
  inflating: instantclient_18_5/libociei.so  
  inflating: instantclient_18_5/libocijdbc18.so  
  inflating: instantclient_18_5/libons.so  
  inflating: instantclient_18_5/liboramysql18.so  
   creating: instantclient_18_5/network/
  inflating: instantclient_18_5/ojdbc8.jar  
  inflating: instantclient_18_5/ucp.jar  
  inflating: instantclient_18_5/uidrvci  
  inflating: instantclient_18_5/xstreams.jar  
   creating: instantclient_18_5/network/admin/
  inflating: instantclient_18_5/network/admin/README  
finishing deferred symbolic links:
  instantclient_18_5/libclntsh.so -> libclntsh.so.18.1
  instantclient_18_5/libocci.so -> libocci.so.18.1
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/opt/oracle$ sudo unzip instantclient-sdk-linux.x64-18.5.0.0.0dbru.zip 
Archive:  instantclient-sdk-linux.x64-18.5.0.0.0dbru.zip
  inflating: instantclient_18_5/SDK_LICENSE  
   creating: instantclient_18_5/sdk/
   creating: instantclient_18_5/sdk/admin/
  inflating: instantclient_18_5/sdk/admin/oraaccess.xsd  
 extracting: instantclient_18_5/sdk/ottclasses.zip  
   creating: instantclient_18_5/sdk/include/
  inflating: instantclient_18_5/sdk/include/xmlotn.h  
  inflating: instantclient_18_5/sdk/include/ociap.h  
  inflating: instantclient_18_5/sdk/include/occi.h  
  inflating: instantclient_18_5/sdk/include/oci8dp.h  
  inflating: instantclient_18_5/sdk/include/oci.h  
  inflating: instantclient_18_5/sdk/include/xmlxptr.h  
  inflating: instantclient_18_5/sdk/include/ocidfn.h  
  inflating: instantclient_18_5/sdk/include/xmlev.h  
  inflating: instantclient_18_5/sdk/include/xmlerr.h  
  inflating: instantclient_18_5/sdk/include/xmldf.h  
  inflating: instantclient_18_5/sdk/include/oraxmlcg.h  
  inflating: instantclient_18_5/sdk/include/ociver.h  
  inflating: instantclient_18_5/sdk/include/odci.h  
  inflating: instantclient_18_5/sdk/include/orastruc.h  
  inflating: instantclient_18_5/sdk/include/xmlxvm.h  
  inflating: instantclient_18_5/sdk/include/xmlurl.h  
  inflating: instantclient_18_5/sdk/include/xmlsch.h  
  inflating: instantclient_18_5/sdk/include/oci1.h  
  inflating: instantclient_18_5/sdk/include/ort.h  
  inflating: instantclient_18_5/sdk/include/xmlctx.hpp  
  inflating: instantclient_18_5/sdk/include/oraxsd.h  
  inflating: instantclient_18_5/sdk/include/occiAQ.h  
  inflating: instantclient_18_5/sdk/include/oro.h  
  inflating: instantclient_18_5/sdk/include/orid.h  
  inflating: instantclient_18_5/sdk/include/occiCommon.h  
  inflating: instantclient_18_5/sdk/include/xmlsoapc.hpp  
  inflating: instantclient_18_5/sdk/include/xa.h  
  inflating: instantclient_18_5/sdk/include/oratypes.h  
  inflating: instantclient_18_5/sdk/include/xmlxsl.h  
  inflating: instantclient_18_5/sdk/include/ocikpr.h  
  inflating: instantclient_18_5/sdk/include/xml.h  
  inflating: instantclient_18_5/sdk/include/ocidef.h  
  inflating: instantclient_18_5/sdk/include/oraxml.hpp  
  inflating: instantclient_18_5/sdk/include/xmldav.h  
  inflating: instantclient_18_5/sdk/include/oraxsd.hpp  
  inflating: instantclient_18_5/sdk/include/oraxml.h  
  inflating: instantclient_18_5/sdk/include/occiObjects.h  
  inflating: instantclient_18_5/sdk/include/xml.hpp  
  inflating: instantclient_18_5/sdk/include/orl.h  
  inflating: instantclient_18_5/sdk/include/nzt.h  
  inflating: instantclient_18_5/sdk/include/ori.h  
  inflating: instantclient_18_5/sdk/include/xmlsoap.h  
  inflating: instantclient_18_5/sdk/include/ocixmldb.h  
  inflating: instantclient_18_5/sdk/include/occiControl.h  
  inflating: instantclient_18_5/sdk/include/occiData.h  
  inflating: instantclient_18_5/sdk/include/xmlsoap.hpp  
  inflating: instantclient_18_5/sdk/include/ociextp.h  
  inflating: instantclient_18_5/sdk/include/ocidem.h  
  inflating: instantclient_18_5/sdk/include/ociapr.h  
  inflating: instantclient_18_5/sdk/include/xmlotn.hpp  
  inflating: instantclient_18_5/sdk/include/ldap.h  
  inflating: instantclient_18_5/sdk/include/ocixstream.h  
  inflating: instantclient_18_5/sdk/include/xmlproc.h  
  inflating: instantclient_18_5/sdk/include/nzerror.h  
  inflating: instantclient_18_5/sdk/ott  
   creating: instantclient_18_5/sdk/demo/
  inflating: instantclient_18_5/sdk/demo/occidemod.sql  
  inflating: instantclient_18_5/sdk/demo/setuporamysql.sh  
  inflating: instantclient_18_5/sdk/demo/oraaccess.xml  
  inflating: instantclient_18_5/sdk/demo/cdemo81.c  
  inflating: instantclient_18_5/sdk/demo/occiobj.cpp  
  inflating: instantclient_18_5/sdk/demo/occidml.cpp  
  inflating: instantclient_18_5/sdk/demo/demo.mk  
  inflating: instantclient_18_5/sdk/demo/occiobj.typ  
  inflating: instantclient_18_5/sdk/demo/occidemo.sql  
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/opt/oracle$ ln -s /opt/oracle/instantclient_18_5/libclntsh.so.18.1 /opt/oracle/instantclient_18_5/libclntsh.so
ln: failed to create symbolic link '/opt/oracle/instantclient_18_5/libclntsh.so': File exists
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/opt/oracle$ ln -s /opt/oracle/instantclient_18_5/libocci.so.18.1 /opt/oracle/instantclient_18_5/libocci.so
ln: failed to create symbolic link '/opt/oracle/instantclient_18_5/libocci.so': File exists
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/opt/oracle$ echo /opt/oracle/instantclient_18_5 > /etc/ld.so.conf.d/oracle-instantclient
bash: /etc/ld.so.conf.d/oracle-instantclient: Permission denied
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/opt/oracle$ sudo echo /opt/oracle/instantclient_18_5 > /etc/ld.so.conf.d/oracle-instantclient
bash: /etc/ld.so.conf.d/oracle-instantclient: Permission denied
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/opt/oracle$ cd ..
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/opt$ chown -R root:www-data /opt/oracle
chown: changing ownership of '/opt/oracle/instantclient_18_5/libociei.so': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/BASIC_README': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/uidrvci': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/ucp.jar': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/libipc1.so': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/libons.so': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/network/admin/README': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/network/admin': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/network': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/SDK_README': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/libclntsh.so': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/genezi': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/liboramysql18.so': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/adrci': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/libclntshcore.so.18.1': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/libnnz18.so': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/BASIC_LICENSE': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/xstreams.jar': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/ojdbc8.jar': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/admin/oraaccess.xsd': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/admin': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/demo/occiobj.cpp': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/demo/oraaccess.xml': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/demo/setuporamysql.sh': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/demo/occidml.cpp': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/demo/occiobj.typ': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/demo/occidemod.sql': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/demo/cdemo81.c': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/demo/occidemo.sql': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/demo/demo.mk': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/demo': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/ott': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/xmlctx.hpp': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/ldap.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/xmlotn.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/ocixstream.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/occiCommon.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/occi.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/ociap.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/oro.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/xmlsoapc.hpp': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/occiControl.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/ocikpr.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/ociapr.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/xmlurl.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/oraxml.hpp': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/xmlsoap.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/nzerror.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/ort.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/xa.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/ociver.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/xmldav.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/xmlerr.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/occiAQ.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/oci1.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/ociextp.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/ocixmldb.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/xmlotn.hpp': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/ocidef.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/xmldf.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/xmlproc.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/xmlsch.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/xmlxsl.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/nzt.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/orastruc.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/oratypes.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/occiObjects.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/oraxml.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/xml.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/odci.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/orid.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/oci.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/ori.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/xmlxptr.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/xmlxvm.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/xml.hpp': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/oraxsd.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/ocidem.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/oraxsd.hpp': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/xmlsoap.hpp': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/orl.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/oci8dp.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/occiData.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/oraxmlcg.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/ocidfn.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include/xmlev.h': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/include': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk/ottclasses.zip': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/sdk': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/libocijdbc18.so': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/libmql1.so': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/libocci.so': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/SDK_LICENSE': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/libclntsh.so.18.1': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5/libocci.so.18.1': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient_18_5': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient-sdk-linux.x64-12.2.0.1.0.zip': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient-basic-linux.x64-12.2.0.1.0.zip': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient-sdk-linux.x64-18.5.0.0.0dbru.zip': Operation not permitted
chown: changing ownership of '/opt/oracle/instantclient-basic-linux.x64-18.5.0.0.0dbru.zip': Operation not permitted
chown: changing ownership of '/opt/oracle': Operation not permitted
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/opt$ sudo chown -R root:www-data /opt/oracle
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/opt$ sudo echo /opt/oracle/instantclient > /etc/ld.so.conf.d/oracle-instantclient
bash: /etc/ld.so.conf.d/oracle-instantclient: Permission denied
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/opt$ ldconfig
^[[A^[[A^[[A^Z
[1]+  Stopped                 ldconfig
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/opt$ ldconfig
/sbin/ldconfig.real: Can't create temporary cache file /etc/ld.so.cache~: Permission denied
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/opt$ sudo ldconfig
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/opt$ sudo echo /opt/oracle/instantclient > /etc/ld.so.conf.d/oracle-instantclient
bash: /etc/ld.so.conf.d/oracle-instantclient: Permission denied
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/opt$ sudo sh -c "echo /opt/oracle/instantclient > /etc/ld.so.conf.d/oracle-instantclient"
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/opt$ sudo ldconfig
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/opt$ php install oci8
Could not open input file: install
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/opt$ pecl install oci8
WARNING: channel "pecl.php.net" has updated its protocols, use "pecl channel-update pecl.php.net" to update
Cannot install, php_dir for channel "pecl.php.net" is not writeable by the current user
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/opt$ sudo pecl install oci8
WARNING: channel "pecl.php.net" has updated its protocols, use "pecl channel-update pecl.php.net" to update
downloading oci8-2.2.0.tgz ...
Starting to download oci8-2.2.0.tgz (196,449 bytes)
.........................................done: 196,449 bytes
11 source files, building
running: phpize
Configuring for:
PHP Api Version:         20170718
Zend Module Api No:      20170718
Zend Extension Api No:   320170718
Please provide the path to the ORACLE_HOME directory. Use 'instantclient,/path/to/instant/client/lib' if you're compiling with Oracle Instant Client [autodetect] : instantclient,/opt/oracle/instantclient
building in /tmp/pear/temp/pear-build-rootoLxpB5/oci8-2.2.0
running: /tmp/pear/temp/oci8/configure --with-php-config=/usr/bin/php-config --with-oci8=instantclient,/opt/oracle/instantclient
checking for grep that handles long lines and -e... /bin/grep
checking for egrep... /bin/grep -E
checking for a sed that does not truncate output... /bin/sed
checking for cc... cc
checking whether the C compiler works... yes
checking for C compiler default output file name... a.out
checking for suffix of executables...
checking whether we are cross compiling... no
checking for suffix of object files... o
checking whether we are using the GNU C compiler... yes
checking whether cc accepts -g... yes
checking for cc option to accept ISO C89... none needed
checking how to run the C preprocessor... cc -E
checking for icc... no
checking for suncc... no
checking whether cc understands -c and -o together... yes
checking for system library directory... lib
checking if compiler supports -R... no
checking if compiler supports -Wl,-rpath,... yes
checking build system type... x86_64-pc-linux-gnu
checking host system type... x86_64-pc-linux-gnu
checking target system type... x86_64-pc-linux-gnu
checking for PHP prefix... /usr
checking for PHP includes... -I/usr/include/php/20170718 -I/usr/include/php/20170718/main -I/usr/include/php/20170718/TSRM -I/usr/include/php/20170718/Zend -I/usr/include/php/20170718/ext -I/usr/include/php/20170718/ext/date/lib
checking for PHP extension directory... /usr/lib/php/20170718
checking for PHP installed headers prefix... /usr/include/php/20170718
checking if debug is enabled... no
checking if zts is enabled... no
checking for re2c... no
configure: WARNING: You will need re2c 0.13.4 or later if you want to regenerate PHP parsers.
checking for gawk... no
checking for nawk... nawk
checking if nawk is broken... no
checking for Oracle Database OCI8 support... yes, shared
checking PHP version... 7.2.19, ok
checking OCI8 DTrace support... no
checking size of long int... 8
checking checking if we're on a 64-bit platform... yes
checking Oracle Instant Client directory... /opt/oracle/instantclient
checking Oracle Instant Client SDK header directory... configure: error: Oracle Instant Client SDK header files not found
ERROR: `/tmp/pear/temp/oci8/configure --with-php-config=/usr/bin/php-config --with-oci8=instantclient,/opt/oracle/instantclient' failed
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/opt$ sudo pecl install oci8
WARNING: channel "pecl.php.net" has updated its protocols, use "pecl channel-update pecl.php.net" to update
downloading oci8-2.2.0.tgz ...
Starting to download oci8-2.2.0.tgz (196,449 bytes)
.........................................done: 196,449 bytes
11 source files, building
running: phpize
Configuring for:
PHP Api Version:         20170718
Zend Module Api No:      20170718
Zend Extension Api No:   320170718
Please provide the path to the ORACLE_HOME directory. Use 'instantclient,/path/to/instant/client/lib' if you're compiling with Oracle Instant Client [autodetect] : instantclient,/opt/oracle/instantclient_1^C      
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/opt$ sudo pecl install oci8
WARNING: channel "pecl.php.net" has updated its protocols, use "pecl channel-update pecl.php.net" to update
downloading oci8-2.2.0.tgz ...
Starting to download oci8-2.2.0.tgz (196,449 bytes)
.........................................done: 196,449 bytes
11 source files, building
running: phpize
Configuring for:
PHP Api Version:         20170718
Zend Module Api No:      20170718
Zend Extension Api No:   320170718
Please provide the path to the ORACLE_HOME directory. Use 'instantclient,/path/to/instant/client/lib' if you're compiling with Oracle Instant Client [autodetect] : instantclient,/opt/oracle/instantclient_18_5
building in /tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0
running: /tmp/pear/temp/oci8/configure --with-php-config=/usr/bin/php-config --with-oci8=instantclient,/opt/oracle/instantclient_18_5
checking for grep that handles long lines and -e... /bin/grep
checking for egrep... /bin/grep -E
checking for a sed that does not truncate output... /bin/sed
checking for cc... cc
checking whether the C compiler works... yes
checking for C compiler default output file name... a.out
checking for suffix of executables...
checking whether we are cross compiling... no
checking for suffix of object files... o
checking whether we are using the GNU C compiler... yes
checking whether cc accepts -g... yes
checking for cc option to accept ISO C89... none needed
checking how to run the C preprocessor... cc -E
checking for icc... no
checking for suncc... no
checking whether cc understands -c and -o together... yes
checking for system library directory... lib
checking if compiler supports -R... no
checking if compiler supports -Wl,-rpath,... yes
checking build system type... x86_64-pc-linux-gnu
checking host system type... x86_64-pc-linux-gnu
checking target system type... x86_64-pc-linux-gnu
checking for PHP prefix... /usr
checking for PHP includes... -I/usr/include/php/20170718 -I/usr/include/php/20170718/main -I/usr/include/php/20170718/TSRM -I/usr/include/php/20170718/Zend -I/usr/include/php/20170718/ext -I/usr/include/php/20170718/ext/date/lib
checking for PHP extension directory... /usr/lib/php/20170718
checking for PHP installed headers prefix... /usr/include/php/20170718
checking if debug is enabled... no
checking if zts is enabled... no
checking for re2c... no
configure: WARNING: You will need re2c 0.13.4 or later if you want to regenerate PHP parsers.
checking for gawk... no
checking for nawk... nawk
checking if nawk is broken... no
checking for Oracle Database OCI8 support... yes, shared
checking PHP version... 7.2.19, ok
checking OCI8 DTrace support... no
checking size of long int... 8
checking checking if we're on a 64-bit platform... yes
checking Oracle Instant Client directory... /opt/oracle/instantclient_18_5
checking Oracle Instant Client SDK header directory... /opt/oracle/instantclient_18_5/sdk/include
checking Oracle Instant Client library version compatibility... 18.1
checking how to print strings... printf
checking for a sed that does not truncate output... (cached) /bin/sed
checking for fgrep... /bin/grep -F
checking for ld used by cc... /usr/bin/ld
checking if the linker (/usr/bin/ld) is GNU ld... yes
checking for BSD- or MS-compatible name lister (nm)... /usr/bin/nm -B
checking the name lister (/usr/bin/nm -B) interface... BSD nm
checking whether ln -s works... yes
checking the maximum length of command line arguments... 1572864
checking how to convert x86_64-pc-linux-gnu file names to x86_64-pc-linux-gnu format... func_convert_file_noop
checking how to convert x86_64-pc-linux-gnu file names to toolchain format... func_convert_file_noop
checking for /usr/bin/ld option to reload object files... -r
checking for objdump... objdump
checking how to recognize dependent libraries... pass_all
checking for dlltool... no
checking how to associate runtime and link libraries... printf %s\n
checking for ar... ar
checking for archiver @FILE support... @
checking for strip... strip
checking for ranlib... ranlib
checking for gawk... (cached) nawk
checking command to parse /usr/bin/nm -B output from cc object... ok
checking for sysroot... no
checking for a working dd... /bin/dd
checking how to truncate binary pipes... /bin/dd bs=4096 count=1
checking for mt... mt
checking if mt is a manifest tool... no
checking for dlfcn.h... yes
checking for objdir... .libs
checking if cc supports -fno-rtti -fno-exceptions... no
checking for cc option to produce PIC... -fPIC -DPIC
checking if cc PIC flag -fPIC -DPIC works... yes
checking if cc static flag -static works... yes
checking if cc supports -c -o file.o... yes
checking if cc supports -c -o file.o... (cached) yes
checking whether the cc linker (/usr/bin/ld -m elf_x86_64) supports shared libraries... yes
checking whether -lc should be explicitly linked in... no
checking dynamic linker characteristics... GNU/Linux ld.so
checking how to hardcode library paths into programs... immediate
checking whether stripping libraries is possible... yes
checking if libtool supports shared libraries... yes
checking whether to build shared libraries... yes
checking whether to build static libraries... no
configure: creating ./config.status
config.status: creating config.h
config.status: executing libtool commands
running: make
/bin/bash /tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/libtool --mode=compile cc  -I. -I/tmp/pear/temp/oci8 -DPHP_ATOM_INC -I/tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/include -I/tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/main -I/tmp/pear/temp/oci8 -I/usr/include/php/20170718 -I/usr/include/php/20170718/main -I/usr/include/php/20170718/TSRM -I/usr/include/php/20170718/Zend -I/usr/include/php/20170718/ext -I/usr/include/php/20170718/ext/date/lib -I/opt/oracle/instantclient_18_5/sdk/include  -DHAVE_CONFIG_H  -g -O2   -c /tmp/pear/temp/oci8/oci8.c -o oci8.lo
libtool: compile:  cc -I. -I/tmp/pear/temp/oci8 -DPHP_ATOM_INC -I/tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/include -I/tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/main -I/tmp/pear/temp/oci8 -I/usr/include/php/20170718 -I/usr/include/php/20170718/main -I/usr/include/php/20170718/TSRM -I/usr/include/php/20170718/Zend -I/usr/include/php/20170718/ext -I/usr/include/php/20170718/ext/date/lib -I/opt/oracle/instantclient_18_5/sdk/include -DHAVE_CONFIG_H -g -O2 -c /tmp/pear/temp/oci8/oci8.c  -fPIC -DPIC -o .libs/oci8.o
/bin/bash /tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/libtool --mode=compile cc  -I. -I/tmp/pear/temp/oci8 -DPHP_ATOM_INC -I/tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/include -I/tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/main -I/tmp/pear/temp/oci8 -I/usr/include/php/20170718 -I/usr/include/php/20170718/main -I/usr/include/php/20170718/TSRM -I/usr/include/php/20170718/Zend -I/usr/include/php/20170718/ext -I/usr/include/php/20170718/ext/date/lib -I/opt/oracle/instantclient_18_5/sdk/include  -DHAVE_CONFIG_H  -g -O2   -c /tmp/pear/temp/oci8/oci8_lob.c -o oci8_lob.lo
libtool: compile:  cc -I. -I/tmp/pear/temp/oci8 -DPHP_ATOM_INC -I/tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/include -I/tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/main -I/tmp/pear/temp/oci8 -I/usr/include/php/20170718 -I/usr/include/php/20170718/main -I/usr/include/php/20170718/TSRM -I/usr/include/php/20170718/Zend -I/usr/include/php/20170718/ext -I/usr/include/php/20170718/ext/date/lib -I/opt/oracle/instantclient_18_5/sdk/include -DHAVE_CONFIG_H -g -O2 -c /tmp/pear/temp/oci8/oci8_lob.c  -fPIC -DPIC -o .libs/oci8_lob.o
/bin/bash /tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/libtool --mode=compile cc  -I. -I/tmp/pear/temp/oci8 -DPHP_ATOM_INC -I/tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/include -I/tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/main -I/tmp/pear/temp/oci8 -I/usr/include/php/20170718 -I/usr/include/php/20170718/main -I/usr/include/php/20170718/TSRM -I/usr/include/php/20170718/Zend -I/usr/include/php/20170718/ext -I/usr/include/php/20170718/ext/date/lib -I/opt/oracle/instantclient_18_5/sdk/include  -DHAVE_CONFIG_H  -g -O2   -c /tmp/pear/temp/oci8/oci8_statement.c -o oci8_statement.lo
libtool: compile:  cc -I. -I/tmp/pear/temp/oci8 -DPHP_ATOM_INC -I/tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/include -I/tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/main -I/tmp/pear/temp/oci8 -I/usr/include/php/20170718 -I/usr/include/php/20170718/main -I/usr/include/php/20170718/TSRM -I/usr/include/php/20170718/Zend -I/usr/include/php/20170718/ext -I/usr/include/php/20170718/ext/date/lib -I/opt/oracle/instantclient_18_5/sdk/include -DHAVE_CONFIG_H -g -O2 -c /tmp/pear/temp/oci8/oci8_statement.c  -fPIC -DPIC -o .libs/oci8_statement.o
/bin/bash /tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/libtool --mode=compile cc  -I. -I/tmp/pear/temp/oci8 -DPHP_ATOM_INC -I/tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/include -I/tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/main -I/tmp/pear/temp/oci8 -I/usr/include/php/20170718 -I/usr/include/php/20170718/main -I/usr/include/php/20170718/TSRM -I/usr/include/php/20170718/Zend -I/usr/include/php/20170718/ext -I/usr/include/php/20170718/ext/date/lib -I/opt/oracle/instantclient_18_5/sdk/include  -DHAVE_CONFIG_H  -g -O2   -c /tmp/pear/temp/oci8/oci8_collection.c -o oci8_collection.lo
libtool: compile:  cc -I. -I/tmp/pear/temp/oci8 -DPHP_ATOM_INC -I/tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/include -I/tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/main -I/tmp/pear/temp/oci8 -I/usr/include/php/20170718 -I/usr/include/php/20170718/main -I/usr/include/php/20170718/TSRM -I/usr/include/php/20170718/Zend -I/usr/include/php/20170718/ext -I/usr/include/php/20170718/ext/date/lib -I/opt/oracle/instantclient_18_5/sdk/include -DHAVE_CONFIG_H -g -O2 -c /tmp/pear/temp/oci8/oci8_collection.c  -fPIC -DPIC -o .libs/oci8_collection.o
/bin/bash /tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/libtool --mode=compile cc  -I. -I/tmp/pear/temp/oci8 -DPHP_ATOM_INC -I/tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/include -I/tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/main -I/tmp/pear/temp/oci8 -I/usr/include/php/20170718 -I/usr/include/php/20170718/main -I/usr/include/php/20170718/TSRM -I/usr/include/php/20170718/Zend -I/usr/include/php/20170718/ext -I/usr/include/php/20170718/ext/date/lib -I/opt/oracle/instantclient_18_5/sdk/include  -DHAVE_CONFIG_H  -g -O2   -c /tmp/pear/temp/oci8/oci8_interface.c -o oci8_interface.lo
libtool: compile:  cc -I. -I/tmp/pear/temp/oci8 -DPHP_ATOM_INC -I/tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/include -I/tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/main -I/tmp/pear/temp/oci8 -I/usr/include/php/20170718 -I/usr/include/php/20170718/main -I/usr/include/php/20170718/TSRM -I/usr/include/php/20170718/Zend -I/usr/include/php/20170718/ext -I/usr/include/php/20170718/ext/date/lib -I/opt/oracle/instantclient_18_5/sdk/include -DHAVE_CONFIG_H -g -O2 -c /tmp/pear/temp/oci8/oci8_interface.c  -fPIC -DPIC -o .libs/oci8_interface.o
/bin/bash /tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/libtool --mode=compile cc  -I. -I/tmp/pear/temp/oci8 -DPHP_ATOM_INC -I/tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/include -I/tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/main -I/tmp/pear/temp/oci8 -I/usr/include/php/20170718 -I/usr/include/php/20170718/main -I/usr/include/php/20170718/TSRM -I/usr/include/php/20170718/Zend -I/usr/include/php/20170718/ext -I/usr/include/php/20170718/ext/date/lib -I/opt/oracle/instantclient_18_5/sdk/include  -DHAVE_CONFIG_H  -g -O2   -c /tmp/pear/temp/oci8/oci8_failover.c -o oci8_failover.lo
libtool: compile:  cc -I. -I/tmp/pear/temp/oci8 -DPHP_ATOM_INC -I/tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/include -I/tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/main -I/tmp/pear/temp/oci8 -I/usr/include/php/20170718 -I/usr/include/php/20170718/main -I/usr/include/php/20170718/TSRM -I/usr/include/php/20170718/Zend -I/usr/include/php/20170718/ext -I/usr/include/php/20170718/ext/date/lib -I/opt/oracle/instantclient_18_5/sdk/include -DHAVE_CONFIG_H -g -O2 -c /tmp/pear/temp/oci8/oci8_failover.c  -fPIC -DPIC -o .libs/oci8_failover.o
/bin/bash /tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/libtool --mode=link cc -DPHP_ATOM_INC -I/tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/include -I/tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/main -I/tmp/pear/temp/oci8 -I/usr/include/php/20170718 -I/usr/include/php/20170718/main -I/usr/include/php/20170718/TSRM -I/usr/include/php/20170718/Zend -I/usr/include/php/20170718/ext -I/usr/include/php/20170718/ext/date/lib -I/opt/oracle/instantclient_18_5/sdk/include  -DHAVE_CONFIG_H  -g -O2    -o oci8.la -export-dynamic -avoid-version -prefer-pic -module -rpath /tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/modules  oci8.lo oci8_lob.lo oci8_statement.lo oci8_collection.lo oci8_interface.lo oci8_failover.lo -Wl,-rpath,/opt/oracle/instantclient_18_5 -L/opt/oracle/instantclient_18_5 -lclntsh
libtool: link: cc -shared  -fPIC -DPIC  .libs/oci8.o .libs/oci8_lob.o .libs/oci8_statement.o .libs/oci8_collection.o .libs/oci8_interface.o .libs/oci8_failover.o   -L/opt/oracle/instantclient_18_5 -lclntsh  -g -O2 -Wl,-rpath -Wl,/opt/oracle/instantclient_18_5   -Wl,-soname -Wl,oci8.so -o .libs/oci8.so
libtool: link: ( cd ".libs" && rm -f "oci8.la" && ln -s "../oci8.la" "oci8.la" )
/bin/bash /tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/libtool --mode=install cp ./oci8.la /tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/modules
libtool: install: cp ./.libs/oci8.so /tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/modules/oci8.so
libtool: install: cp ./.libs/oci8.lai /tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/modules/oci8.la
libtool: finish: PATH="/usr/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/snap/bin:/sbin" ldconfig -n /tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/modules
----------------------------------------------------------------------
Libraries have been installed in:
   /tmp/pear/temp/pear-build-roottkt1Iq/oci8-2.2.0/modules

If you ever happen to want to link against installed libraries
in a given directory, LIBDIR, you must either use libtool, and
specify the full pathname of the library, or use the '-LLIBDIR'
flag during linking and do at least one of the following:
   - add LIBDIR to the 'LD_LIBRARY_PATH' environment variable
     during execution
   - add LIBDIR to the 'LD_RUN_PATH' environment variable
     during linking
   - use the '-Wl,-rpath -Wl,LIBDIR' linker flag
   - have your system administrator add LIBDIR to '/etc/ld.so.conf'

See any operating system documentation about shared libraries for
more information, such as the ld(1) and ld.so(8) manual pages.
----------------------------------------------------------------------

Build complete.
Don't forget to run 'make test'.

running: make INSTALL_ROOT="/tmp/pear/temp/pear-build-roottkt1Iq/install-oci8-2.2.0" install
Installing shared extensions:     /tmp/pear/temp/pear-build-roottkt1Iq/install-oci8-2.2.0/usr/lib/php/20170718/
running: find "/tmp/pear/temp/pear-build-roottkt1Iq/install-oci8-2.2.0" | xargs ls -dils
3819724   4 drwxr-xr-x 3 root root   4096 අගෝ  24 11:17 /tmp/pear/temp/pear-build-roottkt1Iq/install-oci8-2.2.0
3820151   4 drwxr-xr-x 3 root root   4096 අගෝ  24 11:17 /tmp/pear/temp/pear-build-roottkt1Iq/install-oci8-2.2.0/usr
3820152   4 drwxr-xr-x 3 root root   4096 අගෝ  24 11:17 /tmp/pear/temp/pear-build-roottkt1Iq/install-oci8-2.2.0/usr/lib
3820153   4 drwxr-xr-x 3 root root   4096 අගෝ  24 11:17 /tmp/pear/temp/pear-build-roottkt1Iq/install-oci8-2.2.0/usr/lib/php
3820154   4 drwxr-xr-x 2 root root   4096 අගෝ  24 11:17 /tmp/pear/temp/pear-build-roottkt1Iq/install-oci8-2.2.0/usr/lib/php/20170718
3820150 748 -rwxr-xr-x 1 root root 763040 අගෝ  24 11:17 /tmp/pear/temp/pear-build-roottkt1Iq/install-oci8-2.2.0/usr/lib/php/20170718/oci8.so

Build process completed successfully
Installing '/usr/lib/php/20170718/oci8.so'
install ok: channel://pecl.php.net/oci8-2.2.0
configuration option "php_ini" is not set to php.ini location
You should add "extension=oci8.so" to php.ini
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/opt$ sudo echo "extension = oci8.so" >> /etc/php/7.2/fpm/php.ini
bash: /etc/php/7.2/fpm/php.ini: Permission denied
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/opt$ sudo sh -c "echo extension = oci8.so >> /etc/php/7.2/fpm/php.ini"
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/opt$ sudo sh -c "echo extension = oci8.so >> /etc/php/7.2/cli/php.ini"
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/opt$ nano /etc/php/7.2/cli/php.ini 
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/opt$ php -m | grep 'oci8'
PHP Warning:  PHP Startup: Unable to load dynamic library 'oci8.so' (tried: /usr/lib/php/20170718/oci8.so (libmql1.so: cannot open shared object file: No such file or directory), /usr/lib/php/20170718/oci8.so.so (/usr/lib/php/20170718/oci8.so.so: cannot open shared object file: No such file or directory)) in Unknown on line 0
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/opt$ cd /etc/php/mods-available
bash: cd: /etc/php/mods-available: No such file or directory
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/opt$ cd /etc/php/
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/etc/php$ ls
7.2
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/etc/php$ cd 7.2/
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/etc/php/7.2$ ls
cli  fpm  mods-available
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/etc/php/7.2$ cd /etc/php/7.2/mods-available
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/etc/php/7.2/mods-available$ vi oci.ini
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/etc/php/7.2/mods-available$ nano oci.ini
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/etc/php/7.2/mods-available$ sudo nano oci.ini
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/etc/php/7.2/mods-available$ cd /etc/php/7.2/fpm/conf.d/
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/etc/php/7.2/fpm/conf.d$ ln -s /etc/php/7.2/mods-available/oci.ini 20-oci.ini
ln: failed to create symbolic link '20-oci.ini': Permission denied
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/etc/php/7.2/fpm/conf.d$ sudo ln -s /etc/php/7.2/mods-available/oci.ini 20-oci.ini
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/etc/php/7.2/fpm/conf.d$ sudo nano /etc/environment 
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/etc/php/7.2/fpm/conf.d$ sudo php7.2-fpm restart
sudo: php7.2-fpm: command not found
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/etc/php/7.2/fpm/conf.d$ sudo php7.2-fpm restart
sudo: php7.2-fpm: command not found
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/etc/php/7.2/fpm/conf.d$ service php7.2-fpm restart
kasun@kasun-HP-Pavilion-g6-Notebook-PC:/etc/php/7.2/fpm/conf.d$ 
