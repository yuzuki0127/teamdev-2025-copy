<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アンケートのお願い</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-2xl mx-auto bg-white p-8 shadow rounded">
        <p class="mb-4">お疲れ様です。</p>
        <p class="mb-4">{{ $user->team->team_name }} の {{ $user->name }} です。</p>
        <p class="mb-4">
            この度、皆さんのご意見を伺うためにアンケートを実施させていただきます。アンケートは、今後の業務改善や職場環境の向上に活かすための貴重な資料として利用させていただきますので、ご協力いただけますようお願い申し上げます。
        </p>

        <p class="mb-4 text-lg font-bold text-blue-default">
            所要時間： 約{{ $result }}
        </p>

        <div class="mb-4">
            <a href="{{ route('survey', ['surveyId' => $survey->id, 'employeeId' => $employee->id]) }}"
                class="inline-block bg-blue-default text-white py-2 px-4 rounded hover:bg-cyan-800">
                アンケートリンクはこちら
            </a>
        </div>

        <p class="mb-4 text-lg font-bold text-red-600">
            回答期限： {{ $survey->response_deadline }} まで
        </p>

        <p class="mb-4">
            ご回答いただいた内容は匿名で集計されますので、ご安心ください。また、皆さんの率直なご意見をお聞かせいただけることを楽しみにしております。
        </p>
        <p class="mb-4">
            なお、アンケートに関してご不明点等がございましたら、私までお気軽にご連絡ください。
        </p>
        <p class="mb-4">
            今後とも、より良い職場環境づくりに向けて皆さんと一緒に取り組んでまいりますので、引き続きよろしくお願いします。
        </p>

        <p class="mb-4">
            {{ $user->name }}<br>
            {{ $user->team->team_name }}<br>
            連絡先: {{ $user->email }}
        </p>
    </div>
</body>

</html>
