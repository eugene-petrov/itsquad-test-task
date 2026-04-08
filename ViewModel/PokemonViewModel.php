<?php
/**
 * Aeqet Pokedex — ViewModel for the Pokédex search page
 */

declare(strict_types=1);

namespace Aeqet\Pokedex\ViewModel;

use Aeqet\Pokedex\Model\Data\PokemonData;
use Aeqet\Pokedex\Model\PokemonService;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class PokemonViewModel implements ArgumentInterface
{
    /**
     * @param PokemonService $pokemonService
     * @param RequestInterface $request
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly PokemonService    $pokemonService,
        private readonly RequestInterface  $request,
        private readonly LoggerInterface   $logger
    ) {
    }

    /**
     * Return the raw Pokemon identifier from the GET parameter.
     *
     * @return int
     */
    public function getRequestedId(): int
    {
        return (int) $this->request->getParam('id', '');
    }

    /**
     * Fetch Pokemon data for the current request.
     *
     * Returns null when no ID is present or when the Pokemon is not found.
     *
     * @return PokemonData|null
     */
    public function getPokemonData(): ?PokemonData
    {
        $pokemonId = $this->getRequestedId();

        if (empty($pokemonId)) {
            return null;
        }

        try {
            return $this->pokemonService->getPokemon($pokemonId);
        } catch (Throwable $e) {
            $this->logger->error(
                'Pokedex: failed to fetch pokemon data',
                ['id' => $pokemonId, 'error' => $e->getMessage()]
            );
            return null;
        }
    }
}
