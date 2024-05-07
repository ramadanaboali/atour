<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8"><title>طلب جديد</title>
    </head>
    <body>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        
                        <div class="mb-1 col-md-4">
                            <label class="form-label">{{ __('orders.status') }}</label>
                            <input disabled class="form-control" type="text" value="{{ __('orders.statuses.' . $item->status) }}">
                        </div>
                        <div class="mb-1 col-md-4">
                            <label class="form-label">{{ __('orders.address') }}</label>
                            <input disabled class="form-control" type="text" value="{{ $item->address }}">
                        </div>
                        <div class="mb-1 col-md-4">
                            <label class="form-label">{{ __('orders.phone') }}</label>
                            <input disabled class="form-control" type="text" value="{{ $item->phone }}">
                        </div>
                        <div class="mb-1 col-md-4">
                            <label class="form-label">{{ __('orders.delivery_phone') }}</label>
                            <input disabled class="form-control" type="text" value="{{ $item->delivery_phone }}">
                        </div>
                        <div class="mb-1 col-md-4">
                            <label class="form-label">{{ __('orders.area') }}</label>
                            <input disabled class="form-control" type="text" value="{{ $item->area }}">
                        </div>

                        <div class="mb-1 col-md-4">
                            <label class="form-label">{{ __('orders.delivery_date') }}</label>
                            <input disabled class="form-control" type="text" value="{{ $item->delivery_date }}">
                        </div>

                        <div class="mb-1 col-md-4">
                            <label class="form-label">{{ __('orders.payment') }}</label>
                            <input disabled class="form-control" type="text" value="{{ __('orders.payments.'.$item->payment) }}">
                        </div>
                        @if($item->type==2)
                        <div class="mb-1 col-md-4">
                            <label class="form-label">{{ __('orders.guarantee_amount') }}</label>
                            <input disabled class="form-control" type="text" value="{{ $item->guarantee_amount }}">
                        </div>
                       @endif
                        <div class="mb-1 col-md-4">
                            <label class="form-label">{{ __('orders.user') }}</label>
                            <input disabled class="form-control" type="text" value="{{ $item->user?->name }}">
                        </div>
                        <div class="mb-1 col-md-4">
                            <label class="form-label">{{ __('orders.company') }}</label>
                            <input disabled class="form-control" type="text" value="{{ $item->company?->name }}">
                        </div>

                         <div class="mb-1 col-md-4">
                            <label class="form-label">{{ __('orders.localtion') }}</label>


                            <a href="http://maps.google.com/?q={{ $item->lat }},{{ $item->long }}" class="btn btn-sm btn-outline-primary me-1 waves-effect form-control">الذهاب الى الموقع</a>
                        </div>
                        <div class="mb-1 col-md-4">
                            <label class="form-label">{{ __('orders.type') }}</label>
                            <input disabled class="form-control" type="text" value="{{ __('orders.types.' . $item->type) }}">
                        </div>

                        <div class="mb-1 col-md-4">
                            <label class="form-label">{{ __('orders.created_at') }}</label>
                            <input disabled class="form-control" type="text" value="{{ $item->created_at }}">
                        </div>
                    </div>
                    <div class="row">
                        <br>
                         <h4 class="text-center">{{ __('orders.details') }}</h4>

                         <table class="table">
                             @if ($item->type==1)
                            <tr>
                             <th>{{ __('products.plural') }}</th>
                             <th >{{ __('products.qty') }}</th>
                             <th>{{ __('products.price') }}</th>
                            </tr>
                            @else
                            <tr>
                             <th>{{ __('products.plural') }}</th>
                             <th>{{ __('products.attributes.1') }}</th>
                             <th>{{ __('products.attributes.2') }}</th>
                             <th>{{ __('products.attributes.3') }}</th>
                             <th>{{ __('products.rent_price') }}</th>
                            </tr>
                            @endif
                            @if ($item->type==1)
                            @foreach ($item->details as $product)
                            <tr>
                                <td>{{ $item->productDetails($product['id'])?->name }}</td>
                                <td >{{ $product['qty'] }}</td>
                                <td >{{ $product['price']??'' }}</td>
                            </tr>
                            @endforeach
                            @else
                            @foreach ($item->details as $product)
                            <tr>
                                <td>{{ $item->productDetails($product['id'])?->name }}</td>
                                <td>{{ $product['attribute_1'] }}</td>
                                <td>{{ $product['attribute_2'] }}</td>
                                <td>{{ $product['attribute_3'] }}</td>
                                <td>{{ $product['price']??'' }}</td>
                            </tr>
                            @endforeach
                            @endif
                        </table>
                    </div>
                    @if($item->type==2)
                    <div class="row">
                        <div class="mb-1 col-md-4">
                            <label class="form-label">{{ __('orders.attachment1') }}</label>
                            @if(pathinfo($item->attachmentpayment1, PATHINFO_EXTENSION)=='pdf')
                            <br>
                                <a href="{{ $item->attachmentpayment1 }}" download>
                                <img src="{{ asset('default.jpg') }}" class="img-fluid img-thumbnail">
                                </a>
                                @else
                                <a href="{{ $item->attachmentpayment1 }}" download>
                                <img src="{{ $item->attachmentpayment1 }}" class="img-fluid img-thumbnail">
                                </a>
                            @endif
                        </div>
                        <div class="mb-1 col-md-4">
                            <label class="form-label">{{ __('orders.attachment2') }}</label>
                            @if(pathinfo($item->attachmentpayment2, PATHINFO_EXTENSION)=='pdf')
                            <br>
                                <a href="{{ $item->attachmentpayment2 }}" download>
                                <img src="{{ asset('default.jpg') }}" class="img-fluid img-thumbnail">
                                </a>
                                @else
                                <a href="{{ $item->attachmentpayment2 }}" download>
                                <img src="{{ $item->attachmentpayment2 }}" class="img-fluid img-thumbnail">
                                </a>
                            @endif
                        </div>
                        <div class="mb-1 col-md-4">
                            <label class="form-label">{{ __('orders.check_guarantee') }}</label>
                            @if(pathinfo($item->checkamount, PATHINFO_EXTENSION)=='pdf')
                            <br>
                                <a href="{{ $item->checkamount }}" download>
                                <img src="{{ asset('default.jpg') }}" class="img-fluid img-thumbnail">
                                </a>
                                @else
                                <a href="{{ $item->checkamount }}" download>
                                <img src="{{ $item->checkamount }}" class="img-fluid img-thumbnail">
                                </a>
                            @endif
                        </div>
                        <div class="mb-1 col-md-4">
                            <label class="form-label">{{ __('orders.check_guarantee_amount') }}</label>
                            @if(pathinfo($item->checkguaranteeamount, PATHINFO_EXTENSION)=='pdf')
                            <br>
                            <a href="{{ $item->checkguaranteeamount }}" download>
                                <img src="{{ asset('default.jpg') }}" class="img-fluid img-thumbnail">
                            </a>
                            @else
                            <a href="{{ $item->checkguaranteeamount }}" download>
                            <img src="{{ $item->checkguaranteeamount }}" class="img-fluid img-thumbnail">
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif
                    @if($item->type==1)
                    <div class="row">

                        <div class="mb-1 col-md-4">
                            <label class="form-label">{{ __('orders.check_amount') }}</label>
                            @if(pathinfo($item->checkamount, PATHINFO_EXTENSION)=='pdf')
                            <br>
                                <a href="{{ $item->checkamount }}" download>
                                <img src="{{ asset('default.jpg') }}" class="img-fluid img-thumbnail">
                                </a>
                                @else
                                <a href="{{ $item->checkamount }}" download>
                                <img src="{{ $item->checkamount }}" class="img-fluid img-thumbnail">
                                </a>
                            @endif
                        </div>

                    </div>
                    @endif

                </div>
            </div>
    </body>
</html>
