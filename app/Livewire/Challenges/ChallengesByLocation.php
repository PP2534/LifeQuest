<?php

namespace App\Livewire\Challenges;

use App\Models\Challenge;
use App\Models\Province;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChallengesByLocation extends Component
{
    public $challenges;
    public $provinces;    
    public $selectedProvince; 

    public function mount()
    {
        $this->provinces = Province::all();
        $this->challenges= collect();
    }

    public function search()
    {
        if($this->selectedProvince){
            $this->challenges = Challenge::with('categories','ward')
            ->active()
            ->whereHas('ward', function($q){
                $q->where('province_id', $this->selectedProvince);
                })
                ->orderBy('start_date', 'desc')
                ->get();
                }else{
                    $this->challenges = collect();
                    }
    }
    
    public function render()
    {
        return view('livewire.challenges.challenges-by-location', ['challenges' => $this->challenges,])->layout('layouts.app');
    }
}
