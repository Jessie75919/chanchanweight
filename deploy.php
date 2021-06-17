<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'chanchanweight');
set('default_stage', 'production');
// Project repository
set('repository', 'git@github.com:Jessie75919/chanchanweight.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

set('keep_releases', 3);

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', []);

task('reload:php-fpm', function () {
    run('sudo /bin/systemctl restart php7.4-fpm');
});

// Hosts
inventory('hosts.yml');

// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

after('deploy', 'reload:php-fpm');
