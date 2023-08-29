<div class="table-responsive">
    <table class="table table-hover table-bordered table-head-custom" id="financeTable" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th class="text-center">{{trans('display.payment_id')}}</th>
                <th class="text-center">{{trans('display.payment_date')}}</th>
                <th class="text-center">{{trans('display.profile_title')}}</th>
                <th class="text-center">{{trans('display.comp_academy_name')}}</th>
                <th class="text-center">{{trans('display.general_amount')}}</th>
                <th class="text-center">{{trans('display.payment_from_type')}}</th>
                <th class="text-center">{{trans('display.general_status')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($finance as $payment)
                <tr>
                    <td class="text-center border-right">{{ ++$loop->index }}</td>
                    <td class="text-center border-right"><strong>{{ $payment->paymentid }}</strong></td>
                    <td class="text-center border-right"><strong>{{ $payment->date }}</strong></td>
                    <td class="min-w-200px text-center border-right"><strong>{{ $payment->lastname }} {{ $payment->firstname }} </strong></td>
                    <td class="text-center border-right"><strong>{{ $payment->academyname }}</strong></td>
                    <td class="text-center border-right"><strong>{{ $payment->amount }}</strong></td>
                    <td class="text-center border-right"><strong>{{ Config::get("enums.payment_from_type")[$payment->from_type] }}</strong></td>
                    <td class="text-center border-right">
                        <span class="label label-{{ $payment->status == 1 ? 'success' : 'warning' }} label-inline font-weight-lighter mr-2">
                            {{ Config::get("enums.payment_status")[$payment->status] }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>  
    </table>                                                                              
</div>