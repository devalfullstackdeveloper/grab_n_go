@extends('layouts.layout')

@section('content')
<section>
	<div class="container-fluid">
		<div class="d-sm-flex align-items-center justify-content-between mb-4">
			<h1 class="h3 mb-0 text-gray-800">Sub Category List</h1>

			<a href="{{route('subcategory.create')}}" class="btn btn-primary btn-icon-split">
				<span class="icon text-white-50">
					<i class="fa fa-plus"></i>
				</span>
				<span class="text">Add Sub Category</span></a>
			</div>
			<div class="container-fluid">
				<!-- DataTales Example -->
				<div class="card shadow mb-4">
					<div class="card-header py-3">
						<h6 class="m-0 font-weight-bold text-primary">Sub Category List</h6>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th>Sr.no</th>
										<th>Title</th>
										<th>Category Title</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th>Sr.no</th>
										<th>Title</th>
										<th>Category Title</th>
										<th>Status</th>
										<th>Action</th>

									</tr>
								</tfoot>
								<tbody>
									<?php $i=1;?>
									@foreach($getCategoryName as  $subCategoryData)
									<tr>
										<td>{{$i}}</td>
										<td>{{ $subCategoryData['sub_category_title'] }}</td>
										<td>{{ $subCategoryData['category_title'] }}</td>
										<td>@if($subCategoryData['status'] == 1)
											Available
											@else  
											Unavailable                                    
											@endif
										</td>
										<td>
											<div class="action-wrap-btn">
												<a href="{{ url('subcategory/show', $subCategoryData['id']) }}" class="btn btn-success btn-circle"><i class="fas fa-eye"></i></a>
												<a href="{{ url('subcategory/edit', $subCategoryData['id']) }}" class="btn btn-primary btn-circle"><i class="fas fa-edit"></i></a>

												<form method="POST" action="{{ route('subcategory.delete', $subCategoryData['id']) }}">

													@csrf
													<input name="_method" type="hidden" value="DELETE">
													<input name="isActive" type="hidden" value="{{$subCategoryData['isActive']}}">
													<button type="submit" class="btn btn-xs btn-flat show_confirm btn btn-danger btn-circle" data-toggle="tooltip" title='Delete'><i class="fas fa-trash"></i></button>
												</form>
											</div>
											<!-- <a href="#" class="btn btn-danger btn-circle"><i class="fas fa-trash"></i></a> -->
										</td>
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

	<script src="{{asset('/public/site/js/jquery/jquery.min.js')}}"></script>
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
