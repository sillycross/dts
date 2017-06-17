#!/bin/bash

luanch_new_daemon()
{
	#要求一个参数，为1表示是root daemon
	if [ $1 -eq 1 ]; then
		rm -f ./gamedata/tmp/server/request_new_root_server
		local extr='&is_root=1'
	else
		rm -f ./gamedata/tmp/server/request_new_server
		local extr=''
	fi
	
	for ((;1;))
	do
		#大概是这样的…… 
		#-t 1 设置失败不重试 dns解析时限30秒，connect时限5秒
		#这样如果没成功发出http请求，wget会在timeout的25秒时限内退出并返回非0值
		#如果command.php执行正常，会一直不返回，这样wget就会被timeout杀掉返回124
		#如果command.php出bug了，wget会正常返回0
		#这样就可以根据返回值判定是哪种情况了……
		timeout 40 wget -q -t 1 --dns-timeout=30 --connect-timeout=5 ${sv}/command.php --post-data="conn_passwd=${pw}&command=start${extr}" -O /dev/null
		local retv=$?
		if [ $retv -eq 124 ]; then	#正常启动
			#echo 'seems like a success'
			break;
		fi
		if [ $retv -eq 0 ]; then	#command.php出bug了，不重试
			#echo 'command.php has something wrong..';
			break;
		fi
		#因为某些原因连接失败，重试
		#echo "failed for some reason, wget return value=${retv}, retrying..."
	done
}

#服务器地址
sv=`php -r 'error_reporting(0); include "./include/modules/core/sys/config/server.config.php"; echo $server_address;'`

#获取连接密码
pw=`php -r 'error_reporting(0); include "./include/modulemng/modulemng.config.php"; echo $___MOD_CONN_PASSWD;'`

for ((;1;))
do
	echo `date +%s` > './gamedata/tmp/server/scriptalive.txt'
	
	if [ -f ./gamedata/tmp/server/script_quit ]; then
		rm -f ./gamedata/tmp/server/script_quit
		echo 0 > './gamedata/tmp/server/scriptalive.txt'
		exit
	fi
	
	#获取模式
	md=`php -r 'error_reporting(0); include "./include/modulemng/modulemng.config.php"; echo $___MOD_SRV;'`

	if [ $md -eq 1 ]; then
		if [ -f ./gamedata/tmp/server/request_new_server ]; then
			luanch_new_daemon 0 &
		fi
		if [ -f ./gamedata/tmp/server/request_new_root_server ]; then
			luanch_new_daemon 1 &
		fi
	fi

	sleep 2
done
