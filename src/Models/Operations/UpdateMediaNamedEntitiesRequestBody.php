<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;


class UpdateMediaNamedEntitiesRequestBody
{
    /**
     * Enable or disable named entity extraction. Set to `true` to enable or `false` to disable.
     *
     *
     *
     * @var bool $namedEntities
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('namedEntities')]
    public bool $namedEntities;

    /**
     * @param  bool  $namedEntities
     * @phpstan-pure
     */
    public function __construct(bool $namedEntities)
    {
        $this->namedEntities = $namedEntities;
    }
}