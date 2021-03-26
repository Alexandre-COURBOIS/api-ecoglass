<?php


namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Container
{

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getGlassContainerApi(): array
    {

        $response = $this->client->request(
            'GET',
            'https://www.data.gouv.fr/fr/datasets/r/85ad9858-0f57-4ae0-9af4-e90165ee83ae'
        );

        $content = $response->getContent();

        $content = $response->toArray();

        return $content;

    }

    /**
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getGlassContainerApiToulouse(): array
    {

        $response = $this->client->request(
            'GET',
            'https://data.toulouse-metropole.fr/api/records/1.0/search/?dataset=points-dapport-volontaire-dechets-et-moyens-techniques&q=&rows=5000&facet=commune&facet=flux&facet=centre_ville&facet=prestataire&facet=zone&facet=pole&refine.flux=R%C3%A9cup%27verre'
        );

        $content = $response->getContent();

        $content = $response->toArray();

        return $content;

    }

    /**
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getGlassContainerApiPoitiers(): array
    {

        $response = $this->client->request(
            'GET',
            'https://data.grandpoitiers.fr/api/records/1.0/search/?dataset=proprete-bornes-a-verre-grand-poitiers-donnees-metiers&q=&rows=5000&facet=frequence_de_la_collecte&facet=jour_de_la_collecte'
        );

        $content = $response->getContent();

        $content = $response->toArray();

        return $content;

    }








}