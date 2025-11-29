<div x-data="{ confirmDelete: false, deleteId: null, commentNotFoundModal: false }"
     x-init="() => {
        const checkCommentHash = () => {
             const hash = window.location.hash;
             if (hash && hash.startsWith('#comment-')) {
                 const commentId = hash.substring('#comment-'.length);
                 // Đợi DOM được cập nhật bởi Livewire/Alpine
                 Alpine.nextTick(() => {
                     const element = document.getElementById('comment-' + commentId);
                     if (element) {
                         // Nếu phần tử tồn tại, cuộn đến nó
                         element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                         element.classList.add('highlight');
                         setTimeout(() => element.classList.remove('highlight'), 2000);
                     } else if (/^\d+$/.test(commentId)) {
                         // Nếu không tồn tại, hiển thị modal thông báo
                         commentNotFoundModal = true;
                     }
                 });
             }
        };
 
        checkCommentHash(); // Kiểm tra khi tải trang lần đầu
        window.addEventListener('hashchange', checkCommentHash); // Kiểm tra khi hash thay đổi
        document.addEventListener('livewire:navigated', checkCommentHash); // Kiểm tra sau khi điều hướng bằng wire:navigate
     }"
>
    <main role="main" class="container mx-auto px-4 py-12 max-w-4xl">
    <article aria-label="Challenge detail" class="bg-white rounded-lg shadow p-8">
        
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6" role="alert">
                <p class="font-bold">Thành công!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if (session('info'))
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded-lg mb-6" role="alert">
                <p>{{ session('info') }}</p>
            </div>
        @endif

        <header class="mb-6">
            <h1 class="text-3xl font-bold text-teal-600 mb-2">{{ $challenge->title }}</h1>
            <p class="text-sm text-teal-700 font-semibold mb-1">
                {{ $challenge->categories->first()->name ?? 'Chưa phân loại' }}
            </p>
            <p class="text-sm text-gray-600">Thời gian thử thách: {{ $challenge->duration_days }} ngày</p>
        </header>

        <section aria-label="Challenge description" class="mb-8">
            <h2 class="text-xl font-semibold mb-2">Mô tả thử thách</h2>
            <div class="text-gray-700 leading-relaxed prose max-w-none">
                {!! $challenge->description !!}
            </div>
        </section>

        <section aria-label="Join challenge" class="mb-8">
            @auth
                @if ($isParticipant)
                    <button wire:click="leaveChallenge" wire:confirm="Bạn có chắc chắn muốn rời khỏi thử thách này không?"
                            class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-3 rounded-lg focus:outline-none focus:ring-4 focus:ring-red-400" 
                            aria-pressed="true">
                        Đã tham gia (Rời khỏi)
                    </button>
                @else
                    <button wire:click="joinChallenge"
                            class="bg-teal-600 hover:bg-teal-700 text-white font-semibold px-6 py-3 rounded-lg focus:outline-none focus:ring-4 focus:ring-teal-400" 
                            aria-pressed="false">
                        Tham gia thử thách
                    </button>
                @endif
            @else
                <a href="{{ route('login') }}" wire:navigate
                   class="bg-teal-600 hover:bg-teal-700 text-white font-semibold px-6 py-3 rounded-lg focus:outline-none focus:ring-4 focus:ring-teal-400">
                    Đăng nhập để tham gia
                </a>
            @endauth
        </section>

        @if ($isParticipant && $myParticipation)
            <section aria-label="Progress bar" class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Tiến trình của bạn</h2>
                <div class="w-full bg-gray-200 rounded-full h-6 relative overflow-hidden" 
                     role="progressbar" aria-valuemin="0" aria-valuemax="100" 
                     aria-valuenow="{{ $myParticipation->progress_percent }}" 
                     aria-label="Tiến trình phần trăm">
                    
                    <div id="progress-fill" class="bg-teal-600 h-6 rounded-full transition-width duration-1000 ease-in-out" 
                         style="width: {{ $myParticipation->progress_percent }}%;">
                    </div>
                    
                    <span class="absolute right-3 top-0 text-white font-semibold text-sm leading-6">
                        {{ $myParticipation->progress_percent }}%
                    </span>
                </div>
                @if ($myParticipation->streak > 0)
                    <div class="inline-block mt-2 bg-amber-400 text-amber-900 text-xs font-semibold px-3 py-1 rounded-full select-none" 
                         aria-label="Chuỗi ngày liên tiếp">
                        Chuỗi {{ $myParticipation->streak }} ngày 
                        </div>
                @endif
            </section>
        @endif

        <section aria-label="Leaderboard" class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Bảng xếp hạng ({{ $leaderboard->count() }} thành viên)</h2>
            <div class="bg-white border border-gray-300 rounded shadow max-h-64 overflow-y-auto">
                <table class="w-full text-left text-gray-700 text-sm">
                    <thead class="bg-gray-100 sticky top-0">
                        <tr>
                            <th class="px-4 py-2">Hạng</th>
                            <th class="px-4 py-2">Tên người dùng</th>
                            <th class="px-4 py-2">Tiến trình</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($leaderboard as $index => $participant)
                            @if ($participant->user)
                                <tr class="border-t border-gray-200 hover:bg-teal-50 
                                    {{ ($myParticipation && $participant->user_id == $myParticipation->user_id) ? 'bg-teal-100 font-bold' : '' }}">
                                    <td class="px-4 py-2 font-semibold">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 flex items-center">
                                        <img src="{{ $participant->user->avatar ?? 'https://i.pravatar.cc/40?u='.$participant->user_id }}" alt="Avatar" class="w-8 h-8 rounded-full mr-2" />
                                        {{ $participant->user->name }}
                                        @if($participant->role == 'creator')
                                            <span class="ml-2 text-xs bg-indigo-100 text-indigo-800 px-2 py-0.5 rounded-full">Người tạo</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">{{ $participant->progress_percent }}%</td>
                                </tr>
                            @endif
                        @empty
                            <tr class="border-t border-gray-200">
                                <td colspan="3" class="px-4 py-3 text-center text-gray-500">
                                    Chưa có ai tham gia thử thách này.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section aria-label="Comments" class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Bình luận ({{ $challenge->comments->count() }})</h2>

            @auth
                <form wire:submit="addComment" class="flex items-start mb-6">
                    <img src="{{ Auth::user()->avatar ?? 'https://i.pravatar.cc/40?u=me' }}" alt="Avatar" class="w-10 h-10 rounded-full mr-3" />
                    <div class="flex-1">
                        <div wire:ignore
                            x-data
                            x-init="
                                () => {
                                    const trixEditor = $refs.trix;

                                    trixEditor.addEventListener('trix-change', (event) => {
                                        $wire.set('newComment', event.target.value)
                                    });

                                    trixEditor.addEventListener('trix-attachment-add', (event) => {
                                        if (event.attachment.file) {
                                            $wire.upload(
                                                'newCommentAttachment',
                                                event.attachment.file,
                                                (uploadedFilename) => {
                                                    // Success callback.
                                                    @this.call('completedUpload', uploadedFilename, event.attachment);
                                                },
                                                () => {
                                                    // Error callback.
                                                },
                                                (event) => {
                                                    // Progress callback.
                                                    event.attachment.setUploadProgress(event.detail.progress);
                                                }
                                            )
                                        }
                                    });
                                }
                            ">
                            <input id="trix-input-{{ $challenge->id }}" type="hidden" wire:model.defer="newComment">
                            <trix-editor x-ref="trix" input="trix-input-{{ $challenge->id }}" class="prose max-w-none bg-white"></trix-editor>
                        </div>
                        @error('newComment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        <div class="text-right mt-2">
                            <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold px-4 py-2 rounded-lg">
                                Gửi
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <div class="text-center bg-gray-50 p-4 rounded-lg mb-6">
                    <a href="{{ route('login') }}" wire:navigate class="font-semibold text-teal-600 hover:underline">Đăng nhập</a>
                    <span class="text-gray-600"> để tham gia thảo luận.</span>
                </div>
            @endauth

            <ul wire:poll.5s class="space-y-4">
                @forelse ($comments as $comment)
                    @if ($comment->user)
                        <li id="comment-{{ $comment->id }}" class="flex items-star comment-item">
                            <img src="{{ $comment->user->avatar ?? 'https://i.pravatar.cc/40?u='.$comment->user->id }}" alt="Avatar" class="w-10 h-10 rounded-full mr-3" />
                            <div class="flex-1 bg-gray-100 rounded-lg px-4 py-3 comment-content">
                                <div class="flex justify-between items-center mb-1">
                                    <div>
                                        <span class="font-semibold text-sm">{{ $comment->user->name }}</span>
                                        <span class="text-xs text-gray-500 ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    @can('delete', $comment)
                                        <button 
                                            @click="confirmDelete = true; deleteId = {{ $comment->id }}" 
                                            class="text-gray-400 hover:text-red-600"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    @endcan

                                </div>
                                <div class="prose prose-sm max-w-none text-gray-700">
                                    {!! $comment->content !!}
                                </div>
                            </div>
                        </li>
                    @endif
                @empty
                    <li class="text-center text-gray-500 text-sm">
                        Chưa có bình luận nào.
                    </li>
                @endforelse
            </ul>

            @if ($comments->count() < $commentsCount)
                <div class="text-center mt-6">
                    <button wire:click="loadMoreComments" wire:loading.attr="disabled"
                            class="text-teal-600 font-semibold hover:underline disabled:text-gray-400 disabled:cursor-wait">
                        Tải thêm bình luận...
                    </button>
                </div>
            @endif
        </section>
    </article>
    </main>
        <!-- Modal -->
        <div 
            x-show="confirmDelete"
            x-cloak
            class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
        >
            <div class="bg-white p-6 rounded-lg shadow-lg w-100">
                <h2 class="text-lg font-semibold mb-3">Xác nhận xóa</h2>
                <p class="text-sm text-gray-600 mb-5">
                    Bạn có chắc chắn muốn xóa bình luận này?
                </p>

                <div class="flex justify-end gap-3">
                    <button 
                        class="px-3 py-1 rounded bg-gray-200"
                        @click="confirmDelete = false"
                    >Hủy</button>

                    <button 
                        class="px-3 py-1 rounded bg-red-600 text-white"
                        @click="$wire.deleteComment(deleteId); confirmDelete = false;"
                    >Xóa</button>
                </div>
            </div>
        </div>

        <!-- "Comment Not Found" Modal -->
        <div 
            x-show="commentNotFoundModal"
            x-cloak
            @keydown.escape.window="commentNotFoundModal = false"
            class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
        >
            <div @click.outside="commentNotFoundModal = false" class="bg-white p-6 rounded-lg shadow-lg w-full max-w-sm mx-4">
                <h2 class="text-lg font-semibold mb-3">Thông báo</h2>
                <p class="text-sm text-gray-600 mb-5">
                    Bình luận không tồn tại hoặc đã bị xóa.
                </p>

                <div class="flex justify-end">
                    <button 
                        class="px-4 py-2 rounded bg-teal-600 hover:bg-teal-700 text-white font-semibold"
                        @click="commentNotFoundModal = false"
                    >Đã hiểu</button>
                </div>
            </div>
        </div>
</div>