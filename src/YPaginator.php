<?php

namespace dotzero;

/**
 * Class YPaginator
 *
 * A lightweight PHP paginator without a database dependency, for generating pagination controls in the style of Yandex.
 *
 * @package dotzero
 * @version 0.7
 * @author dotzero <mail@dotzero.ru>
 * @link http://www.dotzero.ru/
 * @link https://github.com/dotzero/ypaginator-php
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class YPaginator
{
    /**
     * @var int Total items in set.
     */
    private $totalItems = 0;

    /**
     * @var int Number of pages.
     */
    private $totalPages = 0;

    /**
     * @var int Items per page.
     */
    private $perPage = 10;

    /**
     * @var int Current page number.
     */
    private $currentPage = 1;

    /**
     * @var int Current page neighbours.
     */
    private $neighbours = 2;

    /**
     * @var string URL mask for replacement.
     */
    private $urlMask = '{page}';

    /**
     * @var string URL template with mask.
     */
    private $urlTemplate = '?page={page}';

    /**
     * YPaginator constructor.
     *
     * @param int $totalItems Total items in set
     * @param int $perPage Items per page
     * @param int $currentPage Current page number
     */
    public function __construct($totalItems, $perPage, $currentPage = 1)
    {
        $this->totalItems = intval($totalItems);
        $this->perPage = intval($perPage);
        $this->setCurrentPage($currentPage);
    }

    /**
     * Get current page number
     *
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * Set current page number
     *
     * @param $page Current page number
     * @return $this
     */
    public function setCurrentPage($page)
    {
        $this->currentPage = intval($page);

        if ($this->currentPage < 2) {
            $this->currentPage = 1;
        }

        return $this;
    }

    /**
     * Set current page neighbours count
     *
     * @param int $num Neighbours count
     * @return $this
     */
    public function setNeighbours($num)
    {
        $this->neighbours = intval($num);

        return $this;
    }

    /**
     * Set URL mask for replacement
     *
     * @param string $mask URL mask
     * @return $this
     */
    public function setUrlMask($mask)
    {
        $this->urlMask = $mask;

        return $this;
    }

    /**
     * Set URL template with mask
     *
     * @param string $template URL template
     * @return $this
     */
    public function setUrlTemplate($template)
    {
        $this->urlTemplate = $template;

        return $this;
    }

    /**
     * Get Paginator array based of params
     *
     * @return array
     */
    public function getPaginator()
    {
        $paginator = array(
            'prev' => array(),
            'pages' => $this->build(),
            'next' => array(),
        );

        if ($paginator['pages']) {
            if ($this->currentPage > 1) {
                $paginator['prev'] = $this->buildPage($this->currentPage - 1);
            }

            if ($this->currentPage < $this->totalPages) {
                $paginator['next'] = $this->buildPage($this->currentPage + 1);
            }
        }

        return $paginator;
    }

    /**
     * Recalculate number of pages
     */
    protected function recalculate()
    {
        if ($this->totalItems > 0 AND $this->perPage > 0) {
            $this->totalPages = ceil($this->totalItems / $this->perPage);
        }

        if ($this->currentPage > $this->totalPages) {
            $this->currentPage = $this->totalPages;
        }
    }

    /**
     * Calculate pages logic
     *
     * @return array
     */
    protected function build()
    {
        $this->recalculate();

        if ($this->totalPages < 2) {
            return array();
        }

        $paginator = array();

        $start = $this->currentPage - $this->neighbours;
        if ($start < 1) {
            $start = 1;
        }

        $end = $this->currentPage + $this->neighbours;
        if ($end > $this->totalPages) {
            $end = $this->totalPages;
        }

        // first
        if ($start > 1) {
            $paginator[] = $this->buildPage(1);
        }

        // ellipsis
        if ($start > 2) {
            $paginator[] = $this->buildPage($start - 1, '...');
        }

        // current page with neighbours
        for ($i = $start; $i <= $end; $i++) {
            $paginator[] = $this->buildPage($i);
        }

        // ellipsis
        if ($end + 1 < $this->totalPages) {
            $paginator[] = $this->buildPage($end + 1, '...');
        }

        // last
        if ($end < $this->totalPages) {
            $paginator[] = $this->buildPage($this->totalPages);
        }

        return $paginator;
    }

    /**
     * Paginator page builder
     *
     * @param int $num Number of page
     * @param mixed $name Name of label
     * @return array
     */
    protected function buildPage($num, $name = null)
    {
        if ($name === null) {
            $name = $num;
        }

        return array(
            'name' => $name,
            'url' => str_replace($this->urlMask, $num, $this->urlTemplate),
            'current' => $this->getCurrentPage() == $num,
        );
    }
}
