@extends('layouts.layout')

@section('content')
<section>
	<div class="container-fluid">
		<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-800">Banner List</h1>

	<a href="{{route('banner.create')}}" class="btn btn-sm btn-primary shadow-sm cust-add-btn">
		<i class="fas fa-download fa-sm text-white-50"></i> Add Banner</a>

                                <!-- <button type="submit" class="btn btn-primary">Submit</button> -->

</div>
		</div>
	</section>

@endsection

.<!-- cust-add-btn {
	 padding: 8px 20px;
} -->