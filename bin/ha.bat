@echo OFF
set FOUND=
for %%e in (%PATHEXT%) do (
  for %%X in (php%%e) do (
    if not defined FOUND (
      set FOUND=%%~$PATH:X
    )
  )
)

if not defined FOUND (
  START /MAX cmd.exe /k "ECHO php.exe could not be found, please install php and add path for php.exe to PATH"
) else (
  START /MAX cmd.exe /k "php ha & ECHO\ & ECHO Full syntax for available commands: & ECHO php ha command [options] [arguments]"
)                

