@extends('layouts.layout')

@section('content')
<section>
	<div class="container-fluid">
		<div>
			<div class="pull-right">
				<a href="{{route('mastercategory.list')}}" class="btn btn-primary btn-icon-split">
					<span class="icon text-white-50">
						<i class="fa fa-arrow-left" style="font-size:24px"></i>
					</span>
					<span class="text">Back</span>
				</a>
			</div>
			
			<form action="{{route('maincategory.store')}}" method="POST" enctype="multipart/form-data">
				@csrf
				<div class="row">
					<div class="col-lg-12">
						<!-- Overflow Hidden -->
						<div class="card mb-4">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary">Add Master Categoey</h6>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Main Categoey Title</label>
											<input type="text" class="form-control" name="main_category_name" value="{{ isset($mainCategoryData->main_category_name) ? $mainCategoryData->main_category_name : ''}}" placeholder="Enter main category title">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Main Categoey Image</label>
											<input type="file" class="form-control" name="main_category_image" value="{{ isset($mainCategoryData->main_category_image) ? $mainCategoryData->main_category_image : ''}}">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Master Category</label>
											<select class="custom-select" name="mastercategory_id[]" multiple="">
												<option selected>Choose...</option>
												@foreach($masterCategoryData as $mastercategory)
												<option value="{{$mastercategory->id}}">{{$mastercategory->master_category_name}}</option>
												@endforeach											
											</select>
										</div>
									</div>
									

									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">status</label>
											<select class="custom-select" name="status">
												<option selected>Choose...</option>
												<option {{ $mainCategoryData->status=='1' ? 'selected' : '' }}  value="1">Available</option>
												<option {{ $mainCategoryData->status=='2' ? 'selected' : '' }}  value="2">Unavailable</option>
											</select>
										</div>
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

