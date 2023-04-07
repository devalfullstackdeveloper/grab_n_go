@extends('layouts.layout')

@section('content')

<section>
	<div class="container-fluid">
		<div>
			<div class="pull-right">
				<a href="{{route('bannercategory.list')}}" class="btn btn-primary btn-icon-split">
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
			<form action="{{route('bannercategory.store')}}" method="POST" enctype="multipart/form-data">
			@csrf
            <input type="hidden" name="id" value="">
			<div class="card-body" style="padding: 0;">
				<div class="col-md-12">
				<div class="form-group">
					<label for="title">Mastercategory:</label>
					<select name="mastercategory_id" class="form-control">
					<option value="">Select mastercategory here</option>
					@foreach ($masterCategoryData as $key => $value)
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
			</form>
		</div>
	</div>
</section>

<script src="{{asset('/public/site/js/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('/public/site/js/sweetalert.min.js')}}"></script>

	<script type="text/javascript">
	$(document).ready(function() {

		var url = {!! json_encode(url('/')) !!};

		//main category ajax call
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
	//category ajax call
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
		//sub category ajax call
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
