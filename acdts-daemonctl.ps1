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
echo 'version 0.2 unpowered by POWERSHELL'
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
		$tp = -1
		if(test-path .\gamedata\tmp\server\request_new_server)
		{
			$tp = 0
			$output=$(Get-Date -Format 'HH:mm:ss')+' New server request received. Launching a new daemon...'
			echo $output
			remove-item -force .\gamedata\tmp\server\request_new_server
		}elseif(test-path .\gamedata\tmp\server\request_new_root_server)
		{
			$tp = 1
			$output=$(Get-Date -Format 'HH:mm:ss')+' New root-server request received. Launching a new root-daemon...'
			echo $output
			remove-item -force .\gamedata\tmp\server\request_new_root_server
		}
		if($tp -ne -1){			
			Start-Job -InitializationScript {
				function luanch_new_daemon($ir,$sv,$pw)
				#要求一个参数，为1表示是root daemon
				{
					#Register-EngineEvent -SourceIdentifier job_message
					if ($ir -eq 1) {
						$isroot=1
					} else {
						$isroot=0
					}
					$objUri = "$sv/command.php"
					$postParams = @{conn_passwd=$pw;command='start';is_root=$isroot}
					#如果command.php执行正常，会一直不返回，这样timeout返回$null
					#如果command.php出bug了，会正常返回一个结果对象
					
					for(;1;)
					{
						$cres = curl -uri $objUri -timeoutsec 10 -method POST -body $postParams
						#$cres=Invoke-TimeOutCommand -Timeout 20 -ArgumentList $objUri,$postParams -ScriptBlock {
							#param($objUri,$postParams)
							#curl -uri $objUri -timeoutsec 10 -method POST -body $postParams
						#}
						if($cres) #返回值不是$null，说明command.php出bug了，不重试
						{
							#$null = New-Event -SourceIdentifier job_message -MessageData 'command.php has something wrong..'
							return 'command.php has something wrong..'
							break;
						}
						else #返回值是$null，说明timeout，正常启动
						{
							#$null = New-Event -SourceIdentifier job_message -MessageData 'Seems like a success.'
							return 'Seems like a success.'
							break;
						}
					}
				}
			} -ScriptBlock {
				param($tp,$sv,$pw)
				luanch_new_daemon $tp $sv $pw
			} -ArgumentList $tp,$sv,$pw 
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