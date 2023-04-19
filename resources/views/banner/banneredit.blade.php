@extends('layouts.layout')

@section('content')
<section>
    <div class="container-fluid">
        <div class="pull-right">
            <a href="{{route('banner.list')}}" class="btn btn-primary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fa fa-arrow-left"></i>
                </span>
                <span class="text">Back</span>
            </a>
        </div>
        @if($errors->any())
        <div class="alert alert-danger" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>

            @foreach($errors->all() as $error)
            {{ $error }}<br/>
            @endforeach
        </div>
        @endif
        @php 
        $mastercategory_id = $bannerMasterData[0]['mastercategory_id'];
        $maincategory_id = $bannerMasterData[0]['maincategory_id'];
        $category_id = $bannerMasterData[0]['category_id'];
        $subcategory_id = $bannerMasterData[0]['subcategory_id'];
        @endphp
        <form method="POST" action="{{ route('banner.update')}}" enctype="multipart/form-data">
           @csrf
           <input type="hidden" name="id" value="{{$bannerMasterData[0]['id']}}">
           <div class="row">
            <div class="col-lg-12">
                <!-- Overflow Hidden -->
                <div class="card mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Edit Banner</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Banner Title</label>
                                    <input type="text" class="form-control" name="banner_name"  value="{{ $bannerMasterData[0]['banner_name'] }}" placeholder="Enter banner title">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Banner Image</label>
                                    <input type="file" class="form-control" name="banner_image" value="{{ isset($bannerMasterData[0]['banner_image']) ? $bannerMasterData[0]['banner_image'] : ''}}">
                                    <input type="hidden" id="product_image_old" name="product_image_old" value="{{ $bannerMasterData[0]['banner_image']}}" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Banner Offer Type</label>
                                    <input type="text" class="form-control" name="banner_offer_type" value="{{ $bannerMasterData[0]['banner_offer_type']}}" placeholder="Enter banner offer type">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">status</label>
                                    <select class="custom-select" name="status">
                                        <option {{ $bannerMasterData[0]['status'] =='1' ? 'selected' : '' }}  value="1">Available</option>
                                        <option {{ $bannerMasterData[0]['status'] =='2' ? 'selected' : '' }}  value="2">Unavailable</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Master Category</label>
                                    <select class="custom-select" name="mastercategory_id">
                                        <option value="">Select mastercategory here</option>
                                      @foreach($masterCategoryData as $key => $value)
                                          @if(in_array($value['id'],$mastercategory_id))
                                              <option value="{{$value['id']}}" selected>{{$value['master_category_name']}}</option>
                                          @else
                                              <option value="{{$value['id']}}" >{{$value['master_category_name']}}</option>
                                          @endif
                                      @endforeach

                                  </select>
                              </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Main Category</label>
                                <select class="custom-select" name="maincategory_id">
                                    <option value="">Select maincategory here</option>
                                    @foreach($bannerMainCategoryData as $key => $value)
                                    
                                          @if(in_array($value['maincategory_id'],$maincategory_id))
                                              <option value="{{$value['maincategory_id']}}" selected>{{$value['main_category_name']}}</option>
                                          @else
                                              <option value="{{$value['maincategory_id']}}" >{{$value['main_category_name']}}</option>
                                          @endif 
                                      @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Category</label>
                                <select class="custom-select" name="category_id">
                                    <option value="">Select category here</option>
                                    @foreach($bannerCategoryData as $key => $value)

                                          @if(in_array($value['category_id'],$category_id))
                                              <option value="{{$value['category_id']}}" selected>{{$value['category_name']}}</option>
                                          @else
                                              <option value="{{$value['category_id']}}" >{{$value['category_name']}}</option>
                                          @endif 
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Sub Category</label>
                                <select class="custom-select" name="subcategory_id">
                                    <option value="">Select sub category here</option>
                                    @foreach($bannerSubCategoryData as $key => $value)

                                          @if(in_array($value['subcategory_id'],$subcategory_id))
                                              <option value="{{$value['subcategory_id']}}" selected>{{$value['sub_category_name']}}</option>
                                          @else
                                              <option value="{{$value['subcategory_id']}}" >{{$value['sub_category_name']}}</option>
                                          @endif 
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                                    <!--  <div class="form-group">
                                        <label for="exampleInputEmail1">Banner Title</label>
                                        <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter banner title">
                                    </div> -->
                                </div>
                                <div class="col-md-6">
                                    <div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

@endsection

<script src="{{asset('/public/site/js/jquery/jquery.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var url = {!! json_encode(url('/')) !!};

        $('select[name="mastercategory_id"]').on('change', function() {
            var masterID = $(this).val();
            if(masterID) {
                //console.log(masterID);
                $.ajax({
                    url: url+'/maincategorydropdown/'+ masterID,
                    type: "GET",
                    dataType: "json",
                    success:function(tb1) {

                        $('select[name="maincategory_id"]').empty();
                        $('select[name="category_id"]').empty();
                        $('select[name="subcategory_id"]').empty();
                        $('select[name="maincategory_id"]').append('<option value="">Select maincategory here</option>');
                        $('select[name="category_id"]').append('<option value="">Select category here</option>');
                        $('select[name="subcategory_id"]').append('<option value="">Select sub category here</option>');
                        $.each(tb1, function(key, value) {
                            $('select[name="maincategory_id"]').append('<option value="'+ value.id +'">'+ value.main_category_name +'</option>');
                        });
                    },
                    error: function (textStatus, errorThrown) {
                        //console.log(errorThrown);
                        // Success = false;//doesn't go here
                    }
                });
            }else{
                $('select[name="maincategory_id"]').empty();
            }
        });
        $('select[name="maincategory_id"]').on('change', function() {
            var mainID = $(this).val();

            if(mainID) {
                //console.log(mainID);
                $.ajax({
                    url: url+'/categorydropdown/'+ mainID,
                    type: "GET",
                    dataType: "json",
                    success:function(tb2) {

                        $('select[name="category_id"]').empty();
                        $('select[name="subcategory_id"]').empty();
                        $('select[name="category_id"]').append('<option value="">Select category here</option>');
                        $('select[name="subcategory_id"]').append('<option value="">Select sub category here</option>');
                        $.each(tb2, function(key, value) {
                            console.log(value);
                            $('select[name="category_id"]').append('<option value="'+ value.id +'">'+ value.category_name +'</option>');
                        });
                    },
                    error: function (textStatus, errorThrown) {
                        //console.log(errorThrown);
                        // Success = false;//doesn't go here
                    }
                });
            }else{
                $('select[name="category_id"]').empty();
            }
        });
        $('select[name="category_id"]').on('change', function() {
            var catID = $(this).val();

            if(catID) {
                //console.log(catID);
                $.ajax({
                    url: url+'/subcategorydropdown/'+ catID,
                    type: "GET",
                    dataType: "json",
                    success:function(tb3) {
                        $('select[name="subcategory_id"]').empty();
                        $('select[name="subcategory_id"]').append('<option value="">Select sub category here</option>');

                        $.each(tb3, function(key, value) {
                            console.log(value);
                            $('select[name="subcategory_id"]').append('<option value="'+ value.id +'">'+ value.sub_category_name +'</option>');
                        });
                    },
                    error: function (textStatus, errorThrown) {
                        //console.log(errorThrown);
                        // Success = false;//doesn't go here
                    }
                });
            }else{
                $('select[name="subcategory_id"]').empty();
            }
        });
    });
</script>

