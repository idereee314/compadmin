<div class="row">
    <div class="col-lg-12">
        <div class="card mb-8">
        	<div class="card-body">
        		<div class="p-6">
                    <?php
                    $totalWeights = 0;
                    $totalApprovalWeights = 0;
                    $totalRegAllWeights = 0;
                    foreach ($countedWeightForOrg as $counted) {
                        $totalWeights += $counted->counted_weight;
                    }
                    foreach ($registredCountedWeightForOrgApproved as $counted) {
                        $totalApprovalWeights += $counted->total_count;
                    }
                    foreach ($registredCountedWeightForOrgAll as $counted) {
                        $totalRegAllWeights += $counted->total_count;   
                    }                                         
                    ?>
                    <h2 class="card-label"><strong> Тэмцээнд нийт <?php echo $totalWeights; ?> жин байгаагаас <?php echo $totalRegAllWeights ?> жинд хүмүүс бүртгэгдэж үүнээс <?php echo $totalApprovalWeights; ?> жингийн хүмүүс бүртгэлээ баталгаажуулсан байна.</strong></h2>
        			<!--begin::Accordion-->
        			<div class="accordion accordion-light accordion-light-borderless accordion-svg-toggle" id="accordionExample1">
        				<!--begin::Item-->
        				<div class="card">
        					<!--begin::Header-->
        					<div class="card-header" id="headingOne1">
        						<div class="card-title collapsed" data-toggle="collapse" data-target="#collapseOne1" aria-expanded="false" aria-controls="collapseOne1" role="button">
        							<span class="svg-icon svg-icon-primary"><!--begin::Svg Icon | path:/metronic/theme/html/demo3/dist/assets/media/svg/icons/Navigation/Angle-double-right.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero"></path>
                                                <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) "></path>
                                            </g>
                                        </svg>
                                        <!--end::Svg Icon-->
                                    </span>
                                    <div class="card-label text-dark pl-4">
                                        <?php
                                        $totalCount = 0;
                                        foreach ($countedWeightForOrg as $counted) {
                                            $totalCount += $counted->counted_weight;
                                        }
                                        ?>
                                        Нийт жингийн жагсаалт болон [Тэмцээнд нийт <?php echo $totalCount; ?> жин байна.]
                                    </div>
        						</div>
        					</div>
        					<!--end::Header-->
        					<!--begin::Body-->
        					<div id="collapseOne1" class="collapse" aria-labelledby="headingOne1" data-parent="#accordionExample1" style="">
        						<!-- <div class="card-body text-dark-50 font-size-lg pl-12">
                                        
                                </div> -->
                                <div class="card-body text-dark-50 font-size-lg pl-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered table-head-custom" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th class="text-center">{{trans('display.general_category')}}</th>
                                                    <th class="text-center">{{trans('display.human_gender_code')}}</th>
                                                    <th class="text-center">{{trans('display.comp_entry_weight')}}</th>
                                                    <th class="text-center">{{trans('display.comp_entry_belt')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($statsWeightForOrg as $weights)
                                                    <tr>
                                                        <td class="text-center border-right">{{ ++$loop->index }}</td>
                                                        <td class="text-center border-right"><strong>{{ $weights->entry_name }}</strong></td>
                                                        <td class="text-center border-right"><strong>{{ Config::get("enums.gender_code")[@$weights->gender_code] }}</strong></td>
                                                        <td class="text-center border-right"><strong>{{ $weights->weight }}</strong></td>
                                                        <td class="min-w-200px text-center border-right"><strong>{{ $weights->belt_name }}</strong></td>
                                                    </tr>
                                                @endforeach 
                                            </tbody>  
                                        </table>                                                                              
                                    </div>
                                </div>
        					</div>
        					<!--end::Body-->
        				</div>
        				<!--end::Item-->
        				<!--begin::Item-->
        				<div class="card border-top-0">
        					<!--begin::Header-->
        					<div class="card-header" id="headingTwo1">
        						<div class="card-title collapsed" data-toggle="collapse" data-target="#collapseTwo1" aria-expanded="false" aria-controls="collapseTwo1" role="button">
        							<span class="svg-icon svg-icon-primary"><!--begin::Svg Icon | path:/metronic/theme/html/demo3/dist/assets/media/svg/icons/Navigation/Angle-double-right.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero"></path>
                                                <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) "></path>
                                            </g>
                                        </svg>
                                        <!--end::Svg Icon-->
                                    </span>
                                    <div class="card-label text-dark pl-4">
                                        <?php
                                        $totalCount = 0;
                                        foreach ($registredCountedWeightForOrgApproved as $counted) {
                                            $totalCount += $counted->total_count;
                                        }
                                        ?>
                                        Тамирчид бүртгүүлсэн ангилал [Баталгаажсан] болон [Нийт <?php echo $totalCount; ?> жин байна.]
                                    </div>
        						</div>
        					</div>
        					<!--end::Header-->
        					<!--begin::Body-->
        					<div id="collapseTwo1" class="collapse" aria-labelledby="headingTwo1" data-parent="#accordionExample1" style="">
        						<div class="card-body text-dark-50 font-size-lg pl-12">
                                    @if(count($registredWeightForOrgApproved) > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover table-bordered table-head-custom">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th class="text-center">{{trans('display.comp_academy_name')}}</th>
                                                        <th class="text-center">{{trans('display.general_athlete_count')}}</th>
                                                        <th class="text-center">{{trans('display.general_athlete_count')}}</th>
                                                        <th class="text-center">{{trans('display.general_athlete_count')}}</th>
                                                        <th class="text-center">{{trans('display.general_athlete_count')}}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($registredWeightForOrgApproved as $stats)
                                                        <tr>
                                                            <td class="text-center border-right">{{ ++$loop->index }}</td>
                                                            <td class="min-w-200px text-center border-right"><strong>{{ $stats->category_name }}</strong></td>
                                                            <td class="text-center border-right"><strong>{{ $stats->weight }}</strong></td>
                                                            <td class="text-center border-right"><strong>{{ $stats->belt_name }}</strong></td>
                                                            <td class="text-center border-right"><strong>{{ $stats->athlete_count }}</strong></td>
                                                            <td class="text-center border-right"><strong>{{ Config::get("enums.gender_code")[@$stats->gender_code] }}</strong></td>
                                                        </tr>
                                                    @endforeach 
                                                </tbody>  
                                            </table>                                                                              
                                        </div>
                                    @else
                                        <tr>
                                            <td colspan="12" class="text-center"><strong>{{ trans('display.general_no_athlete') }}</strong></td>
                                        </tr>
                                    @endif 
        						</div>
        					</div>
        					<!--end::Body-->
        				</div>
        				<!--end::Item-->
        				<!--begin::Item-->
        				<div class="card">
        					<!--begin::Header-->
        					<div class="card-header" id="headingThree1">
        						<div class="card-title collapsed" data-toggle="collapse" data-target="#collapseThree1" aria-expanded="false" aria-controls="collapseThree1" role="button">
        							<span class="svg-icon svg-icon-primary"><!--begin::Svg Icon | path:/metronic/theme/html/demo3/dist/assets/media/svg/icons/Navigation/Angle-double-right.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero"></path>
                                                <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) "></path>
                                            </g>
                                        </svg><!--end::Svg Icon-->
                                    </span>
                                    <div class="card-label text-dark pl-4">
                                        <?php
                                        $totalCount = 0;
                                        foreach ($registredCountedWeightForOrgAll as $counted) {
                                            $totalCount += $counted->total_count;
                                        }
                                        ?>
                                        Тамирчид бүртгүүлсэн ангилал [Бүгд] болон [Нийт <?php echo $totalCount; ?> жин байна.]
                                    </div>
        						</div>
        					</div>
        					<!--end::Header-->
        					<!--begin::Body-->
        					<div id="collapseThree1" class="collapse" aria-labelledby="headingThree1" data-parent="#accordionExample1" style="">
        						<div class="card-body text-dark-50 font-size-lg pl-12">
                                    @if(count($registredWeightForOrgAll) > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover table-bordered table-head-custom">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th class="text-center">{{trans('display.comp_academy_name')}}</th>
                                                        <th class="text-center">{{trans('display.general_athlete_count')}}</th>
                                                        <th class="text-center">{{trans('display.general_athlete_count')}}</th>
                                                        <th class="text-center">{{trans('display.general_athlete_count')}}</th>
                                                        <th class="text-center">{{trans('display.general_athlete_count')
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($registredWeightForOrgAll as $stats)
                                                        <tr>
                                                            <td class="text-center border-right">{{ ++$loop->index }}</td>
                                                            <td class="min-w-200px text-center border-right"><strong>{{ $stats->category_name }}</strong></td>
                                                            <td class="text-center border-right"><strong>{{ $stats->weight }}</strong></td>
                                                            <td class="text-center border-right"><strong>{{ $stats->belt_name }}</strong></td>
                                                            <td class="text-center border-right"><strong>{{ $stats->athlete_count }}</strong></td>
                                                            <td class="text-center border-right"><strong>{{ Config::get("enums.gender_code")[@$stats->gender_code] }}</strong></td>
                                                        </tr>
                                                    @endforeach 
                                                </tbody>  
                                            </table>                                                                              
                                        </div>
                                    @else
                                        <tr>
                                            <td colspan="12" class="text-center"><strong>{{ trans('display.general_no_athlete') }}</strong></td>
                                        </tr>
                                    @endif
                                </div>
        					</div>
        					<!--end::Body-->
        				</div>
        				<!--end::Item-->
        			</div>
        			<!--end::Accordion-->
        		</div>
        	</div>
        </div>
    </div>
</div>