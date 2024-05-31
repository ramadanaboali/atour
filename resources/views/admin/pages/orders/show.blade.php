@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('orders.plural') }}</title>
@endsection
@section('content')
 <div class="content-header row">
    </div>
    <div class="content-body">
        <section class="invoice-preview-wrapper">
            <div class="row invoice-preview">
                <!-- Invoice -->
                <div class="col-xl-12 col-md-12 col-12">
                    <div class="card invoice-preview-card">
                        <div class="card-body invoice-padding pb-0">
                            <!-- Header starts -->
                            <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                                <div>
                                    <div class="logo-wrapper">
                                        <img src="{{  asset('storage/settings/' .general_setting('header_logo')) }}" alt="" height="70" width="70">
                                    </div>
                                    <p class="card-text mb-25"> &nbsp;</p>
                                    <p class="card-text mb-25 text-center">{{ config('app.name') }}</p>
                                </div>
                                <div class="mt-md-0 mt-2">
                                    <h4 class="invoice-title">
                                        {{ __('orders.code') }}
                                        <span class="invoice-number">#{{ $item->code }}</span>
                                    </h4>
                                    <div class="invoice-date-wrapper d-flex">
                                        <p class="invoice-date-title">{{ __('orders.created_at') }} : </p>
                                        <p class="invoice-date">{{ $item->created_at }}</p>
                                    </div>
                                    <div class="invoice-date-wrapper d-flex">
                                        <p class="invoice-date-title">{{ __('orders.order_date') }}:</p>
                                        <p class="invoice-date">{{ $item->order_date }}</p>
                                    </div>
                                    <div class="invoice-date-wrapper d-flex">
                                        <p class="invoice-date-title">{{ __('orders.order_status') }} : </p>
                                        <p class="invoice-date">{{ __('orders.statuses.'.$item->status) }}</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Header ends -->
                        </div>

                        <hr class="invoice-spacing" />

                        <!-- Address and Contact starts -->
                        <div class="card-body invoice-padding pt-0">
                            <div class="row invoice-spacing">
                                <div class="col-xl-8 p-0">
                                    <h6 class="mb-2">{{ __('orders.client') }}:</h6>
                                    <div class="d-flex">

                                        <h6 class="mb-25">{{ __('orders.client_name') }} : </h6>
                                        <p class="card-text mb-25">{{ $item->client?->name }}</p>
                                    </div>
                                    <div class="d-flex">

                                        <h6 class="mb-25">{{ __('orders.phone') }} : </h6>
                                        <p class="card-text mb-25">{{ $item->client?->phone }}</p>
                                    </div>
                                    <div class="d-flex">

                                        <h6 class="mb-25">{{ __('orders.email') }} : </h6>
                                        <p class="card-text mb-25">{{ $item->client?->email }}</p>
                                    </div>

                                </div>
                                <div class="col-xl-4 p-0 mt-xl-0 mt-2">
                                    <h6 class="mb-2">{{ __('orders.vendor') }}:</h6>
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td class="pe-1">{{ __('orders.client_name') }}:</td>
                                                <td><span class="fw-bold">{{ $item->trip?->vendor?->user?->name }}</span></td>
                                            </tr>
                                            <tr>
                                                <td class="pe-1">{{ __('orders.phone') }}:</td>
                                                <td>{{ $item->trip?->vendor?->user?->phone }}</td>
                                            </tr>
                                            <tr>
                                                <td class="pe-1">{{ __('orders.email') }}:</td>
                                                <td>{{ $item->trip?->vendor?->user?->email }}</td>
                                            </tr>
                                            <tr>
                                                <td class="pe-1">{{ __('suppliers.url') }}:</td>
                                                <td>{{ $item->trip?->vendor?->url }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Address and Contact ends -->

                        <!-- Invoice Description starts -->
                        <div class="table-responsive">
                            <h3>{{ __('orders.order_details') }}</h3>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="py-1">{{ __('orders.members') }}</th>
                                        <th class="py-1">{{ __('orders.booking_date') }}</th>
                                        <th class="py-1">{{ __('orders.meeting_place') }}</th>
                                        <th class="py-1">{{ __('orders.client_name') }}</th>
                                        <th class="py-1">{{ __('orders.phone') }}</th>
                                        <th class="py-1">{{ __('orders.clients_data') }}</th>
                                        <th class="py-1">{{ __('orders.language') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="py-1">
                                            <p class="card-text fw-bold mb-25">{{ $item->members }}</p>
                                        </td>
                                        <td class="py-1">
                                            <span class="fw-bold">{{ $item->order_date }}</span>
                                        </td>
                                        <td class="py-1">
                                            <span class="fw-bold">{{ $item->trip?->start_point }}</span>
                                        </td>
                                        <td class="py-1">
                                            <span class="fw-bold">{{ $item->trip?->title }}</span>
                                        </td>
                                        <td class="py-1">
                                            <span class="fw-bold">{{ $item->trip?->phone }}</span>
                                        </td>
                                        <td class="py-1">
                                            <span class="fw-bold"></span>
                                        </td>
                                        <td class="py-1">
                                            <span class="fw-bold"></span>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <!-- Invoice Description starts -->
                        <div class="table-responsive">
                            <h3>{{ __('orders.trip') }}</h3>

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="py-1">{{ __('orders.client_name') }}</th>
                                        <th class="py-1">{{ __('orders.description') }}</th>
                                        <th class="py-1">{{ __('orders.total') }}</th>
                                        <th class="py-1">{{ __('orders.phone') }}</th>
                                        <th class="py-1">{{ __('orders.start_point') }}</th>
                                        <th class="py-1">{{ __('orders.end_point') }}</th>
                                        <th class="py-1">{{ __('orders.cancelation_policy') }}</th>
                                        <th class="py-1">{{ __('orders.free_cancelation') }}</th>
                                        <th class="py-1">{{ __('orders.pay_later') }}</th>
                                        <th class="py-1">{{ __('orders.vendor') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>

                                        <td class="py-1">
                                            <span class="fw-bold">{{ $item->trip?->title }}</span>
                                            <span class="fw-bold">{{ $item->trip?->description }}</span>
                                            <span class="fw-bold">{{ $item->trip?->price }}</span>
                                            <span class="fw-bold">{{ $item->trip?->phone }}</span>
                                            <span class="fw-bold">{{ $item->trip?->start_point }}</span>
                                            <span class="fw-bold">{{ $item->trip?->end_point }}</span>
                                            <span class="fw-bold">{{ $item->trip?->cancelation_policy }}</span>
                                            <span class="fw-bold">{{ $item->trip?->free_cancelation }}</span>
                                            <span class="fw-bold">{{ $item->trip?->pay_later }}</span>
                                            <span class="fw-bold">{{ $item->trip?->vendor?->user?->name }}</span>
                                        </td>

                                    </tr>

                                </tbody>
                            </table>
                        </div>


                    </div>
                </div>
                <!-- /Invoice -->

            </div>
        </section>


    </div>
@stop

@push('scripts')

@endpush
