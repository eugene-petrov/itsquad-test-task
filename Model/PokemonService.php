<?php
/**
 * Aeqet Pokedex — Pokemon service (caching + validation)
 */

declare(strict_types=1);

namespace Aeqet\Pokedex\Model;

use Aeqet\Pokedex\Model\Data\PokemonData;
use Aeqet\Pokedex\Model\Data\PokemonDataFactory;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class PokemonService
{
    private const CACHE_PREFIX   = 'aeqet_pokedex_pokemon_';
    private const CACHE_TAG      = 'AEQET_POKEDEX';
    private const CACHE_LIFETIME = 3600; // 1 hour

    /**
     * Constructor.
     *
     * @param PokemonApiClient    $apiClient
     * @param CacheInterface      $cache
     * @param SerializerInterface $serializer
     * @param PokemonDataFactory  $pokemonDataFactory
     * @param LoggerInterface     $logger
     */
    public function __construct(
        private readonly PokemonApiClient    $apiClient,
        private readonly CacheInterface      $cache,
        private readonly SerializerInterface $serializer,
        private readonly PokemonDataFactory  $pokemonDataFactory,
        private readonly LoggerInterface     $logger
    ) {
    }

    /**
     * Return Pokemon data by numeric ID.
     *
     * @param int $pokemonId Raw user input (numeric ID)
     *
     * @return PokemonData|null
     */
    public function getPokemon(int $pokemonId): ?PokemonData
    {
        $cacheKey = self::CACHE_PREFIX . $pokemonId;

        try {
            /** @var string|false $cached */
            $cached = $this->cache->load($cacheKey);
        } catch (Throwable $e) {
            $this->logger->warning(
                'Pokedex: cache load failed',
                ['key' => $cacheKey, 'error' => $e->getMessage()]
            );
            $cached = false;
        }

        if (is_string($cached)) {
            try {
                return $this->hydrateFromCache($cached);
            } catch (Throwable $e) {
                $this->logger->warning(
                    'Pokedex: corrupted cache data, re-fetching',
                    ['key' => $cacheKey, 'error' => $e->getMessage()]
                );
            }
        }

        $result = $this->apiClient->fetchPokemon($pokemonId);
        if (empty($result)) {
            return null;
        }

        try {
            $serialized = $this->serializer->serialize($result->toArray());
            if (is_string($serialized)) {
                $this->cache->save($serialized, $cacheKey, [self::CACHE_TAG], self::CACHE_LIFETIME);
            }
        } catch (Throwable $e) {
            $this->logger->warning(
                'Pokedex: failed to cache pokemon data',
                ['id' => $pokemonId, 'error' => $e->getMessage()]
            );
        }

        return $result;
    }

    /**
     * Reconstruct a PokemonData from a cached JSON string, or return null on miss/corruption.
     *
     * @param string $cached
     *
     * @return PokemonData|null
     *
     * @throws Throwable when unserialize fails (caller logs and falls through to API)
     */
    private function hydrateFromCache(string $cached): ?PokemonData
    {
        $data = $this->serializer->unserialize($cached);

        if (!is_array($data)) {
            return null;
        }

        $pokemon = $this->pokemonDataFactory->create();
        $pokemon->fromArray($data);

        return $pokemon;
    }
}
