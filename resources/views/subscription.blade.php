@include('templates/header')
<div class="contain">
    <div class="content">
        @if(session('success'))
            <span id="success_message">{{session('success')}}</span><br/>
        @elseif (session('error_subs'))
            <span id="error_message">{{session('error_subs')}}</span><br/>
        @elseif (session('error_log'))
            <span id="error_message">{{session('error_log')}}</span><br/>
        @endif
        @foreach ($subscriptions as $subs)
            @if ($subs->subs_name == '小店')
                <div class="sbubs-container sc-color-shouten">
            @elseif ($subs->subs_name == '一年小店')
                <div class="sbubs-container sc-color-ichinenshouten">
            @elseif ($subs->subs_name == 'ミドル')
                <div class="sbubs-container sc-color-middle">
            @elseif ($subs->subs_name == '一年ミドル')
                <div class="sbubs-container sc-color-ichinenmiddle">
            @elseif ($subs->subs_name == '大店')
                <div class="sbubs-container sc-color-oodana">
            @endif
                <div class="subs-header"><span>{{$subs->subs_name}} プラン</span></div>
                <div class="subs-boddy">
                    <div class="subs-notice"><span>{{$subs->price_notice}}</span></div>
                    <div class="subs-text"><span>{{ number_format($subs->subs_price) }}円</span></div>
                    <div class="line-subs"></div>
                    <div class="subs-notice"><span>{{$subs->text_notice}}</span></div>
                    <div class="subs-text"><span>{{$subs->subs_text}}</span></div>
                    <div class="line-subs"></div>
                    <div class="subs-notice"><span>{{$subs->duration_notice}}</span></div>
                    <div class="subs-text"><span>{{$subs->subs_duration_text}}</span></div>
                    @if (!Auth::check())
                        <div class="subs-button"><a href="/plans/subscribe/{{$subs->id}}"><button>{{$subs->subs_name}}に申し込み</button></a></div>
                    @endif
                    @can('isNotSubscribed')
                        <div class="subs-button"><a href="/plans/subscribe/{{$subs->id}}"><button>{{$subs->subs_name}}に申し込み</button></a></div>
                    @elsecan('isSubscribed')
                        <div class="subs-button"><a href="/plans_up/subscribe/{{$subs->id}}"><button>{{$subs->subs_name}}に変える</button></a></div>
                    @endcan
                </div>
            </div>
        @endforeach
    </div>
</div>
@include('templates/footer')