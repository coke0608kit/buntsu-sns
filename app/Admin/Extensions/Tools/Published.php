<?php
 
namespace App\Admin\Extensions\Tools;

use App\Ticket;
use Encore\Admin\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;
 
class Published extends AbstractTool
{
    /**
     * Render published button.
     *
     * @return string
     */
    public function render()
    {
        $tickets = Ticket::where('published', 0)->count();
        if ($tickets != 0) {
            return view('laravel-admin.published');
        }
        return view('laravel-admin.autoCreate');
    }
}
