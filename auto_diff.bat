@echo off
setlocal EnableDelayedExpansion
set /p sha1=请输入较新的历史版本的commit_id，忽略为使用HEAD  
set /p sha2=请输入较旧的历史版本的commit_id  
if not defined sha1 set sha1=HEAD
git diff %sha2% %sha1% --name-only > auto_diff.tmp
for /f "delims=" %%i IN (auto_diff.tmp) DO (
  if %%~xi neq .gitignore (
  	call :parse_filename ..\auto_diff\.\%%i
    if exist %%~fsi (
      xcopy /s /y /i %%~fsi !return!
	  )
  )
)
del /q auto_diff.tmp
exit

:parse_filename
set return=%~dp1
goto :eof