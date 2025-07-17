<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Log Viewer
    |--------------------------------------------------------------------------
    |
    | You can here change all the settings related to the log viewer.
    |
    */

    /**
     * The base path for the log viewer.
     * For example, if you set it to 'my-logs', the log viewer will be available at http://yourapp.test/my-logs.
     *
     * This can be a great way to hide your log viewer from public access. For example, you can use a UUID
     * as the path, so only you know where to access the log viewer.
     *
     * You can also set this to an empty string to make the log viewer available at the root of the domain.
     * But it is highly recommended to use a long, random string to prevent unauthorized access.
     *
     * You can also set this to `null` to disable the log viewer route registration altogether.
     */
    'path' => 'log-viewer',

    /**
     * The route-specific middleware for the log viewer.
     * By default, it is 'web' for all Laravel applications.
     * If you have a different middleware group, you can change it here.
     *
     * You can also add your own middleware to the array.
     * For example, you can add the 'auth' middleware to protect the log viewer.
     *
     * It is highly recommended to protect the log viewer from unauthorized access.
     */
    'middleware' => [
        'web',
        'can:is-admin', // Pastikan baris ini ditambahkan
    ],

    /**
     * The log viewer is enabled by default. You can turn it off here.
     * This is useful if you want to disable the log viewer in a specific environment.
     *
     * You can also use the `LOG_VIEWER_ENABLED` environment variable to override this setting.
     */
    'enabled' => env('LOG_VIEWER_ENABLED', true),

    /**
     * Include the following files from the `storage/logs` directory.
     * By default, it includes all `.log` files and all files in sub-directories.
     *
     * You can specify a file path, or a directory path.
     * For example:
     * 'include_files' => ['*.log', 'my-app/logs/*.log'],
     */
    'include_files' => ['*.log', '**/*.log'],

    /**
     * Exclude the following files from the `storage/logs` directory.
     * By default, it excludes nothing.
     *
     * You can specify a file path, or a directory path.
     * For example:
     * 'exclude_files' => ['my-secret-file.log'],
     */
    'exclude_files' => [],

    /**
     * The number of logs to display per page.
     */
    'per_page' => 20,

    /**
     * The maximum number of log files to display in the file list.
     * This is to prevent the file list from becoming too large.
     */
    'max_files' => 50,

    /**
     * The theme of the log viewer.
     * You can choose between 'light', 'dark' and 'auto'.
     *
     * 'auto' will use the OS preference.
     */
    'theme' => 'auto',

    /**
     * The "back to system" URL.
     * If you are embedding the log viewer in your own application,
     * you may want to have a link to go back to your application.
     *
     * You can specify a URL here.
     * For example:
     * 'back_to_system_url' => config('app.url'),
     */
    'back_to_system_url' => null,

    /**
     * The title of the log viewer.
     * This is the text that is displayed in the browser tab.
     */
    'title' => 'Log Viewer',
];