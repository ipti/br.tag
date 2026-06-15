<?php

require_once dirname(__DIR__) . '/components/utils/BuildInfo.php';

$buildCommit = BuildInfo::resolveBuildCommit(dirname(__DIR__, 2));

return [
    'commit' => $buildCommit !== '' ? $buildCommit : '1e78b7b3939c',
];
