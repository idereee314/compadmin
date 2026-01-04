<style>
.bootstrap-timepicker-widget.dropdown-menu { z-index: 1050 !important; }
</style>
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="modal-header bg-gray-100">
            <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_new')}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
        </div>
    </div>
    <div class="panel-body no-padding">
        <!--begin: Wizard-->
        <div class="wizard wizard-4" id="create-event-wizard" data-wizard-state="step-first" data-wizard-clickable="true">
            <!--begin: Wizard Nav-->
            <div class="wizard-nav">
                <div class="wizard-steps">
                    <!--begin::Wizard Step 1 Nav-->
                    <div class="wizard-step" data-wizard-type="step" data-wizard-state="current">
                        <div class="wizard-wrapper">
                            <div class="wizard-number">1</div>
                            <div class="wizard-label">
                                <div class="wizard-title">Бүртгэл</div>
                                <div class="wizard-desc">Хүсэлтийн ерөнхий мэдээлэл</div>
                            </div>
                        </div>
                    </div>
                    <!--end::Wizard Step 1 Nav-->
                    <!--begin::Wizard Step 2 Nav-->
                    <div class="wizard-step" data-wizard-type="step">
                        <div class="wizard-wrapper">
                            <div class="wizard-number">2</div>
                            <div class="wizard-label">
                                <div class="wizard-title">Холбоо барих</div>
                                <div class="wizard-desc">Утасны дугаар болон имэйл</div>
                            </div>
                        </div>
                    </div>
                    <!--end::Wizard Step 2 Nav-->
                    <!--begin::Wizard Step 3 Nav-->
                    <div class="wizard-step" data-wizard-type="step">
                        <div class="wizard-wrapper">
                            <div class="wizard-number">3</div>
                            <div class="wizard-label">
                                <div class="wizard-title">Хавсралт</div>
                                <div class="wizard-desc">Хавсралт баримт бичиг</div>
                            </div>
                        </div>
                    </div>
                    <!--end::Wizard Step 3 Nav-->
                    <!--begin::Wizard Step 4 Nav-->
                    <div class="wizard-step" data-wizard-type="step">
                        <div class="wizard-wrapper">
                            <div class="wizard-number">4</div>
                            <div class="wizard-label">
                                <div class="wizard-title">Хариу</div>
                                <div class="wizard-desc">Хариу хүлээн авах мэдээлэл</div>
                            </div>
                        </div>
                    </div>
                    <!--end::Wizard Step 4 Nav-->
                </div>
            </div>
            <!--end: Wizard Nav-->
            <!--begin: Wizard Body-->
            <div class="card card-custom card-shadowless rounded-top-0">
                <div class="card-body p-0">
                    <div class="row justify-content-center py-8 px-8 py-lg-15 px-lg-10">
                        <div class="col-xl-12 col-xxl-7">
                            <!--begin: Wizard Form-->
                            <form class="form mt-0 mt-lg-10" id="create-event-form" method="POST" action="{{ route('event.list.store') }}" enctype="multipart/form-data">
                                <input type="hidden" name="source_type" id="source_type" value="{{ @Config::get('smart.request_source_type')['local'] }}"/>
                                <input type="hidden" name="class_type" id="class_type" value="{{ @Config::get('smart.request_class_type')['request'] }}"/>
                                <input type="hidden" name="is_delivery" id="is_delivery"/>
                                <!--begin: Wizard Step 1-->
                                <div class="pb-5" data-wizard-type="step-content" data-wizard-state="current">
                                    <div class="mb-10 font-weight-bold text-dark"><h5>Хүсэлтийн мэдээлэл оруулах</h5></div>
                                    <!--begin::Input-->
                                    <div class="form-group">
                                        <label>{{ trans('display.request_type') }}: <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-solid">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="la la-book"></i>
                                                </span>
                                            </div>
                                            
                                        </div>
                                        <div class="error-here"></div>
                                    </div>
                                    <!--end::Input-->
                                    <!--begin::Input-->
                                    <div class="form-group type-reference" style="display: none;">
                                        <label>{{ trans('display.request_reference_type') }}: <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-solid">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="la la-book"></i>
                                                </span>
                                            </div>
                                            <select class="form-control select" name="type_id" id="child_type_id" data-style="form-control-solid" data-rule-required="true" data-msg-required="{{ trans('validation.required', ['Attribute' => trans('display.request_type')]) }}">
                                                <option value="">-- {{ trans('display.general_select') }} --</option>
                                            </select>
                                        </div>
                                        <span class="form-text text-muted" id="archive_after_date"></span>
                                        <div class="error-here"></div>
                                    </div>
                                    <div class="overflow-auto" id="div-party-append"></div>
                                    <!--begin::Input-->
                                    <div class="form-group">
                                        <label>{{ trans('display.request_date') }}: <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-solid date">
                                            <input type="text" class="form-control" name="request_date" id="request_date" readonly="readonly" data-rule-required="true" {{ @$today ? 'data-date-end-date='.$today : '' }} data-msg-required="{{ trans('validation.required', ['Attribute', trans('display.request_date')]) }}" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}" data-rule-date="true" data-msg-date="{{ trans('validation.date', ['Attribute', trans('display.request_date')]) }}"/>
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="la la-calendar-check-o"></i> 
                                                </span>
                                            </div>
                                        </div>
                                        <div class="error-here"></div>
                                    </div>
                                    <!--end::Input-->
                                    <div class="mb-10 font-weight-bold text-dark"><h5>Шаардлагатай үзүүлэлт</h5></div>
                                    <div id="div-attribute-append"></div>
                                </div>
                                <!--end: Wizard Step 1-->
                                <!--begin: Wizard Step 2-->
                                <div class="pb-5" data-wizard-type="step-content">
                                    <div class="mb-10 font-weight-bold text-dark"><h5>Холбоо барих мэдээлэл оруулах</h5></div>
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <!--begin::Input-->
                                            <div class="form-group">
                                                <label>{{ trans('display.request_contacts') }} 1: <span class="text-danger">*</span></label>
                                                <div class="input-group input-group-solid">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="la la-phone"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control form-control-solid" name="contact_phones[]" id="contact_phones" data-inputmask="'mask': '9{8}', 'greedy': false" data-rule-required="true" data-msg-required="{{ trans('validation.required', ['Attribute' => trans('display.request_contacts')]) }}" data-rule-digits="true" data-msg-digits="{{ trans('validation.phones', ['Attribute' => trans('display.request_contacts')]) }}"/>
                                                </div>
                                                <div class="error-here"></div>
                                            </div>
                                            <!--end::Input-->
                                        </div>
                                        <div class="col-xl-6">
                                            <!--begin::Input-->
                                            <div class="form-group">
                                                <label>{{ trans('display.request_contacts') }} 2: </label>
                                                <div class="input-group input-group-solid">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="la la-phone"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control form-control-solid" name="contact_phones[]" id="contact_phones" data-inputmask="'mask': '9{8}', 'greedy': false" data-rule-digits="true" data-msg-digits="{{ trans('validation.phones', ['Attribute' => trans('display.request_contacts')]) }}"/>
                                                </div>
                                                <div class="error-here"></div>
                                            </div>
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                    <!--begin::Input-->
                                    <div class="form-group">
                                        <label>{{ trans('display.request_contact_email') }}: <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-solid">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="la la-at"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" name="contact_email" id="contact_email" data-rule-required="true" data-msg-required="{{ trans('validation.required', ['Attribute' => trans('display.request_contact_email')]) }}" data-rule-email="true" data-msg-email="{{ trans('validation.email', ['Attribute' => trans('display.request_contact_email')]) }}" data-inputmask="'alias': 'email'"/>
                                        </div>
                                        <span class="form-text text-muted">Хариуг цахимаар авах бол энэ имэйл хаягаар илгээнэ.</span>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end: Wizard Step 2-->
                                <!--begin: Wizard Step 3-->
                                <div class="pb-5" data-wizard-type="step-content">
                                    <div class="mb-10 font-weight-bold text-dark"><h5>Баримт бичгийн бүрдүүлбэр шалгах</h5></div>
                                    <div id="div-file-append"></div>
                                </div>
                                <!--end: Wizard Step 3-->
                                <!--begin: Wizard Step 4-->
                                <div class="pb-5" data-wizard-type="step-content">
                                    <!--begin::Input-->
                                    <div class="form-group response">
                                        <label>{{ trans('display.request_response_type') }}: <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-solid">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="la la-book"></i>
                                                </span>
                                            </div>
                                            <select class="form-control select" name="response_type_id" id="response_type_id" data-style="form-control-solid" data-rule-required="true" data-msg-required="{{ trans('validation.required', ['Attribute' => trans('display.request_response_type')]) }}">
                                            </select>
                                        </div>
                                        <div class="error-here"></div>
                                    </div>
                                    <!--end::Input-->
                                    <div id="div-delivered" style="display: none;">
                                        <!--begin::Input-->
                                        <div class="form-group">
                                            <label>{{ trans('display.request_delivery_address') }}: <span class="text-danger">*</span></label>
                                            <div class="input-group input-group-solid">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="la la-map-marker"></i>
                                                    </span>
                                                </div>
                                                <div class="input-group-prepend" style="min-width: 200px">
                                                    <input type="hidden" name="city_id" id="city_id" value="1"/>
                                                    
                                                </div>
                                                <select class="form-control select2" name="khoroo_id" id="khoroo_id" data-style="form-control-solid" data-rule-required="true" data-msg-required="{{ trans('validation.required', ['Attribute' => trans('display.address_khoroo')]) }}">
                                                </select>
                                            </div>
                                            <div class="error-here"></div>
                                        </div>
                                        <!--end::Input-->
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <!--begin::Input-->
                                                <div class="form-group">
                                                    <label>{{ trans('display.request_delivery_street') }}: <span class="text-danger">*</span></label>
                                                    <div class="input-group input-group-solid">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="la la-map-marker"></i>
                                                            </span>
                                                        </div>
                                                        <input type="text" class="form-control form-control-solid" name="street" id="street" data-rule-required="true" data-msg-required="{{ trans('validation.required', ['Attribute' => trans('display.request_delivery_street')]) }}"/>
                                                    </div>
                                                    <div class="error-here"></div>
                                                </div>
                                                <!--end::Input-->
                                            </div>
                                            <div class="col-xl-6">
                                                <!--begin::Input-->
                                                <div class="form-group">
                                                    <label>{{ trans('display.request_delivery_building') }}: <span class="text-danger">*</span></label>
                                                    <div class="input-group input-group-solid">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="la la-map-marker"></i>
                                                            </span>
                                                        </div>
                                                        <input type="text" class="form-control form-control-solid" name="building" id="building" data-rule-required="true" data-msg-required="{{ trans('validation.required', ['Attribute' => trans('display.request_delivery_building')]) }}"/>
                                                    </div>
                                                    <div class="error-here"></div>
                                                </div>
                                                <!--end::Input-->
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <!--begin::Input-->
                                                <div class="form-group">
                                                    <label>{{ trans('display.request_delivery_door') }}: <span class="text-danger">*</span></label>
                                                    <div class="input-group input-group-solid">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="la la-map-marker"></i>
                                                            </span>
                                                        </div>
                                                        <input type="text" class="form-control form-control-solid" name="door" id="door" data-rule-required="true" data-msg-required="{{ trans('validation.required', ['Attribute' => trans('display.request_delivery_door')]) }}"/>
                                                    </div>
                                                    <div class="error-here"></div>
                                                </div>
                                                <!--end::Input-->
                                            </div>
                                            <div class="col-xl-6">
                                                
                                            </div>
                                        </div>
                                        <!--begin::Input-->
                                        <div class="form-group">
                                            <label>{{ trans('display.general_description') }}: </label>
                                            <textarea class="form-control form-control-solid" name="description" id="description" row="3"></textarea>
                                        </div>
                                        <!--end::Input-->
                                    </div>
                                </div>
                                <!--end: Wizard Step 4-->
                                <!--begin: Wizard Actions-->
                                <div class="d-flex justify-content-between border-top mt-5 pt-10">
                                    <div class="mr-2">
                                        <button type="button" class="btn btn-light-primary font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-prev">{{ trans('display.general_previous') }}</button>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-success font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-submit">{{ trans('display.general_save') }}</button>
                                        <button type="button" class="btn btn-primary font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-next">{{ trans('display.general_next') }}</button>
                                    </div>
                                </div>
                                <!--end: Wizard Actions-->
                            </form>
                            <!--end: Wizard Form-->
                        </div>
                    </div>
                </div>
            </div>
            <!--end: Wizard Bpdy-->
        </div>
        <!--end: Wizard-->
    </div>
    <div class="modal-footer">
        <button type="button" id="close" class="btn btn-default" data-dismiss="modal">{{ trans('display.general_close') }}</button>
        <button type="submit" class="btn btn-success" id="btn-submit">{{ trans('display.general_save') }}</button>
    </div>
</div>
<!-- LAYER -->
<script type="text/javascript" src="{{asset('js/script/base/corelayers.js')}}"></script>
<script type="text/javascript" src="{{asset('js/script/event/map_script_event.js')}}"></script>