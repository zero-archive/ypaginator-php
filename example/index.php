<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>YPaginator example</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" media="all" href="style.css" />
</head>

<body>

    <?php
    include '../libs/YPaginator.php';

    $currentPage = 30;

    $paginator = new YPaginator(100);
    $paginatorArray = $paginator->setRecordsPerPage(2)
                                ->setCurrentPage($currentPage)
                                ->setPadding(2)
                                ->setPrevNext(TRUE)
                                ->linkTemplate('{page}', '/news/page/{page}/')
                                ->getPaginator();
    ?>

    <div class="searchPager">
        <strong>
            <b>Страницы</b>

            <?if(!empty($paginatorArray['prev'])):?>
                <em><i>&larr;</i> <em>Ctrl</em> <a href="<?=$paginatorArray['prev']?>">предыдущая</a></em>
            <?else:?>
                <em class="nopage"><i>&larr;</i> <em>Ctrl</em> предыдущая</em>
            <?endif;?>

            <?if(!empty($paginatorArray['next'])):?>
                <em><a href="<?=$paginatorArray['next']?>">следующая</a> <em>Ctrl</em> <i>&rarr;</i></em>
            <?else:?>
                <em class="nopage">следующая <em>Ctrl</em> <i>&rarr;</i></em>
            <?endif;?>
        </strong>
        <span>
            <?foreach ($paginatorArray['pages'] AS $page):?>
            <a href="<?=$page['link']?>" <?=( ($currentPage == $page['name']) ? 'class="on"' : '' )?>><b><?=$page['name']?></b></a>
            <?endforeach;?>
        </span>
    </div>

</body>
</html>
