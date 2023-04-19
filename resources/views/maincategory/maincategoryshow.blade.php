@extends('layouts.layout')

@section('content')
<section>
	<div class="container-fluid">
		<div class="d-sm-flex align-items-center justify-content-between mb-4">
			<h1 class="h3 mb-0 text-gray-800">Main Category Show</h1>

			<div class="pull-right">
				<a href="{{route('maincategory.list')}}" class="btn btn-primary btn-icon-split">
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
						<h6 class="m-0 font-weight-bold text-primary">Main Category Show</h6>
					</div>
					<div class="card-body">
						<div>
							<table class="table table-bordered">
								<tbody>
									<tr>
										<td><b>Title</b></td>
										<td>{{ isset($getdata[0]['main_category_name']) ? $getdata[0]['main_category_name'] : ''}}</td>
									</tr>
									<tr>
										<td><b>Master Category Title</b></td>
										<td>{{ isset($getdata[0]['master_category_name']) ? $getdata[0]['master_category_name'] : ''}}</td>
									</tr>
									<tr>
										<td><b>Image</b></td>
										<td><img src="{{asset($getdata[0]['main_category_image'])}}" alt="-" style="width: 150px;height: 150px;"></td>
									</tr>

									<tr>
										<td><b>Banner Image</b></td>
										@if(isset($getdata[0]['main_category_banner_image']) != '')
										<td><img src="{{asset($getdata[0]['main_category_banner_image'])}}" alt="" style="width: 150px;height: 150px;"></td>
										@else
										<td>-</td>
										@endif
										<td>
									</tr>
									
									<tr>
										<td><b>Ststus</b></td>
										<td>
											@if(isset($getdata[0]['status']) == 1) Available @else Unvailable @endif
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
