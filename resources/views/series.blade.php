<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ 'Series' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <livewire:add-series-form></livewire:add-series-form>
                <table class="table-auto w-full text-sm border-collapse">
                    <thead>
                        <tr>
                            <th class="p-2"/>

                            <th class="text-left p-2">
                                User Title
                            </th>
                            <th class="text-left p-2">
                                Season
                            </th>
                            <th class="text-left p-2">
                                English Title
                            </th>
                            <th class="text-left p-2">
                                German Title
                            </th>
                            <th class="text-left p-2">
                                Episodes
                            </th>
                            <th class="text-left p-2">
                                Completed
                            </th>
                            <th class="text-left p-2">
                                Downloaded
                            </th>
                            <th class="text-left p-2">
                                Scraped
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($series as $serie)
                        <tr>
                            <td class="p-2">
                                <a href="{{route('episodes', ['series' => $serie->TitleORG])}}">
                                    <x-heroicon-m-arrow-up-on-square style="min-height: 25px ;max-height: 25px"/>
                                </a>
                            </td>
                            <td class="p-2">
                                {{$serie->Title}}
                            </td>
                            <td class="p-2">
                                {{$serie->Season}}
                            </td>
                            <td class="p-2">
                                {{$serie->TitleEN}}
                            </td>
                            <td class="p-2">
                                {{$serie->TitleGER}}
                            </td>
                            <td class="p-2">
                                {{$serie->Episodes}}
                            </td>
                            <td class="p-2">
                                @if($serie->Completed)
                                    <x-heroicon-o-check style="min-height: 25px ;max-height: 25px"/>
                                @endif
                            </td>
                            <td class="p-2">
                                @if($serie->Downloaded)
                                    <x-heroicon-o-check style="min-height: 25px ;max-height: 25px"/>
                                @endif
                            </td>
                            <td class="p-2">
                                @if($serie->Scraped)
                                    <x-heroicon-o-check style="min-height: 25px ;max-height: 25px"/>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
