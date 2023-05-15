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

			<form action="{{route('explore.update')}}" method="POST" enctype="multipart/form-data">
				@csrf
                <input type="hidden" name="id" value="{{$getExploreData[0]['explore_id']}}">
				<div class="row">
					<div class="col-lg-12">
						<!-- Overflow Hidden -->
						<div class="card mb-4">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary">Edit Explore</h6>
							</div>

							<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="exampleInputEmail1">Explore Title</label>
											<input type="text" class="form-control" name="explore_name"
                                            placeholder="enter explore title" value="{{ $getExploreData[0]['explore_name'] }}">
										</div>
									</div>

									<div class="col-md-12">
										<div class="form-group">
  											<label for="exampleFormControlTextarea3">Explore Details</label>
  											<textarea class="form-control" id="exampleFormControlTextarea3" name="explore_details"
                                            placeholder="enter explore details Title"  rows="3">{{ $getExploreData[0]['explore_details'] }}</textarea>
										</div>
									</div>	
									<div class="col-md-12">
										<div>
											<button type="submit" class="btn btn-primary">Update Details</button>
										</div>
									</div>
								</div>
						
			</form>

			<form action="{{route('explore.explorecategorystore')}}" method="POST" enctype="multipart/form-data">
				@csrf
                <input type="hidden" name="id" value="{{$getExploreData[0]['explore_id']}}">
						
				<br>
						<div class="row">
						<div class="col-md-12">
								<button data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" 
										aria-controls="collapseExample" type="button" class="btn btn-primary showCategory">Add Category Item</button>
						</div>
						</div>

						<div class="card-body colspan_drop" style="padding: 0;">
							<div class="row">
							<div class="col-md-12">
							<div class="collapse" id="collapseExample">

							<div class="col-md-12">
							<div class="form-group">
								<label for="title">Mastercategory:</label>
								<select name="mastercategory_id" class="form-control">
								<option value="">Select mastercategory here</option>
									@foreach ($mastercategory as $key => $value)
										<option value="{{ $value->id }}">{{ $value->master_category_name }}</option>
									@endforeach
								</select>
							</div>
							</div>
							<div class="col-md-12">
							<div class="form-group">
								<label for="title">Maincategory:</label>
								<select name="maincategory_id" class="form-control">
								<option value="">Select maincategory here</option>

								</select>
							</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label for="title">Category:</label>
									<select name="category_id" class="form-control">
									<option value="">Select category here</option>

									</select>
									</select>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label for="title">SubCategory:</label>
									<select name="subcategory_id" class="form-control">
									<option value="">Select subcategory here</option>

									</select>
								</div>
							</div>
							<div class="col-md-12">
								<div>
									<button type="submit" class="btn btn-primary">Add Details</button>
								</div>
							</div>
							</div>
							</div>
							</div>
						</div>
						</div>
					</div>
				</div>
			</form>
			<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>Id</th>
						<th>Mastercategory id</th>
						<th>Maincategory id</th>
						<th>Category id</th>
						<th>Subcategory id</th>
						<th>Action</th>
					</tr>
				</thead>

				<tbody>
					<?php $i = 1;?>
					@foreach ($data as $getExploreDetailsData)
					<tr>
					
						<td>{{$i}}</td>

							<td>{{ $getExploreDetailsData['mastercategory_id'] }}</td>
							<td>{{ ($getExploreDetailsData['maincategory_id']!='') ? $getExploreDetailsData['maincategory_id'] : '-' }}</td>
							<td>{{ ($getExploreDetailsData['category_id']!='') ? $getExploreDetailsData['category_id'] : '-' }}</td>
							<td>{{ ($getExploreDetailsData['subcategory_id']!='') ? $getExploreDetailsData['subcategory_id'] : '-' }}</td>

							<td>

							<form method="POST" action="{{route('explorecategorydelete.delete',['id'=>$getExploreDetailsData['explore_id']])}}">
								@csrf
								<input name="_method" type="hidden" value="DELETE">
								<input name="isActive" type="hidden" value="{{$getExploreDetailsData['isActive']}}">
								<button type="submit" class="btn btn-xs btn-flat show_confirm btn btn-danger btn-circle"
								data-toggle="tooltip" title='Delete'><i class="fas fa-trash"></i></button>
							</form>


							</td>
						</td>
					</tr>
					<?php $i++;?>
					@endforeach

				</tbody>
			</table>
		</div>
	</div>
</section>

	<script src="{{asset('/public/site/js/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('/public/site/js/sweetalert.min.js')}}"></script>

	<script type="text/javascript">
	$(document).ready(function() {
		var url = {!! json_encode(url('/')) !!};

		$('select[name="mastercategory_id"]').on('change', function() {
			var masterID = $(this).val();
			if(masterID) {
				console.log(masterID);
				$.ajax({
					url: url+'/maincategorydropdownajax/'+ masterID,
					type: "GET",
					dataType: "json",
					success:function(tb1) {

						$('select[name="maincategory_id"]').empty();
						$('select[name="maincategory_id"]').append('<option value="">Select maincategory here</option>');
						$.each(tb1, function(key, value) {
							$('select[name="maincategory_id"]').append('<option value="'+ value.id +'">'+ value.main_category_name +'</option>');
						});
					},
					error: function (textStatus, errorThrown) {
						console.log(errorThrown);
						// Success = false;//doesn't go here
					}
				});
			}else{
				$('select[name="maincategory_id"]').empty();
			}
		});
	});
	</script>

	<script type="text/javascript">
	$(document).ready(function() {
		var url = {!! json_encode(url('/')) !!};

		$('select[name="maincategory_id"]').on('change', function() {
			var mainID = $(this).val();

			if(mainID) {
				console.log(mainID);
				$.ajax({
					url: url+'/categorydropdownajax/'+ mainID,
					type: "GET",
					dataType: "json",
					success:function(tb2) {

						$('select[name="category_id"]').empty();
						$('select[name="category_id"]').append('<option value="">Select category here</option>');
						$.each(tb2, function(key, value) {
							console.log(value);
							$('select[name="category_id"]').append('<option value="'+ value.id +'">'+ value.category_name +'</option>');
						});
					},
					error: function (textStatus, errorThrown) {
						console.log(errorThrown);
						// Success = false;//doesn't go here
					}
				});
			}else{
				$('select[name="category_id"]').empty();
			}
		});
	});
	</script>

	<script type="text/javascript">
	$(document).ready(function() {
		var url = {!! json_encode(url('/')) !!};

		$('select[name="category_id"]').on('change', function() {
			var catID = $(this).val();

			if(catID) {
				console.log(catID);
				$.ajax({
					url: url+'/subcategorydropdownajax/'+ catID,
					type: "GET",
					dataType: "json",
					success:function(tb3) {
						$('select[name="subcategory_id"]').empty();
						$('select[name="subcategory_id"]').append('<option value="">Select subcategory here</option>');

						$.each(tb3, function(key, value) {
							console.log(value);
							$('select[name="subcategory_id"]').append('<option value="'+ value.id +'">'+ value.sub_category_name +'</option>');
						});
					},
					error: function (textStatus, errorThrown) {
						console.log(errorThrown);
						// Success = false;//doesn't go here
					}
				});
			}else{
				$('select[name="subcategory_id"]').empty();
			}
		});
	});
	</script>


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


