<?php

namespace App\Admin\Extensions\Tools;

use App\Shipment;
use Encore\Admin\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class labelPublishedDone extends AbstractTool
{
    /**
     * Render published button.
     *
     * @return string
     */
    public function render()
    {
        $thisYear = date('Y');
        $thisMonth = date('n');
        $judgeTerm = 'second';
        $shipments = Shipment::where('year', $thisYear)->where('month', $thisMonth)->where('term', $judgeTerm)->where('shipment', 0)->count();
        if ($shipments != 0) {
            return view('laravel-admin.csvPublishedDone');
        }
    }
}
