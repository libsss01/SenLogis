<?php

function getSiteVisitsStoragePath(){
    return __DIR__ . '/../storage/site-visits.json';
}

function getVisitorHash(){
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown-ip';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown-agent';

    return hash('sha256', $ip . '|' . $userAgent);
}

function readSiteVisits(){
    $path = getSiteVisitsStoragePath();

    if (!file_exists($path)) {
        return [];
    }

    $content = file_get_contents($path);
    $visits = json_decode($content, true);

    return is_array($visits) ? $visits : [];
}

function writeSiteVisits($visits){
    $path = getSiteVisitsStoragePath();
    $directory = dirname($path);

    if (!is_dir($directory)) {
        mkdir($directory, 0775, true);
    }

    return file_put_contents($path, json_encode($visits, JSON_PRETTY_PRINT), LOCK_EX) !== false;
}

function trackVitrineVisit($page = 'index')
{
    $visits = readSiteVisits();
    $visits[] = [
        'page' => $page,
        'visitor_hash' => getVisitorHash(),
        'visited_at' => date('c')
    ];

    return writeSiteVisits($visits);
}

function countVitrineVisits(){
    $visits = readSiteVisits();
    $total = 0;

    foreach ($visits as $visit) {
        if (($visit['page'] ?? '') === 'index') {
            $total++;
        }
    }

    return $total;
}

function countUniqueVitrineVisitors(){
    $visits = readSiteVisits();
    $uniqueVisitors = [];

    foreach ($visits as $visit) {
        if (($visit['page'] ?? '') === 'index' && !empty($visit['visitor_hash'])) {
            $uniqueVisitors[$visit['visitor_hash']] = true;
        }
    }

    return count($uniqueVisitors);
}
?>
