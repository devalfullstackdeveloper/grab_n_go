@extends('layouts.layout')

@section('content')
<section>
	<div class="container-fluid">
		<div>
			<form method="POST" action="{{route('banner.store')}}">
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
											<input type="text" class="form-control" name="banner_name" placeholder="Enter banner title">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Banner Image</label>
											<input type="file" class="form-control" name="banner_image" placeholder="Enter banner image">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exampleInputEmail1">Banner Offer Type</label>
											<input type="text" class="form-control" name="banner_offer_type" placeholder="Enter banner offer type">
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

