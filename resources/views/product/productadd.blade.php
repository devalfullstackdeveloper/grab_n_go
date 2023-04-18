@extends('layouts.layout')

@section('content')
<section>
	<div class="container-fluid">
		<div>
			<div class="pull-right">
				<a href="{{route('product.list')}}" class="btn btn-primary btn-icon-split">
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
			<form action="{{route('product.store')}}" method="POST" enctype="multipart/form-data">
				@csrf
				<div class="row">
					<div class="col-lg-12">
						<!-- Overflow Hidden -->
						<div class="card mb-4">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary">Add Product</h6>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Product Title</label>
											<input type="text" class="form-control" name="product_name" placeholder="Enter product title" value="{{old('product_name')}}">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Product Image</label>
											<input type="file" class="form-control" name="product_image[]" multiple="multiple" value="{{old('product_image')}}">
										</div>
									</div>
									

									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Product Price</label>
											<input type="text" class="form-control" name="product_price" placeholder="Enter product price" value="{{old('product_price')}}">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Quantity</label>
											<input type="text" class="form-control" name="quantity" placeholder="Enter product quantity" value="{{old('quantity')}}">
											
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Master Category</label>
											<select class="custom-select" name="mastercategory_id[]" multiple="">
												@foreach($masterCategorydata as $mastercategory)
												<option value="{{$mastercategory->id}}">{{$mastercategory->master_category_name}}</option>
												@endforeach											
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Main Category</label>
											<select class="custom-select" name="maincategory_id[]" multiple="">
												@foreach($mainCategorydata as $maincategory)
												<option value="{{$maincategory->id}}">{{$maincategory->main_category_name}}</option>
												@endforeach											
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Category</label>
											<select class="custom-select" name="category_id[]" multiple="">
												<option value="0" selected>Choose...</option>
												@foreach($categorydata as $category)
												<option value="{{$category->id}}">{{$category->category_name}}</option>
												@endforeach											
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Sub Category</label>
											<select class="custom-select" name="subcategory_id[]" multiple="">
												<option value="0" selected>Choose...</option>
												@foreach($subCategorydata as $subcategory)
												<option value="{{$subcategory->id}}">{{$subcategory->sub_category_name}}</option>
												@endforeach											
											</select>
										</div>
									</div>
									
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Points</label>
											<input type="text" class="form-control" name="point" placeholder="Enter product point title" value="{{old('point')}}">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Sale</label>
											<select class="custom-select" name="sale">
												<option selected>Choose...</option>
												<option value="1" @if(old('sale') == '1') selected @endif>Yes</option>
												<option value="2" @if(old('sale') == '2') selected @endif>No</option>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Sale Price</label>
											<input type="text" class="form-control" name="sale_price" placeholder="Enter sale price" value="{{old('sale_price')}}">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Packet</label>
											<input type="text" class="form-control" name="packet" placeholder="Enter packet" value="{{old('packet')}}">
										</div>
									</div>
									
									
									
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleFormControlTextarea3">Product Details</label>
											<textarea class="form-control" id="exampleFormControlTextarea3" name="product_details" placeholder="Enter product details title" value="" rows="7">{{old('product_details')}}</textarea>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group add-location">
											<label for="exampleInputEmail1">Lat Long</label>
											<div class="control-form">
												<div class="entry input-group">
													<input type="text" class="form-control" name="lat[]" value="{{ old('lat.0') }}" placeholder="Enter lat">
													<input type="text" class="form-control" name="long[]" value="{{ old('long.0') }}" placeholder="Enter long">
													<span class="input-group-btn">
														<button class="btn btn-success btn-add" type="button">
															<i class="fa fa-plus" aria-hidden="true"></i>
														</button>
													</span>
												</div>
											</div>

										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Status</label>
											<select class="custom-select" name="status">
												<option selected>Choose...</option>
												<option value="1" @if(old('status') == '1') selected @endif>Available</option>
												<option value="2" @if(old('status') == '2') selected @endif>Unavailable</option>
											</select>
										</div>
									</div>
									<div class="col-md-6">
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
			</div>
		</div>
	</form>
</div>
</div>

</section>


<script src="{{asset('/public/site/js/jquery/jquery.min.js')}}"></script>


<script type="text/javascript">
	//sale and sale price field disable
	$('select[name="sale"]').on('change', function() {
		if($(this).val() == 2){
			$("input[name='sale_price']").attr("disabled", true);
			$("input[name='sale_price']").val('');
		}else{
			$("input[name='sale_price']").attr("disabled", false);
		}
	});

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


