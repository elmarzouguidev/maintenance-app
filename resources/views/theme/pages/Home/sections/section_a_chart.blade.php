<div class="card">
    <div class="card-body">
        <div class="d-sm-flex flex-wrap">

            <h4 class="card-title mb-4">{{ $chart->options['chart_title'] }}</h4>

            
        </div>
        {!! $chart->renderHtml() !!}
        <div id="stacked-column-chart" class="apex-charts" dir="ltr"></div>
    </div>
</div> 