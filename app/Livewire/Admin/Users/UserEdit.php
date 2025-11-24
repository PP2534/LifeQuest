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
    public ?int $ward_id = null;
    public ?int $province_id = null;
    public array $provinces = [];
    public array $wards = [];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->ward_id = $user->ward_id;

        $this->provinces = Province::select('id', 'full_name')->orderBy('full_name')->get()->toArray();

        if ($this->ward_id) {
            $ward = Ward::find($this->ward_id);
            if ($ward) {
                $this->province_id = $ward->province_id;
                $this->loadWards();
            }
        }
    }

    public function updatedProvinceId($value)
    {
        $this->ward_id = null;
        $this->loadWards();
    }

    private function loadWards()
    {
        if ($this->province_id) {
            $this->wards = Ward::where('province_id', $this->province_id)
                ->select('id', 'name_with_type')
                ->orderBy('name_with_type')
                ->get()
                ->toArray();
        } else {
            $this->wards = [];
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
