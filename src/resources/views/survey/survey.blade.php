<x-guest-layout>
    <div class="mx-auto w-175">
        <div class="bg-white rounded-lg shadow-xl mt-12 mb-8 px-28 py-8">
            <h1 class="text-3xl font-bold text-center mb-4">
                {{ $surveys->survey_name }}
            </h1>
            <div class="mb-4">
                <h2 class="text-xl font-bold mb-1">実施の目的</h2>
                <p>従業員の皆さんがより働きやすい職場環境を実現するために、会社の現状を正しく把握することです。</p>
            </div>
            <div class="mb-4">
                <h2 class="text-xl font-bold mb-1">回答予定時間</h2>
                <p>{{ $result }}</p>
            </div>
            <div class="mb-4">
                <h2 class="text-xl font-bold mb-1">匿名性</h2>
                <p>回答は匿名化され、個人が特定されることは一切ありません。</p>
            </div>
            <div class="flex justify-end pr-4">
                <div id="remaining-time" class="text-xl font-bold">所要時間0分0秒</div>
            </div>
        </div>
        <div class="progress-bar-container flex justify-center px-4 mb-6 bg-white rounded-lg shadow-xl">
            <div class="w-full mb-2.5">
                <div class="flex justify-end pr-4">
                    <div id="remaining-time" class="text-sm font-bold">残り0分0秒</div>
                </div>
                <div class="flex items-center gap-4">
                    <div id="progress-text" class="text-navy-default font-medium">0%</div>
                    <div class="flex-1 bg-gray-200 rounded-full h-2.5">
                        <div id="progress-bar" class="bg-blue-default h-2.5 rounded-full transition-all duration-300">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <form
                action="{{ route('survey.answers.store', ['surveyId' => $surveys->id, 'employeeId' => $employeeId]) }}"
                method="POST">
                @csrf
                <!-- Progress bar -->
                <div id="survey-meta" data-total-questions="{{ $surveys->surveyQuestions->count() }}">
                </div>

                <div>
                    <div class="mb-12">
                        @foreach ($surveys->surveyQuestions as $questionIndex => $question)
                            <div class="survey-question bg-white mb-4 px-12 py-6 rounded-sm shadow">
                                <div class="mb-6 font-bold text-base text-center">{{ $questionIndex + 1 }}.
                                    {{ $question->survey_question_text }}</div>
                                <div class="flex justify-center gap-4">
                                    @foreach ($ratings as $ratingValue => $rating)
                                        <div class="flex flex-col items-center justify-center w-24">
                                            <input type="radio" name="answers[{{ $question->id }}][answer]"
                                                value="{{ $ratingValue }}" class="hidden"
                                                id="q{{ $question->id }}-{{ $ratingValue }}">
                                            <label for="q{{ $question->id }}-{{ $ratingValue }}"
                                                class="rounded-full border border-gray-400 mb-2 cursor-pointer flex items-center justify-center radio-label"
                                                style="width: {{ $rating['circleSize'] }}; height: {{ $rating['circleSize'] }}; line-height: {{ $rating['circleSize'] }}; position: relative;">
                                                <svg class="checkmark hidden absolute"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor"
                                                    style="width: {{ $rating['circleSize'] }}/2; height: {{ $rating['circleSize'] }}/2; color: white;">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </label>
                                            <span class="text-xs mt-1">{{ $rating['label'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="flex justify-end mt-4">
                                    <button type="button"
                                        class="border border-gray-500 bg-white px-4 py-1 rounded flex items-center mx-auto shadow-lg text-sm toggle-description"
                                        data-target="description-{{ $question->id }}">
                                        <span>記述する（任意）</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </div>
                                <div id="description-{{ $question->id }}" class="mt-4 hidden-form hidden">
                                    <div class="mb-4">
                                        <div class="mb-2 font-semibold">詳細</div>
                                        <textarea name="answers[{{ $question->id }}][detail_answer]" placeholder="思ったことや、気になる点などお書きください"
                                            class="w-full border border-gray-300 rounded p-2 focus:outline-none resize-none" rows="2"></textarea>
                                    </div>
                                    <input type="hidden" name="answers[{{ $question->id }}][survey_question_id]"
                                        value="{{ $question->id }}">
                                    <input type="hidden" name="answers[{{ $question->id }}][survey_category_id]"
                                        value="{{ $question->survey_category_id }}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Submit button -->
                <div class="mt-6 flex justify-center mb-8">
                    <button type="submit" id="submit-button"
                        class="bg-blue-default text-white font-bold px-8 py-3 rounded-md hover:opacity-80 transition shadow-lg"
                        disabled>
                        送信
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        input[type="radio"]:checked+label .checkmark {
            display: block;
        }

        .survey-question .hidden+.toggle-description svg.rotated {
            transform: rotate(180deg);
        }

        .toggle-description svg {
            transition: transform 0.3s ease;
        }

        /* 最初は非表示で透明に */
        .toggle-description+.hidden-form {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, opacity 0.3s ease;
        }

        .toggle-description+.hidden-form.show {
            max-height: 1000px;
            opacity: 1;
        }

        /* レスポンシブ対応 */
        @media (max-width: 1024px) {
            .mx-auto {
                width: 90%;
                /* 中央寄せを維持しつつ、画面の幅に合わせて調整 */
            }

            .px-28 {
                padding-left: 2rem;
                padding-right: 2rem;
            }

            .survey-question {
                padding-left: 2rem;
                padding-right: 2rem;
                margin-bottom: 2rem;
                /* 質問間の間隔を少し狭く */
            }

            .progress-bar-container {
                width: 100%;
                /* 進捗バーが画面幅に合わせて調整される */
            }

            .progress-stacked {
                width: 90%;
            }

            .survey-question .radio-label {
                width: 20vw;
                /* ボタンの幅を相対的に調整 */
                height: 20vw;
            }

            .survey-question .text-xs {
                font-size: 0.8rem;
                /* 小さな文字を縮小 */
            }

            .toggle-description {
                font-size: 0.8rem;
            }

            #submit-button {
                font-size: 1rem;
                padding: 1rem 2rem;
                /* ボタンの大きさも調整 */
            }

            .mb-12 {
                margin-bottom: 1.5rem;
            }
        }

        @media (max-width: 768px) {

            /* スマートフォンサイズで調整 */
            .mx-auto {
                width: 90%;
            }

            .survey-question .radio-label {
                width: 40vw;
                /* スマートフォンサイズでラジオボタンのサイズを大きく */
                height: 40vw;
            }

            .progress-bar-container {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .survey-question {
                padding: 1rem;
                margin-bottom: 1.5rem;
                /* 質問の間隔を縮小 */
            }

            .text-xs {
                font-size: 0.7rem;
            }

            .toggle-description {
                font-size: 0.75rem;
                /* 説明ボタンのサイズを調整 */
            }

            #submit-button {
                padding: 1rem;
                font-size: 0.9rem;
            }
        }
    </style>
</x-guest-layout>
