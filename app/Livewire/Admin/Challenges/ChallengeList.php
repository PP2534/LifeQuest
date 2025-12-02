<?php

namespace App\Livewire\Admin\Challenges;
use Livewire\Attributes\Layout;

use App\Models\Challenge;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class ChallengeList extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $search = '';
    public $sortField = 'created_at';
    public $sortAsc = false;

    protected $queryString = ['search', 'sortField', 'sortAsc'];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updateChallengeStatus($challengeId, $status)
    {
        $challenge = Challenge::findOrFail($challengeId);
        $challenge->status = $status;
        $challenge->save();

        session()->flash('message', 'Challenge status updated successfully.');
    }

    public function render()
    {
        $challenges = Challenge::with('creator', 'categories')
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhereHas('creator', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.challenges.challenge-list', [
            'challenges' => $challenges,
        ]);
    }
}
