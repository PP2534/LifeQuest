<?php
    \Livewire\Volt\layout('layouts.admin');
?>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Tổng quan Admin</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-500">Tổng số người dùng</h3>
                <p class="mt-1 text-3xl font-bold text-gray-900">{{ $totalUsers }}</p>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-500">Tổng số thử thách</h3>
                <p class="mt-1 text-3xl font-bold text-gray-900">{{ $totalChallenges }}</p>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-500">Tổng số thói quen</h3>
                <p class="mt-1 text-3xl font-bold text-gray-900">{{ $totalHabits }}</p>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-500">Người dùng chưa xác thực</h3>
                <p class="mt-1 text-3xl font-bold text-gray-900">{{ $unverifiedUsersCount }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Thử thách mới nhất</h3>
                <ul>
                    @forelse($latestChallenges as $challenge)
                        <li class="border-b last:border-b-0 py-2">
                            <a href="#" class="text-teal-600 hover:underline">{{ $challenge->title }}</a>
                            <p class="text-sm text-gray-500">Bởi: {{ $challenge->creator->name ?? 'N/A' }} - {{ $challenge->created_at->locale(app()->getLocale() ?? 'vi')->diffForHumans() }}</p>
                        </li>
                    @empty
                        <p class="text-gray-600">Không có thử thách nào.</p>
                    @endforelse
                </ul>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Biểu đồ tăng trưởng người dùng (30 ngày gần nhất)</h3>
                <div x-data="{ userGrowthData: @js($userGrowthData) }" x-init="
                    const ctx = document.getElementById('userGrowthChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: userGrowthData.labels,
                            datasets: [{
                                label: 'Số người dùng đăng ký',
                                data: userGrowthData.data,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1,
                                tension: 0.1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            if (Number.isInteger(value)) {
                                                return value;
                                            }
                                        }
                                    }
                                }
                            },
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                ">
                    <canvas id="userGrowthChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>