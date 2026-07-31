<?php

define('APP_START', true);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

if (currentAdmin()) {

    logout();

    success(
        'You have been logged out successfully.'
    );

}

redirect(
    BASE_URL . 'login.php'
);