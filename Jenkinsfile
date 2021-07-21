pipeline {
    agent any

    stages {
        stage('Building') {
            steps {
                sh "echo 'Building'"
            }
        }

        stage('Static Code Analysis') {
            steps {
                sh "echo static code analysis"
                // sh "composer install --optimize-autoloader --no-interaction && \
                //     ./vendor/bin/phpstan analyse -l 8 app"
            }
        }

        stage('Unit Testing') {
            steps {
                sh "composer install --optimize-autoloader --no-interaction && \
                    ./vendor/bin/phpunit --filter Unit"
            }
        }

        stage('Integration Testing') {
            steps {
                sh "composer install --optimize-autoloader --no-interaction"
                sh "php artisan config:clear"
                sh "mkdir -p ~/netone/${env.BRANCH_NAME}/current/storage"

                sh "touch ~/netone/${env.BRANCH_NAME}/current/.env"
                sh "touch ~/netone/${env.BRANCH_NAME}/current/current"
                sh "touch ~/netone/${env.BRANCH_NAME}/current/root"
                sh "touch ~/netone/${env.BRANCH_NAME}/current/worker.log"
                sh "touch ~/netone/${env.BRANCH_NAME}/current/supervisord.conf"

                sh "ln -fs ~/netone/${env.BRANCH_NAME}/current/.env .env"
                sh "./vendor/bin/phpunit --filter Feature"
            }
        }

        stage('Deploying') {
            steps {
                sh "rm -rf storage tests Jenkinsfile .env && \
                    mkdir -p ~/netone/${env.BRANCH_NAME}/${env.BUILD_ID} && \
                    cp -r * ~/netone/${env.BRANCH_NAME}/${env.BUILD_ID} && \
                    cd ~/netone/${env.BRANCH_NAME}/${env.BUILD_ID} && \
                    composer install --optimize-autoloader --no-interaction --no-dev && \
                    cp -r ~/netone/${env.BRANCH_NAME}/current/.env ~/netone/${env.BRANCH_NAME}/${env.BUILD_ID}/.env && \
                    ln -fs ~/netone/${env.BRANCH_NAME}/current/storage ~/netone/${env.BRANCH_NAME}/${env.BUILD_ID}/storage && \
                    sudo chgrp -R www-data ~/netone/${env.BRANCH_NAME}/${env.BUILD_ID}/storage && \
                    sudo chmod -R g+w ~/netone/${env.BRANCH_NAME}/${env.BUILD_ID}/storage && \
                    php artisan config:clear && \
                    php artisan optimize && \
                    php artisan config:cache && \
                    php artisan route:cache && \
                    php artisan view:cache && \
                    php artisan migrate --force && \
                    ln -fs ~/netone/${env.BRANCH_NAME}/current/storage/app/public ~/netone/${env.BRANCH_NAME}/${env.BUILD_ID}/public/storage && \
                    npm install && npm run prod && \
                    echo \"root ${env.HOME}/netone/${env.BRANCH_NAME}/${env.BUILD_ID}/public;\" > ../current/root && \
                    echo \"${env.HOME}/netone/${env.BRANCH_NAME}/${env.BUILD_ID}/\" > ../current/current"
                sh "sudo nginx -s reload"
                sh "cd ~/netone/${env.BRANCH_NAME}/${env.BUILD_ID} && \
                    php artisan queue:restart && \
                    mv supervisord.prod.conf supervisord.conf && \
                    sed -i 's|laravel-worker|netone-${env.BRANCH_NAME}-worker|' supervisord.conf && \
                    sed -i 's|artisan|${env.HOME}/netone/${env.BRANCH_NAME}/${env.BUILD_ID}/artisan|' supervisord.conf && \
                    cp ~/netone/${env.BRANCH_NAME}/${env.BUILD_ID}/supervisord.conf ~/netone/${env.BRANCH_NAME}/current/supervisord.conf && \
                    sudo /usr/local/bin/supervisorctl -c /etc/supervisor/supervisord.conf update all"
                sh "cd ~/netone/${env.BRANCH_NAME}/${env.BUILD_ID} && php artisan up"
            }
        }

        stage('Clean up') {
            steps {
                sh "cd ~/netone/${env.BRANCH_NAME} && ls -t | tail -n +4 | egrep -v 'current' | xargs rm -rf --"
            }
        }
    }
}
