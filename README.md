YouTrack Issue Indicator
========================

Installation
------------

`git clone https://github.com/nepda/youtrack-issue-indicator.git`

Create a file `.auth.php` with

    <?php
    define('YOUTRACK_URL', 'https://<username>.myjetbrains.com/youtrack');
    define('YOUTRACK_USERNAME', 'user');
    define('YOUTRACK_PASSWORD', 'password');

Make a webserver point to ./public/ and access the site via `http://example.com/?issue=ISSUE-123`.
