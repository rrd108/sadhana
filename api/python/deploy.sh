#!/bin/bash

# SSH should be set up to do not ask for password - See gist

RED='\e[1;41m'
GREEN='\e[1;42m'
NC='\033[0m' # No Color

SSH_USER=$1
SSH_HOST=$2
SSH_PATH='../../web/api/python'

if ! echo "$SSH_USER" | grep -q "dhan"; then
	echo -e "${RED}The SSH_USER seems to be different then the required${NC}"
    exit 1
fi

echo $'\n' "Copy python folder to server" $'\n'
rsync --progress -azh --exclude='deploy.sh' ./ \
    -e "ssh -i /home/rrd/.ssh/id_ed25519" \
    $SSH_USER@$SSH_HOST:$SSH_PATH

if [ $? -eq 0 ]; then
    echo -e $'\n' "${GREEN} \u2714 python folder uploaded ${NC}" $'\n'
else
    echo -e $'\n' "${RED} \u2a2f python folder upload failed ${NC}" $'\n'
    PREV_STEP=0
fi
