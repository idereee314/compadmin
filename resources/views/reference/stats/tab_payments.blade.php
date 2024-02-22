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
            @forelse($finance as $index => $payment)
                <tr>
                    <td class="text-center border-right">{{ $index + 1 }}</td>
                    <td class="text-center border-right"><strong>{{ $payment->paymentid ?? 'N/A' }}</strong></td>
                    <td class="text-center border-right"><strong>{{ $payment->date ?? 'N/A' }}</strong></td>
                    <td class="min-w-200px text-center border-right"><strong>{{ $payment->lastname ?? 'N/A' }} {{ $payment->firstname ?? 'N/A' }} </strong></td>
                    <td class="text-center border-right"><strong>{{ $payment->academyname ?? 'N/A' }}</strong></td>
                    <td class="text-center border-right"><strong>{{ $payment->amount ?? 'N/A' }}</strong></td>
                    <td class="text-center border-right"><strong>{{ isset(Config::get("enums.payment_from_type")[$payment->from_type]) ? Config::get("enums.payment_from_type")[$payment->from_type] : 'N/A' }}</strong></td>
                    <td class="text-center border-right">
                        <span class="label label-{{ @$payment->status == 1 ? 'success' : 'warning' }} label-inline font-weight-lighter mr-2">
                            {{ isset(Config::get("enums.payment_status")[$payment->status]) ? Config::get("enums.payment_status")[$payment->status] : 'N/A' }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No finance records available.</td>
                </tr>
            @endforelse
        </tbody>  
    </table>                                                                              
</div>
