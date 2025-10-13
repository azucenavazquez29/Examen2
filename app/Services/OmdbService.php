<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class OmdbService
{
    private $apiKey;
    private $apiUrl = 'https://www.omdbapi.com/';

    public function __construct()
    {
        $this->apiKey = config('services.omdb.key');
        
        if (!$this->apiKey) {
            throw new Exception('OMDb API key not configured');
        }
    }

    /**
     * Buscar película por título
     */
    public function searchByTitle($title)
    {
        try {
            // Construir URL manualmente para evitar problemas de encoding
            $url = $this->apiUrl . '?' . http_build_query([
                'apikey' => $this->apiKey,
                't' => $title,
                'type' => 'movie',
                'plot' => 'full'
            ]);

            \Log::info('OMDb Search URL: ' . $url);

            $response = Http::timeout(10)->get($url);

            \Log::info('OMDb Response Status: ' . $response->status());
            \Log::info('OMDb Response Body: ' . $response->body());

            if ($response->failed()) {
                throw new Exception('Error al conectar con OMDb API - Status: ' . $response->status());
            }

            $data = $response->json();

            if (isset($data['Response']) && $data['Response'] === 'False') {
                throw new Exception($data['Error'] ?? 'Película no encontrada');
            }

            return $this->formatMovieData($data);
        } catch (Exception $e) {
            throw new Exception('Error al buscar película: ' . $e->getMessage());
        }
    }

    /**
     * Buscar película por ID de OMDb
     */
    public function searchById($imdbId)
    {
        try {
            $url = $this->apiUrl . '?' . http_build_query([
                'apikey' => $this->apiKey,
                'i' => $imdbId,
                'plot' => 'full'
            ]);

            $response = Http::timeout(10)->get($url);

            if ($response->failed()) {
                throw new Exception('Error al conectar con OMDb API');
            }

            $data = $response->json();

            if (isset($data['Response']) && $data['Response'] === 'False') {
                throw new Exception($data['Error'] ?? 'Película no encontrada');
            }

            return $this->formatMovieData($data);
        } catch (Exception $e) {
            throw new Exception('Error al buscar película: ' . $e->getMessage());
        }
    }

    /**
     * Formatear datos de la película desde OMDb
     */
    private function formatMovieData($data)
    {
        return [
            'title' => $data['Title'] ?? 'N/A',
            'description' => $data['Plot'] ?? null,
            'release_year' => $this->extractYear($data['Released'] ?? null),
            'length' => $this->extractMinutes($data['Runtime'] ?? null),
            'rating' => $this->mapRating($data['Rated'] ?? 'G'),
            'imdb_id' => $data['imdbID'] ?? null,
            'poster' => $data['Poster'] ?? null,
            'actors' => $this->parseActors($data['Actors'] ?? ''),
            'genres' => $this->parseGenres($data['Genre'] ?? ''),
            'imdb_rating' => $data['imdbRating'] ?? '0',
            'director' => $data['Director'] ?? null,
        ];
    }

    /**
     * Extraer año de la fecha
     */
    private function extractYear($released)
    {
        if (!$released || $released === 'N/A') {
            return null;
        }
        
        preg_match('/(\d{4})/', $released, $matches);
        return $matches[1] ?? null;
    }

    /**
     * Extraer minutos del runtime
     */
    private function extractMinutes($runtime)
    {
        if (!$runtime || $runtime === 'N/A') {
            return null;
        }
        
        preg_match('/(\d+)/', $runtime, $matches);
        return (int)($matches[1] ?? 0);
    }

    /**
     * Mapear rating de OMDb al formato sakila
     */
    private function mapRating($omdbRating)
    {
        $sakilaRatings = ['G', 'PG', 'PG-13', 'R', 'NC-17'];
        
        if (in_array($omdbRating, $sakilaRatings)) {
            return $omdbRating;
        }

        // Mapeo aproximado
        $mapping = [
            'APPROVED' => 'G',
            'UNRATED' => 'R',
            'NOT RATED' => 'R',
            'PASSED' => 'G'
        ];

        return $mapping[strtoupper($omdbRating)] ?? 'G';
    }

    /**
     * Parsear actores de string separado por comas
     */
    private function parseActors($actorsString)
    {
        if (!$actorsString || $actorsString === 'N/A') {
            return [];
        }

        return array_map('trim', explode(',', $actorsString));
    }

    /**
     * Parsear géneros de string separado por comas
     */
    private function parseGenres($genresString)
    {
        if (!$genresString || $genresString === 'N/A') {
            return [];
        }

        return array_map('trim', explode(',', $genresString));
    }
}