<?php
require_once 'libs/Pagination/Page.php';
require_once 'libs/Pagination/Filter.php';
require_once 'libs/Pagination/Sort.php';

class PaginationHelper{
    /**
     * En base a un request construye una página con todos los parámetros pedidos por url
     * @param mixed $req
     * @return Page
     */
    public static function getPage($req){
        $page = new Page();

        $page->setNumber($req->query->page_number ?? 1);
        $page->setSize($req->query->page_size ?? 50);
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
        if(!empty($req->query->filter_field) && !empty($req->query->filter)){
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
            $sort->setOrder($req->query->order ?? "ASC");
            return $sort;
        }
        return null;
    }
}