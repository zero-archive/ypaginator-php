<?php
/**
 * YPaginator
 *
 * Виджет для создания 'Yandex-like' пагинатора
 * - отображение ссылок на первую и последнюю страницы
 * - замена премежуточных значений на `...`
 * - отображение дополнительных ссылок слева и справа от текущей
 *
 * Пример пагинатора:
 * << предыдущая | следующая >>
 * |1| ... |5||6||7| ... |100|
 *
 * @package YPaginator
 * @author  dZ <mail@dotzero.ru>
 * @version 0.3 (4-feb-2011)
 * @link    http://dotzero.ru
 * @link    https://github.com/dotzero/YPaginator/
 *
 * @example
 * $paginator = new YPaginator(9);
 * $paginatorArray = $paginator->setRecordsPerPage(2)
 *                             ->setCurrentPage(3)
 *                             ->setPadding(2)
 *                             ->setPrevNext(TRUE)
 *                             ->linkTemplate('{page}', '/news/page/{page}/')
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
     * Количество отображаемых ссылок слева и справа от текущей
     */
    private $paddingCount = 2;

    /**
     * Добавление в массив ссылок на следующую и предыдущие страницы
     */
    private $usePrevNextLinks = TRUE;

    /**
     * Шаблон для замены в ссылках
     */
    private $maskPattern = '{page}';

    /**
     * Шаблон ссылок
     */
    private $linkTemplate = '?page={page}';

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
     * Установка шаблона и маски для замены в ссылках
     *
     * @param string $mask
     * @param string $template
     * @return $this
     */
    public function linkTemplate($mask, $template)
    {
        $this->maskPattern = $mask;
        $this->linkTemplate = $template;

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

        if($this->totalRecords > 0 AND $pages = $this->calcPages())
        {
            foreach ($pages AS $key => $val)
            {
                $val['link'] = str_replace($this->maskPattern, $val['link'], $this->linkTemplate);
                $pages[$key] = $val;
            }

            $result['pages'] = $pages;

            if($this->usePrevNextLinks)
            {
                $result[''] = array();

                $result['prev'] = (($this->currentPage - 1) > 1) ? str_replace($this->maskPattern, ($this->currentPage - 1), $this->linkTemplate) : NULL;
                $result['next'] = ($this->currentPage < $this->totalPages) ? str_replace($this->maskPattern, ($this->currentPage + 1), $this->linkTemplate) : NULL;
            }

            return $result;
        }

        return FALSE;
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

        $end = $this->currentPage + $this->paddingCount;
        $end = (intval($end) > $this->totalPages) ? $this->totalPages : intval($end);

        if($start > ($this->paddingCount + 1))
        {
            $paginator[] = array('name' => '1', 'link' => 1);
        }

        if($start > 2)
        {
            $paginator[] = array('name' => '...', 'link' => $start - 1);
        }

        for($i = $start; $i <= $end; $i++)
        {
            $paginator[] = ($this->currentPage == $i) ? array('name' => $i, 'link' => $i, 'current' => '1') : array('name' => $i, 'link' => $i);
        }

        if($end + 1 < $this->totalPages)
        {
            $paginator[] = array('name' => '...', 'link' => $end + 1);
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