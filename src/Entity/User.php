<?php

declare(strict_types = 1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(
            requirements: ['id' => '\\d+']
        ),
        new Post(),
        new Patch(
            requirements: ['id' => '\\d+']
        ),
        new Delete(
            requirements: ['id' => '\\d+']
        ),
    ],
    normalizationContext: ['groups' => [AbstractEntity::OUTPUT_GROUP]],
    denormalizationContext: ['groups' => [AbstractEntity::INPUT_GROUP]],
)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(columns: ['email'])]
class User extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups([self::OUTPUT_GROUP])]
    protected ?int $id;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank]
    #[Groups([
        AbstractEntity::INPUT_GROUP,
        AbstractEntity::OUTPUT_GROUP,
    ])]
    private string $name;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Groups([
        AbstractEntity::INPUT_GROUP,
        AbstractEntity::OUTPUT_GROUP,
    ])]
    private string $email;

    #[ORM\ManyToOne(targetEntity: Group::class, inversedBy: 'users')]
    #[Groups([
        AbstractEntity::INPUT_GROUP,
        AbstractEntity::OUTPUT_GROUP,
    ])]
    private ?Group $group = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getGroup(): ?Group
    {
        return $this->group;
    }

    public function setGroup(?Group $group): void
    {
        $this->group = $group;
    }
}
