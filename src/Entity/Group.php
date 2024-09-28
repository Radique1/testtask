<?php

declare(strict_types = 1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\DataProvider\GroupReportDataProvider;
use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
        new Get(
            uriTemplate: '/groups/{id}/report',
            requirements: ['id' => '\\d+'],
            description: 'Report',
            provider: GroupReportDataProvider::class,
        ),
    ],
    normalizationContext: ['groups' => [AbstractEntity::OUTPUT_GROUP]],
    denormalizationContext: ['groups' => [AbstractEntity::INPUT_GROUP]],
)]
#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[Orm\Table(name: '`group`')]
class Group extends AbstractEntity
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

    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'group')]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function setUsers(Collection $users): self
    {
        $this->users = $users;

        return $this;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setGroup($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->add($user);
            $user->setGroup(null);
        }

        return $this;
    }
}
