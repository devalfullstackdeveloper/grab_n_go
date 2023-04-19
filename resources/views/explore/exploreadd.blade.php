@extends('layouts.layout')

@section('content')

<section>
	<div class="container-fluid">
		<div>
			<div class="pull-right">
				<a href="{{route('explore.list')}}" class="btn btn-primary btn-icon-split">
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
			<form action="{{route('explore.store')}}" method="POST" id="myform" enctype="multipart/form-data">
				@csrf
				<div class="row">
					<div class="col-lg-12">
						<!-- Overflow Hidden -->
						<div class="card mb-4">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary">Add Explore</h6>
							</div>

								<div class="card-body">
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label for="exampleInputEmail1">Explore Title</label>
												<input type="text" class="form-control" name="explore_name" placeholder="Enter Explore title" value="{{old('explore_name')}}">
											</div>
										</div>

										<div class="col-md-12">
											<div class="form-group">
												<label for="exampleFormControlTextarea3">Explore Details</label>
												<textarea class="form-control" id="exampleFormControlTextarea3" name="explore_details" placeholder="Enter Explore details title" value="{{old('explore_details')}}" rows="3"></textarea>
											</div>
										</div>

										</br>
										</br>
										</br>
									
										<div class="col-md-6">
												<button type="submit" class="btn btn-primary">Submit</button>
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
