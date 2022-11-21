<?php

namespace App\Admin\Controllers;

use App\Buying;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class BuyingController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '購入履歴';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Buying());

        $grid->column('id')->display(function ($id) {
            return "<a href=\"/admin/manager/buyings/$id/edit\">$id</a>";
        });
        $grid->column('user_id', __('ユーザID'));
        $grid->column('plan', __('プラン'));
        $grid->column('item', __('item'));
        $grid->column('quantity')->display(function ($id) {
            if ($this->item == 'singleQutte') {
                return $id;
            } elseif ($this->item == 'setQutte') {
                return $id * 3 .' ('.$id.')';
            }
        });
        $grid->column('totalPrice', __('総合計金額'));
        $grid->column('done', __('done'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->equal('user_id', 'ユーザID');
            $filter->equal('plan', 'プラン');
            $filter->equal('done', 'done');
        });
        $grid->expandFilter();

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
        $show = new Show(Buying::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('ユーザID'));
        $show->field('item', __('item'));
        $show->field('quantity', __('quantity'));
        $show->field('totalPrice', __('総合計金額'));
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
        $form = new Form(new Buying());

        $form->text('id', __('id'));
        $form->text('user_id', __('ユーザID'));
        $form->number('done', __('done'));
        $form->display('item', __('item'));
        $form->display('quantity', __('quantity'));
        $form->display('totalPrice', __('総合計金額'));
        $form->display('profile.zipcode1', __('郵便番号前半'));
        $form->display('profile.zipcode2', __('郵便番号後半'));
        $form->display('profile.address1', __('住所１'));
        $form->display('profile.address2', __('住所２'));
        $form->display('profile.realname', __('氏名'));
        $form->display('user.delivery_id', __('配達ナンバー'));
        $form->display('plan', __('プラン'));

        return $form;
    }
}
