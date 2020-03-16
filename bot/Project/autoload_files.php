<?php

namespace Bot;

$responses = glob(__DIR__ . '/responses/*.php');
$process = glob(__DIR__ . '/process/*.php');

if ($responses === false) {
    throw new RuntimeException("Failed to glob for function files");
}

if ($process === false) {
    throw new RuntimeException("Failed to glob for function files");
}

foreach ($process as $file) {
    require_once $file;
}
unset($file);
foreach ($responses as $file) {
    require_once $file;
}
unset($file);

unset($responses);
unset($process);