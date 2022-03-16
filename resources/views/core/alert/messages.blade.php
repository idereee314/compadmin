@if(@$response && $response['status'] == "success")
<div class="alert alert-custom alert-notice alert-light-success fade show mb-5" role="alert">
    <div class="alert-icon"><i class="far fa-check-circle icon-lg"></i></div>
    <div class="alert-text">{!! $response['msg']; !!}</div>
</div>
@elseif(@$response && $response['status'] == "error")
<div class="alert alert-custom alert-notice alert-light-danger fade show mb-5">
    <div class="alert-icon"><i class="flaticon-warning"></i></div>
        {!! @$response['msg'] ? '<strong>'.$response['msg'].'</strong>' : '' !!}
        @if(is_object(@$response['errors']))
        {!! html_entity_decode(HTML::ul(@$response['errors']->all())) !!}
        @else
            <div class="alert-text">{!! @$response['errors']; !!}</div>
        @endif
</div>
@elseif(@$response && $response['status'] == "info")
<div class="alert alert-custom alert-notice alert-light-info fade show mb-5" role="alert">
    <div class="alert-icon">
        <i class="flaticon-warning"></i>
    </div>
    <div class="alert-text">{!! $response['msg']; !!}</div>
</div>
@elseif(@$response && $response['status'] == "warning")
<div class="alert alert-custom alert-notice alert-light-warning fade show mb-5" role="alert">
    <div class="alert-icon">
        <i class="flaticon-warning"></i>
    </div>
    <div class="alert-text">{!! $response['msg']; !!}</div>
</div>
@elseif(@$response && $response['status'] == "success-short")
<div class="alert alert-success">
    {!! $response['msg']; !!}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@elseif(@$response && $response['status'] == "info-short")
<div class="alert alert-info">
    {!! $response['msg']; !!}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@elseif(@$response && $response['status'] == "warning-short")
<div class="alert alert-warning">
    {!! $response['msg']; !!}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@elseif(@$response && $response['status'] == "error-short")
<div class="alert alert-danger">
{!! @$response['msg'] ? $response['msg'] : '' !!}
    @if(is_object(@$response['errors']))
    {!! HTML::ul(@$response['errors']->all()) !!}
    @else
    <strong> {!! @$response['errors']; !!}</strong>
    @endif
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
