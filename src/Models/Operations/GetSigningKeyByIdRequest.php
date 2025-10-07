<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Utils\FastPixMetadata;
class GetSigningKeyByIdRequest
{
    /**
     * When creating the signing key, FastPix assigns a universally unique identifier with a maximum length of 255 characters. 
     *
     * @var string $signingKeyId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=signingKeyId')]
    public string $signingKeyId;

    /**
     * @param  string  $signingKeyId
     * @phpstan-pure
     */
    public function __construct(string $signingKeyId)
    {
        $this->signingKeyId = $signingKeyId;
    }
}