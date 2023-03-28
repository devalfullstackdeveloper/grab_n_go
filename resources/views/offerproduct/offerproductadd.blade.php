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
			<form action="{{route('offerproduct.store')}}" method="POST" id="explore_more_form" enctype="multipart/form-data">
				@csrf
				<div class="row">
					<div class="col-lg-12">
						<!-- Overflow Hidden -->
						<div class="card mb-4">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary">Add Offer Product</h6>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Offer Product Title</label>
											<input type="text" class="form-control" name="offer_product_name" placeholder="Enter offer product title" value="{{old('offer_product_name')}}">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Offer Product Detail</label>
											<textarea  name="offer_product_detail" rows="4" cols="50" class="form-control" placeholder="Enter offer product detail">{{ old('offer_product_detail') }}</textarea>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">status</label>
											<select class="custom-select" name="status">
												<option selected>Choose...</option>
												<option value="1" @if(old('status') == '1') selected @endif>Available</option>
												<option value="2" @if(old('status') == '2') selected @endif>Unavailable</option>
											</select>
										</div>
									</div>
									
									<div id="dualSelectExample" name="offer_product_detail_name"style="background-color:#F0F0F0; padding:10px;"></div><br>

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

		$.ajax({
			type : 'get',
			url : '{{URL::to('allproducts')}}',
			data:{'search':''},
			success:function(data){
				console.log("Data::--",data.product.candidateItems);
				var dsl = $('#dualSelectExample').DualSelectList({

					'candidateItems' : data.product.candidateItems,

					'selectionItems' :'',

				});
			}
		});
	});
</script> 
