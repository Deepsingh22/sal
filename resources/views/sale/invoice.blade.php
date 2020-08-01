<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="{{url('public/logo', $general_setting->site_logo)}}" />
    <title>{{$general_setting->site_title}}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">

    <style type="text/css">
        * {
            font-size: 11px;
            line-height: 18px;
            font-weight: bold;
            font-family: 'Ubuntu', sans-serif;
            text-transform: capitalize;
        }
        .btn {
            padding: 7px 10px;
            text-decoration: none;
            border: none;
            display: block;
            text-align: center;
            margin: 7px;
            cursor:pointer;
        }

        .btn-info {
            background-color: #999;
            color: #FFF;
        }

        .btn-primary {
            background-color: #6449e7;
            color: #FFF;
            width: 100%;
        }
        td,
        th,
        tr,
        table {
            border: 1px solid black;
            border-collapse: collapse;
            width: 100%;
            text-align: left;
        }
        tr {border-bottom: 1px dotted #ddd;}
        td,th {text-align: center; padding: 10px;}
        table {width: 100%;}
        tfoot tr th:first-child {text-align: left;}

        .centered {
            text-align: center;
            align-content: center;
        }
        small{font-size:11px;}

        @media print {
            * {
                font-size:10px;
                font-weight: bold;
                line-height: 15px;
            }
            td,th {padding: 5px 0;}
            .hidden-print {
                display: none !important;
            }
            @page { margin: 0; } body { margin: 0.5cm; margin-bottom:1.6cm; }
        }
    </style>
  </head>
<body>

<div style="max-width:450px;margin:0 auto">
    @if(preg_match('~[0-9]~', url()->previous()))
        @php $url = '../../pos'; @endphp
    @else
        @php $url = url()->previous(); @endphp
    @endif
    <div class="hidden-print">
        <table1>
            <tr1>
                <td1><a href="{{$url}}" class="btn btn-info"><i class="fa fa-arrow-left"></i> {{trans('file.Back')}}</a> </td1>
                <td1><button onclick="window.print();" class="btn btn-primary"><i class="dripicons-print"></i> {{trans('file.Print')}}</button></td1>
            </tr1>
        </table1>
        <br>
    </div>

    <div id="receipt-data">
        <div class="centered">
            @if($general_setting->site_logo)
                <img src="{{url('public/logo', $general_setting->site_logo)}}" height="42" width="42" style="margin:10px 0;filter: brightness(0);">
            @endif
            <h2>{{$lims_biller_data->company_name}}</h2>
            <p>{{trans('file.Address')}}: {{$lims_warehouse_data->address}}
                <br>{{trans('file.Phone Number')}}: {{$lims_warehouse_data->phone}}
            </p>
        </div>
        <p>{{trans('file.Date')}}: {{$lims_sale_data->created_at}}<br>
            {{trans('file.reference')}}: {{$lims_sale_data->reference_no}}<br>
            <!-- {{trans('file.customer')}}: {{$lims_customer_data->name}} -->
        </p>
        <table>
             <tbody>
               <tr style="background-color:#ddd;"><td style="width:10%">Sr n.</td>
                 <td colspan="2">Product</td>
                  <td>QTY.</td>
                   <td>Price</td>
                    <td>Total Amt.</td>
                    </tr>
             </tbody>
            <tbody>
               
                @foreach($lims_product_sale_data as $key => $product_sale_data)
                @php
                    $lims_product_data = \App\Product::find($product_sale_data->product_id);
                    if($product_sale_data->variant_id) {
                        $variant_data = \App\Variant::find($product_sale_data->variant_id);
                        $product_name = $lims_product_data->name.' ['.$variant_data->name.']';

                    }
                    else    
                        $product_name = $lims_product_data->name;
                @endphp

                <tr>
                  <td  style="width:10%">{{$key+1}}</td>
                    <td colspan="2" style="text-align:left">{{$product_name}}</td>
                    <td style="padding: 5px; width:30% text-align:center">{{$product_sale_data->qty}}</td>
                    <td style="padding: 5px; width:40% text-align:center">{{number_format((float)($product_sale_data->total / $product_sale_data->qty), 2, '.', '')}}</td>
                    <td style="padding: 5px; width:40% text-align:center">{{number_format((float)$product_sale_data->total, 2, '.', '')}}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">{{trans('file.Total')}}</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="text-align:Center width:100%">{{number_format((float)$lims_sale_data->total_price, 2, '.', '')}}</th>
                </tr>
                @if($lims_sale_data->order_tax)
                <tr>
                    <th colspan="2">{{trans('file.Order Tax')}}</th>
                    <th></th>
                    <th></th>
                    <th style="text-align:right">{{number_format((float)$lims_sale_data->order_tax, 2, '.', '')}}</th>
                </tr>
                @endif
                @if($lims_sale_data->order_discount)
                <tr>
                    <th colspan="2">{{trans('file.Order Discount')}}</th>
                    <th></th>
                    <th></th>
                    <th style="text-align:right">{{number_format((float)$lims_sale_data->order_discount, 2, '.', '')}}</th>
                </tr>
                @endif
                @if($lims_sale_data->coupon_discount)
                <tr>
                    <th colspan="2">{{trans('file.Coupon Discount')}}</th>
                    <th></th>
                    <th></th>
                    <th style="text-align:right">{{number_format((float)$lims_sale_data->coupon_discount, 2, '.', '')}}</th>
                </tr>
                @endif
                @if($lims_sale_data->shipping_cost)
                <tr>
                    <th colspan="2">{{trans('file.Shipping Cost')}}</th>
                    <th></th>
                    <th></th>
                    <th style="text-align:right">{{number_format((float)$lims_sale_data->shipping_cost, 2, '.', '')}}</th>
                </tr>
                @endif
                <tr>
                    <th colspan="2">{{trans('file.grand total')}}</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="text-align:center">{{number_format((float)$lims_sale_data->grand_total, 2, '.', '')}}</th>
                </tr>

            </tfoot>
        </table>
        <table>
            <tbody>
                @foreach($lims_payment_data as $payment_data)
                <tr style="background-color:#ddd;">
                    <td style="padding: 5px;width:30%">{{trans('file.Paid By')}}: {{$payment_data->paying_method}}</td>
                    <td style="padding: 5px;width:30%">{{trans('file.Change')}}: {{number_format((float)$payment_data->change, 2, '.', '')}}</td>
                    <td style="padding: 5px;width:40%">{{trans('file.Amount')}}: {{number_format((float)$payment_data->amount, 2, '.', '')}}</td>
                </tr>
                 <tr>
                    @if($general_setting->currency_position == 'prefix')
                    <th class="centered" colspan="3">{{trans('file.In Words')}}: <span>{{$general_setting->currency}}</span> <span>{{str_replace("-"," ",$numberInWords)}}</span></th>
                    @else
                    <th class="centered" colspan="3">{{trans('file.In Words')}}: <span>{{str_replace("-"," ",$numberInWords)}}</span> <span>{{$general_setting->currency}}</span></th>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    function auto_print() {
        window.print()
    }
    setTimeout(auto_print, 1000);
</script>

</body>
</html>
