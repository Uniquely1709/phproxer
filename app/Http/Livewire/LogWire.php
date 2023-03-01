<?php

namespace App\Http\Livewire;

use App\Models\LogEntry;
use Livewire\Component;
use Livewire\WithPagination;

class LogWire extends Component
{
    use WithPagination;
    public $message;
    public $level;

    public function render()
    {
        $entries = LogEntry::orderBy('created_at', 'desc')
            ->when($this->level, fn ($query, $level) => $query->where('Level', $level))->when($this->message, fn ($query, $message) => $query->where('Entry', 'like', '%'.$message.'%'))
            ->paginate(50);

        return view('livewire.log-wire', [
            'entries'=>$entries,
        ]);
    }
}
