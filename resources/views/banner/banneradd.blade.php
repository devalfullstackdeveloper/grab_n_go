@extends('layouts.layout')

@section('content')
<section>
	<div class="container-fluid">
		<div class="pull-right">
			<a href="{{route('banner.list')}}" class="btn btn-primary btn-icon-split">
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
			<form method="POST" action="{{route('banner.store')}}"  enctype="multipart/form-data">
				 @csrf
				<div class="row">
					<div class="col-lg-12">
						<!-- Overflow Hidden -->
						<div class="card mb-4">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary">Add Banner</h6>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Banner Title</label>
											<input type="text" class="form-control" name="banner_name" placeholder="Enter banner title" value="{{old('banner_name')}}">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Banner Image</label>
											<input type="file" class="form-control" name="banner_image" id="banner_image" placeholder="Enter banner image" value="{{old('banner_image')}}">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Banner Offer Type</label>
											<input type="text" class="form-control" name="banner_offer_type" placeholder="Enter banner offer type" value="{{old('banner_offer_type')}}">
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
</section>

@endsection

