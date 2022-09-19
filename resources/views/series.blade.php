<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ 'Series' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div>Hello</div>


                <table class="table-auto w-full text-sm border-collapse">
                    <thead>
                        <tr>
                            <th class="text-left p-2">
                                Original Title
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
                                {{$serie->TitleORG}}
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
                                    <x-heroicon-o-check style="max-height: 25px"/>
                                @endif
                            </td>
                            <td class="p-2">
                                @if($serie->Downloaded)
                                    <x-heroicon-o-check style="max-height: 25px"/>
                                @endif
                            </td>
                            <td class="p-2">
                                @if($serie->Scraped)
                                    <x-heroicon-o-check style="max-height: 25px"/>
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
