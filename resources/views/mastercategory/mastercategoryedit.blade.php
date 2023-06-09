@extends('layouts.layout')

@section('content')
<section>
	<div class="container-fluid">
		<div>
			<div class="pull-right">
				<a href="{{route('mastercategory.list')}}" class="btn btn-primary btn-icon-split">
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
			<form action="{{route('mastercategory.update')}}" method="POST" enctype="multipart/form-data">
				@csrf
				<input type="hidden" name="id" value="{{$data->id}}">

				<div class="row">
					<div class="col-lg-12">
						<!-- Overflow Hidden -->
						<div class="card mb-4">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary">Edit Master Category</h6>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Master Category Title</label>
											<input type="text" class="form-control" name="master_category_name" value="{{ isset($data->master_category_name) ? $data->master_category_name : ''}}" placeholder="Enter master category title">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Master Category Image</label>
											<input type="file" class="form-control" name="master_category_image" value="{{ isset($data->master_category_image) ? $data->master_category_image : ''}}">
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
	                           		<!--  <div class="form-group">
									    <label for="exampleInputEmail1">Banner Title</label>
									    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter banner title">
									</div> -->
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

