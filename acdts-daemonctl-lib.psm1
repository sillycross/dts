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
<#
function Invoke-TimeOutCommand()
{
	param(
		[int]$Timeout,
		[array]$ArgumentList,
		[ScriptBlock]$ScriptBlock
	)
	$job = Start-Job -ScriptBlock $ScriptBlock -ArgumentList $ArgumentList
	$job | Wait-Job -Timeout $Timeout
	if($job.State -ne 'Completed')
	{
		#Write-Warning 'timeout'
		$job | Stop-Job | Remove-Job
		return $null
	}
	else
	{
		return $job | Receive-Job
	}
}
#>