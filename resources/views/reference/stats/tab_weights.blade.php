<div class="row">
    <div class="col-xl-12">
        <!--begin::Card-->
        <div class="card card-custom gutter-b">                                        
            <div class="card-body">
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
                <h3 class="card-label"><strong> Тэмцээнд нийт <?php echo $totalWeights; ?> жин байгаагаас <?php echo $totalRegAllWeights ?> жинд хүмүүс бүртгэгдэж үүнээс <?php echo $totalApprovalWeights; ?> жингийн хүмүүс бүртгэлээ баталгаажуулсан байна.</strong
            </div>
        </div>
        <!--end::Card-->
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <!--begin::Card-->
        <div class="card card-custom gutter-b">                            
            <div class="card-header">
                <div class="card-title">
                <?php
                $totalCount = 0;
                foreach ($countedWeightForOrg as $counted) {
                    $totalCount += $counted->counted_weight;
                }
                ?>
                <h3 class="card-label"><strong> Нийт жингийн жагсаалт болон [Тэмцээнд нийт <?php echo $totalCount; ?> жин байна.]</strong></h3>
                </div>
            </div>
            <div class="card-body">
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
        <!--end::Card-->
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <!--begin::Card-->
        <div class="card card-custom gutter-b">
            <div class="card-header">
                <div class="card-title">
                    <?php
                    $totalCount = 0;
                    foreach ($registredCountedWeightForOrgApproved as $counted) {
                        $totalCount += $counted->total_count;
                    }
                    ?>
                    <h3 class="card-label"><strong> Тамирчид бүртгүүлсэн ангилал [Баталгаажсан] болон [Нийт <?php echo $totalCount; ?> жин байна.]</strong></h3>
                </div>
            </div>
            <div class="card-body">
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
        <!--end::Card-->
        <!--begin::Card-->
        <div class="card card-custom gutter-b">
            <div class="card-header">
                <div class="card-title">
                    <?php
                    $totalCount = 0;
                    foreach ($registredCountedWeightForOrgAll as $counted) {
                        $totalCount += $counted->total_count;
                    }
                    ?>
                    <h3 class="card-label"><strong> Тамирчид бүртгүүлсэн ангилал [Бүгд] болон [Нийт <?php echo $totalCount; ?> жин байна.] </strong></h3>
                </div>
            </div>
            <div class="card-body">                                            
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
                                <th class="text-center">{{trans('display.general_athlete_count')}}</th>
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
        <!--end::Card-->
    </div>
</div>