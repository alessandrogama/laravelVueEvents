<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Smalot\Text2Vec\Vectorizer;

class IAController extends Controller
{
    // Função para perguntar ao ChatGPT
    private function askChatGPT($pergunta)
    {
        $client = new Client();
        $response = $client->post('https://api.openai.com/v1/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'text-davinci-003', // Modelo do ChatGPT
                'prompt' => $pergunta,
                'max_tokens' => 150, // Limite de tokens na resposta
                'temperature' => 0.7, // Controla a criatividade da resposta
            ],
        ]);

        $data = json_decode($response->getBody(), true);
        return $data['choices'][0]['text'];
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

    // Função para calcular similaridade semântica
    private function calcularSimilaridade($texto1, $texto2)
    {
        $vectorizer = new Vectorizer();
        $vetor1 = $vectorizer->vectorize($texto1);
        $vetor2 = $vectorizer->vectorize($texto2);

        // Calcula a similaridade de cosseno
        $similaridade = $vectorizer->cosineSimilarity($vetor1, $vetor2);
        return $similaridade;
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
            $pontuacoes[$ia1] = $similaridadeMedia * strlen($resposta1); // Combina similaridade e comprimento
        }

        // Escolhe a resposta com a maior pontuação
        arsort($pontuacoes);
        $melhorIa = array_key_first($pontuacoes);
        return $respostas[$melhorIa];
    }

    // Função principal para fazer as IAs conversarem
    public function perguntar(Request $request)
    {
        dd($request);
        $pergunta = $request->input('pergunta');

        // Coletar respostas de cada IA
        $respostas = [
            'chatgpt' => $this->askChatGPT($pergunta),
            'gemini' => $this->askGemini($pergunta),
            'deepseek' => $this->askDeepSeek($pergunta),
            'copilot' => $this->askCopilot($pergunta),
        ];

        // Escolher a melhor resposta
        $melhorResposta = $this->avaliarRespostas($respostas);

        return response()->json([
            'respostas' => $respostas,
            'melhor_resposta' => $melhorResposta,
        ]);
    }
}