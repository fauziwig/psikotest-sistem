@extends('candidate.layouts.app')

@section('title', 'Pengerjaan Tes - ' . $assessment->title)

@section('header_actions')
<div class="flex items-center space-x-3 text-xs text-slate-500">
    <div class="hidden sm:block">
        <span class="font-medium text-slate-900">{{ $submission->candidate->name }}</span>
        <span class="text-slate-400">({{ $submission->candidate->applied_position }})</span>
    </div>
</div>
@endsection

@section('content')
<div x-data="discRunner({
    totalQuestions: {{ $assessment->questions->count() }},
    remainingSeconds: {{ $remainingSeconds }},
    durationMinutes: {{ $assessment->duration_minutes }},
    submitUrl: '{{ route('candidate.submit', $assessment->slug) }}',
    csrfToken: '{{ csrf_token() }}',
    submissionId: {{ $submission->id }}
})" x-init="init()" class="flex-1 flex flex-col">

    <!-- Sticky Timer & Progress Top Bar -->
    <div class="bg-white border-b border-slate-200 sticky top-16 z-20 shadow-sm py-3 px-4 sm:px-6">
        <div class="max-w-5xl mx-auto flex items-center justify-between gap-4">
            
            <!-- Question Status / Progress -->
            <div class="flex items-center space-x-3">
                <div class="text-xs font-semibold text-slate-500 hidden sm:block">
                    Progress: <span class="text-slate-900 font-bold" x-text="completedCount"></span>/<span x-text="totalQuestions"></span> Soal
                </div>
                <div class="w-28 sm:w-44 bg-slate-100 rounded-full h-2.5 overflow-hidden">
                    <div class="h-2.5 rounded-full transition-all duration-300"
                         style="background-color: var(--color-primary);"
                         :style="`width: ${(completedCount / totalQuestions) * 100}%`"></div>
                </div>
                <span class="text-xs font-bold text-slate-700" x-text="`${Math.round((completedCount / totalQuestions) * 100)}%`"></span>
            </div>

            <!-- Countdown Timer Display -->
            <div class="flex items-center space-x-2">
                <div class="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg border text-sm font-mono font-bold shadow-sm"
                     :class="remainingTime <= 120 ? 'bg-red-50 border-red-300 text-red-600 animate-pulse' : 'bg-slate-50 border-slate-200 text-slate-800'">
                    <svg class="w-4 h-4" :class="remainingTime <= 120 ? 'text-red-500' : 'text-slate-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span x-text="formattedTime"></span>
                </div>

                <!-- Submit Button -->
                <button type="button" @click="confirmSubmit()"
                        class="px-4 py-1.5 text-xs sm:text-sm font-bold text-white rounded-lg shadow transition hover:opacity-90 flex items-center space-x-1"
                        style="background-color: var(--color-primary);">
                    <span>Selesai</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="max-w-5xl mx-auto px-4 py-6 sm:px-6 w-full flex-1 grid grid-cols-1 lg:grid-cols-4 gap-6">

        <!-- Question Navigation Sidebar (Desktop) -->
        <div class="hidden lg:block lg:col-span-1">
            <div class="bg-white rounded-xl border border-slate-200 p-4 sticky top-36 shadow-sm">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3 flex items-center justify-between">
                    <span>Daftar Nomor</span>
                    <span class="text-blue-600 font-semibold" x-text="`${completedCount}/${totalQuestions}`"></span>
                </h4>
                <div class="grid grid-cols-4 gap-2">
                    @foreach($assessment->questions as $question)
                        <button type="button"
                                @click="scrollToQuestion({{ $question->question_number }})"
                                class="h-9 text-xs font-bold rounded-lg border flex items-center justify-center transition-all"
                                :class="isAnswered({{ $question->question_number }})
                                    ? 'bg-blue-600 border-blue-600 text-white shadow-sm'
                                    : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50'"
                                :style="isAnswered({{ $question->question_number }}) ? 'background-color: var(--color-primary); border-color: var(--color-primary);' : ''">
                            {{ $question->question_number }}
                        </button>
                    @endforeach
                </div>

                <div class="mt-4 pt-4 border-t border-slate-100 text-[11px] text-slate-500 space-y-1.5">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded bg-blue-600" style="background-color: var(--color-primary);"></div>
                        <span>Sudah dipilih Most & Least</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded bg-white border border-slate-300"></div>
                        <span>Belum lengkap</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Questions List Area -->
        <div class="lg:col-span-3 space-y-6">
            @foreach($assessment->questions as $question)
                <div id="q-{{ $question->question_number }}"
                     class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 sm:p-6 scroll-mt-36 transition-all"
                     :class="isAnswered({{ $question->question_number }}) ? 'border-l-4 border-l-blue-600' : ''"
                     :style="isAnswered({{ $question->question_number }}) ? 'border-left-color: var(--color-primary);' : ''">
                    
                    <!-- Question Header -->
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                        <div class="flex items-center space-x-2">
                            <span class="w-7 h-7 rounded-lg text-white font-extrabold text-xs flex items-center justify-center shadow-sm"
                                  style="background-color: var(--color-primary);">
                                {{ $question->question_number }}
                            </span>
                            <span class="text-sm font-bold text-slate-900">Pilih 1 Most (M) dan 1 Least (L)</span>
                        </div>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded"
                              :class="isAnswered({{ $question->question_number }}) ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700'"
                              x-text="isAnswered({{ $question->question_number }}) ? 'Lengkap' : 'Belum Lengkap'"></span>
                    </div>

                    <!-- Options Table / List -->
                    <div class="space-y-2.5">
                        @foreach($question->options as $option)
                            <div class="flex items-center justify-between p-3 rounded-lg border border-slate-200 hover:border-slate-300 transition-colors bg-slate-50/50">
                                
                                <!-- Statement Text -->
                                <div class="text-sm text-slate-800 font-medium pr-4 flex-1">
                                    {{ $option->option_text }}
                                </div>

                                <!-- Action Buttons Most & Least -->
                                <div class="flex items-center space-x-2 shrink-0">
                                    <!-- Button Most (M / +) -->
                                    <button type="button"
                                            @click="selectMost({{ $question->question_number }}, {{ $option->id }}, '{{ $option->disc_type }}')"
                                            class="w-14 sm:w-16 py-1.5 text-xs font-extrabold rounded-md border transition-all flex items-center justify-center space-x-1"
                                            :class="isMostSelected({{ $question->question_number }}, {{ $option->id }})
                                                ? 'bg-emerald-600 border-emerald-600 text-white shadow-sm'
                                                : 'bg-white border-slate-300 text-slate-600 hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-700'">
                                        <span>+ M</span>
                                    </button>

                                    <!-- Button Least (L / -) -->
                                    <button type="button"
                                            @click="selectLeast({{ $question->question_number }}, {{ $option->id }}, '{{ $option->disc_type }}')"
                                            class="w-14 sm:w-16 py-1.5 text-xs font-extrabold rounded-md border transition-all flex items-center justify-center space-x-1"
                                            :class="isLeastSelected({{ $question->question_number }}, {{ $option->id }})
                                                ? 'bg-rose-600 border-rose-600 text-white shadow-sm'
                                                : 'bg-white border-slate-300 text-slate-600 hover:bg-rose-50 hover:border-rose-300 hover:text-rose-700'">
                                        <span>- L</span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- Bottom Submit Area -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 text-center shadow-sm">
                <p class="text-sm text-slate-600 mb-4">Pastikan Anda telah mengisi seluruh nomor soal sebelum menekan tombol submit.</p>
                <button type="button" @click="confirmSubmit()"
                        class="w-full sm:w-auto px-8 py-3 text-base font-bold text-white rounded-lg shadow-md hover:opacity-90 transition duration-150"
                        style="background-color: var(--color-primary);">
                    Kirim Jawaban Assessment
                </button>
            </div>
        </div>
    </div>

    <!-- Hidden Submission Form for Auto/Manual Submit -->
    <form id="submissionForm" action="{{ route('candidate.submit', $assessment->slug) }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="submission_id" value="{{ $submission->id }}">
        <input type="hidden" name="is_timeout" :value="isTimeOut ? '1' : '0'">
        <input type="hidden" name="answers" :value="JSON.stringify(getFormattedAnswers())">
    </form>

    <!-- Confirmation Modal if Incomplete -->
    <div x-show="showConfirmModal" x-cloak
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl text-center" @click.away="showConfirmModal = false">
            <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-600 mx-auto flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            
            <h3 class="text-lg font-bold text-slate-900 mb-2">Konfirmasi Pengiriman</h3>
            <p class="text-sm text-slate-600 mb-6" x-show="completedCount < totalQuestions">
                Masih ada <strong class="text-red-600" x-text="totalQuestions - completedCount"></strong> nomor soal yang belum lengkap. Apakah Anda yakin ingin tetap mengirimkan jawaban?
            </p>
            <p class="text-sm text-slate-600 mb-6" x-show="completedCount === totalQuestions">
                Seluruh <strong><span x-text="totalQuestions"></span> nomor soal</strong> telah terjawab lengkap. Kirim jawaban Anda sekarang?
            </p>

            <div class="flex items-center space-x-3">
                <button type="button" @click="showConfirmModal = false"
                        class="flex-1 py-2.5 px-4 rounded-lg border border-slate-300 text-slate-700 font-semibold text-sm hover:bg-slate-50">
                    Periksa Lagi
                </button>
                <button type="button" @click="executeSubmit()"
                        class="flex-1 py-2.5 px-4 rounded-lg text-white font-semibold text-sm shadow hover:opacity-90"
                        style="background-color: var(--color-primary);">
                    Ya, Kirim
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('discRunner', (config) => ({
            totalQuestions: config.totalQuestions,
            remainingTime: config.remainingSeconds,
            durationMinutes: config.durationMinutes,
            answers: {},
            showConfirmModal: false,
            isTimeOut: false,
            timerInterval: null,

            init() {
                // Initialize answers state
                for (let i = 1; i <= this.totalQuestions; i++) {
                    this.answers[i] = {
                        question_number: i,
                        most_option_id: null,
                        least_option_id: null,
                        most_disc: null,
                        least_disc: null
                    };
                }

                // Start countdown timer
                this.startTimer();
            },

            startTimer() {
                if (this.remainingTime <= 0) {
                    this.triggerTimeoutSubmit();
                    return;
                }

                this.timerInterval = setInterval(() => {
                    if (this.remainingTime > 0) {
                        this.remainingTime--;
                    } else {
                        clearInterval(this.timerInterval);
                        this.triggerTimeoutSubmit();
                    }
                }, 1000);
            },

            get formattedTime() {
                const minutes = Math.floor(this.remainingTime / 60);
                const seconds = this.remainingTime % 60;
                return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            },

            get completedCount() {
                return Object.values(this.answers).filter(q => q.most_option_id !== null && q.least_option_id !== null).length;
            },

            isAnswered(questionNumber) {
                const q = this.answers[questionNumber];
                return q && q.most_option_id !== null && q.least_option_id !== null;
            },

            isMostSelected(questionNumber, optionId) {
                return this.answers[questionNumber]?.most_option_id === optionId;
            },

            isLeastSelected(questionNumber, optionId) {
                return this.answers[questionNumber]?.least_option_id === optionId;
            },

            selectMost(questionNumber, optionId, discType) {
                if (!this.answers[questionNumber]) return;

                // If already selected, deselect
                if (this.answers[questionNumber].most_option_id === optionId) {
                    this.answers[questionNumber].most_option_id = null;
                    this.answers[questionNumber].most_disc = null;
                    return;
                }

                // If selected as Least, remove from Least
                if (this.answers[questionNumber].least_option_id === optionId) {
                    this.answers[questionNumber].least_option_id = null;
                    this.answers[questionNumber].least_disc = null;
                }

                this.answers[questionNumber].most_option_id = optionId;
                this.answers[questionNumber].most_disc = discType;
            },

            selectLeast(questionNumber, optionId, discType) {
                if (!this.answers[questionNumber]) return;

                // If already selected, deselect
                if (this.answers[questionNumber].least_option_id === optionId) {
                    this.answers[questionNumber].least_option_id = null;
                    this.answers[questionNumber].least_disc = null;
                    return;
                }

                // If selected as Most, remove from Most
                if (this.answers[questionNumber].most_option_id === optionId) {
                    this.answers[questionNumber].most_option_id = null;
                    this.answers[questionNumber].most_disc = null;
                }

                this.answers[questionNumber].least_option_id = optionId;
                this.answers[questionNumber].least_disc = discType;
            },

            scrollToQuestion(questionNumber) {
                const element = document.getElementById(`q-${questionNumber}`);
                if (element) {
                    element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            },

            confirmSubmit() {
                this.showConfirmModal = true;
            },

            triggerTimeoutSubmit() {
                this.isTimeOut = true;
                alert('Waktu pengerjaan telah habis! Jawaban Anda akan otomatis dikirimkan.');
                this.executeSubmit();
            },

            getFormattedAnswers() {
                return Object.values(this.answers).filter(q => q.most_option_id !== null || q.least_option_id !== null);
            },

            executeSubmit() {
                clearInterval(this.timerInterval);
                const form = document.getElementById('submissionForm');
                if (form) {
                    form.submit();
                }
            }
        }));
    });
</script>
@endpush
