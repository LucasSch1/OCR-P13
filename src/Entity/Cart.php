<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, CartProducts>
     */
    #[ORM\OneToMany(targetEntity: CartProducts::class, mappedBy: 'cart', cascade: ['remove'], orphanRemoval: true)]
    private Collection $cartProducts;

    #[ORM\ManyToOne(inversedBy: 'carts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    public function __construct()
    {
        $this->cartProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, CartProducts>
     */
    public function getCartProducts(): Collection
    {
        return $this->cartProducts;
    }

    public function addCartProduct(CartProducts $cartProducts): static
    {
        if (!$this->cartProducts->contains($cartProducts)) {
            $this->cartProducts->add($cartProducts);
            $cartProducts->setCart($this);
        }

        return $this;
    }

    public function removeCartProduct(CartProducts $cartProducts): static
    {
        if ($this->cartProducts->removeElement($cartProducts)) {
            // set the owning side to null (unless already changed)
            if ($cartProducts->getCart() === $this) {
                $cartProducts->setCart(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getTotalPrice(): ?string
    {
        $totalPrice = 0;
        foreach ($this->cartProducts as $cartProduct) {
            $totalPrice += $cartProduct->getUnitPrice() * $cartProduct->getQuantity();
        }
        return $totalPrice;
    }
}
