<div>
    <div>
        <div class="flex justify-between bg-white pb-6 border-b border-gray-200">
            <div class="text-2xl">Logs</div>
            <div class="">
                <button wire:click="render" type="button" class="btn btn-default bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" >
                    refresh
                </button>
            </div>
        </div>
        <div class="py-4 flex justify-between border-b">
            <div>
                <label>Loglevel</label>
                <select wire:model="level" class="rounded">
                    <option value=""></option>
                    <option value="debug">Debug</option>
                    <option value="info">Info</option>
                    <option value="warning">Warning</option>
                    <option value="error">Error</option>
                </select>
            </div>
            <div>
                <label>Message</label>
                <input wire:model="message" type="text" class="rounded">
            </div>
        </div>
        <div wire:poll.5s>
            <table class="pt-4 table-auto w-full">
                <thead>
                <tr>
                    <th>Level</th>
                    <th>Message</th>
                    <th>Timestamp</th>
                </tr>
                </thead>
                <tbody>
                @foreach($entries as $entry)
                    @if($entry->Level == 'error')
                        <tr class="bg-red-100 hover:bg-gray-200">
                            <td>{{$entry->Level}}</td>
                            <td class="px-6">{{$entry->Entry}}</td>
                            <td>{{$entry->ProcessId}}</td>
                            <td>{{$entry->created_at}}</td>
                        </tr>
                    @endif
                    @if($entry->Level == 'warning')
                        <tr class="bg-amber-500 hover:bg-gray-200">
                            <td>{{$entry->Level}}</td>
                            <td class="px-6">{{$entry->Entry}}</td>
                            <td>{{$entry->ProcessId}}</td>
                            <td>{{$entry->created_at}}</td>
                        </tr>
                    @endif
                    @if($entry->Level == 'info')
                        <tr class="bg-green-100 hover:bg-gray-200">
                            <td>{{$entry->Level}}</td>
                            <td class="px-6">{{$entry->Entry}}</td>
                            <td>{{$entry->ProcessId}}</td>
                            <td>{{$entry->created_at}}</td>
                        </tr>
                    @endif
                    @if($entry->Level == 'debug')
                        <tr class="hover:bg-gray-200">
                            <td>{{$entry->Level}}</td>
                            <td class="px-6">{{$entry->Entry}}</td>
                            <td>{{$entry->ProcessId}}</td>
                            <td>{{$entry->created_at}}</td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
        {{$entries->onEachSide(5)->links()}}
    </div>
</div>
