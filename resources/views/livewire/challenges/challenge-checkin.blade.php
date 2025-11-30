<div class="min-h-screen bg-gray-100 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <a href="{{ route('challenges.show', $challenge) }}" wire:navigate class="text-teal-600 hover:underline mb-2 block">&larr; Quay lại chi tiết</a>
                <h1 class="text-3xl font-bold text-gray-900">Điểm Danh: {{ $challenge->title }}</h1>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-500">Tiến độ hiện tại</div>
                <div class="text-2xl font-bold text-teal-600">{{ $participant->progress_percent }}%</div>
                <div class="text-xs text-orange-500 font-semibold">🔥 Chuỗi {{ $participant->streak }} ngày</div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="flex justify-between items-center p-4 bg-teal-600 text-white">
                <button wire:click="previousMonth" class="p-2 hover:bg-teal-700 rounded">&laquo; Tháng trước</button>
                <h2 class="text-xl font-bold">Tháng {{ $currentMonth }} / {{ $currentYear }}</h2>
                <button wire:click="nextMonth" class="p-2 hover:bg-teal-700 rounded">Tháng sau &raquo;</button>
            </div>

            <div class="grid grid-cols-7 bg-gray-50 border-b">
                @foreach(['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'] as $day)
                    <div class="py-3 text-center font-semibold text-gray-600">{{ $day }}</div>
                @endforeach
            </div>

            <div class="grid grid-cols-7">
                @foreach($daysInMonth as $dayObj)
                    @if($dayObj === null)
                        <div class="h-32 border-b border-r bg-gray-50"></div>
                    @else
                        @php
                            $bgColor = 'bg-white';
                            $cursor = 'cursor-pointer hover:bg-gray-50';
                            
                            if ($dayObj['status'] == 'done') {
                                $bgColor = 'bg-green-100 border-green-200';
                            } elseif ($dayObj['status'] == 'missed') {
                                $bgColor = 'bg-red-100 border-red-200';
                            } elseif ($dayObj['is_today']) {
                                $bgColor = 'bg-blue-50 ring-2 ring-inset ring-blue-300';
                            } elseif ($dayObj['is_future']) {
                                $bgColor = 'bg-gray-50 text-gray-400';
                                $cursor = 'cursor-not-allowed';
                            }elseif ($dayObj['is_future'] || $dayObj['is_before_start']) { 
                                $bgColor = 'bg-gray-100 text-gray-400';
                                $cursor = 'cursor-not-allowed';
                            }
                        @endphp

                        <div wire:click="selectDate({{ $dayObj['day'] }})" 
                             class="h-32 border-b border-r p-2 relative transition {{ $bgColor }} {{ $cursor }}">
                            
                            <div class="flex justify-between">
                                <span class="font-semibold {{ $dayObj['is_today'] ? 'text-blue-600' : 'text-gray-700' }}">
                                    {{ $dayObj['day'] }}
                                </span>
                                @if($dayObj['is_today'])
                                    <span class="text-[10px] font-bold text-blue-600 uppercase">Hôm nay</span>
                                @endif
                            </div>

                            @if($dayObj['status'] == 'done')
                                <div class="mt-4 flex flex-col items-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-green-500 rounded-full text-white shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </span>
                                    <span class="text-xs text-green-700 font-semibold mt-1">Đạt</span>
                                </div>
                            @elseif($dayObj['status'] == 'missed')
                                <div class="mt-4 flex flex-col items-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-red-400 rounded-full text-white shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </span>
                                    <span class="text-xs text-red-600 font-semibold mt-1">Trượt</span>
                                </div>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    @if($showModal)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full transform transition-all">
                
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50 rounded-t-xl">
                    <h3 class="text-xl font-bold text-gray-800">
                        Cập nhật ngày {{ $selectedDateDisplay }}
                    </h3>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-6">
                    @if($currentStatusOnDate == 'done')
                        <div class="mb-6 bg-green-50 text-green-800 p-3 rounded-lg text-sm text-center">
                            Ngày này đang được ghi nhận là <strong>Hoàn thành</strong>.
                        </div>
                    @elseif($currentStatusOnDate == 'missed')
                        <div class="mb-6 bg-red-50 text-red-800 p-3 rounded-lg text-sm text-center">
                            Ngày này đang được ghi nhận là <strong>Thất bại/Bỏ lỡ</strong>.
                        </div>
                    @else
                        <div class="mb-6 bg-gray-50 text-gray-600 p-3 rounded-lg text-sm text-center">
                            Chưa có dữ liệu cho ngày này.
                        </div>
                    @endif

                    <div class="mb-8">
                        <h4 class="font-semibold text-gray-900 mb-3">1. Đánh dấu Hoàn thành</h4>
                        
                        @if($challenge->need_proof)
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tải ảnh minh chứng (Bắt buộc)
                                </label>
                                <div class="flex items-center justify-center w-full">
                                    <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                        @if ($proofImage)
                                            <img src="{{ $proofImage->temporaryUrl() }}" class="h-full object-contain rounded-lg">
                                        @else
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <svg class="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                                <p class="text-xs text-gray-500">Bấm để chọn ảnh</p>
                                            </div>
                                        @endif
                                        <input id="dropzone-file" wire:model="proofImage" type="file" class="hidden" accept="image/*" />
                                    </label>
                                </div>
                                @error('proofImage') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <button wire:click="markAsDone" 
                                class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded-lg transition flex justify-center items-center"
                                wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="markAsDone">✅ Xác nhận Hoàn Thành</span>
                            <span wire:loading wire:target="markAsDone">Đang xử lý...</span>
                        </button>
                    </div>

                    <div class="relative flex py-2 items-center">
                        <div class="flex-grow border-t border-gray-300"></div>
                        <span class="flex-shrink-0 mx-4 text-gray-400 text-xs uppercase">Hoặc</span>
                        <div class="flex-grow border-t border-gray-300"></div>
                    </div>

                    <div class="mt-4 text-center">
                        <h4 class="font-semibold text-gray-900 mb-3">2. Đánh dấu Thất bại / Bỏ lỡ</h4>
                        <p class="text-xs text-gray-500 mb-3">Việc này sẽ làm ngắt chuỗi (streak) của bạn.</p>
                        <button wire:click="markAsMissed" 
                                class="text-red-600 hover:text-red-800 hover:bg-red-50 font-semibold py-2 px-4 rounded-lg transition border border-red-200 w-full">
                            ❌ Xác nhận Bỏ lỡ
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif
</div>