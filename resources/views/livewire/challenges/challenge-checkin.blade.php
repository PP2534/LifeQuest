<div>
<div class="min-h-screen bg-gray-100 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center mb-6">
            <div>
                <a href="{{ route('challenges.show', $challenge) }}" class="text-teal-600 hover:underline mb-2 block">&larr; Quay lại chi tiết</a>
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
                                $cursor = 'cursor-default'; // Đã làm rồi thì không click nữa
                            } elseif ($dayObj['status'] == 'missed') {
                                $bgColor = 'bg-red-100';
                            } elseif ($dayObj['is_today']) {
                                $bgColor = 'bg-blue-50 ring-2 ring-inset ring-blue-300';
                            } elseif ($dayObj['is_future']) {
                                $bgColor = 'bg-gray-50 text-gray-400';
                                $cursor = 'cursor-not-allowed';
                            }
                        @endphp

                        <div wire:click="selectDate({{ $dayObj['day'] }})" 
                             class="h-32 border-b border-r p-2 relative transition {{ $bgColor }} {{ $cursor }}">
                            
                            <span class="font-semibold {{ $dayObj['is_today'] ? 'text-blue-600' : 'text-gray-700' }}">
                                {{ $dayObj['day'] }}
                            </span>

                            @if($dayObj['status'] == 'done')
                                <div class="mt-2 flex justify-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-green-500 rounded-full text-white">
                                        ✓
                                    </span>
                                </div>
                                <p class="text-xs text-center text-green-700 mt-1 font-medium">Hoàn thành</p>
                            @elseif($dayObj['status'] == 'missed')
                                <p class="text-xs text-center text-red-500 mt-4 font-medium">Bỏ lỡ</p>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    @if($showModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
            <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full">
                <h3 class="text-2xl font-bold mb-4 text-gray-900">Điểm danh ngày {{ $selectedDate }}</h3>
                
                <form wire:submit.prevent="submitCheckin">
                    
                    @if($challenge->need_proof)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Hình ảnh minh chứng <span class="text-red-500">*</span>
                            </label>
                            
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    @if ($proofImage)
                                        <img src="{{ $proofImage->temporaryUrl() }}" class="mx-auto h-32 object-cover rounded">
                                    @else
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-teal-600 hover:text-teal-500 focus-within:outline-none">
                                                <span>Tải ảnh lên</span>
                                                <input id="file-upload" wire:model="proofImage" type="file" class="sr-only">
                                            </label>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @error('proofImage') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            <div wire:loading wire:target="proofImage" class="text-sm text-gray-500 mt-1">Đang xử lý ảnh...</div>
                        </div>
                    @else
                        <p class="mb-6 text-gray-600">Xác nhận bạn đã hoàn thành nhiệm vụ hôm nay?</p>
                    @endif

                    <div class="flex justify-end space-x-3">
                        <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                            Hủy
                        </button>
                        <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="submitCheckin">Xác nhận</span>
                            <span wire:loading wire:target="submitCheckin">Đang lưu...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div></div>
