<div class="row">
    <div class="col-xl-4">
        <!--begin::Card-->
        <div class="card card-custom gutter-b">
            <div class="card-header">
                <div class="card-title title-center">
                    <h3 class="card-label"><strong> Нийт бүртгэл </strong></h3>
                </div>
            </div>
            <div class="card-body">
            @if(count($eventRegistrationStatusStats) > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-head-custom">
                        <thead>
                            <tr>                                                                
                                <th class="text-center">{{trans('display.general_status')}}</th>
                                <th class="text-center">{{trans('display.general_athlete_count')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eventRegistrationStatusStats as $stats)
                                <tr>
                                    <td class="min-w-200px border-right"><strong>{{ Config::get("enums.event_registration_status_for_stats")[@$stats->status] }}</strong></td>
                                    <td class="text-center border-right"><strong>{{ $stats->status_count }}</strong></td>
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
        <!--end::Card-->
        <!--begin::Card-->
        <div class="card card-custom gutter-b">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label"><strong> {{ trans('display.comp_country_name') }} [Баталгаажсан] </strong></h3>
                </div>
            </div>
            <div class="card-body">
            @if(count($eventRegistrationCountryStats) > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-head-custom">
                        <thead>
                            <tr>                                
                                <th class="text-center"> {{ trans('display.comp_country_name') }} </th> 
                                <th class="text-center">{{trans('display.general_athlete_count')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eventRegistrationCountryStats as $stats)
                                <tr>                                                                                                         
                                    <td class="min-w-200px text-center border-right"><strong>{{ $stats->name }} - {{ strtoupper($stats->abbreviation) }}</strong></td>
                                    <td class="text-center border-right"><strong>{{ $stats->count_country }}</strong></td>
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
        <!--end::Card-->
        <!--begin::Card-->
        <div class="card card-custom gutter-b">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label"><strong> {{ trans('display.comp_country_name') }} [Бүгд]</strong></h3>
                </div>
            </div>
            <div class="card-body">
            @if(count($eventRegistrationCountryAllStats) > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-head-custom">
                        <thead>
                            <tr>                                
                                <th class="text-center"> {{ trans('display.comp_country_name') }} </th>
                                <th class="text-center">{{trans('display.general_athlete_count')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eventRegistrationCountryAllStats as $stats)
                                <tr>                                                                                                         
                                    <td class="min-w-200px text-center border-right"><strong>{{ $stats->name }} - {{ strtoupper($stats->abbreviation) }}</strong></td>
                                    <td class="text-center border-right"><strong>{{ $stats->count_country }}</strong></td>
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
        <!--end::Card-->
        <!--begin::Card-->
        <div class="card card-custom gutter-b">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label"><strong> Байгууллага [Баталгаажсан] </strong></h3>
                </div>
            </div>
            <div class="card-body">
            @if(count($eventRegistrationOrgTypeStats) > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-head-custom">
                        <thead>
                            <tr>                                
                                <th class="text-center"> Байгууллага </th>
                                <th class="text-center">{{trans('display.general_org_count')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eventRegistrationOrgTypeStats as $stats)
                                <tr>                                                                                                         
                                    <td class="min-w-200px text-center border-right"><strong>{{ Config::get("enums.org_type")[@$stats->org_type] }}</strong></td>
                                    <td class="text-center border-right"><strong>{{ $stats->org_count }}</strong></td>
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
        <!--end::Card-->
        <!--begin::Card-->
        <div class="card card-custom gutter-b">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label"><strong> Байгууллага [Бүгд]</strong></h3>
                </div>
            </div>
            <div class="card-body">
            @if(count($eventRegistrationOrgTypeStats) > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-head-custom">
                        <thead>
                            <tr>                                
                                <th class="text-center"> Байгууллага </th>
                                <th class="text-center">{{trans('display.general_org_count')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eventRegistrationOrgTypeStats as $stats)
                                <tr>                                                                                                         
                                    <td class="min-w-200px text-center border-right"><strong>{{ Config::get("enums.org_type")[@$stats->org_type] }}</strong></td>
                                    <td class="text-center border-right"><strong>{{ $stats->org_count }}</strong></td>
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
        <!--end::Card-->
        <!--begin::Card-->
        <div class="card card-custom gutter-b">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label"><strong> Хүйс [Баталгаажсан] </strong></h3>
                </div>
            </div>
            <div class="card-body">
            @if(count($eventRegistrationGenderStats) > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-head-custom">
                        <thead>
                            <tr>                                
                                <th class="text-center"> Хүйс </th>
                                <th class="text-center">{{trans('display.general_athlete_count')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eventRegistrationGenderStats as $stats)
                                <tr>                                                                                                         
                                    <td class="min-w-200px text-center border-right"><strong>{{ Config::get("enums.gender_code")[@$stats->gender_code] }}</strong></td>
                                    <td class="text-center border-right"><strong>{{ $stats->gender_count }}</strong></td>
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
        <!--end::Card-->
        <!--begin::Card-->
        <div class="card card-custom gutter-b">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label"><strong> Хүйс [Бүгд]</strong></h3>
                </div>
            </div>
            <div class="card-body">
            @if(count($eventRegistrationGenderAllStats) > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-head-custom">
                        <thead>
                            <tr>                                
                                <th class="text-center"> Хүйс </th>
                                <th class="text-center">{{trans('display.general_athlete_count')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eventRegistrationGenderAllStats as $stats)
                                <tr>                                                                                                         
                                    <td class="min-w-200px text-center border-right"><strong>{{ Config::get("enums.gender_code")[@$stats->gender_code] }}</strong></td>
                                    <td class="text-center border-right"><strong>{{ $stats->gender_count }}</strong></td>
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
        <!--end::Card-->
    </div>
    <div class="col-xl-4">
        <!--begin::Card-->
        <div class="card card-custom gutter-b">                            
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label"><strong> Тэмцээний ангилал [Баталгаажсан]</strong></h3>
                </div>
            </div>
            <div class="card-body">
            @if(count($eventRegistrationEntriesStats) > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-head-custom">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-center"> Тэмцээнд оролцох төрлүүд </th>
                                <th class="text-center"> Хүйс </th>
                                <th class="text-center">{{trans('display.general_athlete_count')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eventRegistrationEntriesStats as $stats)
                                <tr>
                                    <td class="text-center border-right">{{ ++$loop->index }}</td>
                                    <td width="60%" class="border-right"><strong>{{ $stats->name }}</strong></td>
                                    <td width="15%" class="text-center border-right"><strong>{{ Config::get("enums.gender_code_for_stats")[@$stats->gender_code] }}</strong></td>
                                    <td width="15%" class="text-center border-right"><strong>{{ $stats->entry_count }}</strong></td>
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
        <!--end::Card-->
        <!--begin::Card-->
        <div class="card card-custom gutter-b">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label"><strong> Тэмцээний ангилал [Бүгд] </strong></h3>
                </div>
            </div>
            <div class="card-body">
            @if(count($eventRegistrationEntriesAllStats) > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-head-custom">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-center"> Тэмцээнд оролцох төрлүүд </th>
                                <th class="text-center"> Хүйс </th>
                                <th class="text-center">{{trans('display.general_athlete_count')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eventRegistrationEntriesAllStats as $stats)
                                <tr>
                                    <td class="text-center border-right">{{ ++$loop->index }}</td>
                                    <td width="60%" class="border-right"><strong>{{ $stats->name }}</strong></td>
                                    <td width="15%" class="text-center border-right"><strong>{{ Config::get("enums.gender_code_for_stats")[@$stats->gender_code] }}</strong></td>
                                    <td width="15%" class="text-center border-right"><strong>{{ $stats->entry_count }}</strong></td>
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
        <!--end::Card-->
    </div>
    <div class="col-xl-4">
        <!--begin::Card-->
        <div class="card card-custom gutter-b">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label"><strong> Тэмцээнд бүртгүүлсэн академи [Баталгаажсан]</strong></h3>
                </div>
            </div>
            <div class="card-body">
            @if(count($eventRegistrationAcademyStats) > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-head-custom">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-center">{{trans('display.comp_academy_name')}}</th>
                                <th class="text-center">{{trans('display.general_athlete_count')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eventRegistrationAcademyStats as $stats)
                                <tr>
                                    <td class="text-center border-right">{{ ++$loop->index }}</td>
                                    <td class="min-w-200px text-center border-right"><strong>{{ $stats->name }}</strong></td>
                                    <td class="text-center border-right"><strong>{{ $stats->academy_count }}</strong></td>
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
        <!--end::Card-->
        <!--begin::Card-->
        <div class="card card-custom gutter-b">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label"><strong> Тэмцээнд бүртгүүлсэн академи [Бүгд]</strong></h3>
                </div>
            </div>
            <div class="card-body">
            @if(count($eventRegistrationAllAcademyStats) > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-head-custom">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-center">{{trans('display.comp_academy_name')}}</th>
                                <th class="text-center">{{trans('display.general_athlete_count')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eventRegistrationAllAcademyStats as $stats)
                                <tr>
                                    <td class="text-center border-right">{{ ++$loop->index }}</td>
                                    <td class="min-w-200px text-center border-right"><strong>{{ $stats->name }}</strong></td>
                                    <td class="text-center border-right"><strong>{{ $stats->academy_count }}</strong></td>
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
        <!--end::Card-->
    </div>
</div>