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
            </div>
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-4 col-sm-12">
        <div class="card shadow border-top-primary h-100">
            <div class="card-header">
                <h6 class="font-weight-bold text-primary pt-1">
                    <img src="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.4.3/flags/4x3/ro.svg"
                        class="img-fluid mr-3" style="width: 25px;">
                    Romania current stats
                </h6>
            </div>
            <div class="card-body">
                <div>
                    {!! $currentDataChart->container() !!}
                </div>
                <table class="table table-borderless w-100">
                    <tbody>
                        <tr>
                            <th><i class="fa fa-syringe"></i> Tested</th>
                            <td class="float-right">{{ number_format($currentData->tested, 0, ".", ",") }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-virus"></i> Infected</th>
                            <td class="float-right">{{ number_format($currentData->infected, 0, ".", ",") }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-hand-holding-medical"></i> Recovered</th>
                            <td class="float-right">{{ number_format($currentData->recovered, 0, ".", ",") }}</td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-disease"></i> Deceased</th>
                            <td class="float-right">{{ number_format($currentData->deceased, 0, ".", ",") }}</td>
                        </tr>
                        <tr style="border-top: 1px solid black;">
                            <th>Last update</th>
                            <td class="float-right">
                                {{ $currentData->last_updated_at_source->format('d M Y - H:i') }}
                            </td>
                        </tr>
                        <tr>
                            <th>Data source</th>
                            <td class="float-right">
                                <a href="{{ $currentData->source_url }}" target="_blank">link</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-8 col-sm-12">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="font-weight-bold text-primary">
                    <img src="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.4.3/flags/4x3/ro.svg"
                        class="img-fluid mr-3" style="width: 25px;">
                    Romania historic data
                </h6>
            </div>
            <div class="card-body">
                <div>
                    {!! $historicDataChart->container() !!}
                </div>
                <div class="card shadow mt-3">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning mb-1">Tested</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800">
                                    @php
                                        $tested = $lastTwoHistoricData[1]->tested - $lastTwoHistoricData[0]->tested;
                                    @endphp
                                    {{ $tested >= 0 ? '+' : '-' }} {{ $tested }} since yesterday
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-syringe text-grey-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow mt-1">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger mb-1">Infected</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800">
                                    @php
                                        $infected = $lastTwoHistoricData[1]->infected - $lastTwoHistoricData[0]->infected;
                                    @endphp
                                    {{ $infected >= 0 ? '+' : '-' }} {{ $infected }} since yesterday
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-virus text-grey-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow mt-1">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success mb-1">Recovered</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800">
                                    @php
                                        $recovered = $lastTwoHistoricData[1]->recovered - $lastTwoHistoricData[0]->recovered;
                                    @endphp
                                    {{ $recovered >= 0 ? '+' : '-' }} {{ $recovered }} since yesterday
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-hand-holding-medical text-grey-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow mt-1">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary mb-1">Deceased</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800">
                                    @php
                                        $deceased = $lastTwoHistoricData[1]->deceased - $lastTwoHistoricData[0]->deceased;
                                    @endphp
                                    {{ $deceased >= 0 ? '+' : '-' }} {{ $deceased }} since yesterday
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
    </div>
</div>

@endsection

@section('js')
{!! $currentDataChart->script() !!}
{!! $historicDataChart->script() !!}
@endsection