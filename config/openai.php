<?php

function askOpenAI(string $systemPrompt, string $userPrompt): string
{
    $apiKey = getenv("OPENAI_API_KEY");

    if (!$apiKey) {
        throw new RuntimeException("OPENAI_API_KEY is not configured.");
    }

    $payload = json_encode([
        "model" => getenv("OPENAI_MODEL") ?: "gpt-4o-mini",
        "input" => [
            [
                "role" => "system",
                "content" => [["type" => "input_text", "text" => $systemPrompt]]
            ],
            [
                "role" => "user",
                "content" => [["type" => "input_text", "text" => $userPrompt]]
            ]
        ],
        "max_output_tokens" => 700
    ]);

    $curl = curl_init("https://api.openai.com/v1/responses");
    curl_setopt_array($curl, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer " . $apiKey
        ],
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_TIMEOUT => 30
    ]);

    $response = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $error = curl_error($curl);
    curl_close($curl);

    if ($response === false || $error || $status < 200 || $status >= 300) {
        throw new RuntimeException("OpenAI request failed.");
    }

    $decoded = json_decode($response, true);
    $text = $decoded["output"][0]["content"][0]["text"] ?? "";

    if ($text === "") {
        throw new RuntimeException("OpenAI returned an empty response.");
    }

    return trim($text);
}

?>