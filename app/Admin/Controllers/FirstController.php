<?php

namespace App\Admin\Controllers;

use App\First;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class FirstController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '初回配送';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new First());

        $grid->column('id')->display(function ($id) {
            return "<a href=\"/admin/manager/tickets/$id/edit\">$id</a>";
        });
        $grid->column('user_id', __('ユーザID'));
        $grid->column('done', __('done'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(First::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('ユーザID'));
        $show->field('done', __('done'));
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
        $form = new Form(new First());
        $form->text('id', __('id'));
        $form->text('user_id', __('ユーザID'));
        $form->number('done', __('done'));
        $form->display('', __(''));
        $form->date('created_at', __('Created at'));
        $form->date('updated_at', __('Updated at'));
        $form->display('profile.zipcode1', __('郵便番号前半'));
        $form->display('profile.zipcode2', __('郵便番号後半'));
        $form->display('profile.address1', __('住所１'));
        $form->display('profile.address2', __('住所２'));
        $form->display('profile.realname', __('氏名'));
        $form->display('user.delivery_id', __('配達ナンバー'));

        return $form;
    }
}
