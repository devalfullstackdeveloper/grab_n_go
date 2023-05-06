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
											<input type="text" class="form-control" name="product_price" placeholder="Enter product price title" value="{{old('product_price')}}">
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
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Category</label>
											<select class="custom-select" name="category_id[]" multiple="">
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Sub Category</label>
											<select class="custom-select" name="subcategory_id[]" multiple="">
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
			$("input[name='sale_price']").attr("required", true);
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

	$(document).ready(function() {
		var url = {!! json_encode(url('/')) !!};
		//main category ajax call
		$('select[name="mastercategory_id[]"]').on('change', function() {
			var mastercategory_id = $(this).val();
			if(mastercategory_id) {
				$.ajax({
					url: url+'/maincategoryproduct',
					type: "POST",
					dataType: "json",
					data: {
						"_token": "{{ csrf_token() }}",
						"mastercategory_id": mastercategory_id
					},
					success:function(mainCategoryData) {
						$('select[name="maincategory_id[]"]').empty()
						$('select[name="category_id[]"]').empty()
						$('select[name="subcategory_id[]"]').empty()
						if(mainCategoryData[0].length != 0){
							$('select[name="maincategory_id[]"]').append('<option value="0">Choose....</option>');
						}
						$.each(mainCategoryData, function(key, mainCategoryDataValue) {
							$.each(mainCategoryDataValue, function(key, value) {
								$('select[name="maincategory_id[]"]').append('<option value="'+ value.id +'">'+ value.main_category_name +'</option>');
							});
						});
					},
					error: function (textStatus, errorThrown) {
						console.log(errorThrown);
					}
				});
			}else{
				$('select[name="maincategory_id[]"]').empty();
			}
		});
		//category ajax call
		$('select[name="maincategory_id[]"]').on('change', function() {
			var maincategory_id = $(this).val();
			if(maincategory_id) {
				$.ajax({
					url: url+'/categoryproduct',
					type: "POST",
					dataType: "json",
					data: {
						"_token": "{{ csrf_token() }}",
						"maincategory_id": maincategory_id
					},
					success:function(categoryData) {
						$('select[name="category_id[]"]').empty()
						$('select[name="subcategory_id[]"]').empty()


						if(categoryData[0].length != 0){
							$('select[name="category_id[]"]').append('<option value="0">Choose....</option>');
						}
						$.each(categoryData, function(key, categoryDataValue) {
							$.each(categoryDataValue, function(key, value2) {
								$('select[name="category_id[]"]').append('<option value="'+ value2.id +'">'+ value2.category_name +'</option>');
							});
						});
					},
					error: function (textStatus, errorThrown) {
						console.log(errorThrown);
						// Success = false;//doesn't go here
					}
				});
			}else{
				$('select[name="category_id[]"]').empty();
			}
		});
		//sub category ajax call
		$('select[name="category_id[]"]').on('change', function() {
			var category_id = $(this).val();
			if(category_id) {
				$.ajax({
					url: url+'/subcategoryproduct',
					type: "POST",
					dataType: "json",
					data: {
						"_token": "{{ csrf_token() }}",
						"category_id": category_id
					},
					success:function(subCategoryData) {
						$('select[name="subcategory_id[]"]').empty()

						if(subCategoryData[0].length != 0){
							$('select[name="subcategory_id[]"]').append('<option value="0">Choose....</option>');
						}

						$.each(subCategoryData, function(key, subCategoryDataValue) {
							$.each(subCategoryDataValue, function(key, value3) {
								$('select[name="subcategory_id[]"]').append('<option value="'+ value3.id +'">'+ value3.sub_category_name +'</option>');
							});
						});
					},
					error: function (textStatus, errorThrown) {
						console.log(errorThrown);
						// Success = false;//doesn't go here
					}
				});
			}else{
				$('select[name="subcategory_id[]"]').empty();
			}
		});
	});
</script>	

@endsection


