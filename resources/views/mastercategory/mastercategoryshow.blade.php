@extends('layouts.layout')

@section('content')
<section>
	<div class="container-fluid">
		<div class="d-sm-flex align-items-center justify-content-between mb-4">
			<h1 class="h3 mb-0 text-gray-800">Master Category Show</h1>

			<div class="pull-right">
				<a href="{{route('mastercategory.list')}}" class="btn btn-primary btn-icon-split">
					<span class="icon text-white-50">
						<i class="fa fa-arrow-left"></i>
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
						<h6 class="m-0 font-weight-bold text-primary">Master Category Show</h6>
					</div>
					<div class="card-body">
						<div>
							<table class="table table-bordered">
								<tbody>
									<tr>
										<td><b>Title</b></td>
										<td>{{ isset($data->master_category_name) ? $data->master_category_name : ''}}</td>
									</tr>
									<tr>
										<td><b>Image</b></td>
										<td><img src="{{asset($data->master_category_image)}}" alt="" style="width: 150px;height: 150px;"></td>
									</tr>
									<tr>
										<td><b>Ststus</b></td>
										<td>
											@if($data->status == 1) Available @else Unvailable @endif
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
