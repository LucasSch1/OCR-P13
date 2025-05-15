<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getProducts"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getProducts"])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(["getProducts"])]
    private ?string $longDescription = null;

    #[ORM\Column(length: 128)]
    #[Groups(["getProducts"])]
    private ?string $shortDescription = null;

    #[ORM\Column]
    #[Groups(["getProducts"])]
    private ?string $productPrice = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getProducts"])]
    private ?string $image = null;

    /**
     * @var Collection<int, OrderProduct>
     */
    #[ORM\OneToMany(targetEntity: OrderProduct::class, mappedBy: 'product', orphanRemoval: true)]
    private Collection $orderProducts;


    /**
     * @var Collection<int, CartProducts>
     */
    #[ORM\OneToMany(targetEntity: CartProducts::class, mappedBy: 'product')]
    private Collection $cartProducts;


    public function __construct()
    {
        $this->orderProducts = new ArrayCollection();
        $this->cartProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLongDescription(): ?string
    {
        return $this->longDescription;
    }

    public function setLongDescription(string $longDescription): static
    {
        $this->longDescription = $longDescription;

        return $this;
        return $this;
    }

    public function getProductPrice(): ?string
    {
        return $this->productPrice;
    }

    public function setProductPrice(string $productPrice): static
    {
        $this->productPrice = $productPrice;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, OrderProduct>
     */
    public function getOrderProducts(): Collection
    {
        return $this->orderProducts;
    }

    public function addOrderProduct(OrderProduct $orderProduct): static
    {
        if (!$this->orderProducts->contains($orderProduct)) {
            $this->orderProducts->add($orderProduct);
            $orderProduct->setProduct($this);
        }

        return $this;
    }

    public function removeOrderProduct(OrderProduct $orderProduct): static
    {
        if ($this->orderProducts->removeElement($orderProduct)) {
            // set the owning side to null (unless already changed)
            if ($orderProduct->getProduct() === $this) {
                $orderProduct->setProduct(null);
            }
        }

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): static
    {
        $this->shortDescription = $shortDescription;

        return $this;
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
            $cartProducts->setProduct($this);
        }

        return $this;
    }

    public function removeCartProduct(CartProducts $cartProducts): static
    {
        if ($this->cartProducts->removeElement($cartProducts)) {
            // set the owning side to null (unless already changed)
            if ($cartProducts->getProduct() === $this) {
                $cartProducts->setProduct(null);
            }
        }

        return $this;
    }
}
