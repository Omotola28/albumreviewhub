<?php

namespace AlbumReview\AlbumReviewBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 * @ORM\Entity
 * @ORM\Table(name="`users`")
 */
class User extends BaseUser
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AlbumReview\AlbumReviewBundle\Entity\AlbumEntry", mappedBy="author")
     */
    protected $entries;


    public function __construct()
    {
        parent::__construct();
        $this->entries = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Add entry.
     *
     * @param \AlbumReview\AlbumReviewBundle\Entity\AlbumEntry $entry
     *
     * @return User
     */
    public function addEntry(\AlbumReview\AlbumReviewBundle\Entity\AlbumEntry $entry)
    {
        $this->entries[] = $entry;

        return $this;
    }

    /**
     * Remove entry.
     *
     * @param \AlbumReview\AlbumReviewBundle\Entity\AlbumEntry $entry
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeEntry(\AlbumReview\AlbumReviewBundle\Entity\AlbumEntry $entry)
    {
        return $this->entries->removeElement($entry);
    }

    /**
     * Get entries.
     *
     * @return Collection
     */
    public function getEntries()
    {
        return $this->entries;
    }
}
