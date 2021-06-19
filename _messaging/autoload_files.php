<?php

namespace Bot;

$responses = glob(__DIR__ . '/responses/*.php');
$yml_responses = glob(__DIR__ . '/yml_responses/*.php');
$process = glob(__DIR__ . '/process/*.php');

if ($responses === false) {
    throw new RuntimeException("Failed to glob for function files (responses)");
}

if ($yml_responses === false) {
    throw new RuntimeException("Failed to glob for function files (yaml responses)");
}

if ($process === false) {
    throw new RuntimeException("Failed to glob for function files (personalized process)");
}

foreach ($responses as $file) {
    require_once $file;
}
unset($file);
foreach ($yml_responses as $file) {
    require_once $file;
}
unset($file);
foreach ($process as $file) {
    require_once $file;
}
unset($file);

unset($responses);
unset($yml_responses);
unset($process);