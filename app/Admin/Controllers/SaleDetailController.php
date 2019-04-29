<?php

namespace App\Admin\Controllers;

use App\SaleDetail;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class SaleDetailController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SaleDetail);

        $grid->id('ID')->sortable();
        $grid->sale_id('Sale id')->sortable();
        $grid->product_id('Product id')->sortable();
        $grid->count('Count')->sortable();
        //$grid->price('Price');
        $grid->created_at('Created at')->sortable();
        $grid->updated_at('Updated at')->sortable();

        $grid->filter(function($filter){
            $filter->equal('sale_id','Sale id')->integer();
            $filter->equal('product_id','Product id')->integer();
            $filter->between('count','Count');
            $filter->between('created_at','Created time')->datetime();
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
        $show = new Show(SaleDetail::findOrFail($id));

        $show->id('ID');
        $show->sale_id('Sale id');
        $show->product_id('Product id');
        $show->count('Count');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new SaleDetail);

        $form->display('id','ID');
        $form->display('sale_id','Sale id');
        $form->display('product_id','Product id');
        $form->display('count','Count');
        $form->display('created_at','Created at');
        $form->display('updated_at','Updated at');

        return $form;
    }
}
