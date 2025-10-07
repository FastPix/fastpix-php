<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;


class GetDrmConfigurationByIdResponse
{
    /**
     * HTTP response content type for this operation
     *
     * @var string $contentType
     */
    public string $contentType;

    /**
     * HTTP response status code for this operation
     *
     * @var int $statusCode
     */
    public int $statusCode;

    /**
     * Raw HTTP response; suitable for custom response parsing
     *
     * @var \Psr\Http\Message\ResponseInterface $rawResponse
     */
    public \Psr\Http\Message\ResponseInterface $rawResponse;

    /**
     * DRM configuration retrieved successfully
     *
     * @var ?GetDrmConfigurationByIdResponseBody $object
     */
    public ?GetDrmConfigurationByIdResponseBody $object = null;

    /**
     * @param  string  $contentType
     * @param  int  $statusCode
     * @param  \Psr\Http\Message\ResponseInterface  $rawResponse
     * @param  ?GetDrmConfigurationByIdResponseBody  $object
     * @phpstan-pure
     */
    public function __construct(string $contentType, int $statusCode, \Psr\Http\Message\ResponseInterface $rawResponse, ?GetDrmConfigurationByIdResponseBody $object = null)
    {
        $this->contentType = $contentType;
        $this->statusCode = $statusCode;
        $this->rawResponse = $rawResponse;
        $this->object = $object;
    }
}