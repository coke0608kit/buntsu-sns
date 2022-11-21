<?php

namespace App\Admin\Controllers;

use App\Shipment;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Admin\Extensions\Tools\labelPublish;
use App\Admin\Extensions\Tools\labelPublishedDone;
use App\Admin\Extensions\Tools\labelRePublish;

class ShipmentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'サブスク';

    /**
     * Make a grid builder.
     *
     * @return Gridスク
     */
    protected function grid()
    {
        $grid = new Grid(new Shipment());

        $grid->column('id')->display(function ($id) {
            return "<a href=\"/admin/manager/shipments/$id/edit\">$id</a>";
        });
        $grid->column('user_id', __('ユーザID'));
        $grid->column('plan', __('プラン'));
        $grid->column('year', __('ターム年'));
        $grid->column('month', __('ターム月'));
        $grid->column('term', __('ターム'));
        $grid->column('shipment', __('発送状態'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        $grid->tools(function ($tools) {
            $tools->append(new labelPublish());
            $tools->append(new labelPublishedDone());
            $tools->append(new labelRePublish());
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Shipment::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('ユーザID'));
        $show->field('plan', __('プラン'));
        $show->field('year', __('ターム年'));
        $show->field('month', __('ターム月'));
        $show->field('term', __('ターム'));
        $show->field('shipment', __('発送状態'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Shipment());
        $form->text('id', __('id'));
        $form->text('user_id', __('ユーザID'));
        $form->text('plan', __('プラン'));
        $form->number('year', __('ターム年'));
        $form->number('month', __('ターム月'));
        $form->text('term', __('ターム'));
        $form->number('shipment', __('発送状態'));
        $form->date('created_at', __('Created at'));
        $form->date('updated_at', __('Updated at'));

        return $form;
    }

    public function labelPublish()
    {
        /*
        $tickets = Ticket::where('published', 0)->get();
        foreach ($tickets as $ticket) {
            $ticket->published = 1;
            $ticket->save();
        }
        return redirect('/admin/manager/tickets');
        */
        // コールバック関数に１行ずつ書き込んでいく処理を記述
        $callback = function () {
            $thisYear = date('Y');
            $thisMonth = date('n');
            $judgeTerm = 'second';
            // 出力バッファをopen
            $stream = fopen('php://output', 'w');
            // 文字コードをShift-JISに変換
            stream_filter_prepend($stream, 'convert.iconv.utf-8/cp932//TRANSLIT');
            // ヘッダー行
            fputcsv($stream, ['ユーザID','郵便番号','住所１','住所２','氏名','配達ID - プラン']);
            // データ
            $shipments = Shipment::where('year', $thisYear)->where('month', $thisMonth)->where('term', $judgeTerm)->where('shipment', 0)->get();
            // ２行目以降の出力
            // cursor()メソッドで１レコードずつストリームに流す処理を実現できる。
            foreach ($shipments as $shipment) {
                fputcsv($stream, [
                $shipment->user_id,$shipment->profile->zipcode1.'-'.$shipment->profile->zipcode2,$shipment->profile->address1,$shipment->profile->address2,$shipment->profile->realname,$shipment->user->delivery_id.' '.$shipment->user->plan
            ]);
            }
            fclose($stream);
        };
        $thisYear = date('Y');
        $thisMonth = date('n');
        $judgeTerm = 'second';

        // 保存するファイル名
        $filename = sprintf($thisYear.'年'.$thisMonth.'月'.'second宛名ラベル一覧-%s.txt', date('Ymd'));

        // ファイルダウンロードさせるために、ヘッダー出力を調整
        $header = [
        'Content-Type' => 'application/octet-stream',
        ];

        return response()->streamDownload($callback, $filename, $header);
    }

    public function labelPublishedDone()
    {
        $thisYear = date('Y');
        $thisMonth = date('n');
        $judgeTerm = 'second';
        $shipments = Shipment::where('year', $thisYear)->where('month', $thisMonth)->where('term', $judgeTerm)->where('shipment', 0)->get();

        foreach ($shipments as $shipment) {
            $shipment->shipment = 9999;
            $shipment->save();
        }
        return redirect('/admin/manager/shipments');
    }

    public function labelRePublish()
    {
        $thisYear = date('Y');
        $thisMonth = date('n');
        $judgeTerm = 'second';
        $shipments = Shipment::where('year', $thisYear)->where('month', $thisMonth)->where('term', $judgeTerm)->where('shipment', 9999)->get();

        foreach ($shipments as $shipment) {
            $shipment->shipment = 0;
            $shipment->save();
        }
        return redirect('/admin/manager/shipments');
    }
}
