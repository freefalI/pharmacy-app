<?php

namespace App\Admin\Controllers;

use App\Product;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ProductController extends Controller
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
        $grid = new Grid(new Product);

        $grid->id('ID')->sortable();
        $grid->name('Name');
        $grid->category('Category')->sortable();
        $grid->description('Description');
        $grid->producer('Producer')->sortable();
        $grid->price('Price')->sortable();
        $grid->created_at('Created at')->sortable();
        $grid->updated_at('Updated at')->sortable();
        
        $grid->filter(function($filter){
            $filter->like('name','Name');
            $filter->like('category','Category');
            $filter->like('description','Description');
            $filter->like('producer','Producer');
            $filter->between('price','Price');
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
        $show = new Show(Product::findOrFail($id));

        $show->id('ID');
        $show->name('Name');
        $show->category('Category');
        $show->description('Description');
        $show->producer('Producer');
        $show->price('Price');
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
        $form = new Form(new Product);

        $form->display('id','ID');

        $form->text('name','Name');
        $form->text('category','Category');
        $form->textarea('description','Description');
        $form->text('producer','Producer');
        $form->number('price','Price');


        $form->display('created_at','Created at');
        $form->display('updated_at','Updated at');

        return $form;
    }
}
