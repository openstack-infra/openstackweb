** Scheduled tasks

make cache folder writable to all

sudo chmod 777 -R tmp/path/to/ss/cache/folder

add to _ss_environment.php file following global:

global $_FILE_TO_URL_MAPPING;
$_FILE_TO_URL_MAPPING['/path/to/project'] = 'http://localhost';


PullCLAFromGerritTask:

pulls gerrit endpoint https://gerrit-review.googlesource.com/Documentation/rest-api-groups.html#group-members
using icla group id, and update all members on Member table with gerrit id and icla signed

* php /path/to/project/sapphire/cli-script.php /PullCLAFromGerritTask

UpdateLastCommitedDateTask:

pulls from gerrit the last commit date for all users with icla signed and update on member table

* php /path/to/project/sapphire/cli-script.php /UpdateLastCommittedDateTask