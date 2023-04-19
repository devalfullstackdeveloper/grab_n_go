@extends('layouts.layout')

@section('content')
<section>
	<div class="container-fluid">
		<div>
			<div class="pull-right">
				<a href="{{route('category.list')}}" class="btn btn-primary btn-icon-split">
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
			<form action="{{route('category.update')}}" method="POST" enctype="multipart/form-data">
				@csrf
				<input type="hidden" name="id" value="{{$getdata[0]['id']}}">

				<div class="row">
					<div class="col-lg-12">
						<!-- Overflow Hidden -->
						<div class="card mb-4">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary">Edit Category</h6>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Category Title</label>
											<input type="text" class="form-control" name="category_name" value="{{ isset($getdata[0]['category_name']) ? $getdata[0]['category_name'] : ''}}" placeholder="Enter category title">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Category Image</label>
											<input type="file" class="form-control" name="category_image" value="{{ isset($getdata[0]['category_image']) ? $getdata[0]['category_image'] : ''}}">
										</div>
									</div>
									<?php
									$main_category_id = $getdata[0]['main_category_id'];
									?>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Main Category</label>
											<select class="custom-select" name="maincategory_id[]" multiple="">

												@foreach($mainCategoryData as $keys => $data)

												@if(in_array($data['id'],$main_category_id))
												<option value="{{$data['id']}}" selected>{{$data['main_category_name']}}</option>
												@else
												<option value="{{$data['id']}}" >{{$data['main_category_name']}}</option>

												@endif
												
												@endforeach

											</select>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Category Banner Image</label>
											<input type="file" class="form-control" name="category_banner_image" value="{{ isset($getdata[0]['category_banner_image']) ? $getdata[0]['category_banner_image'] : ''}}">
										</div>
									</div>
									

									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">status</label>
											<select class="custom-select" name="status">
												<option {{ $getdata[0]['status']=='1' ? 'selected' : '' }}  value="1">Available</option>
												<option {{ $getdata[0]['status']=='2' ? 'selected' : '' }}  value="2">Unavailable</option>
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
			</form>
		</div>
	</div>
</section>

@endsection

