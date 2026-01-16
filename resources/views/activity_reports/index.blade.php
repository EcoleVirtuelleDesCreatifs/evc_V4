@extends('layouts.app')

@section('title', "Rapports d'activité - EVC")

@section('content')
<div class="bg-white">
    <div class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
        <div class="mx-auto max-w-3xl text-center">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Rapports d'activité</h1>
            <p class="mt-4 text-gray-600">
                Retrouvez ici nos rapports d'activité publiés.
            </p>
        </div>

        <div class="mx-auto mt-12 max-w-4xl">
            @if($reports->isEmpty())
                <div class="rounded-2xl border border-gray-200 bg-gray-50 p-10 text-center">
                    <div class="text-gray-700 font-semibold">Aucun rapport disponible pour le moment.</div>
                    <div class="mt-2 text-sm text-gray-500">Revenez bientôt.</div>
                </div>
            @else
                <div class="grid grid-cols-1 gap-6">
                    @foreach($reports as $report)
                        <div class="rounded-2xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full bg-orange-100 text-orange-700 flex items-center justify-center font-bold">PDF</div>
                                        <div>
                                            <div class="text-lg font-semibold text-gray-900">{{ $report->title }}</div>
                                            <div class="text-sm text-gray-500">
                                                @if($report->year)
                                                    {{ $report->year }}
                                                @else
                                                    &nbsp;
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if($report->description)
                                        <p class="mt-4 text-gray-600">{{ $report->description }}</p>
                                    @endif
                                </div>

                                <div class="shrink-0">
                                    <a href="{{ route('activity-reports.download', $report) }}" class="inline-flex items-center justify-center rounded-full bg-orange-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-orange-700">
                                        Télécharger
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
