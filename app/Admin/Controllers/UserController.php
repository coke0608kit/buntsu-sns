<?php

namespace App\Admin\Controllers;

use App\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'User';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        $grid->column('id', __('Id'));
        $grid->column('delivery_id', __('delivery_id'));
        $grid->column('name', __('Name'));
        $grid->column('nickname', __('Nickname'));
        $grid->column('email', __('Email'));
        $grid->column('payjp_customer_id', __('Payjp customer id'));
        $grid->column('subId', __('Payjp subId'));
        $grid->column('plan', __('Plan'));
        $grid->column('planStatus', __('契約状況'));
        $grid->column('official', __('公式アカウント'));
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
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('delivery_id', __('delivery_id'));
        $show->field('name', __('Name'));
        $show->field('nickname', __('Nickname'));
        $show->field('email', __('Email'));
        $show->field('payjp_customer_id', __('Payjp customer id'));
        $show->field('subId', __('Payjp subId'));
        $show->field('plan', __('Plan'));
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
        $form = new Form(new User());

        $form->text('delivery_id', __('delivery_id'));
        $form->text('name', __('Name'));
        $form->text('nickname', __('Nickname'));
        $form->email('email', __('Email'));
        $form->text('plan', __('Plan'));
        $form->number('planStatus', __('契約状況'));
        $form->text('payjp_customer_id', __('Payjp customer id'));
        $form->text('subId', __('Payjp subId'));
        $form->text('official', __('公式アカウント'));

        return $form;
    }
}
