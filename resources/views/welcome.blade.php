@extends('layouts.app')

@section('content')

<div class="row mt-5">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="font-weight-bold text-primary">
                    What is this
                </h6>
            </div>
            <div class="card-body">
                <p>
                    This is a perfectly feasible explanation
                </p>
                <table class="table table-borderless table-hover" id="countriestable">
                    <thead>
                        <th>Flag</th>
                        <th>Name</th>
                        <th>Confirmed</th>
                        <th>Deaths</th>
                        <th>Recovered</th>
                        <th>Population</th>
                    </thead>
                    <tbody>
                        @foreach ($countries as $country)
                        <tr>
                            <td><img src="https://www.countryflags.io/{{ $country->iso2 }}/flat/24.png"
                                    class="img-fluid mr-3" style="width: 25px;"></td>
                            <td>{{ $country->name }}</td>
                            @php
                                $first = $country->dayReports->sortByDesc('date')->first();
                            @endphp
                            <td>{{  $first ? number_format($first->confirmed, 0, ".", ",") : '0'  }}</td>
                            <td>
                                {{  $first ? number_format($first->deaths, 0, ".", ",") : '0'  }}
                                <span class="text-xs text-danger">
                                    {{ $first ? number_format($first->deaths * 100 / $first->confirmed, 2, ".", ",") : '0' }}%
                                </span>
                            </td>
                            <td>
                                {{  $first ? number_format($first->recovered, 0, ".", ",") : '0'  }}
                                <span class="text-xs text-success">
                                    {{ $first ? number_format($first->recovered * 100 / $first->confirmed, 2, ".", ",") : '0' }}%
                                </span>
                            </td>
                            <td>{{ number_format($country->population, 0, ".", ",") }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- <div class="row mt-2">
    <div class="col-lg-4 col-md-12">
        <div class="card shadow mt-1">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-s font-weight-bold text-info mb-1">Total cases:
                            {{ number_format($globalData['TotalConfirmed'], 0, ".", ",") }}</div>
<div class="h6 mb-0 font-weight-bold text-gray-800">
    {{ number_format($globalData['NewConfirmed'], 0, ".", ",") }} since yesterday
</div>
</div>
<div class="col-auto">
    <i class="fa fa-virus text-grey-300"></i>
</div>
</div>
</div>
</div>
</div>
<div class="col-lg-4 col-md-12">
    <div class="card shadow mt-1">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-s font-weight-bold text-success mb-1">Recovered:
                        {{ number_format($globalData['TotalRecovered'], 0, ".", ",") }}</div>
                    <div class="h6 mb-0 font-weight-bold text-gray-800">
                        {{ number_format($globalData['NewRecovered'], 0, ".", ",") }} since yesterday
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fa fa-hand-holding-medical text-grey-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-4 col-md-12">
    <div class="card shadow mt-1">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-s font-weight-bold text-warning mb-1">Deaths:
                        {{ number_format($globalData['TotalDeaths'], 0, ".", ",") }}</div>
                    <div class="h6 mb-0 font-weight-bold text-gray-800">
                        {{ number_format($globalData['NewDeaths'], 0, ".", ",") }} since yesterday
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fa fa-disease text-grey-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<div class="row mt-2">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header d-flex d-row justify-content-between">
                <h6 class="font-weight-bold text-primary">Countries</h6>
                <div class="text-grey-200 text-s">
                    Last updated: {{ (new Carbon\Carbon($countriesData[1]['Date']))->format('d M Y - H:i') }}
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="countriestable">
                        <thead>
                            <th>Flag</th>
                            <th>Country</th>
                            <th>Confirmed cases</th>
                            <th>Deaths</th>
                            <th>Recovered</th>
                            <th>Death %</th>
                            <th>Recovered %</th>
                        </thead>
                        <tbody>
                            @foreach ($countriesData as $countryData)
                            <tr>
                                <td>
                                    <img
                                        src="https://www.countryflags.io/{{ $countryData['CountryCode'] }}/flat/24.png">
                                </td>
                                <td>
                                    <a href="{{ route('index', ['country' => $countryData['Slug']]) }}">
                                        {{ $countryData['Country'] }}
                                    </a>
                                </td>
                                <td>
                                    {{ number_format($countryData['TotalConfirmed'], 0, ".", ",") }}
                                </td>
                                <td>
                                    {{ number_format($countryData['TotalDeaths'], 0, ".", ",") }}
                                </td>
                                <td>
                                    {{ number_format($countryData['TotalRecovered'], 0, ".", ",") }}
                                </td>
                                <td>
                                    <p class="text-danger">
                                        {{ $countryData['TotalConfirmed'] ?  number_format(($countryData['TotalDeaths'] * 100 ) / $countryData['TotalConfirmed'], 2, ".", ",") : 0 }}
                                        %
                                    </p>
                                </td>
                                <td>
                                    <p class="text-success">
                                        {{ $countryData['TotalConfirmed'] ?  number_format(($countryData['TotalRecovered'] * 100 ) / $countryData['TotalConfirmed'], 2, ".", ",") : 0 }}
                                        %
                                    </p>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-6 col-sm-12">
        <div class="card shadow border-top-primary h-100">
            <div class="card-header">
                <h6 class="font-weight-bold text-primary pt-1">
                    <img src="https://www.countryflags.io/{{ $currentData->country_code }}/flat/24.png"
                        class="img-fluid mr-3" style="width: 25px;">
                    {{ $currentData->country }} current stats
                </h6>
            </div>
            <div class="card-body">
                <div>
                    {!! $currentDataChart->container() !!}
                </div>
                <div class="card shadow mt-3">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-s font-weight-bold text-info mb-1">Confirmed cases:
                                    {{ number_format($currentData->confirmed, 0, ".", ",") }}</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800">
                                    + {{ number_format($currentData->new_confirmed, 0, ".", ",") }} since yesterday
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-virus text-grey-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow mt-3">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-s font-weight-bold text-success mb-1">Recovered:
                                    {{ number_format($currentData->recovered, 0, ".", ",") }}</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800">
                                    + {{ number_format($currentData->new_recovered, 0, ".", ",") }} since yesterday
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-hand-holding-medical text-grey-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow mt-3">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-s font-weight-bold text-warning mb-1">Deaths:
                                    {{ number_format($currentData->deaths, 0, ".", ",") }}</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800">
                                    + {{ number_format($currentData->new_deaths, 0, ".", ",") }} since yesterday
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-disease text-grey-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-borderless w-100 mt-3">
                    <tbody>
                        <tr style="border-top: 1px solid black;">
                            <th>Last update</th>
                            <td class="float-right">
                                {{ $currentData->last_update->format('d M Y - H:i') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="card shadow h-100">
            <div class="card-header">
                <h6 class="font-weight-bold text-primary">
                    <img src="https://www.countryflags.io/{{ $currentData->country_code }}/flat/24.png"
                        class="img-fluid mr-3" style="width: 25px;">
                    {{ $currentData->country }} historic data since day one
                </h6>
            </div>
            <div class="card-body">
                <div>
                    {!! $historicDataChart->container() !!}
                </div>
                <p class="text-grey-300 mt-3 border-top-grey">
                    The upper graph shows the progression of the virus in the country since it first appeared. Some
                    irregularities may appear in the data as it is not always complete. The <b>Active cases</b> are
                    calculated by subtracting the recovered and deceased cases from the confirmed cases and this line
                    represents the best the actual state of the virus in the country, provided that the data is
                    complete.
                </p>
            </div>
        </div>
    </div>
</div> --}}

@endsection

@section('js')
{{-- {!! $currentDataChart->script() !!}
{!! $historicDataChart->script() !!} --}}
@endsection