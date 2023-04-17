@extends('layouts.layout')

@section('content')
<section>
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Product Show</h1>

            <div class="pull-right">
                <a href="{{route('product.list')}}" class="btn btn-primary btn-icon-split">
                    <span class="icon text-white-50">
                        <i class="fa fa-arrow-left" style="font-size:24px"></i>
                    </span>
                    <span class="text">Back</span>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <!-- Overflow Hidden -->
                <div class="card mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Product Show</h6>
                    </div>
                    <div class="card-body">
                        <div>
                            <table class="table table-bordered">
                                <tbody>
                                     <tr>
                                        <td><b>Title</b></td>
                                        <td>{{ isset($data['product_name']) ? $data['product_name'] : ''}}</td>
                                    </tr>
                                     <tr>
                                        <td><b>Product Image</b></td>
                                        <td>@if(isset($data['product_image']))

                                            @foreach($data['product_image'] as $img)
                                                    <img src="{{asset($img)}}" alt="" style="width: 150px;height: 150px;">@endforeach
                                                @endif</td>
                                    </tr>
                                    <tr>
                                        <td><b>Product Details</b></td>
                                        <td>{{ isset($data['product_details']) ? $data['product_details'] : ''}}</td>
                                    </tr>
                                     <tr>
                                        <td><b>Product Price</b></td>
                                        <td>{{ isset($data['product_price']) ? $data['product_price'] : ''}}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Master Category Title</b></td>
                                        <td>{{ isset($data['master_category_name']) ? $data['master_category_name'] : ''}}</td>
                                    </tr>
                                     <tr>
                                        <td><b>Main Category Title</b></td>
                                        <td>{{ isset($data['main_category_name']) ? $data['main_category_name'] : ''}}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Category Title</b></td>
                                        <td>{{ ($data['category_name']!='') ? $data['category_name'] : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Sub Category Title</b></td>
                                        <td>{{ ($data['sub_category_name']!='') ? $data['sub_category_name'] : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Quantity</b></td>
                                        <td>{{ isset($data['quantity']) ? $data['quantity'] : ''}}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Points</b></td>
                                        <td>{{ isset($data['point']) ? $data['point'] : ''}}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Sale</b></td>
                                        <td>{{ isset($data['sale']) ? $data['sale'] : ''}}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Sale Price</b></td>
                                        <td>{{ ($data['sale_price']!='') ? $data['sale_price'] : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Packet</b></td>
                                        <td>{{ ($data['packet']!='') ? $data['packet'] : '-' }}</td>
                                    </tr> 
                                    <tr>
                                        <td><b>Lat</b></td>
                                        <td>{{ isset($data['lat']) ? $data['lat'] : ''}}</td>
                                    </tr> 
                                    <tr>
                                        <td><b>Long</b></td>
                                        <td>{{ isset($data['long']) ? $data['long'] : ''}}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Status</b></td>
                                        <td>
                                            @if(isset($data['status']) == 1) Available @else Unvailable @endif
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    @endsection
