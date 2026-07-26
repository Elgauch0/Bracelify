<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

  

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $discount = 0;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;




    /**
     * @var Collection<int, OrderItem>
     */
    #[ORM\OneToMany(targetEntity: OrderItem::class, mappedBy: 'product')]
    private Collection $orderItems;

    /**
     * @var Collection<int, ProductImage>
     */
    #[ORM\OneToMany(targetEntity: ProductImage::class, mappedBy: 'product',cascade: ['persist','remove'], orphanRemoval: true)]
    private Collection $productImages;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $Quantity = 1;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'product', orphanRemoval: true)]
    private Collection $comments;

    /**
     * @var Collection<int, ProductCollection>
     */
    #[ORM\ManyToMany(targetEntity: ProductCollection::class, mappedBy: 'product')]
    private Collection $productCollections;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
        $this->productImages = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->productCollections = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    public function setDiscount(int $discount): static
    {
        $this->discount = $discount;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): static
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems->add($orderItem);
            $orderItem->setProduct($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): static
    {
        if ($this->orderItems->removeElement($orderItem)) {
            // set the owning side to null (unless already changed)
            if ($orderItem->getProduct() === $this) {
                $orderItem->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProductImage>
     */
    public function getProductImages(): Collection
    {
        return $this->productImages;
    }

    public function addProductImage(ProductImage $productImage): static
    {
        if (!$this->productImages->contains($productImage)) {
            $this->productImages->add($productImage);
            $productImage->setProduct($this);
        }

        return $this;
    }

    public function removeProductImage(ProductImage $productImage): static
    {
        if ($this->productImages->removeElement($productImage)) {
            // set the owning side to null (unless already changed)
            if ($productImage->getProduct() === $this) {
                $productImage->setProduct(null);
            }
        }

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->Quantity;
    }

    public function setQuantity(int $Quantity): static
    {
        $this->Quantity = $Quantity;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setProduct($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getProduct() === $this) {
                $comment->setProduct(null);
            }
        }

        return $this;
    }



    public function getFinalPrice(): ?int
    {
        if ($this->price === null) {
            return null;
        }

        
        return max(0, $this->price - $this->discount);
    }

   public function consume(int $number): int
    {
    if ($this->Quantity < $number) {
        // On calcule combien il en manque avant de remettre le stock à 0
        $missing = $number - $this->Quantity;
        $this->Quantity = 0;
        
        return $missing; // On retourne le déficit pour l'admin
    }

    $this->Quantity -= $number;
    return 0; // Tout est OK
    }


    #[ORM\PrePersist]
    public function setInitialQuantity(): void
    {
        if ($this->Quantity === null) {
            $this->Quantity = 0; // Ou une autre valeur par défaut
        }
    }

    /**
     * @return Collection<int, ProductCollection>
     */
    public function getProductCollections(): Collection
    {
        return $this->productCollections;
    }

    public function addProductCollection(ProductCollection $productCollection): static
    {
        if (!$this->productCollections->contains($productCollection)) {
            $this->productCollections->add($productCollection);
            $productCollection->addProduct($this);
        }

        return $this;
    }

    public function removeProductCollection(ProductCollection $productCollection): static
    {
        if ($this->productCollections->removeElement($productCollection)) {
            $productCollection->removeProduct($this);
        }

        return $this;
    }
}
