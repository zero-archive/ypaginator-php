<?php

use \dotzero\YPaginator;

class YPaginatorTest extends PHPUnit_Framework_TestCase
{
    protected $y;

    public function setUp()
    {
        $this->y = new YPaginator(100, 10, 1);
    }

    /**
     * @dataProvider getTestData
     */
    public function testPaginatorPages($total, $perpage, $current, $neighbours, $expected)
    {
        $y = new YPaginator($total, $perpage, $current);

        $paginator = $y->setNeighbours($neighbours)->getPaginator();
        $actual = array_map(function($page) { return $page['name']; }, $paginator['pages']);

        $this->assertEquals($expected, $actual);
    }

    public function getTestData()
    {
        return array(
            // total, perpage, current, neighbours, expected
            array(100, 10, 1, 2, array(1, 2, 3, '...', 10)),
            array(100, 10, 2, 2, array(1, 2, 3, 4, '...', 10)),
            array(100, 10, 3, 2, array(1, 2, 3, 4, 5, '...', 10)),
            array(100, 10, 4, 2, array(1, 2, 3, 4, 5, 6, '...', 10)),
            array(100, 10, 5, 2, array(1, '...', 3, 4, 5, 6, 7, '...', 10)),
            array(100, 10, 6, 2, array(1, '...', 4, 5, 6, 7, 8, '...', 10)),
            array(100, 10, 7, 2, array(1, '...', 5, 6, 7, 8, 9, 10)),
            array(100, 10, 8, 2, array(1, '...', 6, 7, 8, 9, 10)),
            array(100, 10, 9, 2, array(1, '...', 7, 8, 9, 10)),
            array(100, 10, 10, 2, array(1, '...', 8, 9, 10)),

            array(100, 10, 1, 0, array(1, '...', 10)),
            array(100, 10, 2, 0, array(1, 2, '...', 10)),
            array(100, 10, 3, 0, array(1, '...', 3, '...', 10)),
            array(100, 10, 8, 0, array(1, '...', 8, '...', 10)),
            array(100, 10, 9, 0, array(1, '...', 9, 10)),
            array(100, 10, 10, 0, array(1, '...', 10)),

            array(100, 10, 11, 2, array(1, '...', 8, 9, 10)), // current > max = max
            array(100, 10, 0, 2, array(1, 2, 3, '...', 10)),  // current = 0 = 1

            array(100, 100, 1, 2, array()),  // perpage >= total = empty
            array(100, 0, 1, 2, array()),  // perpage < 0

            array(100, 10, 1, 200, array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10)),  // neighbours > total
        );
    }

    public function testPrevLink()
    {
        $p = $this->y->setCurrentPage(1)->getPaginator();
        $this->assertEquals($p['prev'], array());

        $p = $this->y->setCurrentPage(2)->getPaginator();
        $this->assertEquals($p['prev']['name'], 1);

        $p = $this->y->setCurrentPage(100)->getPaginator();
        $this->assertEquals($p['prev']['name'], 9);
    }

    public function testNextLink()
    {
        $p = $this->y->setCurrentPage(1)->getPaginator();
        $this->assertEquals($p['next']['name'], 2);

        $p = $this->y->setCurrentPage(9)->getPaginator();
        $this->assertEquals($p['next']['name'], 10);

        $p = $this->y->setCurrentPage(100)->getPaginator();
        $this->assertEquals($p['next'], array());
    }

    public function testUrlMask()
    {
        $p = $this->y
            ->setUrlMask('###')
            ->setUrlTemplate('/p/###/')
            ->setCurrentPage(5)
            ->getPaginator();

        $this->assertEquals($p['prev']['url'], '/p/4/');
        $this->assertEquals($p['next']['url'], '/p/6/');
        $this->assertEquals($p['pages'][0]['url'], '/p/1/');
    }
}
