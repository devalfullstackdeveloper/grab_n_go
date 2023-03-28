@extends('layouts.layout')

@section('content')
<section>
	<div class="container-fluid">
		<div>
			<div class="pull-right">
				<a href="{{route('product.list')}}" class="btn btn-primary btn-icon-split">
					<span class="icon text-white-50">
						<i class="fa fa-arrow-left" style="font-size:24px"></i>
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

<?php
									

$master_category_id = $data['master_category_id'];
$main_category_id = $data['main_category_id'];
$category_id = $data['category_id'];
$subcategory_id = $data['subcategory_id'];
?>
			
			<form action="{{route('product.update')}}" method="POST" enctype="multipart/form-data">
				@csrf
				<input type="hidden" name="id" value="{{$data['product_id']}}">
				<div class="row">
					<div class="col-lg-12">
						<!-- Overflow Hidden -->
						<div class="card mb-4">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary">Edit Product</h6>
							</div>

							<div class="card-body">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Product Title</label>
											<input type="text" class="form-control" name="product_name" placeholder="Enter product title" value="{{$data['product_name']}}">
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Product Image</label>
											<input type="file" class="form-control" name="product_image[]" multiple value="{{old('product_image')}}">
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
  											<label for="exampleFormControlTextarea3">Product Details</label>
  											<textarea class="form-control" id="exampleFormControlTextarea3" name="product_details" placeholder="Enter product details title" value="{{$data['product_details']}}" rows="7">{{$data['product_details']}}</textarea>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Product Price</label>
											<input type="text" class="form-control" name="product_price" placeholder="Enter product price title" value="{{$data['product_price']}}">
										</div>
									</div>


									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Master Category</label>
											<select class="custom-select" name="mastercategory_id[]" multiple="">
												<option value="0">Choose...</option>
												@foreach($masterCategoryData as $keys => $masterCategoryName)

													@if(in_array($masterCategoryName['id'],$master_category_id))
													<option value="{{$masterCategoryName['id']}}" selected>{{$masterCategoryName['master_category_name']}}</option>
													@else
													<option value="{{$masterCategoryName['id']}}" >{{$masterCategoryName['master_category_name']}}</option>
													@endif
												
												@endforeach

											</select>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Main Category</label>
											<select class="custom-select" name="maincategory_id[]" multiple="">
												<option value="0">Choose...</option>
												@foreach($mainCategoryData as $keys => $MainCategoryName)


												@if(in_array($MainCategoryName['id'],$main_category_id))
												<option value="{{$MainCategoryName['id']}}" selected>{{$MainCategoryName['main_category_name']}}</option>
												@else
												<option value="{{$MainCategoryName['id']}}" >{{$MainCategoryName['main_category_name']}}</option>

												@endif
												
												@endforeach

											</select>
										</div>
									</div>


									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Category</label>
											<select class="custom-select" name="category_id[]" multiple="">
												<option value="0">Choose...</option>
												@foreach($CategoryData as $keys => $CategoryName)


												@if(in_array($CategoryName['id'],$category_id))
												<option value="{{$CategoryName['id']}}" selected>{{$CategoryName['category_name']}}</option>
												@else
												<option value="{{$CategoryName['id']}}" >{{$CategoryName['category_name']}}</option>

												@endif
												
												@endforeach

											</select>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Sub Category</label>
											<select class="custom-select" name="subcategory_id[]" multiple="">
												<option value="0">Choose...</option>
												@foreach($SubCategoryData as $keys => $SubCategoryName)


												@if(in_array($SubCategoryName['id'],$subcategory_id))
												<option value="{{$SubCategoryName['id']}}" selected>{{$SubCategoryName['sub_category_name']}}</option>
												@else
												<option value="{{$SubCategoryName['id']}}" >{{$SubCategoryName['sub_category_name']}}</option>

												@endif
												
												@endforeach

											</select>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Quantity</label>
											<input type="number" min="1" max="100" class="form-control" name="quantity" placeholder="Enter product quantity title" value="{{$data['quantity']}}">
											
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Points</label>
											<input type="text" class="form-control" name="point" placeholder="Enter product point title" value="{{$data['point']}}">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Sale</label>
											<select class="custom-select" name="sale">
												<option value="1"  @if($data['sale'] == 'Yes') @endif>Yes</option>
												<option value="2"  @if($data['sale'] == 'No') selected @endif>No</option>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Sale Price</label>
											<input type="text" class="form-control" name="sale_price" placeholder="Enter sale price title" value="{{$data['sale_price']}}">
										</div>
									</div>
									
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Status</label>
											<select class="custom-select" name="status">
												<option selected>Choose...</option>
												<option value="1" @if($data['status'] == '1') selected @endif>Available</option>
												<option value="2" @if($data['status'] == '2') selected @endif>Unavailable</option>
											</select>
										</div>
									</div>
									<div class="col-md-6">
	                           		 <div class="form-group add-location">
									    <label for="exampleInputEmail1">Lat Long</label>
									     <div class="control-form">
									     	@if(count($data['latlong']) == 0)
											<div class="entry input-group">
										      <input type="text" class="form-control" name="lat[]" placeholder="Enter lat">
										       <input type="text" class="form-control" name="long[]" placeholder="Enter long">
										      <span class="input-group-btn">
										        <button class="btn btn-success btn-add" type="button">
									         <i class="fa fa-plus" aria-hidden="true"></i>
									        </button>
										      </span>
										    </div>
											@else
											@if($data['latlong'] != '')
												@foreach($data['latlong'] as $keys => $latLong)
												<?php
												echo $latLong;
												?>
												@endforeach
											@endif
											<div class="entry input-group">
										      <input type="text" class="form-control" name="lat[]" placeholder="Enter lat">
										       <input type="text" class="form-control" name="long[]" placeholder="Enter long">
										      <span class="input-group-btn">
										        <button class="btn btn-success btn-add" type="button">
									         <i class="fa fa-plus" aria-hidden="true"></i>
									        </button>
										      </span>
										    </div>
										    @endif
									  	</div>
										</div>
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
	</div>
</section>

<script src="{{asset('/public/site/js/jquery/jquery.min.js')}}"></script>


<script type="text/javascript">
	$('.add-location').on('click','.btn-add', function(e){
  e.preventDefault();
  var controlForm = $('.control-form'),
      currentEntry = $(this).closest('.entry'),
      newEntry = $(currentEntry.clone()).appendTo(controlForm);
  
  newEntry.find('input').val('');
  controlForm.find('.entry:not(:last) .btn-add')
    .removeClass('btn-add').addClass('btn-remove')
    .removeClass('btn-success').addClass('btn-default')
    .html('<i class="fa fa-minus" aria-hidden="true"></i>');
}).on('click', '.btn-remove', function(e){
		$(this).parents('.entry:first').remove();

		e.preventDefault();
		return false;
	});
</script>	

@endsection

