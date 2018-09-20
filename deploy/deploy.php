<?php
namespace Deployer;

require_once 'recipe/common.php';

// Project repository
set('repository', 'git@bitbucket.org:rossmintymurray/miles_apart.git');

set('shared_dirs', ['app/logs']);
set('writable_dirs', ['app/cache', 'app/logs']);

// Servers
inventory('./deploy/servers.yml');

set('app/console', function () {
    return parse('{{bin/php}} {{release_path}}/app/console --no-interaction');
});

set('bin_dir', 'app');
set('var_dir', 'app');

/**
 * Create cache dir
 */
task('deploy:create_cache_dir', function () {
    // Set cache dir
    set('cache_dir', '{{release_path}}/' . trim(get('var_dir'), '/') . '/cache');

    // Remove cache dir if it exist
    run('if [ -d "{{cache_dir}}" ]; then rm -rf {{cache_dir}}; fi');

    // Create cache dir
    run('mkdir -p {{cache_dir}}');

    // Set rights
    run("chmod -R g+w {{cache_dir}}");
})->desc('Create cache dir');

/**
 * Create image cache dir
 */
task('deploy:create_image_cache_dir', function () {
    // Set cache dir
    set('image_cache_dir', '{{release_path}}/web/cache');

    // Remove cache dir if it exist
    run('if [ -d "{{image_cache_dir}}" ]; then sudo rm -rf {{image_cache_dir}}; fi');

    // Create cache dir
    run('mkdir -p {{image_cache_dir}}');

    // Set rights
    run("sudo chown www-data:www-data {{image_cache_dir}}");

    // Set rights
    run("chmod -R g+w {{image_cache_dir}}");
})->desc('Create image cache dir');

desc('Migrate database');
task('database:migrate', function () {
    run('{{app/console}} doctrine:migrations:migrate --allow-no-migration');
});

desc('Clear cache');
task('deploy:cache:clear', function () {
    run('{{app/console}} cache:clear --no-warmup');
});

desc('Warm up cache');
task('deploy:cache:warmup', function () {
    run('{{app/console}}  cache:warmup');
});

desc('Dump assetic');
task('deploy:assetic:dump', function () {
    run('{{app/console}}  assetic:dump --no-debug');
});

desc('Restart server');
task('deploy:server:restart', function () {
    run('sudo service apache2 restart');
});


desc('Deploy project');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:create_cache_dir',
    'deploy:create_image_cache_dir',
    'deploy:shared',
    'deploy:writable',
    'deploy:vendors',
    'deploy:cache:clear',
    'deploy:cache:warmup',
    'deploy:assetic:dump',
    'deploy:symlink',
    'deploy:server:restart',
    'deploy:unlock',
    'cleanup',
]);

after('deploy', 'success');
