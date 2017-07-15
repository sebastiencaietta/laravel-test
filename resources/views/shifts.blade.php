<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('css/app.css') }}"/>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-sm-12 text-center">
            <h1>Shopworks Staffing</h1>
        </div>
    </div>

    <section class="shifts">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="text-center">
                    <td>Staff ID</td>
                    <?php /**
                     * @var \App\Modules\Shifts\Entities\WorkDay $workDay
                     */
                    ?>
                    @foreach ($shiftsPerDay as $workDay)
                        <td>Day {{$workDay->getDayNr()}}</td>
                    @endforeach
                    </thead>

                    <tbody class="text-center">
                    @foreach ($staffIds as $staffId)
                        <tr>
                            <td>
                                {{ $staffId }}
                            </td>

                            @foreach ($shiftsPerDay as $workDay)
                                <td>
                                    @if($shift = $workDay->getShiftForEmployee($staffId))
                                        {{ $shift->getStartTime() }} - {{ $shift->getEndTime() }}
                                    @else
                                        {{ 'n/a' }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach

                    <tr>
                        <td>Total Hours</td>
                        @foreach ($shiftsPerDay as $workDay)
                            <td>{{$workDay->getTotalHours()}}</td>
                        @endforeach
                    </tr>

                    <tr>
                        <td>Minutes worked alone</td>
                        @foreach ($shiftsPerDay as $workDay)
                            <td>{{$workDay->getMinutesWorkedAlone()}}</td>
                        @endforeach
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
</div>
</body>
</html>
