<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class SigningKeyWorkspaceDTO
{
    /**
     * FastPix generates a unique identifier for each workspace.
     *
     * @var ?string $id
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('id')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $id = null;

    /**
     * Designated title for the workspace.
     *
     * @var ?string $name
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('name')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $name = null;

    /**
     * Describes the type of a workspace.  Possible value: QA, staging, production, or development.
     *
     * @var ?string $workspaceType
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('workspaceType')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $workspaceType = null;

    /**
     * @param  ?string  $id
     * @param  ?string  $name
     * @param  ?string  $workspaceType
     * @phpstan-pure
     */
    public function __construct(?string $id = null, ?string $name = null, ?string $workspaceType = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->workspaceType = $workspaceType;
    }
}