@extends('v1.layout')
@section('title', 'Orders')

@section('content')
    <div class="row">
        <!-- /.col-lg-12 -->
        <div class="col-lg-12">
            <div class="row">
                <h1 class="page-header">

                    Order History

                    <div class="col-lg-2 pull-right">
                        <a href="{{ route('orders', ["status_code" => Request::segment(2)]) }}" class="btn btn-primary">
                            <i class="glyphicon glyphicon-arrow-left"> </i>
                            Back
                        </a>
                    </div>
                </h1>
            </div>
        </div>
        <!-- /.col-lg-12 -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <ul class="timeline">
                            <li>
                                <div class="timeline-badge success"><i class="fa fa-check"></i>
                                </div>
                                <div class="timeline-panel">
                                    <div class="timeline-heading">
                                        <h4 class="timeline-title">
                                            <a target="_blank" href="{{ $product_url }}">
                                                {{ $product_name }}
                                            </a>
                                            <small class="text-muted pull-right">
                                                <i class="fa fa-clock-o"></i>
                                                {{ $products_created_at }}
                                            </small>
                                        </h4>
                                    </div>
                                    <div class="timeline-body">
                                        <hr>
                                        <p>{{ $product_description }}</p>
                                        <hr>
                                        <p>Selling Price- {{ strtoupper($currency)." ".$selling_price }}</p>
                                        <p>Shipping Charge- {{ strtoupper($currency)." ".$shipping_charge }}</p>
                                        <hr>
                                        <p> Created by- {{ $first_name }}</p>
                                        <p> Seller Mobile- {{ $username }}</p>
                                    </div>
                                </div>
                            </li>
                            @for($i=0; $i < count($order_history); $i++)
                                <li @if(($i % 2) == 0) class="timeline-inverted" @endif >
                                    <div @if(($i % 2) == 0) class="timeline-badge info" @else class="timeline-badge success" @endif >
                                        <i @if(($i % 2) == 0) class="fa fa-thumbs-up" @else class="fa fa-check" @endif></i>
                                    </div>
                                    <div class="timeline-panel">
                                        <div class="timeline-heading">
                                            <h4 class="timeline-title">
                                                {{ $order_history[$i]['status_description'] }}
                                                <small class="text-muted pull-right">
                                                    <i class="fa fa-clock-o"></i>
                                                    {{ $order_history[$i]['created_at'] }}
                                                </small>
                                            </h4>
                                        </div>
                                        @if($order_history[$i]['internal_status_code'] == config('status_codes.order_status_codes.OrderInitiated'))
                                            <div class="timeline-body">
                                                <hr>
                                                <p> Sales Channel- {{ ucwords($providers_name) }}</p>
                                                <p> Order Amount- {{ strtoupper($currency)." ".$order_items_price }} </p>
                                                <p> Ordered by- {{ $customers_name }}</p>
                                                <p> Buyer Mobile- {{ $customers_contact_number }}</p>
                                                <p> Buyer Email- {{ $customers_email_address }}</p>
                                            </div>
                                        @elseif($order_history[$i]['internal_status_code'] == config('status_codes.order_status_codes.PaymentCompleted'))
                                            <div class="timeline-body">
                                                <hr>
                                                <p> Payment Reference ID- {{ $payment_ref_id }}</p>
                                                <p> Payment Mode- {{ $payment_mode }}</p>
                                                <p> Total Amount Paid- {{ $total_amount_paid }}</p>
                                            </div>
                                        @elseif($order_history[$i]['internal_status_code'] == config('status_codes.order_status_codes.OrderShipped'))
                                            <div class="timeline-body">
                                                <hr>
                                                <p>Shipped To-
                                                    <p> {{
                                                        strToUpper($shipping_address
                                                        .", "
                                                        .$shipping_city
                                                        .", "
                                                        .$shipping_state
                                                        .", "
                                                        .$shipping_country
                                                        .", "
                                                        .$shipping_pincode
                                                        ." -- "
                                                        .$shipping_landmark)
                                                        }}
                                                    </p>
                                                </p>
                                                <hr>
                                                <p> Shipping Service Used- {{ $shipping_service }} </p>
                                                <p> Tracking Number- {{ $tracking_number }} </p>
                                                <p> Seller Comments- {{ $comments }} </p>
                                            </div>
                                        @endif
                                    </div>
                                </li>
                            @endfor
                        </ul>
                    </div>
                    <!-- /.panel-body -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function()
	{
            $('#payments_orders').addClass('active');
        });
    </script>
@endpush