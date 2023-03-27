@extends('layouts.layout')

@section('content')
<section>
	<div class="container-fluid">
		<div class="d-sm-flex align-items-center justify-content-between mb-4">
			<h1 class="h3 mb-0 text-gray-800">Product List</h1>

			<a href="{{route('product.create')}}" class="btn btn-primary btn-icon-split">
				<span class="icon text-white-50">
					<i class="fa fa-plus" style="font-size:24px"></i>
				</span>
				<span class="text">Add Product</span></a>
			</div>
			<div class="container-fluid">
				<!-- DataTales Example -->
				<div class="card shadow mb-4">
					<div class="card-header py-3">
						<h6 class="m-0 font-weight-bold text-primary">Product List</h6>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th>Sr.no</th>
										<th>Product Title</th>
										<th>Product Details</th>
										<th>Product Price</th>
										<th>Master Category Title</th>
										<th>Main Category Title</th>
										<th>Category Title</th>
										<th>SubCategory Title</th>
										<th>Quantity</th>
										<th>Points</th>
										<th>Sale</th>
										<th>Sale Price</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								
								<!-- <tfoot>
									<tr>
										<th>Sr.no</th>
										<th>Product Title</th>
										<th>Product Details</th>
										<th>Product Price</th>
										<th>Master Category Title</th>
										<th>Main Category Title</th>
										<th>Category Title</th>
										<th>SubCategory Title</th>
										<th>Quantity</th>
										<th>Points</th>
										<th>Sale</th>
										<th>Sale Price</th>
										<th>Lat</th>
										<th>Long</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</tfoot> -->

								<tbody>
									<?php $i=1;
									
									?>
									@foreach($data as  $getProductData)


									<tr>
										<td>{{$i}}</td>
										<td>{{ $getProductData['product_name'] }}</td>
										<td>{{ $getProductData['product_details'] }}</td>
										<td>{{ $getProductData['product_price'] }}</td>
										<td>{{ $getProductData['master_category_name'] }}</td>
										<td>{{ $getProductData['main_category_name'] }}</td>
										<td>{{ $getProductData['category_name'] }}</td>
										<td>{{ $getProductData['sub_category_name'] }}</td>
										<td>{{ $getProductData['quantity'] }}</td>
										<td>{{ $getProductData['point'] }}</td>
										<td>{{ $getProductData['sale'] }}</td>
										<td>{{ $getProductData['sale_price'] }}</td>
										<td>{{ $getProductData['status'] }}</td>
										<td><div class="action-wrap-btn">

											<a href="{{ url('productshow/'.$getProductData['product_id'])}}" class="btn btn-success btn-circle"><i class="fas fa-eye"></i></a>

												<a href="{{ url('productedit/'.$getProductData['product_id'])}}" class="btn btn-primary btn-circle"><i class="fas fa-edit"></i></a>

												<form method="POST" action="{{route('product.delete',['id'=>$getProductData['product_id']]) }}">
													@csrf
													<input name="_method" type="hidden" value="DELETE">
													<button type="submit" class="btn btn-xs btn-flat show_confirm btn btn-danger btn-circle" data-toggle="tooltip" title='Delete'><i class="fas fa-trash"></i></button>
												</form>
											</div></td>
									</tr>
									<?php $i++;?>
									@endforeach
									
								</tbody>
							</table>
						</div>
					</div>
				</div>

			</div>
		</div>
	</section>
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

	<script src="{{asset('/public/site/js/sweetalert.min.js')}}"></script>

	<script type="text/javascript">

		$('.show_confirm').click(function(event) {
			var form =  $(this).closest("form");
			var name = $(this).data("name");
			event.preventDefault();
			swal({
				title: `Are you sure you want to delete this record?`,
				text: "If you delete this, it will be gone forever.",
				icon: "warning",
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					form.submit();
				}
			});
		});

	</script>
	@endsection

