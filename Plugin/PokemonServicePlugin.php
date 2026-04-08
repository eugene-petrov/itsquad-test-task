<?php
/**
 * Aeqet Pokedex — Logger plugin for PokemonService
 */

declare(strict_types=1);

namespace Aeqet\Pokedex\Plugin;

use Aeqet\Pokedex\Model\Data\PokemonData;
use Aeqet\Pokedex\Model\PokemonService;
use Psr\Log\LoggerInterface;

class PokemonServicePlugin
{
    /**
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * Log every getPokemon() call with its query and result.
     *
     * Log format: "Query: {id} - Result: {name}" or "Query: {id} - Result: not found"
     *
     * @param PokemonService $subject
     * @param PokemonData|null $result
     * @param int $pokemonId
     * @return PokemonData|null
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetPokemon(
        PokemonService $subject,
        ?PokemonData $result,
        int $pokemonId
    ): ?PokemonData {
        $name = $result !== null ? $result->getName() : 'not found';
        $this->logger->info(sprintf('Query: %s - Result: %s', $pokemonId, $name));

        return $result;
    }
}
