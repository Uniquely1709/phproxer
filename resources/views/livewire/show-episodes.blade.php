<div>
    <div class="p-6">
        <label for="series">Serie</label>
        <input wire:model="series" id="series" type="text" class="rounded">
    </div>
    <table class="table-auto w-full text-sm border-collapse">
        <thead>
        <tr>
            <th class="text-left p-2">
                Series
            </th>
            <th class="text-left p-2">
                Episode
            </th>
            <th class="text-left p-2">
                Episode Name
            </th>
            <th class="text-left p-2">
                Retries
            </th>
            <th class="text-left p-2">
                Downloaded
            </th>
            <th class="text-left p-2">
                Published
            </th>
            <th class="text-left p-2">
                Downloadurl
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($episodes as $episode)
            <tr>
                <td class="p-2">
                    {{$episode->parent->TitleORG}}
                </td>
                <td class="p-2">
                    {{$episode->EpisodeID}}
                </td>
                <td class="p-2">
                    {{$episode->Episodename}}
                </td>
                <td class="p-2">
                    {{$episode->Retries}}
                </td>
                <td class="p-2">
                    @if($episode->Downloaded)
                        <x-heroicon-o-check style="max-height: 25px"/>
                    @else
                        <x-heroicon-o-x-mark  style="max-height: 25px"/>
                    @endif
                </td>
                <td class="p-2">
                    @if($episode->Published)
                        <x-heroicon-o-check style="max-height: 25px"/>
                    @else
                        <x-heroicon-o-x-mark  style="max-height: 25px"/>
                    @endif
                </td>
                <td class="p-2">
                    @if($episode->DownloadUrl != null)
                        <a href="{{$episode->DownloadUrl}}" rel="noreferrer">
                            <x-heroicon-o-check style="max-height: 25px"/>
                        </a>
                    @endif
                    @if($episode->Retries > 5)
                        <x-heroicon-o-x-mark  style="max-height: 25px; color: red"/>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="px-6 py-3">
        {{$episodes->onEachSide(5)->links()}}
    </div>
</div>
