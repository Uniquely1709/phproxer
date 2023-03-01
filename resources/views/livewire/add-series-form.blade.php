<div>
    <form wire:submit.prevent="submitForm" action="/" method="POST" class="px-6">
        @csrf
        <div>
            @if (session()->has('success_message'))
                <div class="rounded-md bg-green-50 p-4 mt-8">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm leading-5 font-medium text-green-800">
                                {{ session('success_message') }}
                            </p>
                        </div>
                        <div class="ml-auto pl-3">
                            <div class="-mx-1.5 -my-1.5">
                                <button
                                    type="button"
                                    wire:click="clearMessageBox"
                                    class="inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:bg-green-100 transition ease-in-out duration-150"
                                    aria-label="Dismiss">
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                              clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="md:grid md:grid-cols-4 px-6 py-4">
            <div class="md:col-span-1">
                <label for="seriesId" id="seriesId">
                    Proxer ID
                </label>
                <div class="rounded-md shadow-sm rounded-md">
                    <input class="@error('seriesId')border border-red-500 @enderror block w-full flex-1 rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           wire:model="seriesId" name="seriesId" id="seriesId" type="number"
                           placeholder="Proxer Series ID">
                </div>
                @error('seriesId')
                <p class="text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="md:col-span-1 px-2">
                <label for="title" id="title">
                    Title
                </label>
                <input class="@error('title')border border-red-500 @enderror block w-full flex-1 rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" wire:model="title" name="title" id="title" type="text" placeholder="Title for Jellyfin">
                @error('title')
                <p class="text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="md:col-span-1">
                <label for="season" id="season">
                    Season
                </label>
                <input class="@error('season')border border-red-500 @enderror block w-full flex-1 rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" wire:model="season" name="season" id="season" type="number" placeholder="Season for Jellyfin">
                @error('season')
                <p class="text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="md:col-span-1 pl-16">
                <button
                    type="submit"
                    class="w-full btn btn-default bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-6"
                >Submit</button>
            </div>
        </div>
    </form>
</div>
