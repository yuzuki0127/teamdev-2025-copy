<x-app-layout>
    <div class="max-w-5xl mx-auto p-6">
        <div class="space-y-8 max-w-screen-lg mx-auto px-6 mb-4">
            <div class="flex items-center gap-4">
                <div class="flex-grow h-px border-2 border-blue-default"></div>
                <div><img src="{{ asset('images/survey.png') }}" class="w-10 h-10"></div>
                <div class="font-bold text-xl text-navy-default whitespace-nowrap">アンケートを作成する</div>
                <div class="flex-grow h-px border-2 border-blue-default"></div>
            </div>
        </div>
        <form action="{{ route('survey.store') }}" method="POST" id="questionForm">
            @csrf
            @foreach ($templates as $templateIndex => $template)
                <div class="bg-white rounded-md shadow mb-6 p-4">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="font-bold text-base">分類{{ $templateIndex + 1 }}.{{ $template->survey_category_name }}</h2>
                    </div>
                    @foreach ($template->surveyQuestionTemplates as $question)
                        @if ($question->editable === 0)
                            <div class="mb-4 flex ml-20 gap-10">
                                <div class="mb-2 font-bold">質問</div>
                                <div class="text-gray-600 mb-4">{{ $question->survey_question_text }}</div>
                            </div>
                        @elseif ($question->editable === 1)
                            <div class="mb-4 flex ml-20 gap-10" id="question-{{ $question->id }}">
                                <div class="mb-2 font-bold">質問</div>
                                <div class="flex gap-4 items-center">
                                    <input type="textarea"
                                        name="question_texts[{{ $template->id }}][{{ $question->id }}]"
                                        value="{{ $question->survey_question_text }}" placeholder="質問を入力してください"
                                        class="border rounded p-3 w-175 mb-3 border-gray-300" rows="2">
                                    <!-- 削除ボタン -->
                                    <button type="button"
                                        class="delete-question-btn text-orange-default font-semibold border-1.5 border-orange-default rounded-lg px-2 hover:bg-orange-default hover:text-white transition"
                                        data-question-id="{{ $question->id }}">
                                        − 削除
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    <!-- 質問追加フォーム -->
                    <div class="mb-4 flex">
                        <div id="category-{{ $template->id }}">
                        </div>
                    </div>
                    <button type="button"
                        class="block mx-auto bg-blue-300 text-white font-bold px-2 py-1 rounded transition add-question-btn shadow hover:bg-white hover:text-blue-300 hover:border-1.5 hover:border-blue-300"
                        data-category-id="{{ $template->id }}">＋ 質問を追加</button>
                </div>
            @endforeach

            <!-- 保存ボタン -->
            <div class="flex justify-center mt-6">
                <button type="submit"
                    class="bg-blue-default text-white flex items-center gap-2 px-6 py-2 font-bold rounded hover:bg-blue-default hover:bg-opacity-50 transition"><img
                        src="{{ asset('images/download.png') }}" class="w-4 h-4" alt="">保存</button>
            </div>

        </form>
        <!-- 送信ボタン (固定位置) -->
        <form action="{{ route('survey.send') }}" method="POST" id="sendSurveyForm">
            @csrf
            <div class="fixed top-5 right-5">
                <button type="submit"
                    class="bg-blue-default text-white font-bold px-6 py-4 flex items-center gap-2 rounded hover:bg-blue-default hover:bg-opacity-50 transition"><img
                        src="{{ asset('images/mail.png') }}" class="w-6 h-6" alt="">送信</button>
            </div>
        </form>
        <script>
            const teamEmployees = @json($team->employees);
            const currentQuestionCount = @json(count($templates->flatMap->surveyQuestionTemplates));
            const maxQuestions = 30;
        </script>
</x-app-layout>
