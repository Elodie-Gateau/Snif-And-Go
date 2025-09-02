<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

final class GeocodingService
{
    public function __construct(private HttpClientInterface $http) {}


    public function geocode(string $address): ?array
    {
        $q = trim($address);
        if ($q === '') return null;

        $data = $this->http->request('GET', 'https://api-adresse.data.gouv.fr/search/', [
            'query' => ['q' => $q, 'limit' => 1],
        ])->toArray(false);

        $f = $data['features'][0] ?? null;
        if (!$f) return null;
        [$lon, $lat] = $f['geometry']['coordinates'];

        return [
            'lat' => (float)$lat,
            'lon' => (float)$lon,
            'label' => $f['properties']['label'] ?? $q,
        ];
    }


    public function reverse(float $lat, float $lon): ?array
    {
        // --- 1) BAN : on tente plusieurs "type"
        $tries = [
            ['type' => 'street'],
            [], // défaut
            ['type' => 'municipality'],
        ];

        foreach ($tries as $extra) {
            $query = array_merge(['lat' => $lat, 'lon' => $lon], $extra);

            try {
                $data = $this->http->request('GET', 'https://api-adresse.data.gouv.fr/reverse/', [
                    'query'   => $query,
                    'timeout' => 5,
                ])->toArray(false);
            } catch (\Throwable) {
                continue; // tentative suivante
            }

            $feature = $data['features'][0] ?? null;
            if ($feature) {
                $p = $feature['properties'] ?? [];
                return [
                    'label'    => $p['label']    ?? null,
                    'street'   => $p['name']     ?? null,
                    'city'     => $p['city']     ?? ($p['municipality'] ?? null),
                    'postcode' => $p['postcode'] ?? null,
                ];
            }
        }

        // --- 2) Fallback FR: geo.api.gouv.fr (communes par coord.)
        // Doc: https://geo.api.gouv.fr/decoupage-administratif/communes
        try {
            $communes = $this->http->request('GET', 'https://geo.api.gouv.fr/communes', [
                'query' => [
                    'lat'    => $lat,
                    'lon'    => $lon,
                    'fields' => 'nom,codesPostaux',
                    'format' => 'json',
                    'geometry' => 'centre',
                ],
                'timeout' => 5,
            ])->toArray(false);

            $c = $communes[0] ?? null;
            if ($c) {
                // On ne connaît pas la rue, mais on remplit ville + code
                $city = $c['nom'] ?? null;
                $postcode = null;
                if (!empty($c['codesPostaux']) && \is_array($c['codesPostaux'])) {
                    // s'il y a plusieurs CP, on prend le premier
                    $postcode = $c['codesPostaux'][0] ?? null;
                }

                return [
                    'label'    => $city,        // à défaut
                    'street'   => null,         // inconnu à ce niveau
                    'city'     => $city,
                    'postcode' => $postcode,
                ];
            }
        } catch (\Throwable) {
            // on ignore et on renvoie null
        }

        // --- 3) Rien trouvé
        return null;
    }
}
