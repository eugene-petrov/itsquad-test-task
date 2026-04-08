<?php
/**
 * Aeqet Pokedex — PokéAPI HTTP client
 */

declare(strict_types=1);

namespace Aeqet\Pokedex\Model;

use Aeqet\Pokedex\Model\Data\PokemonData;
use Aeqet\Pokedex\Model\Data\PokemonDataFactory;
use Magento\Framework\HTTP\ClientFactory;
use Magento\Framework\Serialize\SerializerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class PokemonApiClient
{
    private const API_BASE = 'https://pokeapi.co/api/v2/pokemon/';
    private const TIMEOUT_SEC = 5;

    /**
     * @param ClientFactory $httpClientFactory
     * @param SerializerInterface $serializer
     * @param PokemonDataFactory $pokemonDataFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly ClientFactory $httpClientFactory,
        private readonly SerializerInterface $serializer,
        private readonly PokemonDataFactory $pokemonDataFactory,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * Fetch a single Pokemon from PokéAPI by ID or name.
     *
     * @param int $pokemonId pre-validated, URL-safe identifier
     *
     * @return PokemonData|null
     */
    public function fetchPokemon(int $pokemonId): ?PokemonData
    {
        try {
            $client = $this->httpClientFactory->create();
            $client->setTimeout(self::TIMEOUT_SEC);
            $client->get(self::API_BASE . urlencode((string)$pokemonId));
        } catch (Throwable $e) {
            $this->logger->warning('Pokedex: API request failed', ['id' => $pokemonId, 'error' => $e->getMessage()]);
            return null;
        }

        $status = $client->getStatus();
        if ($status === 404) {
            return null;
        }
        if ($status !== 200) {
            $this->logger->warning(
                'Pokedex: unexpected API response status',
                ['id' => $pokemonId, 'status' => $status]
            );
            return null;
        }

        try {
            $data = $this->serializer->unserialize($client->getBody());
        } catch (Throwable $e) {
            $this->logger->warning(
                'Pokedex: failed to parse API response',
                ['id' => $pokemonId, 'error' => $e->getMessage()]
            );
            return null;
        }

        if (!is_array($data)) {
            $this->logger->warning('Pokedex: unexpected API response format', ['id' => $pokemonId]);
            return null;
        }

        return $this->pokemonDataFactory->create()
            ->setId((int)($data['id'] ?? 0))
            ->setName((string)($data['name'] ?? ''))
            ->setHeight((int)($data['height'] ?? 0))
            ->setWeight((int)($data['weight'] ?? 0))
            ->setBaseExperience((int)($data['base_experience'] ?? 0))
            ->setTypes(
                array_map(
                    static fn(array $t) => (string)($t['type']['name'] ?? ''),
                    is_array($data['types'] ?? null) ? $data['types'] : []
                )
            );
    }
}
