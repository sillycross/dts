$md0 = 0
#这个东西需要自己改
$env:Path=$env:Path+";D:\phpStudy\php\php-7.0.12-nts"
#服务器地址
$sv=php -r "error_reporting(0); include './include/modules/core/sys/config/server.config.php'; echo `$server_address;"
#获取连接密码
$pw=php -r "error_reporting(0); include './include/modulemng/modulemng.config.php'; echo `$___MOD_CONN_PASSWD;"

#$event = Register-EngineEvent -SourceIdentifier job_message -Action {
#  Write-Output $event.MessageData;
#}

echo 'DTS Daemon system for WIN'
echo 'version 0.1 powered by POWERSHELL'
for(;1;)
{
	if(test-path .\gamedata\tmp\server\script_quit)
	{
		remove-item -force .\gamedata\tmp\server\script_quit
		echo 0 > .\gamedata\tmp\server\scriptalive.txt
		echo 'Exit request received. Daemon system will be shut down.'
		pause
		exit
	}
	else
	{
		$timestamp = Get-Date -date (Get-Date).ToUniversalTime() -uformat %s
		$timestamp = [int]$timestamp
		echo $timestamp | out-file -encoding ascii .\gamedata\tmp\server\scriptalive.txt
		#echo -n $timestamp > .\gamedata\tmp\server\scriptalive.txt
	}
	$md=php -r "error_reporting(0); include './include/modulemng/modulemng.config.php'; echo `$___MOD_SRV;"
	if($md -eq 1)
	{
		if($md0 -eq 0)
		{
			echo 'Daemon system is now ON.'
			$md0 = 1
		}
		if(test-path .\gamedata\tmp\server\request_new_server)
		{
			$output=$(Get-Date -Format 'HH:mm:ss')+' New server request received. Launching a new daemon...'
			echo $output
			#luanch_new_daemon 0 $sv $pw
			Start-Job -InitializationScript {import-module ".\acdts-daemonctl-lib.psm1"} -ScriptBlock {
				param($sv,$pw)
				luanch_new_daemon 0 $sv $pw
			} -ArgumentList $sv,$pw 
			remove-item -force .\gamedata\tmp\server\request_new_server
		}
		if(test-path .\gamedata\tmp\server\request_new_root_server)
		{
			$output=$(Get-Date -Format 'HH:mm:ss')+' New root-server request received. Launching a new root-daemon...'
			echo $output
			#luanch_new_daemon 1 $sv $pw
			Start-Job -InitializationScript {import-module ".\acdts-daemonctl-lib.psm1"} -ScriptBlock {
				param($sv,$pw)
				luanch_new_daemon 1 $sv $pw
			} -ArgumentList $sv,$pw 
			remove-item -force .\gamedata\tmp\server\request_new_root_server
		}
	}
	else
	{
		if($md0 -eq 1)
		{
			echo 'Daemon system is now OFF.'
			$md0 = 0
		}
	}
	Start-Sleep -Seconds 2
}
#luanch_new_daemon 1

pause