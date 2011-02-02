<?php
/**
 * YPaginator
 *
 * 'Yandex like' пагинатор с начальными и конечными ссылками,
 * ссылками на предыдущую и последующие страницы, а также
 * оптимизированный для сокрытия части ссылок на промежуточные значения
 * при большом количестве страниц
 *
 * Пример:
 * |1|...|5||6||7|...|100|
 *
 * @package YPaginator
 * @author  dZ <mail@dotzero.ru>
 * @version 0.2 (2-feb-2011)
 * @link    http://dotzero.ru
 * @link    https://github.com/dotzero/YPaginator/
 *
 * @example
 * $paginator = new YPaginator(100);
 * $paginatorArray = $paginator->setRecordsPerPage(2)
 *                             ->setCurrentPage(3)
 *                             ->setPadding(2)
 *                             ->setPrevNext(TRUE)
 *                             ->makeLink('/news/{page}/')
 *                             ->getPaginator();
 */
class YPaginator
{
    /**
     * Общее количество записей
     */
    private $totalRecords = 0;

    /**
     * Общее количество страниц
     */
    private $totalPages = 0;

    /**
     * Количество записей на страницу
     */
    private $perPage = 10;

    /**
     * Номер текущей страницы
     */
    private $currentPage = 1;

    /**
     * Количество отображаемый страниц слева и справа от текущей
     */
    private $paddingCount = 2;

    /**
     * Использование ссылок следующая / предыдущая
     */
    private $usePrevNextLinks = TRUE;

    /**
     * Дополнительные параметры для ссылки
     */
    private $linkMask = '{page}';

    /**
     * В конструктор необходимо передать общее количество записей
     *
     * @param integer $totalRecords
     */
    public function __construct($totalRecords)
    {
        $this->totalRecords = intval($totalRecords);
    }

    /**
     * Установка текущей страницы
     *
     * @param integer $page
     * @return $this
     */
    public function setCurrentPage($page)
    {
        $this->currentPage = intval($page);

        return $this;
    }

    /**
     * Установка количества записей на страницу
     *
     * @param integer $records
     * @return $this
     */
    public function setRecordsPerPage($records)
    {
        $this->perPage = (intval($records) > 0) ? intval($records) : 10;

        return $this;
    }

    /**
     * Установка количества отображаемый страниц слева и справа от текущей
     *
     * @param integer $records
     * @return $this
     */
    public function setPadding($records)
    {
        $this->paddingCount = intval($records);

        return $this;
    }

    /**
     * Включение в пагинатор ссылок следующая / предыдущая
     *
     * @param bool $flag
     * @return $this
     */
    public function setPrevNext($flag = TRUE)
    {
        $this->usePrevNextLinks = ($flag === TRUE) ? TRUE : FALSE;

        return $this;
    }

    /**
     * Генерация ссылки по шаблону
     * {page} - номер страницы
     *
     * @param string $string
     * @return $this
     */
    public function makeLink($string = '')
    {
        $this->linkMask = $string;

        return $this;
    }

    /**
     * Возвращает ассоциативный массив пагинатора
     *
     * @return mixed
     */
    public function getPaginator()
    {
        $result = array();

        $this->recalculatePages();

        if($this->totalRecords == 0)
        {
            return FALSE;
        }

        if($this->usePrevNextLinks === TRUE)
        {
            if(($this->currentPage - 1) > 1)
            {
                $result['prev'] = str_replace('{page}', ($this->currentPage - 1), $this->linkMask);
            }

            if($this->currentPage < $this->totalPages)
            {
                $result['next'] = str_replace('{page}', ($this->currentPage + 1), $this->linkMask);
            }
        }

        if($pages = $this->calcPages())
        {
            foreach ($pages AS $key => $val)
            {
                $val['link'] = str_replace('{page}', $val['link'], $this->linkMask);
                $pages[$key] = $val;
            }

            $result['pages'] = $pages;
        }

        return $result;
    }

    /**
     * Генерация массива ссылок на страницы
     *
     * @return array
     */
    private function calcPages()
    {
        $paginator = array();

        $start = $this->currentPage - $this->paddingCount;
        $start = (intval($start) < 1) ? 1 : intval($start);

        $end = $start + $this->paddingCount * 2;
        $end = (intval($end) > $this->totalPages) ? $this->totalPages : intval($end);

        if($this->paddingCount > 0 AND $start > 1)
        {
            $paginator[] = array('name' => '1', 'link' => 1);
        }

        if($this->paddingCount > 0 AND $start > 2)
        {
            $paginator[] = array('name' => '...',
                                 'link' => $start - 1);
        }

        for($i = $start; $i <= $end; $i++)
        {
            $paginator[] = array('name' => $i,
                                 'link' => $i);
        }

        if($end + 1 < $this->totalPages)
        {
            $paginator[] = array('name' => '...',
                                 'link' => $end + 1);
        }

        if($end < $this->totalPages)
        {
            $paginator[] = array('name' => $this->totalPages, 'link' => $this->totalPages);
        }

        return $paginator;
    }

    /**
     * Пересчет количества страниц
     *
     * @return bool
     */
    private function recalculatePages()
    {
        if(intval($this->totalRecords) > 0)
        {
            $this->totalPages = ceil( intval($this->totalRecords) / $this->perPage );
            $this->currentPage = ($this->currentPage <= $this->totalPages) ? $this->currentPage : 2;

            return TRUE;
        }

        return FALSE;
    }
}