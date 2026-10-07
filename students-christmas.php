<?php
require_once __DIR__ . '/includes/config.php';
header('Location: ' . page_url('courses.php') . '#student-work', true, 301);
exit;
