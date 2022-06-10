if [%~1]==[] (
    echo Usage: %~0 path
    exit 1
)

set source=%~1
set target=%source:\=/%

scp %source% jsazenkoku@jsazenkoku.sakura.ne.jp:~/%target%
