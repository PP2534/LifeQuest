<?php

namespace App\Livewire\Challenges;

use App\Models\Challenge;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class MyChallengeList extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'tailwind';

    /**
     * Xóa một thử thách (chỉ chủ sở hữu mới có thể)
     */
    public function deleteChallenge(int $challengeId)
    {
        $challenge = Challenge::where('id', $challengeId)->where('status', 'active')
                              ->where('creator_id', Auth::id())
                              ->first();

        if ($challenge) {
            //  Kiểm tra xem thử thách này có file ảnh không
            // ($challenge->image lưu đường dẫn dạng 'challenges/ten_file.png')
            if ($challenge->image) {
                
                // Xóa file ảnh khỏi disk 'public'
                // Thao tác này sẽ xóa file trong 'storage/app/public/challenges/ten_file.png'
                Storage::disk('public')->delete($challenge->image);
            }
            
            // 4. Xóa bản ghi (record) khỏi CSDL
            $challenge->delete();
            
            $challenge->delete();
            session()->flash('success', 'Đã xóa thử thách thành công!');
        } else {
            session()->flash('error', 'Không tìm thấy thử thách hoặc bạn không có quyền xóa.');
        }

        // Tải lại component để cập nhật danh sách
        $this->dispatch('$refresh');
    }

    public function render()
    {
        $userId = Auth::id(); // Lấy ID người dùng hiện tại
        // Chỉ tải các thử thách mà người dùng này đã tạo (creator_id)
        //
       $challenges = Challenge::query()
            // 1. Lấy các thử thách do tôi tạo
            ->where('creator_id', $userId)->where('status', 'active')
            
            // 2. HOẶC (OR) lấy các thử thách tôi đang tham gia
            // (sử dụng mối quan hệ 'participants' đã định nghĩa trong Model)
            ->orWhereHas('participants', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            
            // Tải kèm 'participants' (để tính status) và 'creator' (để kiểm tra quyền)
            ->with(['participants', 'creator']) 
            ->orderBy('created_at', 'desc')
            ->paginate(4);

        return view('livewire.challenges.my-challenge-list', [
            'challenges' => $challenges,
        ])->layout('layouts.app');
    }
}