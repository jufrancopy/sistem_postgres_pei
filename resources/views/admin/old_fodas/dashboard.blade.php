@extends('layouts.master')
@section('content')
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header-primary">
						<h4 class="card-title ">Anális de ambiente interno</h4>
					</div>
					<div class="card-body">
						<div class="row">
							@foreach ($fodas as $foda)
							<div class="col-lg-3 col-md-6 col-sm-6">
								<div class="card card-stats">
									<div class="card-header card-header card-header-icon">
										<div class="card-icon">
											<i class="material-icons">settings_applications
											</i>
										</div>
										<p class="card-category">{{$foda[0]->nivel}}</p>
										<h3 class="card-title">
										<small></small>
										</h3>
									</div>
									<div class="card-footer">
										<div class="stats">
										
											<i class="material-icons">visibility</i> <a href="{{route('fodas.edit', $foda[0]->id)}}">Analizar</a>
											
										</div>
									</div>
								</div>
							</div>
							@endforeach
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection