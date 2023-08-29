<div class="table-responsive">
    <table class="cell-border" id="athleteTable" style="width:100%">
    <!-- <thead>
        <tr>
            <th>#</th>
            <th class="text-center">{{trans('display.payment_id')}}</th>
            <th class="text-center">{{trans('display.payment_date')}}</th>
            <th class="text-center">{{trans('display.profile_title')}}</th>                                                
        </tr>
    </thead> -->
    <tbody>
        @foreach($athlete as $athletes)
            <tr>
                <td class="text-center align-middle"><strong>{{ $loop->index + 1 }}</strong></td>
                <td class="text-center"><img class="mb-1" alt="profile" src="{{ \Storage::disk('s3')->url($athletes->member_profile_photo) }}" style="width: 80px; height: 80px; border-radius: 50%" /></td>
                <td>
                    <strong>                                                                
                            <img class="mb-1 rounded" src="/assets/images/flags/4x3/{{ Config::get('enums.country_alpha')[$athletes->member_country] }}.svg" alt="flag" width="25" height="15">
                            {{ $athletes->member_lname }} <strong>{{ $athletes->member_fname }}</strong>
                    </strong>
                    <span class="text-dark-75 line-height-sm d-block pb-2">
                        <i class="la la-address-book"></i> {{ $athletes->member_birthday }},
                        <i class="la la-phone"></i> {{ Config::get("enums.gender_code")[@$athletes->member_gender] }}
                    </span>
                    <span class="text-dark-75 line-height-sm d-block pb-2">
                        <a href="/profile/{{$athletes->member_id}}" type="button" class="btn btn-primary" target="_blank">View profile</a>
                    </span>
                </td>
                <td>{{ $athletes->member_birthday }}</td>
                <td>{{ Config::get("enums.gender_code")[@$athletes->member_gender] }}</td>
                <td><a href="/profile/{{$athletes->member_id}}" type="button" class="btn btn-primary" target="_blank">View profile</a></td>
            </tr>
        @endforeach 
    </tbody>
    </table>                                                                              
</div>