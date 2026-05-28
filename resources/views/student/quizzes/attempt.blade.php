<!DOCTYPE html>
<html lang="en" class="h-full dark bg-zinc-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Secure Locked Session - {{ $quiz->title }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind & Alpine (via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full text-zinc-100 bg-zinc-950 antialiased overflow-hidden select-none"
      x-data="{
          started: false,
          remainingSeconds: {{ $remainingSeconds }},
          timeString: '--:--',
          currentQuestionIndex: 0,
          totalQuestions: {{ count($quiz->questions) }},
          fullscreenActive: false,
          answeredQuestions: {},

          // Track answered state in Alpine for real-time sidebar coloring
          initAnsweredState() {
              @foreach($quiz->questions as $idx => $q)
                  @php
                      $studentAns = collect($attempt->answers)->firstWhere('question_id', $q['id']);
                      $selectedVal = $studentAns ? $studentAns['selected_option'] : null;
                  @endphp
                  @if($selectedVal !== null)
                      this.answeredQuestions['{{ $q['id'] }}'] = {{ $selectedVal }};
                  @endif
              @endforeach
          },

          enterSecureMode() {
              let docEl = document.documentElement;
              if (docEl.requestFullscreen) {
                  docEl.requestFullscreen();
              } else if (docEl.webkitRequestFullscreen) {
                  docEl.webkitRequestFullscreen();
              } else if (docEl.mozRequestFullScreen) {
                  docEl.mozRequestFullScreen();
              } else if (docEl.msRequestFullscreen) {
                  docEl.msRequestFullscreen();
              }

              this.started = true;
              window.quizStarted = true; // Set global flag for native JS tracking
              this.fullscreenActive = true;
              this.initTimer();
              this.initFullscreenListener();
          },

          restoreFullscreen() {
              let docEl = document.documentElement;
              if (docEl.requestFullscreen) {
                  docEl.requestFullscreen();
              } else if (docEl.webkitRequestFullscreen) {
                  docEl.webkitRequestFullscreen();
              }
              this.fullscreenActive = true;
          },

          initTimer() {
              this.updateTimeString();
              let interval = setInterval(() => {
                  if (this.remainingSeconds <= 0) {
                      clearInterval(interval);
                      this.forceSubmit();
                  } else {
                      this.remainingSeconds--;
                      this.updateTimeString();
                  }
              }, 1000);
          },

          updateTimeString() {
              let minutes = Math.floor(this.remainingSeconds / 60);
              let seconds = this.remainingSeconds % 60;
              this.timeString = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
          },

          initFullscreenListener() {
              const handleFullscreenChange = () => {
                  this.fullscreenActive = !!(document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement);
              };
              document.addEventListener('fullscreenchange', handleFullscreenChange);
              document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
              document.addEventListener('mozfullscreenchange', handleFullscreenChange);
          },

          forceSubmit() {
              document.getElementById('submitQuizForm').submit();
          }
      }"
      x-init="initAnsweredState()"
      @restore-fullscreen.window="restoreFullscreen()">

    <!-- 1. Start Overlay Screen (Mandatory Fullscreen entry) -->
    <div x-show="!started" class="fixed inset-0 z-50 bg-zinc-950 flex items-center justify-center p-6">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(99,102,241,0.08)_0%,transparent_70%)] pointer-events-none"></div>
        
        <div class="relative bg-zinc-900 border border-zinc-800 rounded-3xl w-full max-w-xl shadow-2xl p-8 sm:p-10 text-center space-y-8 animate-fade-in">
            <div class="h-20 w-20 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center mx-auto text-indigo-400">
                <svg class="h-10 w-10 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>

            <div class="space-y-3">
                <span class="inline-flex items-center rounded-lg bg-indigo-500/10 px-2.5 py-0.5 text-xxs font-black text-indigo-400 uppercase tracking-widest border border-indigo-500/20">
                    High-Security Locked Console
                </span>
                <h2 class="text-2xl font-black text-white tracking-tight leading-none mt-2">{{ $quiz->title }}</h2>
                <p class="text-xs text-zinc-400 leading-relaxed max-w-md mx-auto">
                    To maintain absolute testing integrity, this quiz runs in a strict, locked full-screen environment. All window focus switches are logged in real-time.
                </p>
            </div>

            <div class="bg-zinc-950/60 border border-zinc-850 rounded-2xl p-5 text-left text-xxs font-medium text-zinc-400 space-y-3">
                <h4 class="text-xs font-black text-zinc-300 uppercase tracking-wide">Assessment Security Directives:</h4>
                <ul class="list-decimal pl-4 space-y-2 leading-relaxed">
                    <li>Entering **Fullscreen Mode** is mandatory to start.</li>
                    <li>Do not leave full-screen or switch windows/tabs.</li>
                    <li>First, second, and third tab-switches will trigger warning overlays.</li>
                    <li>A **fourth switch** will automatically lock and terminate your attempt.</li>
                </ul>
            </div>

            <button type="button" 
                    @click="enterSecureMode()" 
                    class="w-full inline-flex items-center justify-center gap-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white font-extrabold text-sm py-4 px-6 shadow-lg shadow-indigo-900/30 transition-all uppercase tracking-wider">
                Initialize Secure Exam Session &rarr;
            </button>
        </div>
    </div>

    <!-- 2. Mandatory Fullscreen Escape Lockdown Overlay -->
    <div x-show="started && !fullscreenActive" x-cloak class="fixed inset-0 z-40 bg-zinc-950/95 backdrop-blur-md flex items-center justify-center p-6">
        <div class="bg-zinc-900 border border-rose-500/30 rounded-3xl w-full max-w-md shadow-2xl p-8 text-center space-y-6 animate-fade-in">
            <div class="h-16 w-16 rounded-full bg-rose-500/10 flex items-center justify-center mx-auto text-rose-505 border border-rose-500/20 shadow-inner">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>

            <div class="space-y-2">
                <h3 class="text-lg font-black text-rose-500 uppercase tracking-wide">Security Alert: Screen Escaped</h3>
                <p class="text-xs text-rose-350 leading-relaxed">
                    You have exited Fullscreen mode! The quiz questions have been temporarily hidden. Leaving full-screen is logged as a policy violation.
                </p>
                <p class="text-xxs font-bold text-zinc-400 bg-rose-500/10 border border-rose-500/20 p-2.5 rounded-xl mt-3">
                    Click the button below to restore full-screen console mode immediately.
                </p>
            </div>

            <button type="button" @click="restoreFullscreen()" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs py-3.5 px-4 shadow transition-all uppercase tracking-wider">
                Restore Fullscreen Mode
            </button>
        </div>
    </div>

    <!-- 3. Primary Secure Exam Grid -->
    <div x-show="started" x-cloak class="h-full flex flex-col justify-between">
        
        <!-- Header Console Bar -->
        <header class="bg-zinc-900 border-b border-zinc-800 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="h-2.5 w-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <h3 class="text-xs font-black text-white tracking-widest uppercase">{{ $quiz->title }}</h3>
            </div>
            
            <!-- Floating countdown timer card -->
            <div class="flex items-center gap-4 bg-zinc-950/60 border border-zinc-800 rounded-xl px-4 py-2 shadow-inner">
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-xxxxs uppercase tracking-wider text-zinc-500 font-extrabold">Time Remaining:</span>
                    <span class="text-sm font-black text-rose-500 tracking-wider" x-text="timeString">--:--</span>
                </div>
            </div>
        </header>

        <!-- Main Body Area -->
        <main class="flex-1 overflow-hidden flex flex-col md:flex-row">
            
            <!-- Left Panel: Interactive Question Navigation sidebar -->
            <aside class="w-full md:w-64 bg-zinc-900/50 border-r border-zinc-800 p-6 flex flex-col gap-6 overflow-y-auto">
                <div>
                    <h4 class="text-xxxxs uppercase tracking-widest text-zinc-500 font-black">Assessment Navigation</h4>
                    <p class="text-xxxxs text-zinc-500 mt-0.5">Click number to jump to question.</p>
                </div>

                <!-- Color-Coded Grid of Numbers -->
                <div class="grid grid-cols-5 gap-2.5">
                    @foreach($quiz->questions as $idx => $q)
                        <button type="button" 
                                @click="currentQuestionIndex = {{ $idx }}"
                                :class="{
                                    'border-indigo-500 bg-indigo-500/10 text-indigo-400': currentQuestionIndex === {{ $idx }},
                                    'border-emerald-500/30 bg-emerald-500/10 text-emerald-450': currentQuestionIndex !== {{ $idx }} && answeredQuestions['{{ $q['id'] }}'] !== undefined,
                                    'border-zinc-800 bg-zinc-950/40 text-zinc-500 hover:border-zinc-700': currentQuestionIndex !== {{ $idx }} && answeredQuestions['{{ $q['id'] }}'] === undefined
                                }"
                                class="h-9 w-9 rounded-lg border flex items-center justify-center font-bold text-xs transition-all shadow-sm">
                            {{ $idx + 1 }}
                        </button>
                    @endforeach
                </div>

                <!-- Navigation Legend -->
                <div class="mt-auto space-y-2 border-t border-zinc-850 pt-4 text-xxxxs font-bold uppercase tracking-wider text-zinc-500">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded bg-indigo-500"></span> Active View
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded bg-emerald-500"></span> Saved / Answered
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded bg-zinc-800"></span> Unanswered
                    </div>
                </div>
            </aside>

            <!-- Center Panel: Question Display Card (exactly 1 question at a time) -->
            <section class="flex-1 overflow-y-auto p-6 sm:p-10 flex flex-col justify-center max-w-4xl mx-auto w-full">
                
                <form id="submitQuizForm" action="{{ route('student.quizzes.submit', $attempt->id) }}" method="POST">
                    @csrf
                    
                    @foreach($quiz->questions as $idx => $q)
                        <!-- Premium sliding scale-up enter transitions without layout shifting -->
                        <div x-show="currentQuestionIndex === {{ $idx }}" 
                             x-transition:enter="transition ease-out duration-300 transform"
                             x-transition:enter-start="opacity-0 translate-x-6 scale-95"
                             x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                             class="space-y-6">
                            
                            <!-- Question Meta & Header -->
                            <div class="space-y-1">
                                <span class="text-xxxxs uppercase tracking-wider text-indigo-400 font-extrabold bg-indigo-500/10 border border-indigo-500/20 px-2 py-0.5 rounded">
                                    Question {{ $idx + 1 }} of {{ count($quiz->questions) }}
                                </span>
                                <h2 class="text-lg sm:text-xl font-bold text-white leading-snug mt-3">
                                    {{ $q['question_text'] }}
                                </h2>
                            </div>

                            <!-- Options display (4 cards) -->
                            <div class="grid grid-cols-1 gap-4 pt-2">
                                @foreach($q['options'] as $optIndex => $optionText)
                                    <label class="relative flex items-center gap-4 rounded-xl border border-zinc-850 bg-zinc-900/40 p-5 hover:bg-zinc-900 hover:border-zinc-700 cursor-pointer transition-all shadow-sm select-none"
                                           :class="{
                                               'border-indigo-500 bg-zinc-900/80 shadow-md shadow-indigo-950/20': answeredQuestions['{{ $q['id'] }}'] === {{ $optIndex }},
                                               'border-zinc-850 bg-zinc-900/40': answeredQuestions['{{ $q['id'] }}'] !== {{ $optIndex }}
                                           }">
                                        <input type="radio" 
                                               name="answers[{{ $q['id'] }}]" 
                                               value="{{ $optIndex }}" 
                                               :checked="answeredQuestions['{{ $q['id'] }}'] === {{ $optIndex }}"
                                               @change="saveAnswer('{{ $q['id'] }}', '{{ $optIndex }}'); answeredQuestions['{{ $q['id'] }}'] = {{ $optIndex }}"
                                               class="h-4 w-4 text-indigo-600 border-zinc-800 bg-zinc-955 focus:ring-indigo-500">
                                        
                                        <span class="h-6 w-6 rounded bg-zinc-950 border border-zinc-850 flex items-center justify-center font-bold text-xxs uppercase text-zinc-500">
                                            {{ chr(65 + $optIndex) }}
                                        </span>
                                        <span class="text-xs sm:text-sm font-semibold text-zinc-300 leading-relaxed">{{ $optionText }}</span>
                                    </label>
                                @endforeach
                            </div>

                        </div>
                    @endforeach

                    <!-- Bottom navigation block -->
                    <div class="flex items-center justify-between border-t border-zinc-850 pt-8 mt-8">
                        <button type="button" 
                                @click="if(currentQuestionIndex > 0) currentQuestionIndex--"
                                :disabled="currentQuestionIndex === 0"
                                class="inline-flex items-center gap-1.5 rounded-xl border border-zinc-800 bg-zinc-900 hover:bg-zinc-850 text-zinc-350 font-bold text-xxs py-3 px-6 shadow transition-all uppercase tracking-wider disabled:opacity-30 disabled:cursor-not-allowed">
                            &larr; Previous Question
                        </button>

                        <!-- Next Question or Submit Assessment -->
                        <div class="flex-shrink-0">
                            <!-- Next button -->
                            <button type="button" 
                                    x-show="currentQuestionIndex < totalQuestions - 1"
                                    @click="currentQuestionIndex++"
                                    class="inline-flex items-center gap-1.5 rounded-xl bg-zinc-800 hover:bg-zinc-750 text-white font-bold text-xxs py-3 px-6 shadow border border-zinc-700 transition-all uppercase tracking-wider">
                                Next Question &rarr;
                            </button>

                            <!-- Final Submit button -->
                            <button type="button" 
                                    x-show="currentQuestionIndex === totalQuestions - 1"
                                    @click="if(confirm('Are you absolutely sure you want to finish and submit your secure quiz attempt?')) forceSubmit()"
                                    class="inline-flex items-center gap-1.5 rounded-xl bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-extrabold text-xxs py-3 px-6 shadow-md transition-all uppercase tracking-wider">
                                Finish & Submit Assessment
                            </button>
                        </div>
                    </div>

                </form>
            </section>

        </main>

    </div>

    <!-- 4. Anti-Cheat Warning Modal popup overlay (100% Bulletproof Native Vanilla JS Modal) -->
    <div id="cheatModal" class="fixed inset-0 z-50 bg-zinc-950/95 backdrop-blur-md flex items-center justify-center p-6 hidden">
        <div class="bg-zinc-900 border border-rose-500/30 rounded-3xl w-full max-w-md shadow-2xl p-8 text-center space-y-6 animate-fade-in">
            <div class="h-16 w-16 rounded-full bg-rose-500/10 flex items-center justify-center mx-auto text-rose-505 border border-rose-500/20 shadow-inner">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            
            <div class="space-y-2">
                <h3 class="text-xl font-black text-rose-500 uppercase tracking-wide">Cheating Policy Violation Warning</h3>
                <p class="text-xs text-rose-350 leading-relaxed">
                    You have switched tabs or clicked outside the active secure test workspace! 
                    Focus loss has been securely logged to the database system.
                </p>
                <p class="text-xs font-bold text-zinc-300 mt-2 bg-rose-500/10 border border-rose-500/20 p-2.5 rounded-xl">
                    Warning <span id="cheatModalSwitchNumber">1</span> of 3. Focus loss will trigger locked-out termination!
                </p>
            </div>

            <button type="button" onclick="closeCheatModal()" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs py-3 px-4 shadow transition-all uppercase tracking-wider">
                Resume Secure Quiz Session
            </button>
        </div>
    </div>

    <!-- AJAX Scripts -->
    <script>
        // Global variables for Native JS Modal and Start Tracking
        window.quizStarted = false;

        function showCheatModal(num) {
            document.getElementById('cheatModalSwitchNumber').innerText = num;
            document.getElementById('cheatModal').classList.remove('hidden');
        }

        function closeCheatModal() {
            document.getElementById('cheatModal').classList.add('hidden');
            // Dispatch to Alpine to enforce fullscreen restoration
            window.dispatchEvent(new CustomEvent('restore-fullscreen'));
        }

        // AJAX MCQ option save
        function saveAnswer(questionId, selectedOption) {
            fetch("{{ route('student.quizzes.saveAnswer', $attempt->id) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    question_id: questionId,
                    selected_option: selectedOption
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.error) console.error(data.error);
            })
            .catch(err => console.error(err));
        }

        document.addEventListener('DOMContentLoaded', () => {
            let lastTriggered = 0;

            function logViolation() {
                // Prevent trigger if quiz hasn't officially started
                if (!window.quizStarted) return;

                let now = Date.now();
                // Prevent duplicate triggers in rapid succession (e.g. both blur and visibilitychange firing)
                if (now - lastTriggered < 1500) return;
                lastTriggered = now;

                fetch("{{ route('student.quizzes.logTabSwitch', $attempt->id) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.terminated) {
                        window.location.href = "{{ route('student.quizzes.result', $attempt->id) }}?terminated=true";
                    } else {
                        if (data.switch_count === 1) {
                            showCheatModal(1);
                        } else if (data.switch_count === 2) {
                            showCheatModal(2);
                        } else if (data.switch_count === 3) {
                            showCheatModal(3);
                        }
                    }
                })
                .catch(err => console.error(err));
            }

            document.addEventListener('visibilitychange', () => {
                if (document.visibilityState === 'hidden') {
                    logViolation();
                }
            });

            window.addEventListener('blur', () => {
                logViolation();
            });
        });
    </script>
</body>
</html>
