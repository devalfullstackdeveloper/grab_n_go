@extends('layouts.layout')

@section('content')
<section>
	<div class="container-fluid">
		<div>
			<div class="pull-right">
				<a href="{{route('offerproduct.list')}}" class="btn btn-primary btn-icon-split">
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
			<form action="{{route('offerproduct.update')}}" method="POST" id="explore_more_form">
				@csrf
				<input type="hidden" name="id" value="{{$data->id}}" class="explore_offer_id">

				<div class="row">
					<div class="col-lg-12">
						<!-- Overflow Hidden -->
						<div class="card mb-4">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary">Edit Offer Product</h6>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Offer Product Title</label>
											<input type="text" class="form-control" name="offer_product_name" value="{{ isset($data->offer_product_name) ? $data->offer_product_name : ''}}" placeholder="Enter offer product title">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Offer Product Details</label>
											<input type="text" class="form-control" name="offer_product_detail" value="{{ isset($data->offer_product_detail) ? $data->offer_product_detail : ''}}" placeholder="Enter master category title">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">status</label>
											<select class="custom-select" name="status">
												<option {{ $data->status=='1' ? 'selected' : '' }}  value="1">Available</option>
												<option {{ $data->status=='2' ? 'selected' : '' }}  value="2">Unavailable</option>
											</select>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Products</label>
											<div id="dualSelectExample" name="offer_product_detail_name" style="padding:10px;"></div><br>
										</div>
									</div>

									<div class="col-md-6">
										<div>
											<input type="hidden" value="" name="product_id" id="product_ids">
											<button type="button" id="form_submit" class="btn btn-primary">Submit</button>
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

@endsection
<script src="{{asset('/public/site/js/jquery/jquery.min.js')}}"></script>

<script type="text/javascript">

	$(document).ready(function(){
		var prod_ids = [];
		$("#form_submit").click(function(){
			$(".right-panel .dsl-panel-item").each(function(){
				prod_ids.push($(this).attr('id'));
			})
			$("#product_ids").val(prod_ids.join(','));
			$("#explore_more_form").submit();
		});


		var explore_offer_id = $('.explore_offer_id').val();
		var select_product_remove = [];
		$.ajax({
			type : 'get',
			url : '{{URL::to('selectedproducts')}}',
			data:{explore_offer_id : explore_offer_id},
			success:function(data){
				var allProducts = data.product.candidateItems.allproduct
				var selecteProducts = data.product.candidateItems.selectProductData
				

				var getprodutcData = [];
				var selecteProductsId = []; 
				$.each(selecteProducts, function( key, value ) {
					selecteProductsId.push(value.id);
				});

				
				$.each(allProducts, function (key, value){

					if (jQuery.inArray(value.id, selecteProductsId) == -1) {
						getprodutcData.push(value);
					}

				})
				
				var dsl = $('#dualSelectExample').DualSelectList({

					'candidateItems' : getprodutcData,

					'selectionItems' :selecteProducts,

				});
			}
		});
	});
</script>

