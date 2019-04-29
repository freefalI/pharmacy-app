<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Product;
use App\Sale;
use App\SaleDetail;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    use HasResourceActions;

    public function createOrder(Content $content)
    {
        return $content
            ->header('Create order')
            ->description('description')
            ->body(view('order_form'));
    }

    public function computeOrder(Content $content, Request $request)
    {
        $product_ids = $request->input('product_id');
        $product_counts = $request->input('product_count');
        // print_r($product_ids);
        // print_r($product_counts);
        $invalid=False;
        if (count($product_ids) != count($product_counts)) {
            $invalid = true;
        }
        /*
        for ($i = 0; $i < count($product_ids); $i++) {
            if (!(is_float($product_ids[$i]) || is_int($product_ids[$i]))) {
                $invalid = true;
            }
            if (!(is_float($product_counts[$i]) || is_int($product_counts[$i]))) {
                $invalid = true;
            }
        }*/
        if ($invalid) {
            return $content
                ->header('Create order')
                ->description('description')
                ->body("<h1> invalid input</h1>");
        }

        $request->session()->forget('order');
        $request->session()->forget('order_total');
        // $request->session()->forget('order_id');

        $html = '';
        $total = 0;
        for ($i = 0; $i < count($product_ids); $i++) {

            $product = Product::find($product_ids[$i]);

            $price = $product->price;
            $count = $product_counts[$i];
            $amount = $price * $count;
            $total += $amount;
            $html .= $product->name . " " . $count . 'шт X ' . $price . " = " . $amount . '<br>';

            $request->session()->push('order', [$product->id, $count, $price]);

        }
        $html .= "<h3>До оплати: " . $total . "</h3>";

        $request->session()->push('order_total', $total);
        //$request->session()->push('order_id', $product->id);

        $html .= view('perform_order');

        # code...

        return $content
            ->header('До оплати')
            ->description('description')
            ->body($html);

        //return redirect('admin/tables/medicines');
    }

    public function storeOrder(Content $content, Request $request)
    {
        $sale = new Sale;

        $sale->amount = $request->session()->get('order_total')[0];
        $sale->save();
        //dd($sale);
        $sale_details = $request->session()->get('order');
        foreach ($sale_details as $key => $sal) {
            $sale_detail = new SaleDetail();
            $sale_detail->product_id = $sal[0];
            $sale_detail->sale_id = $sale->id;
            $sale_detail->count = $sal[1];
            $sale_detail->price = $sal[2];
            $sale_detail->save();
            //dump($sale_detail);
        }
        // dd( $request->session()->pull('medicine'));
        //dd( $request->session()->all());

        return redirect('admin/tables/sales');
    }

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
        $grid = new Grid(new Sale);
        $grid->id('ID')->sortable();
        $grid->amount('Amount')->sortable();
        $grid->created_at('Created at')->sortable();
        $grid->updated_at('Updated at')->sortable();
        $grid->filter(function($filter){
            $filter->between('amount','Amount');
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
        $show = new Show(Sale::findOrFail($id));

        $show->id('ID');
        $show->amount('Amount');
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
        $form = new Form(new Sale);

        $form->display('id', 'ID');
        $form->text('amount', 'Amount');
        $form->display('created_at', 'Created at');
        $form->display('updated_at', 'Updated at');
        return $form;
    }
}
