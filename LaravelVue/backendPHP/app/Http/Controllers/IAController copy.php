<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

private function askChatGPT($pergunta)
{
    $client = new Client();
   try{ 
    $response = $client->post('https://api.openai.com/v1/completions', [
        'headers' => [
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            'Content-Type' => 'application/json',
        ],
        'json' => [
            'model' => 'gpt-4-turbo', // Modelo do ChatGPT
            'prompt' => $pergunta,
            'max_tokens' => 150, // Limite de tokens na resposta
            'temperature' => 0.7, // Controla a criatividade da resposta
        ],
    ]);

    $data = json_decode($response->getBody(), true);
    return $data['choices'][0]['text'];
} catch (\Exception $e) {
    return 'Erro ao conectar ao ChatGPT: ' . $e->getMessage();
}
}

class IAController extends Controller
{
    // Função para perguntar ao ChatGPT
    private function askChatGPT($pergunta)
    {
        $client = new Client();
        
        try {
            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-4-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => 'Você é um assistente útil.'],
                        ['role' => 'user', 'content' => $pergunta]
                    ],
                    'max_tokens' => 150,
                    'temperature' => 0.7,
                ],
            ]);
    
            $data = json_decode($response->getBody(), true);
            return $data['choices'][0]['message']['content'];
    
        } catch (\Exception $e) {
            return 'Erro ao conectar ao ChatGPT: ' . $e->getMessage();
        }
    }

    // Nova função para obter embeddings do texto usando OpenAI
    private function getEmbedding($texto)
    {
        $client = new Client();
        
        try {
            $response = $client->post('https://api.openai.com/v1/embeddings', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'input' => $texto,
                    'model' => 'text-embedding-3-small'
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            return $data['data'][0]['embedding'];

        } catch (\Exception $e) {
            return [];
        }
    }

    // Nova função para calcular similaridade de cosseno
    private function cosineSimilarity($vecA, $vecB)
    {
        $dotProduct = 0;
        $normA = 0;
        $normB = 0;

        foreach ($vecA as $i => $value) {
            $dotProduct += $value * $vecB[$i];
            $normA += $value ** 2;
            $normB += $vecB[$i] ** 2;
        }

        if ($normA == 0 || $normB == 0) {
            return 0;
        }

        return $dotProduct / (sqrt($normA) * sqrt($normB));
    }

    // Função atualizada para calcular similaridade semântica
    private function calcularSimilaridade($texto1, $texto2)
    {
        $embedding1 = $this->getEmbedding($texto1);
        $embedding2 = $this->getEmbedding($texto2);

        if (empty($embedding1) || empty($embedding2)) {
            return 0;
        }

        return $this->cosineSimilarity($embedding1, $embedding2);
    }

    // Função para perguntar ao Gemini (exemplo fictício)
    private function askGemini($pergunta)
    {
        // Implementação fictícia (substitua pela API real do Gemini)
        return "Resposta do Gemini: Esta é uma resposta fictícia para a pergunta: $pergunta";
    }

    // Função para perguntar ao DeepSeek (exemplo fictício)
    private function askDeepSeek($pergunta)
    {
        // Implementação fictícia (substitua pela API real do DeepSeek)
        return "Resposta do DeepSeek: Esta é uma resposta fictícia para a pergunta: $pergunta";
    }

    // Função para perguntar ao Copilot (exemplo fictício)
    private function askCopilot($pergunta)
    {
        // Implementação fictícia (substitua pela API real do Copilot)
        return "Resposta do Copilot: Esta é uma resposta fictícia para a pergunta: $pergunta";
    }

    
    // Função para avaliar respostas de forma sofisticada
    private function avaliarRespostas($respostas)
    {
        // Calcula a similaridade média de cada resposta com as outras
        $pontuacoes = [];
        foreach ($respostas as $ia1 => $resposta1) {
            $similaridadeTotal = 0;
            foreach ($respostas as $ia2 => $resposta2) {
                if ($ia1 !== $ia2) {
                    $similaridadeTotal += $this->calcularSimilaridade($resposta1, $resposta2);
                }
            }
            $similaridadeMedia = $similaridadeTotal / (count($respostas) - 1);
            $pontuacoes[$ia1] = $similaridadeMedia * strlen($resposta1);
        }

        arsort($pontuacoes);
        return $respostas[array_key_first($pontuacoes)];
    }

    public function perguntar(Request $request)
    {
        $pergunta = $request->input('pergunta');

        $respostas = [
            'chatgpt' => $this->askChatGPT($pergunta),
            'gemini' => $this->askGemini($pergunta),
            'deepseek' => $this->askDeepSeek($pergunta),
            'copilot' => $this->askCopilot($pergunta),
        ];

        $melhorResposta = $this->avaliarRespostas($respostas);

        return response()->json([
            'respostas' => $respostas,
            'melhor_resposta' => $melhorResposta,
        ]);
    }
}