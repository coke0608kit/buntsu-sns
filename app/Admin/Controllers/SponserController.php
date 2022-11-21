<?php

namespace App\Admin\Controllers;

use App\Sponser;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SponserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'スポンサー';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Sponser());
        $grid->column('id', __('Id'));
        $grid->column('name', __('name'));
        $grid->column('url', __('url'));
        $grid->column('rank', __('rank'));
        $grid->column('image', __('image'));
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
        $show = new Show(Sponser::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('name'));
        $show->field('url', __('url'));
        $show->field('rank', __('rank'));
        $show->field('image', __('image'));
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
        $form = new Form(new Sponser());
        $form->text('name', __('name'));
        $form->text('url', __('url'));
        $form->text('rank', __('rank'));
        $form->image('image', '画像')->uniqueName();

        return $form;
    }
}
