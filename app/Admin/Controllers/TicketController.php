<?php

namespace App\Admin\Controllers;

use App\Ticket;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Admin\Extensions\Tools\Published;

class TicketController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Ticket';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Ticket());

        $grid->column('id')->display(function ($id) {
            return "<a href=\"/admin/manager/tickets/$id/edit\">$id</a>";
        });
        $grid->column('from', __('From'));
        $grid->column('to', __('To'));
        $grid->column('used', __('Used'));
        $grid->column('done', __('Done'));
        $grid->column('published', __('Published'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->equal('id', 'Q手');
            $filter->equal('used', 'used');
            $filter->equal('done', 'done');
            $filter->equal('published', 'published');
        });
        $grid->expandFilter();

        $grid->tools(function ($tools) {
            $tools->append(new Published());
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
        $show = new Show(Ticket::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('from', __('From'));
        $show->field('to', __('To'));
        $show->field('used', __('Used'));
        $show->field('done', __('Done'));
        $show->field('published', __('Published'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('profile.zipcode1', __('郵便番号前半'));
        $show->field('profile.zipcode2', __('郵便番号後半'));
        $show->field('profile.address1', __('住所１'));
        $show->field('profile.address2', __('住所２'));
        $show->field('profile.realname', __('氏名'));
        $show->field('user.delivery_id', __('配達ナンバー'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Ticket());
        $form->text('from', __('From'));
        $form->text('to', __('To'));
        $form->number('used', __('Used'));
        $form->number('done', __('Done'));
        $form->number('published', __('Published'));
        $form->display('id', __('Q手のID'));
        $form->display('profile.zipcode1', __('郵便番号前半'));
        $form->display('profile.zipcode2', __('郵便番号後半'));
        $form->display('profile.address1', __('住所１'));
        $form->display('profile.address2', __('住所２'));
        $form->display('profile.realname', __('氏名'));
        $form->display('user.delivery_id', __('配達ナンバー'));
        $form->display('user.plan', __('プラン'));
        $form->display('to', __('宛先ID'));
        return $form;
    }

    public function published()
    {
        $tickets = Ticket::where('published', 0)->get();
        foreach ($tickets as $ticket) {
            $ticket->published = 1;
            $ticket->save();
        }
        return redirect('/admin/manager/tickets');
    }

    public function autoCreate()
    {
        $volume = 100;
        for ($i = 0; $i < $volume; $i++) {
            $ticket = new Ticket();
            $ticket->published = 0;
            $ticket->used = 0;
            $ticket->done = 0;
            $ticket->save();
        }
        return redirect('/admin/manager/tickets');
    }
}
