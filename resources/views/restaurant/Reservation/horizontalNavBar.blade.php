<ul class="nav nav-pills nav-pills-primary  btn-group" role="tablist">
	<li class="nav-item action_button">
		<a class="list-group-item list-group-item-action active" id="reservation-list"
			data-bs-toggle="list" href="#reservation" role="tab"
			aria-controls="reservation">{{ trans('lang.reservation') }}</a>
	</li>
	<li class="nav-item action_button">
		<a class="list-group-item list-group-item-action" id="payments-list"
			data-bs-toggle="list" href="#payments" role="tab"
			aria-controls="payments">Payments</a>
	</li>
	<li class="nav-item action_button">
		{{-- #view-booking --}}
		<a class="list-group-item list-group-item-action" id="view-booking-list"
			data-bs-toggle="list" href="#view-booking" role="tab"
			aria-controls="view-booking">{{ trans('lang.view') }} {{ trans('lang.booking') }}</a>
	</li>
	<li class="nav-item action_button">
		<a class="list-group-item list-group-item-action" id="restaurant-booking-list"
			data-bs-toggle="list" href="#restaurant-booking" role="tab"
			aria-controls="restaurant-booking">{{ trans('lang.restaurant') }} {{ trans('lang.booking') }}</a>
	</li>
	<li class="nav-item action_button">
		<a class="list-group-item list-group-item-action" id="template-list"
			data-bs-toggle="list" href="#template" role="tab"
			aria-controls="template">{{ trans('lang.est') }}</a>
	</li>
	<li class="nav-item action_button">
		<a class="list-group-item list-group-item-action" id="setting-list"
			data-bs-toggle="list" href="#setting" role="tab"
			aria-controls="setting">{{ trans('lang.reservation') }} {{ trans('lang.menuIconSettings') }} </a>
	</li>
</ul>