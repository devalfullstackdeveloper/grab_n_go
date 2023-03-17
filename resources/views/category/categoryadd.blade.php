@extends('layouts.layout')

@section('content')
<section>
	<div class="container-fluid">
		<div>
			<div class="pull-right">
				<a href="{{route('category.list')}}" class="btn btn-primary btn-icon-split">
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
			<form action="{{route('category.store')}}" method="POST" enctype="multipart/form-data">
				@csrf
				<div class="row">
					<div class="col-lg-12">
						<!-- Overflow Hidden -->
						<div class="card mb-4">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary">Add Categoey</h6>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Categoey Title</label>
											<input type="text" class="form-control" name="category_name" placeholder="Enter master category title" value="{{old('category_name')}}">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Categoey Image</label>
											<input type="file" class="form-control" name="category_image" value="{{old('category_image')}}">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Main Category Title</label>
											<select class="custom-select" name="main_category_id">
												<option selected>Choose...</option>
												<option value="1" @if(old('status') == '1') selected @endif>Available</option>
												<option value="2" @if(old('status') == '2') selected @endif>Unavailable</option>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Status</label>
											<select class="custom-select" name="status">
												<option selected>Choose...</option>
												<option value="1" @if(old('status') == '1') selected @endif>Available</option>
												<option value="2" @if(old('status') == '2') selected @endif>Unavailable</option>
											</select>
										</div>
									</div>
									@if ($errors->has('status'))
									<span class="invalid-feedback" role="alert">
										<strong>{{ $errors->first('status') }}</strong>
									</span>
									@endif
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

