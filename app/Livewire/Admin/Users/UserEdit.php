<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use App\Models\Province;
use App\Models\Ward;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class UserEdit extends Component
{
    public User $user;
    public string $name = '';
    public $ward_id = null;
    public $province_id = null;
    public $provinces;
    public $wards = [];
    public $allWards;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->ward_id = $user->ward_id;

        $this->provinces = Province::orderBy('name')->get();
        $this->allWards = Ward::orderBy('name')->get();

        if ($this->ward_id) {
            $ward = Ward::find($this->ward_id);
            if ($ward) {
                $this->province_id = $ward->province_id;
                $this->wards=Ward::where('province_id', $this->province_id)->orderBy('name')->get();
            }
        }else{
            $this->wards=$this->allWards;
        }
    }

    public function updatedProvinceId($value)
    {
        $this->ward_id = null;
        if($value){
            $this->wards=Ward::where('province_id',$value)->orderBy('name')->get();
        } else{
        $this->wards=$this->allWards;
    }
}

    public function save()
    {
        $validatedData = $this->validate([
            'name' => 'required|string|max:255',
            'ward_id' => 'nullable|integer|exists:wards,id',
        ]);

        $this->user->update($validatedData);

        session()->flash('message', 'Cập nhật người dùng thành công.');
        return $this->redirect(route('admin.users.list'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.users.user-edit');
    }
}
