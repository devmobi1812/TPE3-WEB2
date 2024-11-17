<?php
require_once 'libs/Pagination/Page.php';
require_once 'libs/Pagination/Filter.php';
require_once 'libs/Pagination/Sort.php';
require_once 'config/config.php';
class PaginationHelper{
    /**
     * En base a un request construye una página con todos los parámetros pedidos por url
     * @param mixed $req
     * @return Page
     */
    public static function getPage($req){
        $page = new Page();

        $page->setNumber((!empty($req->query->page_number) && filter_var($req->query->page_number, FILTER_VALIDATE_INT) && $req->query->page_number >= 1) ? $req->query->page_number :1);
        $page->setSize((!empty($req->query->page_size) && filter_var($req->query->page_size, FILTER_VALIDATE_INT) && $req->query->page_size >= 1) ? $req->query->page_size : DEFAULT_PAGE_SIZE);
        $page->setSort(self::getSort($req));
        $page->setFilter( self::getFilter($req));

        return $page;
    }
    /**
     * En base a un request construye el filtro con todos los parámetros pedidos por url
     * @param mixed $req
     * @return Filter|null
     */
    public static function getFilter($req){
        if(!empty($req->query->filter_field)){
            $filter = new Filter();
            $filter->setField($req->query->filter_field);
            $filter->setFilter($req->query->filter);
            return $filter;
        }
        return null;
    }
        /**
     * En base a un request construye un ordenamiento con todos los parámetros pedidos por url
     * @param mixed $req
     * @return Sort|null
     */
    public static function getSort($req){
        if(!empty($req->query->sort_by)){
            $sort = new Sort();
            $sort->setSortedField($req->query->sort_by);
            $sort->setOrder(($req->query->order ) ? $req->query->order : "ASC" );
            return $sort;
        }
        return null;
    }
}