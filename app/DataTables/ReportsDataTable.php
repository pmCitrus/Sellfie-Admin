<?php

namespace App\DataTables;

use Yajra\Datatables\Services\DataTable;

class ReportsDataTable extends DataTable
{
    protected $file_name;
    protected $dynamic_query;
    protected $export_columns;
    
    public function setFileName($file_name)
    {
        $this->file_name        = $file_name;
    }
    
    public function setQuery($dynamic_query)
    {
        $this->dynamic_query    = $dynamic_query;
    }
    
    public function setExportColumns($export_columns)
    {
        $this->export_columns   = $export_columns;
    }
    
    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $data   = $this->datatables
                    ->eloquent($this->query())
                    ->addColumn('action', '')
                    ->make(true);
        return $data;
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $data   = $this->dynamic_query;
        return $this->applyScopes($data);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->columns($this->getColumns())
                    ->ajax('url("reports")')
                    ->addAction(['width' => '80px'])
                    ->parameters($this->getBuilderParameters());
    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        return $this->export_columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return $this->file_name;
    }
}
