#!/bin/bash

# SSH should be set up to do not ask for password - See gist

RED='\e[1;41m'
GREEN='\e[1;42m'
NC='\033[0m' # No Color

SSH_USER=$1
SSH_HOST=$2
SSH_PATH='../../web/'

if ! echo "$SSH_USER" | grep -q "dhan"; then
	echo -e "${RED}The SSH_USER seems to be different then the required${NC}"
    exit 1
fi

PREV_STEP=1

# echo $'\n' "Running Backend Tests" $'\n'
# cd api
# vendor/bin/phpunit
# if [ $? -eq 0 ]; then
#	echo -e $'\n' "${GREEN} \u2714 All backend tests passed ${NC}" $'\n'
#	PREV_STEP=1
# else
# 	echo -e $'\n' "${RED} \u2a2f Some backend tests failed ${NC}" $'\n'
# 	PREV_STEP=0
# fi
	
# if [ $PREV_STEP -eq 1 ];then
# 	echo $'\n' "Running Frontend Tests" $'\n'
#	cd ..
# 	yarn test
# 	if [ $? -eq 0 ]; then
# 		echo -e $'\n' "${GREEN} \u2714 All frontend tests passed ${NC}" $'\n'
# 	else
# 		echo -e $'\n' "${RED} \u2a2f Some frontend tests failed ${NC}" $'\n'
# 		PREV_STEP=0
# 	fi
#fi

if [ $PREV_STEP -eq 1 ];then
	echo $'\n' "Bump frontend version number" $'\n'
	yarn version --patch --no-git-tag-version

	echo $'\n' "Running Frontend Build" $'\n'
	yarn build
	if [ $? -eq 0 ]; then
		echo -e $'\n' "${GREEN} \u2714 Build successfull ${NC}" $'\n'
	else
		echo -e $'\n' "${RED} \u2a2f Build failed ${NC}" $'\n'
		PREV_STEP=0
		yarn version --patch --no-git-tag-version revert
		echo $'\n' "Frontend version number reverted" $'\n'
	fi

	if [ $PREV_STEP -eq 1 ];then
		echo $'\n' "Copy dist folder to server" $'\n'
		rsync --progress -azh \
			--delete --exclude='api/' --exclude='error/' --exclude='stats/' --exclude='.htaccess' --exclude='favicon.ico' --exclude='robots.txt' --exclude='standard_index.html' \
			./dist/ \
			-e "ssh -i /home/rrd/.ssh/id_ed25519" \
			$SSH_USER@$SSH_HOST:$SSH_PATH

		if [ $? -eq 0 ]; then
			echo -e $'\n' "${GREEN} \u2714 dist folder uploaded ${NC}" $'\n'
		else
			echo -e $'\n' "${RED} \u2a2f dist folder upload failed ${NC}" $'\n'
			PREV_STEP=0
		fi
	fi
fi

if [ $PREV_STEP -eq 1 ];then
	echo $'\n' "Copy api folder to server without vendor and ignored files" $'\n'
	rsync --progress -azh \
		--exclude='.github/' \
		--exclude='config/app_local.php' \
		--exclude='config/.env' \
		--exclude='logs/' \
		--exclude='tmp/' \
		--exclude='vendor/' \
		--exclude='python/deploy.sh' \
		./api/ \
		-e "ssh -i /home/rrd/.ssh/id_ed25519" \
		$SSH_USER@$SSH_HOST:$SSH_PATH"api/"

	if [ $? -eq 0 ]; then
		echo -e $'\n' "${GREEN} \u2714 api folder uploaded ${NC}" $'\n'
	else
		echo -e $'\n' "${RED} \u2a2f api folder upload failed ${NC}" $'\n'
		PREV_STEP=0
	fi
fi

if [ $PREV_STEP -eq 1 ];then
	echo $'\n' "Run composer install on server/api" $'\n'
	ssh -i /home/rrd/.ssh/id_ed25519 -t $SSH_USER@$SSH_HOST "chmod 775 -R ${SSH_PATH}api/"
	ssh -i /home/rrd/.ssh/id_ed25519 -t $SSH_USER@$SSH_HOST "cd ${SSH_PATH}api/ && /usr/bin/php8.2 /usr/local/bin/composer install --no-dev --no-interaction --optimize-autoloader"

	echo $'\n' "Set permissions" $'\n'
	ssh -i /home/rrd/.ssh/id_ed25519 -t $SSH_USER@$SSH_HOST "find ${SSH_PATH}api/ -type f -exec chmod 644 {} \;"
	ssh -i /home/rrd/.ssh/id_ed25519 -t $SSH_USER@$SSH_HOST "find ${SSH_PATH}api/ -type d -exec chmod 755 {} \;"
	ssh -i /home/rrd/.ssh/id_ed25519 -t $SSH_USER@$SSH_HOST "chmod +x ${SSH_PATH}api/bin/cake"

	if [ $? -eq 0 ]; then
		echo -e $'\n' "${GREEN} \u2714 composer install was successfull ${NC}" $'\n'
	else
		echo -e $'\n' "${RED} \u2a2f composer install failed ${NC}" $'\n'
		PREV_STEP=0
	fi
fi

if [ $PREV_STEP -eq 1 ];then
	echo -e $'\n' "Run migrations" $'\n'
	ssh -i /home/rrd/.ssh/id_ed25519 -t $SSH_USER@$SSH_HOST "export PHP=/usr/bin/php8.2 && cd ${SSH_PATH}api/ && bin/cake migrations migrate"
	if [ $? -eq 0 ]; then
		echo -e $'\n' "${GREEN} \u2714 migrations were successfull ${NC}" $'\n'
	else
		echo -e $'\n' "${RED} \u2a2f applying migrations failed ${NC}" $'\n'
		PREV_STEP=0
	fi
fi

if [ $PREV_STEP -eq 1 ];then
	echo -e $'\n' "Clear all cache" $'\n'
	ssh -i /home/rrd/.ssh/id_ed25519 -t $SSH_USER@$SSH_HOST "export PHP=/usr/bin/php8.2 && cd ${SSH_PATH}api/ && bin/cake cache clear_all"
	if [ $? -eq 0 ]; then
		echo -e $'\n' "${GREEN} \u2714 Cache cleared ${NC}" $'\n'
	else
		echo -e $'\n' "${RED} \u2a2f Cache clear failed ${NC}" $'\n'
		PREV_STEP=0
	fi
fi

if [ $PREV_STEP -eq 1 ];then
	echo $'\n' "Purge CloudFlare Cache" $'\n'
	source .env.development
	curl_result=$(curl --request POST \
	    --url https://api.cloudflare.com/client/v4/zones/$CF_ZONE_ID/purge_cache \
	    --header 'Content-Type: application/json' \
	    --header "Authorization: Bearer $CF_TOKEN" \
		--data '{"files": ["https://sadhana.1108.cc/*"]}' \
		-s)

	if [[ $(jq -r '.success' <<< "$curl_result") == "true" ]]; then
		echo -e $'\n' "${GREEN} \u2714 CF cache purged ${NC}" $'\n'
	else
		echo -e $'\n' "${RED} \u2a2f CF cache purge failed ${NC}" $'\n'
		error_message=$(jq -r '.errors[0].message' <<< "$curl_result")
		echo "Error purging cache: $error_message"
		PREV_STEP=0
	fi
fi

if [ $PREV_STEP -eq 1 ];then
	echo -e $'\n' "${GREEN} \u2714 All is fine ${NC}" $'\n'
fi