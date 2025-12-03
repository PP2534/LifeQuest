<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Follower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller{
    
   public function index(Request $request){
    $query=User::active()
    ->with(['ward.province','followers'])
    ->where('id', '!=', Auth::id());
    $hasSearchInput = $request->filled('search') || $request->filled('interest') || $request->filled('province_id');

    //Tìm kiếm theo tên
    if($request->filled('search')){
        $query->where('name', 'like', '%' .$request->search. '%');
    }

    //Sở thích
    if($request->filled('interest')){
        $query->where('interests', 'like', '%' .$request->interest. '%');
    }

    //Địa điểm
    if($request->filled('province_id')){
        $query->whereHas('ward.province', function($q) use($request){
            $q->where('id', $request->province_id);
        });
    }

    //Lấy danh sách người dùng
    $users=$query->paginate(3)->withQueryString();

    //Tạo thông báo nếu không tìm thấy
    $errorMessage = null;
    if($hasSearchInput && $users->isEmpty()){
        $errorMessage='Không có người dùng nào tên "' .e($request->search). '" được tìm thấy.';

        if($request->filled('search') && !$request->filled('interest') && !$request->filled('province_id')){
            $errorMessage='Không có người dùng nào tên "' .e($request->search). '" được tìm thấy.';
        }
    }

    return view('livewire.user-profile.create',compact('users', 'errorMessage'));
    }

    public function toggleFollow($id){
     User::active()->findOrFail($id);
    $existingFollow = Follower::where([
        'follower_id' => Auth::id(),
        'following_id' => $id
        ])->first();

    if($existingFollow){
        $existingFollow->delete();
        //return back()->with('success','Đã bỏ theo dõi người dùng.');
        return back()->with('success');
    } else{
        Follower::create([
            'follower_id' => Auth::id(),
            'following_id' => $id
        ]);
        //return back()->with('success','Đã theo dõi người dùng.');
        return back()->with('success');
        }
    }
}

