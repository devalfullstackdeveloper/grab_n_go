@extends('layouts.layout')
   
@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Banner Show</h1>

            <div class="pull-right">
                <a href="{{route('banner.list')}}" class="btn btn-primary btn-icon-split">
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
                        <h6 class="m-0 font-weight-bold text-primary">Banner Show</h6>
                    </div>
                    <div class="card-body">
                        <div>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td><b>Banner Name</b></td>
                                        <td>{{ $banner_data->banner_name }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Banner Image</b></td>
                                        <td><img src="{{asset($banner_data->banner_image)}}" alt="{{ $banner_data->banner_name}}" style="width: 100px;"></td>
                                    </tr>
                                    <tr>
                                        <td><b>Banner Offer Type</b></td>
                                        <td>
                                        {{ $banner_data->banner_offer_type }}                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection