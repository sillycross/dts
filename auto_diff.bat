@echo off
setlocal EnableDelayedExpansion
set /p sha1=请输入第一个历史版本的commit_id，忽略为使用head
set /p sha2=请输入第二个历史版本的commit_id，忽略为使用head
if not defined sha1 set sha1=head
if not defined sha2 set sha2=head
git diff %sha1% %sha2% --name-only > auto_diff.tmp
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