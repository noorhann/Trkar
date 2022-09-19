<?php

namespace App\Libs;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class TablePagination{

    private $viewPath = 'table-pagination.table';

    public $model;

    private $columns = [];

    private $headColumns = [];

    private $headerDescription = null;


    /**
     * @param string $viewPath
     */
    public function setViewPath(string $viewPath)
    {
        $this->viewPath = $viewPath;
        return $this;
    }

    public function setHeadColumns(array $columns){
        $this->headColumns = $columns;
        return $this;
    }

    public function setHeaderDescription(Closure $callback){
        $modal = clone $this->model;
        $this->headerDescription = $callback($modal);
        return $this;
    }

    public function eloquent($model){
       
        $this->model = $model;
        return $this;
    }

    public function addColumn($name,$callback = null){
        $this->columns[$name] = $callback;
        return $this;
    }

    public function render($items = null){
      
        if(empty($this->model)){
            throw new \Exception('Unable to get model');
        }elseif(empty($this->columns)){
            throw new \Exception('Unable to get columns');
        }

        if(!$items) $items = 10;

        $data = $this->model->paginate($items);
        $currentPage = json_decode($data->toJSON());
        return view($this->viewPath,[
            'columns'=> $this->columns,
            'headColumns'=> $this->headColumns,
            'data'=> $data,
            'items'=> $items,
            'currentPage'=> $currentPage->current_page,
            'headerDescription'=> $this->headerDescription
        ]);
    }



}
