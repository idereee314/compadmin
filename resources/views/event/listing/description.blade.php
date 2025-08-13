<div class="modal-header bg-gray-100">
    <h5 class="modal-title">{{ trans('display.general_description') }}</h5>
    <span class="text-muted font-weight-bold">{{ $event->name }}</span>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>

<div class="card card-custom gutter-b">
    <div class="card-body">
        <div class="d-flex">
            <!--begin::Pic-->
            <div class="flex-shrink-0 mr-7">
                <div class="symbol symbol-50 symbol-lg-120">
                    <img alt="Pic" src="{{ \Storage::disk('s3')->url(@$event->picturesMobileCover->first()->dir_url.'/thumbnail/'.@$event->picturesMobileCover->first()->url) }}">
                </div>
            </div>
            <!--end::Pic-->

            <!--begin: Info-->
            <div class="flex-grow-1">
                <!--begin::Title-->
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <!--begin::User-->
                    <div class="mr-3">
                        <div class="d-flex align-items-center mr-3">
                            <!--begin::Name-->
                            <a href="#" class="d-flex align-items-center text-dark text-hover-primary font-size-h5 font-weight-bold mr-3">
                                {{ $event->name }}
                            </a>
                            <!--end::Name-->
                        </div>
                        <!--begin::Contacts-->
                        <div class="d-flex flex-wrap my-2">
                            <a href="#" class="text-muted text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                                {{ trans('display.general_sport_type') }} : <span class="label label-primary label-inline mr-2">{{ @$sport }}</span>
                            </a>
                        </div>
                        <!--end::Contacts-->
                    </div>
                    <!--begin::User-->
                </div>
                <!--end::Title-->
            </div>
            <!--end::Info-->
        </div>

        <div class="row mt-5">
            <div class="col-xl-4">
                <!--begin::Card-->
                <div class="card card-custom gutter-b">
                    <div class="card-header h-auto py-3 border-0">
                        <div class="card-title">
                            <h3 class="card-label text-danger">
        						{{trans('display.general_location')}}
                                <span class="d-block text-muted pt-2 font-size-sm">{{trans('display.general_location_info')}}</span>
        					</h3>
                        </div>
                    </div>
                    <div class="card-body pt-2">
                        <p class="text-dark-50">
                            {{ $event->location }}
                        </p>
                    </div>
                </div>
                <!--end::Card-->

                <!--begin::Card-->
                <div class="card card-custom">
                    <!--begin::Header-->
                    <div class="card-header h-auto py-4">
                        <div class="card-title">
                            <h3 class="card-label">
        						{{trans('display.organization')}}
                                <span class="d-block text-muted pt-2 font-size-sm">{{trans('display.event_organizer')}}</span>
        					</h3>
                        </div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body py-4">

                        <div class="form-group row my-2">
                            <label class="col-4 col-form-label">{{trans('display.general_name')}}</label>
                            <div class="col-8">
                                <span class="form-control-plaintext font-weight-bolder">Loop Inc.</span>
                            </div>
                        </div>
                        <div class="form-group row my-2">
                            <label class="col-4 col-form-label">Location:</label>
                            <div class="col-8">
                                <span class="form-control-plaintext font-weight-bolder">London, UK.</span>
                            </div>
                        </div>
                        <div class="form-group row my-2">
                            <label class="col-4 col-form-label">Revenue:</label>
                            <div class="col-8">
                                <span class="form-control-plaintext"><span class="font-weight-bolder">345,000M</span> &nbsp;<span class="label label-inline label-danger label-bold">Q4, 2019</span></span>
                            </div>
                        </div>
                        <div class="form-group row my-2">
                            <label class="col-4 col-form-label">Phone:</label>
                            <div class="col-8">
                                <span class="form-control-plaintext font-weight-bolder">+456 7890456</span>
                            </div>
                        </div>
                        <div class="form-group row my-2">
                            <label class="col-4 col-form-label">Email:</label>
                            <div class="col-8">
                                <span class="form-control-plaintext font-weight-bolder">
                                    <a href="#">info@loop.com</a>
                                </span>
                            </div>
                        </div>
                        <div class="form-group row my-2">
                            <label class="col-4 col-form-label">Website:</label>
                            <div class="col-8">
                                <span class="form-control-plaintext font-weight-bolder">
                                    <a href="#">www.loop.com</a>
                                </span>
                            </div>
                        </div>
                        <div class="form-group row my-2">
                            <label class="col-4 col-form-label">Contact Person:</label>
                            <div class="col-8">
                                <span class="form-control-plaintext font-weight-bolder">
                                    <a href="#">Nick Bold</a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Card-->
            </div>
            <div class="col-xl-8">
                <!--begin::Card-->
                <div class="card card-custom gutter-b">
                	<!--begin::Header-->
                	<div class="card-header card-header-tabs-line">
                		<div class="card-toolbar">
                			<ul class="nav nav-tabs nav-tabs-space-lg nav-tabs-line nav-bold nav-tabs-line-3x" role="tablist">
                            	<li class="nav-item">
                					<a class="nav-link" data-toggle="tab" href="#event_description_tab_one">
                						<span class="nav-icon mr-2">
                                            <span class="svg-icon mr-3"><!--begin::Svg Icon | path:/metronic/theme/html/demo3/dist/assets/media/svg/icons/General/Notification2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <title>Stockholm-icons / General / Notification2</title>
                                                    <desc>Created with Sketch.</desc>
                                                    <defs></defs>
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24"></rect>
                                                        <path d="M13.2070325,4 C13.0721672,4.47683179 13,4.97998812 13,5.5 C13,8.53756612 15.4624339,11 18.5,11 C19.0200119,11 19.5231682,10.9278328 20,10.7929675 L20,17 C20,18.6568542 18.6568542,20 17,20 L7,20 C5.34314575,20 4,18.6568542 4,17 L4,7 C4,5.34314575 5.34314575,4 7,4 L13.2070325,4 Z" fill="#000000"></path>
                                                        <circle fill="#000000" opacity="0.3" cx="18.5" cy="5.5" r="2.5"></circle>
                                                    </g>
                                                </svg><!--end::Svg Icon-->
                                            </span>
                                        </span>
                                        <span class="nav-text">
                                            {{trans('display.general_notes')}}
                                        </span>
                					</a>
                				</li>
                				<li class="nav-item mr-3">
                					<a class="nav-link" data-toggle="tab" href="#event_description_tab_two">
                                        <span class="nav-icon mr-2">
                                            <span class="svg-icon mr-3"><!--begin::Svg Icon | path:/metronic/theme/html/demo3/dist/assets/media/svg/icons/Communication/Chat-check.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <title>Stockholm-icons / Communication / Chat-check</title>
                                                    <desc>Created with Sketch.</desc>
                                                    <defs></defs>
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24"></rect>
                                                        <path d="M4.875,20.75 C4.63541667,20.75 4.39583333,20.6541667 4.20416667,20.4625 L2.2875,18.5458333 C1.90416667,18.1625 1.90416667,17.5875 2.2875,17.2041667 C2.67083333,16.8208333 3.29375,16.8208333 3.62916667,17.2041667 L4.875,18.45 L8.0375,15.2875 C8.42083333,14.9041667 8.99583333,14.9041667 9.37916667,15.2875 C9.7625,15.6708333 9.7625,16.2458333 9.37916667,16.6291667 L5.54583333,20.4625 C5.35416667,20.6541667 5.11458333,20.75 4.875,20.75 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                                        <path d="M2,11.8650466 L2,6 C2,4.34314575 3.34314575,3 5,3 L19,3 C20.6568542,3 22,4.34314575 22,6 L22,15 C22,15.0032706 21.9999948,15.0065399 21.9999843,15.009808 L22.0249378,15 L22.0249378,19.5857864 C22.0249378,20.1380712 21.5772226,20.5857864 21.0249378,20.5857864 C20.7597213,20.5857864 20.5053674,20.4804296 20.317831,20.2928932 L18.0249378,18 L12.9835977,18 C12.7263047,14.0909841 9.47412135,11 5.5,11 C4.23590829,11 3.04485894,11.3127315 2,11.8650466 Z M6,7 C5.44771525,7 5,7.44771525 5,8 C5,8.55228475 5.44771525,9 6,9 L15,9 C15.5522847,9 16,8.55228475 16,8 C16,7.44771525 15.5522847,7 15,7 L6,7 Z" fill="#000000"></path>
                                                    </g>
                                                </svg><!--end::Svg Icon-->
                                            </span>
                                        </span>
                                        <span class="nav-text">
                                            Contact Details
                                        </span>
                					</a>
                				</li>
                			</ul>
                		</div>
                	</div>
                	<!--end::Header-->
                                            
                	<!--begin::Body-->
                	<div class="card-body px-0">
                		<div class="tab-content pt-5">
                            <!--begin::Tab Content-->
                			<div class="tab-pane active show" id="event_description_tab_one" role="tabpanel">
                				<div class="container">
                					<form class="form">
                						<div class="flex-grow-1 font-weight-bold text-dark-50 py-2 py-lg-2 mr-5">
                                            {!! $event->description !!}
                                        </div>
                                    </form>
                				</div>
                			</div>
                			<!--end::Tab Content-->
                                            
                			<!--begin::Tab Content-->
                			<div class="tab-pane" id="event_description_tab_two" role="tabpanel">
                				<form class="form">
                					<div class="row">
                						<div class="col-lg-9 col-xl-6 offset-xl-3">
                							<h3 class="font-size-h6 mb-5">Contact Info:</h3>
                						</div>
                					</div>
                                            
                					<div class="form-group row">
                						<label class="col-xl-3 col-lg-3 col-form-label text-right">Contact Name</label>
                						<div class="col-lg-9 col-xl-6">
                							<input class="form-control form-control-lg form-control-solid" type="text" value="Nick">
                						</div>
                					</div>
                					<div class="form-group row">
                						<label class="col-xl-3 col-lg-3 col-form-label text-right">Contact Owner</label>
                						<div class="col-lg-9 col-xl-6">
                							<input class="form-control form-control-lg form-control-solid" type="text" value="Bold">
                						</div>
                					</div>
                					<div class="form-group row">
                						<label class="col-xl-3 col-lg-3 col-form-label text-right">Customer Name</label>
                						<div class="col-lg-9 col-xl-6">
                							<input class="form-control form-control-lg form-control-solid" type="text" value="Loop Inc.">
                							<span class="form-text text-muted">If you want your invoices addressed to a company. Leave blank to use your full name.</span>
                						</div>
                					</div>
                                            
                					<div class="separator separator-dashed my-10"></div>
                                            
                					<!--begin::Heading-->
                					<div class="row">
                						<div class="col-lg-9 col-xl-6 offset-xl-3">
                							<h3 class="font-size-h6 mb-5">Contact Info:</h3>
                						</div>
                					</div>
                					<!--end::Heading-->
                                            
                					<div class="form-group row">
                						<label class="col-xl-3 col-lg-3 col-form-label text-right">Contact Phone</label>
                						<div class="col-lg-9 col-xl-6">
                							<div class="input-group input-group-lg input-group-solid">
                								<div class="input-group-prepend"><span class="input-group-text"><i class="la la-phone"></i></span></div>
                								<input type="text" class="form-control form-control-lg form-control-solid" value="+35278953712" placeholder="Phone">
                							</div>
                							<span class="form-text text-muted">We'll never share your email with anyone else.</span>
                						</div>
                					</div>
                                            
                					<div class="form-group row">
                						<label class="col-xl-3 col-lg-3 col-form-label text-right">Email Address</label>
                						<div class="col-lg-9 col-xl-6">
                							<div class="input-group input-group-lg input-group-solid">
                								<div class="input-group-prepend"><span class="input-group-text"><i class="la la-at"></i></span></div>
                								<input type="text" class="form-control form-control-lg form-control-solid" value="nick.bold@loop.com" placeholder="Email">
                							</div>
                						</div>
                					</div>
                					<div class="form-group row">
                						<label class="col-xl-3 col-lg-3 col-form-label text-right">Company Site</label>
                						<div class="col-lg-9 col-xl-6">
                							<div class="input-group input-group-lg input-group-solid">
                								<input type="text" class="form-control form-control-lg form-control-solid" placeholder="Username" value="loop">
                								<div class="input-group-append"><span class="input-group-text">.com</span></div>
                							</div>
                						</div>
                					</div>
                				</form>
                			</div>
                			<!--end::Tab Content-->
                		</div>
                	</div>
                	<!--end::Body-->
                </div>
                <!--end::Card-->
            </div>
        </div>
    </div>
</div>

<!-- <div class="card-body m-4">
    <div class="form-group row">
        <p>{{ $event->description }}</p>
    </div> 
</div> -->

<div class="modal-footer text-right bg-gray-100 border-top-0">
    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">
        {{ trans('display.general_close') }}
    </button>
</div>
