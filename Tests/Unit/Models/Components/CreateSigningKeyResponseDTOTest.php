<?php

declare(strict_types=1);

namespace FastPix\Sdk\Tests\Unit\Models\Components;

use FastPix\Sdk\Models\Components\CreateSigningKeyResponseDTO;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FastPix\Sdk\Models\Components\CreateSigningKeyResponseDTO
 */
class CreateSigningKeyResponseDTOTest extends TestCase
{
    public function test_create_signing_key_response_dto_can_be_created(): void
    {
        $dto = new CreateSigningKeyResponseDTO(
            id: 'test-key-id',
            privateKey: 'test-private-key',
            createdAt: new \DateTime('2023-01-01T00:00:00Z')
        );

        $this->assertEquals('test-key-id', $dto->id);
        $this->assertEquals('test-private-key', $dto->privateKey);
        $this->assertInstanceOf(\DateTime::class, $dto->createdAt);
    }

    public function test_create_signing_key_response_dto_with_null_values(): void
    {
        $dto = new CreateSigningKeyResponseDTO();

        $this->assertNull($dto->id);
        $this->assertNull($dto->privateKey);
        $this->assertNull($dto->createdAt);
    }

    public function test_create_signing_key_response_dto_properties_are_public(): void
    {
        $dto = new CreateSigningKeyResponseDTO();

        $this->assertObjectHasProperty('id', $dto);
        $this->assertObjectHasProperty('privateKey', $dto);
        $this->assertObjectHasProperty('createdAt', $dto);
    }

    public function test_create_signing_key_response_dto_with_partial_data(): void
    {
        $dto = new CreateSigningKeyResponseDTO(
            id: 'partial-key-id',
            privateKey: null,
            createdAt: new \DateTime()
        );

        $this->assertEquals('partial-key-id', $dto->id);
        $this->assertNull($dto->privateKey);
        $this->assertInstanceOf(\DateTime::class, $dto->createdAt);
    }
}
