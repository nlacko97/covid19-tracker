@extends('layouts.app')

@section('content')
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="font-weight-bold text-primary mt-1">About this project</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <p>
                            This website has been independently developed for personal purposes to track and visualize
                            current and historic data of the <span class="font-weight-bold">COVID-19</span> pandemic
                            throughout the world starting at the
                            beginning
                            of the year 2020. The data source is linked below. The data on this website shouldn't be
                            used for professional analysis or medical advice. <br>
                            Data is refreshed twice every day.
                            <hr>
                            This project is open-source and contributions are welcome. <br>
                            You can support this project by buying me a coffee by clicking on the link below. <br>
                            Data source: <a href="https://github.com/CSSEGISandData/COVID-19" target="_blank">Johns
                                Hopkins
                                CSSE</a><br>
                            You can find me on: <a href="https://github.com/nlacko97/covid19-tracker"><i
                                    class="fab fa-github"></i></a> <a
                                href="https://www.linkedin.com/in/l%C3%A1szl%C3%B3-nagy-314583151/"><i
                                    class="fab fa-linkedin"></i></a> <br>
                        </p>
                        <div style="bottom: 0;">
                            <style>
                                .bmc-button img {
                                    height: 25px !important;
                                    width: 30px !important;
                                    margin-bottom: 1px !important;
                                    box-shadow: none !important;
                                    border: none !important;
                                    vertical-align: middle !important;
                                }

                                .bmc-button {
                                    padding: 7px 10px 7px 10px !important;
                                    line-height: 35px !important;
                                    height: 51px !important;
                                    min-width: 217px !important;
                                    text-decoration: none !important;
                                    display: inline-flex !important;
                                    color: #ffffff !important;
                                    background-color: #79D6B5 !important;
                                    border-radius: 5px !important;
                                    border: 1px solid transparent !important;
                                    padding: 7px 10px 7px 10px !important;
                                    font-size: 12px !important;
                                    letter-spacing: -0.08px !important;
                                    box-shadow: 0px 1px 2px rgba(190, 190, 190, 0.5) !important;
                                    -webkit-box-shadow: 0px 1px 2px 2px rgba(190, 190, 190, 0.5) !important;
                                    margin: 0 auto !important;
                                    font-family: 'Lato', sans-serif !important;
                                    -webkit-box-sizing: border-box !important;
                                    box-sizing: border-box !important;
                                    -o-transition: 0.3s all linear !important;
                                    -webkit-transition: 0.3s all linear !important;
                                    -moz-transition: 0.3s all linear !important;
                                    -ms-transition: 0.3s all linear !important;
                                    transition: 0.3s all linear !important;
                                }

                                .bmc-button:hover,
                                .bmc-button:active,
                                .bmc-button:focus {
                                    -webkit-box-shadow: 0px 1px 2px 2px rgba(190, 190, 190, 0.5) !important;
                                    text-decoration: none !important;
                                    box-shadow: 0px 1px 2px 2px rgba(190, 190, 190, 0.5) !important;
                                    opacity: 0.85 !important;
                                    color: #ffffff !important;
                                }
                            </style>
                            <link href="https://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext"
                                rel="stylesheet"><a class="bmc-button" target="_blank"
                                href="https://www.buymeacoffee.com/nagylaszlo"><img
                                    src="https://cdn.buymeacoffee.com/buttons/bmc-new-btn-logo.svg"
                                    alt="Buy me a coffee"><span style="margin-left:15px;font-size:19px !important;">Buy
                                    me a coffee</span></a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <img src="{{ asset('assets/visual_data.svg') }}" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@if ($country)
<div class="row mt-2" id="countrySummary">
    <div class="col-md-6 col-sm-12">
        <div class="card shadow h-100">
            <div class="card-body">
                <div class="d-none d-md-block d-lg-block d-xl-block">
                    <div class="d-flex flex-row justify-content-between mb-4">
                        <h5>
                            <img src="https://www.countryflags.io/{{ $country->iso2 }}/flat/24.png" class="img-fluid mr-3">
                            {{ $country->name }} overview
                        </h5>
                        <p class="text-gray-500">
                            <b>{{ Carbon\Carbon::now()->diffInDays($country->firstConfirmedDate) }}</b> days since first
                            confirmed case
                        </p>
                    </div>
                </div>
                <div class="d-md-none d-lg-none d-xl-none d-xs-block d-sm-block">
                    <div class="d-flex flex-column align-items-center mb-2">
                        <h5>
                            <img src="https://www.countryflags.io/{{ $country->iso2 }}/flat/24.png" class="img-fluid mr-3">
                            {{ $country->name }} overview
                        </h5>
                        <p class="text-gray-500">
                            <b>{{ Carbon\Carbon::now()->diffInDays($country->firstConfirmedDate) }}</b> days since first
                            confirmed case
                        </p>
                    </div>
                </div>
                <div class="row">
                    @php
                    // $first = $country->dayReports()->orderBy('date', 'desc')->first();
                    // $second = $country->dayReports()->orderBy('date', 'desc')->get()->values()->get(1);
                    $first = $country->dayReports->sortByDesc('date')->first();
                    $second = $country->dayReports->sortByDesc('date')->skip(1)->first();
                    // $first = $country->latestReport;
                    // $second = $country->secondLatestReport;
                    @endphp
                    <div class="col-4 d-flex flex-column justify-content-center align-items-center" style="bottom: 0;">
                        <i class="fa fa-virus mb-2"></i>
                        <p class="h4 text-info">{{ $first ? number_format($first->confirmed, 0, ".", ",") : 0 }}</p>
                        <p class="mb-0">Confirmed</p>
                        <p class="text-s text-info mt-0">
                            @php
                            $value = $first && $second ? $first->confirmed - $second->confirmed : 0;
                            @endphp
                            {{ $value >= 0 ? '+' : '-' }}{{ number_format(abs($value), 0, ".", ",") }}
                        </p>
                    </div>
                    <div class="col-4 d-flex flex-column justify-content-center align-items-center" style="bottom: 0;">
                        <i class="fa fa-cross mb-2"></i>
                        <p class="h4 text-danger">{{ $first ? number_format($first->deaths, 0, ".", ",") : 0 }}</p>
                        <p class="mb-0">Deaths</p>
                        <p class="text-s text-danger mt-0">
                            @php
                            $value = $first && $second ? $first->deaths - $second->deaths : 0;
                            @endphp
                            {{ $value >= 0 ? '+' : '-' }}{{ number_format(abs($value), 0, ".", ",") }}
                        </p>
                    </div>
                    <div class="col-4 d-flex flex-column justify-content-center align-items-center" style="bottom: 0;">
                        <i class="fa fa-hand-holding-water mb-2"></i>
                        <p class="h4 text-success">{{ $first ? number_format($first->recovered, 0, ".", ",") : 0 }}</p>
                        <p class="mb-0">Recovered</p>
                        <p class="text-s text-success mt-0">
                            @php
                            $value = $first && $second ? $first->recovered - $second->recovered : 0;
                            @endphp
                            {{ $value >= 0 ? '+' : '-' }}{{ number_format(abs($value), 0, ".", ",") }}
                        </p>
                    </div>
                </div>
            </div>
            {{-- <div class="card-footer d-flex flex-row justify-content-center align-items-center">
                
            </div> --}}
        </div>
    </div>
    <div class="col-md-3 col-sm-12">
        <div class="card shadow h-100">
            @php
            $fatality_rate = $first && $first->confirmed ? (float)number_format($first->deaths * 100 /
            $first->confirmed, 2, ".", ",") : 0;
            @endphp
            <div class="card-body">
                <div class="progress mx-auto" data-value='{{ $fatality_rate }}'>
                    <span class="progress-left">
                        <span class="progress-bar border-danger"></span>
                    </span>
                    <span class="progress-right">
                        <span class="progress-bar border-danger"></span>
                    </span>
                    <div
                        class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                        <div class="h6 font-weight-bold">{{ $fatality_rate }}<span class="small text-gray-600">%</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex flex-row justify-content-center">
                Fatality rate
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-12">
        <div class="card shadow h-100">
            @php
            $recovery_rate = $first && $first->confirmed ? (float)number_format($first->recovered * 100 /
            $first->confirmed, 2, ".", ",") :
            0;
            @endphp
            <div class="card-body">
                <div class="progress mx-auto" data-value='{{ $recovery_rate }}'>
                    <span class="progress-left">
                        <span class="progress-bar border-success"></span>
                    </span>
                    <span class="progress-right">
                        <span class="progress-bar border-success"></span>
                    </span>
                    <div
                        class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                        <div class="h6 font-weight-bold">{{ $recovery_rate }}<span class="small text-gray-600">%</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex flex-row justify-content-center">
                Recovery rate
            </div>
        </div>
    </div>
</div>
<div class="row mt-2">
    <div class="col-md-6 col-sm-12">
        <div class="card shadow">
            <div class="card-body">
                {!! $currentChart->container() !!}
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="card shadow">
            <div class="card-body">
                {!! $historicChart->container() !!}
            </div>
        </div>
    </div>
</div>
@endif
<div class="row mt-2">
    <div class="col-md-8 col-sm-12">
        <div class="card shadow border-bottom-secondary h-100">
            <div class="card-header">
                <h6 class="font-weight-bold text-primary mt-1">
                    Global stats
                </h6>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-borderless table-hover" id="countriestable">
                    <thead>
                        <th></th>
                        <th>Country</th>
                        <th>Confirmed</th>
                        <th>Deaths</th>
                        <th>Recovered</th>
                        <th><i class="fa fa-cross"></i></th>
                        <th><i class="fa fa-hand-holding-water"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($countries as $c)
                        <tr>
                            <td class="text-gray-500"></td>
                            <td class="text-nowrap">
                                <img src="https://www.countryflags.io/{{ $c->iso2 }}/flat/24.png" class="img-fluid mr-3"
                                    style="width: 25px;">
                                <a href="{{ route('index', $c) }}">
                                    {{ $c->name }}
                                </a>
                            </td>
                            @php
                            $first = $c->latestReport;
                            @endphp
                            <td>
                                {{  $first ? number_format($first->confirmed, 0, ".", ",") : '0'  }}
                            </td>
                            <td>
                                {{  $first ? number_format($first->deaths, 0, ".", ",") : '0'  }}
                            </td>
                            <td>
                                {{  $first ? number_format($first->recovered, 0, ".", ",") : '0'  }}
                            </td>
                            <td class="text-danger">
                                {{ $first && $first->confirmed ? (float)number_format($first->deaths * 100 / $first->confirmed, 2, ".", ",") : '0' }}%
                            </td>
                            <td class="text-success">
                                {{ $first && $first->confirmed ? (float)number_format($first->recovered * 100 / $first->confirmed, 2, ".", ",") : '0' }}%
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12 h-100">
        <div class="row">
            <div class="col-12">
                <div class="card border-bottom-info shadow mt-sm-1">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-s font-weight-bold text-info mb-1"><i class="fa fa-globe-americas"></i>
                                    Total cases:
                                    {{ number_format($globalData->confirmed, 0, ".", ",") }}</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-500">
                                    {{ number_format($globalData->new_confirmed, 0, ".", ",") }} since yesterday
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-virus text-gray-400 text-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-12">
                <div class="card border-bottom-success shadow mt-1 w-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-s font-weight-bold text-success mb-1"><i
                                        class="fa fa-globe-americas"></i> Recovered:
                                    {{ number_format($globalData->recovered, 0, ".", ",") }}</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-500">
                                    {{ number_format($globalData->new_recovered, 0, ".", ",") }} since yesterday
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-hand-holding-water text-gray-400 text-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-12">
                <div class="card border-bottom-warning shadow mt-1 w-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-s font-weight-bold text-warning mb-1"><i
                                        class="fa fa-globe-americas"></i> Deaths:
                                    {{ number_format($globalData->deaths, 0, ".", ",") }}</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-500">
                                    {{ number_format($globalData->new_deaths, 0, ".", ",") }} since yesterday
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-cross text-gray-400 text-l"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-1 h-100">
            <div class="col-12">
                <div class="card shadow border-bottom-secondary mt-1 h-100">
                    <div class="card-body">
                        <p class="text-s text-primary font-weight-bold">
                            <i class="fa fa-info-circle text-xs text-primary"></i>
                            Click on the country name to view detailed statistics and graphs.
                        </p>
                        <p class="text-s text-primary">
                            <i class="fa fa-info-circle text-xs text-primary"></i>
                            The table can be sorted alphabetically by country name or ascending/descending by the other
                            columns.
                        </p>
                        <p class="text-s text-primary">
                            <i class="fa fa-info-circle text-xs text-primary"></i>
                            Active cases are calculated by subtracting deaths and recovered cases from the total
                            confirmed cases.
                        </p>
                        <p class="text-s text-primary">
                            <i class="fa fa-info-circle text-xs text-primary"></i>
                            Some innacuracies may appear in the data, especially because some countries like the UK are
                            not reporting recovered cases regularly or at all. This certianly doesn't mean that no one
                            has recovered in those areas.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
@if ($country)
{!! $currentChart->script() !!}
{!! $historicChart->script() !!}
@endif
@endsection