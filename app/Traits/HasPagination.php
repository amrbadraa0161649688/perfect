<?php

namespace App\Traits;

trait HasPagination{

    private function getItems($pageination,$modelClass){
        return  collect($pageination->items())->map(function($item, $index) use ($pageination,$modelClass){
            $page = $pageination->currentPage() - 1;
            $count =  ( $page * $pageination->perPage() ) + ($index +1) ;
            return array_merge([
                'page_count'=>$count,
                'id'=> $item->id
            ],$modelClass::map($item));
        });
    }
    public function getPaginationData($pageination,$mapItem){
        $items = $this->getItems($pageination,$mapItem);
        return [
            'items' => $items,
            'lastPage'=> $pageination->lastPage(),
            'currentPage'=> $pageination->currentPage(),
            'onFirstPage' => $pageination->onFirstPage(),
            'onLastPage' => $pageination->hasMorePages(),
            'links' => $pageination->getOptions(),
            'hasPages'=>$pageination->hasPages(),
            'totalRecords'=>$pageination->total(),
            'p'=> $pageination
        ];
    }

}
