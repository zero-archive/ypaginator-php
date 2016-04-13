<?php

require_once __DIR__ . '/../vendor/autoload.php';

$total = 100; // Total items
$perpage = 10; // Items per page
$current = 5; // Current page
$neighbours = 2; // Neighbours links beside current page

$y = new \dotzero\YPaginator($total, $perpage, $current);

$paginator = $y
    ->setNeighbours($neighbours)
    ->setUrlMask('#num#')
    ->setUrlTemplate('/foo/page/#num#')
    ->getPaginator();

print_r($paginator);

/*
[
    "prev" => ["name" => 4,"url" => "/foo/page/4","current" => false], // Previous
    "pages" => [
        ["name" => 1,"url" => "/foo/page/1","current" => false], // First
        ["name" => "...","url" => "/foo/page/2","current" => false],
        ["name" => 3,"url" => "/foo/page/3","current" => false], // Neighbour
        ["name" => 4,"url" => "/foo/page/4","current" => false], // Neighbour
        ["name" => 5,"url" => "/foo/page/5","current" => true],  // Current
        ["name" => 6,"url" => "/foo/page/6","current" => false], // Neighbour
        ["name" => 7,"url" => "/foo/page/7","current" => false], // Neighbour
        ["name" => "...","url" => "/foo/page/8","current" => false],
        ["name" => 10,"url" => "/foo/page/10","current" => false] // Last
    ],
    "next" => ["name" => 6,"url" => "/foo/page/6","current" => false] // Next
];
*/
