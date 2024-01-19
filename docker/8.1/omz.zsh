alias _pnpm="pnpm update --latest"
alias _guestLSQL="mysql -h mysql -P 3306 -u tjaepxpl_event -p -D tjaepxpl_event  < installer/tjaepxpl_event.sql"
alias _guestSQL="mysql -h guests-db.c4bbjz3osuet.us-east-1.rds.amazonaws.com -P 3306 -u admin -p -D tjaepxpl_event  < installer/tjaepxpl_event.sql"
# alias composer="php /usr/local/bin/composer.phar"
# alias composer="php /usr/local/bin/composer.phar"
alias phpunit="./vendor/bin/phpunit"
alias _php.ini="php -r 'phpinfo();' | grep php.ini" # or php -i |grep "php.ini"
alias unit="phpunit --filter"                       # phpunit --filter '/::testSaveAndDrop$/' escalation/EscalationGroupTest.php specific single test
alias valet="~/.composer/vendor/bin/valet"
alias _queue="ps -aux | grep queue:work" # shows currently active queues
alias sail="./vendor/bin/sail"
alias _dbWipe="_pa db:wipe"
alias _dbWipes="_sa db:wipe"

alias _ci="composer install"
alias _cr="composer require"
alias _cu="composer update"
alias _cif="_ci --ignore-platform-reqs"
alias _cuf="_cu --ignore-platform-reqs"
alias _cda="composer dump-autoload"
alias _scda="sail composer dump-autoload"

alias _sa="sail artisan"
alias _sadb="_sa db:seed"
alias _sailshare="sail share"
alias _sam="_sa migrate"
alias _sat="_sa tinker"
alias _sacm="_sac && _samf"
alias _samf="_sa migrate:fresh"
alias _sasp="_sa sail:publish"
alias _ss="sail shell"
alias _srs="sail root-shell"
alias _sail="sail up -d"
alias _sailf="sail build --no-cache && _sail && _sacf"
alias _saild="sail down"
alias _sc="sail composer"
alias _sci="_sc install"
alias _scif="_sci --ignore-platform-reqs"
alias _scu="_sc upgrade"
alias _scuf="_scu  --ignore-platform-reqs"

alias _pa="php artisan"
alias _phpm="_pa migrate"
alias _pat="_pa tinker"
alias _phpmf="_pa migrate:fresh"
alias _cms="_cu && _phpm &&  _serve"
alias _cmsf="_cuf && _phpf &&  _serve"
alias _serve="_pa serve --port "

alias _vp="valet php"
alias _vc="valet composer"
alias _vw="valet which-php"

alias _proj="cd ~/Projects"
alias _omz="code ~/.oh-my-zsh/custom/" # "omz update" to update omz
alias _zsh="source ~/.zshrc"
alias _chmod="sudo chmod -R 777"               #chmod -R 777 directory
alias _systemctl="sudo launchctl list | grep " # check running services sudo launchctl list | grep service
alias pnpm="BROWSER=NONE pnpm"
alias _exit="killall Terminal"
alias _os="sw_vers || (ver & cat /etc/os-release) & whoami" #macos or linux
alias _hosts="sudo code /opt/homebrew/etc/dnsmasq.conf"
alias _ls="ls -lha"
alias _somz='sh -c "$(wget https://raw.github.com/ohmyzsh/ohmyzsh/master/tools/install.sh -O -)"'
alias _somz2='sh -c "$(curl -fsSL https://raw.github.com/ohmyzsh/ohmyzsh/master/tools/install.sh)"'
alias flutter="~/flutter/bin/flutter"
alias _ip="ip addr show eth0 | grep inet | awk '{ print $2; }' | sed 's/\/.*$//'"
export XDG_DATA_HOME=~/.local/share
export XDG_DATA_HOME=/usr/local/bin
export PNPM_HOME=~/Library/pnpm
export PATH="$PNPM_HOME:$XDG_DATA_HOME:$PATH"

alias _curr="git branch --show-current"
alias gitbr="git branch -a"
alias gitdiff="git diff --name-only"
alias gitdiffs="git diff --name-status"
alias gitdiffw="git diff --word-diff"
alias gitpm="git push mine"
alias gitshown="git show --pretty='format:' --name-only"
alias gitshoww="git show --pretty='format:' --word-diff"
alias gitf=" git push origin HEAD:main -f" #push current commit / checkout 2 remote
alias gitup="git remote add upstream "     # Create a new remote for the upstream repo => $1 is git repo
alias gitswb="git switch -"

function _sac {
    _sa key:generate
    _sa schedule:clear-cache
    _sa permission:cache-reset
    _sa view:clear
    _sa route:clear
    _sa event:clear
    _sa optimize:clear
    _sa auth:clear-resets
    _sa queue:clear
    _sa config:clear
    _sa cache:clear #sudo
}

function _sacf {
    _scda
    _scuf
    _samf
    _sac
}

function _sacfp {
    _sacf
    _sa view:cache
    _sa route:cache
    _sa config:cache
}

function _sscout {
  models=(
    "Product"
    "Service"
    "PriceDesk"
    "Category"
    "PriceDeskCategory"
    "ServiceCategory"
  )

  for model in "${models[@]}"; do
    _sa scout:flush "App\\Models\\$model"
    _sa scout:import "App\\Models\\$model"
  done
}

function _scout {
  models=(
    "Product"
    "Service"
    "PriceDesk"
    "Category"
    "PriceDeskCategory"
    "ServiceCategory"
  )

  for model in "${models[@]}"; do
    _pa scout:flush "App\\Models\\$model"
    _pa scout:import "App\\Models\\$model"
  done
}

function __php {
    _pa key:generate
    _pa schedule:clear-cache
    _pa permission:cache-reset
    _pa view:clear
    _pa route:clear
    _pa event:clear
    _pa optimize:clear
    _pa auth:clear-resets
    _pa queue:clear
    _pa config:clear
    _pa cache:clear #sudo
}

function _php {
    _cda
    __php
}

function _phpf {
    _phpmf
    _php
    _cuf
}

function _phpfp {
    _phpf
    _pa view:cache
    _pa route:cache
    _pa config:cache
}

function mm {
    _pa make:migration "create_$1_table"
}

function _sal {
    _sa lighthouse:"$1" "$2"
}

function _salm {
    _sal "mutation" "$1"
}

function _salq {
    _sal "query" "$1"
}

function _sals {
    _sal "subscription" "$1"
}

function laravel {
    sudo curl -s "https://laravel.build/$1" | bash && cd $1 && giti && gitac 'Initial commit' && code ./ && _sailf
}

function pnpmStore {
    pnpm config -g set store-dir "${1:-$PNPM_HOME}"
}

function _find {
    find / -name $1 -type d
}

function _killport { #find port and kill
    #lsof -i :"$1" # <= check pid
    # kill pid  <= run to stop
    kill -9 $(lsof -ti:$1) 2>/dev/null
    echo "port $1 killed"
}

function _port {
    lsof -i:"$1"
}

function _vim {
    # create parent directory if it doesn't exist
    echo "autocmd BufWritePre * silent! call mkdir(expand('<afile>:p:h'), 'p')" >> ~/.vimrc
}

# remove all stashed files at once:
#git stash clear
# remove a single stashed state from the stash list
#git stash drop 'stash@{index}'

### functions
function gitamend {
    git commit --amend --author="Brotherbond <ehinmitankehinde@gmail.com>" && git rebase --continue
    #https://stackoverflow.com/questions/3042437/how-to-change-the-commit-author-for-one-specific-commit
    #https://stackoverflow.com/questions/750172/how-to-change-the-author-and-committer-name-and-e-mail-of-multiple-commits-in-gi/3404304#3404304
}

function gitcase {
    git config core.ignorecase false
}

function gita {
    git add "${1:-.}"
}

function gitac {
    gita
    git commit -m "${1:-...}"
}
function gitacm {
    gitac "$1"
    git push mine "${2:-''}" "${3:-''}"
}
function gitacmf {
    gitacm $1 $2 $3
}

function gitacp {
    gitac "${1:-...}"
    gitpush
}

function gitbrdel {
    git branch -D "$1"
}

function gitbrru {
    git remote update "${1:-origin}" --prune
}

function gitcache {
    gita && git rm -r --cached "${1:-./}" && gita
}

function gitcheck {
    git checkout -b "$1"
}

function gitcl {
    git clone -b "$2" --single-branch "$1"
    # git clone -b Refactoring2 --single-branch https://github.com/bbtests/language.git
}

function gitcon {
    git config user.name "${1:-Brotherbond}" && git config user.email "${2:-'ehinmitankehinde@gmail.com'}"
}

function gitconmg {
    git config --global user.name "${1:-Brotherbond}"
    git config --global user.email "${2:-'ehinmitankehinde@gmail.com'}"
    git config --global credential.${remote}.username Brotherbond
    git config --global credential.helper store
}
function gitconm {
    gitcon
    git config user.password "$1"
    git config credential.${remote}.username Brotherbond
    git config credential.helper store
}

function gitconafri {
    gitcon "kehinde.ehinmitan" "Kehinde.Ehinmitan@afrikobo.com"
    git config user.password "$1"
    git config credential.${remote}.username Brotherbond
    git config credential.helper store
}

function gitconp {
    gitcon "$1" "$2" && git config user.password "$3" &&
        git config credential.${remote}.username "$1" &&
        git config credential.helper store
}

function _clone {
    git clone "$1" "${2:-./}" && code "$2"
    # https://www.theserverside.com/blog/Coffee-Talk-Java-News-Stories-and-Opinions/How-to-perform-a-shallow-git-clone
    # https://www.theserverside.com/blog/Coffee-Talk-Java-News-Stories-and-Opinions/How-and-when-to-perform-a-depth-1-git-clone
}

# git diff shows unstaged changes.
# git diff --cached shows staged changes.
# git diff HEAD shows all changes (both staged and unstaged).

function giti {
    git init -b "${1:-main}"
}

function githard {
    git reset --hard $1
}

function githardy {
    gita && githard $1
}
function gitlog {
    git log --all --oneline --graph --decorate
}
function gitmerge {
    git merge --squash ${1:-""}
}
function gitprune {
    git config remote."${1:-origin}".prune true # update remote automatically
    # git fetch --prune
    # git pull --prune
}
function gitpruneg {
    git config --global remote."${1:-origin}".prune true # update remote automatically
}

function gitpul {
    git pull --set-upstream origin ${1:-main}
}

function gitpulf {
    gitpul ${1:-main} --allow-unrelated-histories
}

function gitpush {
    git push -u origin #"$1"
}

function gitrmt {
    git remote add "${2:-origin}" "$1"
    gitrv
}

function gitrmtch { #switch remote address
    git remote set-url ${2:-origin} "$1"
    gitrv
}

function gitrv {
    git remote -v
}

function gitsq {
    git rebase -i "$1" # git rebase -i HEAD~3 --squash commits into the one above with pick
    # test out later => git rebase -i origin/master your-branch
}

function gits {
    git status
}

function gitsw {
    git checkout "$1"
}

# git checkout otherbranch
# git checkout -b temp-merge-other
# git merge currentbranch -s ours
# git checkout currentbranch
# git merge temp-merge-other --no-ff
# git diff otherbranch # no difference
# git log              # commit history from dev is included
# git branch -D temp-merge-other

# git merge otherbranch --no-commit --no-ff -X theirs
# git reset currentbranch

#git revert commit-id => 2 revert without touching history unlike reset
