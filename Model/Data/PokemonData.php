<?php
/**
 * Aeqet Pokedex — Pokemon DTO
 */

declare(strict_types=1);

namespace Aeqet\Pokedex\Model\Data;

class PokemonData
{
    /**
     * @var int
     */
    private int $pokemonId = 0;

    /**
     * @var string
     */
    private string $name = '';

    /**
     * @var int
     */
    private int $height = 0;

    /**
     * @var int
     */
    private int $weight = 0;

    /**
     * @var int
     */
    private int $baseExperience = 0;

    /**
     * @var string[]
     */
    private array $types = [];

    /**
     * Get Pokemon ID.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->pokemonId;
    }

    /**
     * Set Pokemon ID.
     *
     * @param int $pokemonId
     * @return PokemonData
     */
    public function setId(int $pokemonId): self
    {
        $this->pokemonId = $pokemonId;
        return $this;
    }

    /**
     * Get Pokemon name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set Pokemon name.
     *
     * @param string $name
     * @return PokemonData
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get Pokemon height in decimetres.
     *
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * Set Pokemon height in decimetres.
     *
     * @param int $height
     * @return PokemonData
     */
    public function setHeight(int $height): self
    {
        $this->height = $height;
        return $this;
    }

    /**
     * Get Pokemon weight in hectograms.
     *
     * @return int
     */
    public function getWeight(): int
    {
        return $this->weight;
    }

    /**
     * Set Pokemon weight in hectograms.
     *
     * @param int $weight
     * @return PokemonData
     */
    public function setWeight(int $weight): self
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * Get base experience yield.
     *
     * @return int
     */
    public function getBaseExperience(): int
    {
        return $this->baseExperience;
    }

    /**
     * Set base experience yield.
     *
     * @param int $baseExperience
     * @return PokemonData
     */
    public function setBaseExperience(int $baseExperience): self
    {
        $this->baseExperience = $baseExperience;
        return $this;
    }

    /**
     * Get list of type names (e.g. ["electric"]).
     *
     * @return string[]
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    /**
     * Set list of type names.
     *
     * @param string[] $types
     * @return PokemonData
     */
    public function setTypes(array $types): self
    {
        $this->types = $types;
        return $this;
    }

    /**
     * Return array representation of the object.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->pokemonId,
            'name' => $this->name,
            'height' => $this->height,
            'weight' => $this->weight,
            'base_experience' => $this->baseExperience,
            'types' => $this->types,
        ];
    }

    /**
     * Restore object state from array.
     *
     * @param mixed[] $data
     */
    public function fromArray(array $data): void
    {
        $this->pokemonId = (int)($data['id'] ?? 0);
        $this->name = (string)($data['name'] ?? '');
        $this->height = (int)($data['height'] ?? 0);
        $this->weight = (int)($data['weight'] ?? 0);
        $this->baseExperience = (int)($data['base_experience'] ?? 0);
        $this->types = array_map('strval', (array)($data['types'] ?? []));
    }
}
