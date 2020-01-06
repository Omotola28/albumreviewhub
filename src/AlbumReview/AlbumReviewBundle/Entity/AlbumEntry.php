<?php

namespace AlbumReview\AlbumReviewBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AlbumEntry
 * @ORM\Entity(repositoryClass="AlbumReview\AlbumReviewBundle\Repository\AlbumEntryRepository")
 * @ORM\Table(name="album_entry")
 */
class AlbumEntry
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $artist;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @var array
     * @ORM\Column(type="string")
     */
    private $trackList;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $reviewer;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $review;

    /**
     * @var \AlbumReview\AlbumReviewBundle\Entity\User
     * @ORM\ManyToOne(targetEntity="\AlbumReview\AlbumReviewBundle\Entity\User", inversedBy="entries")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    private $author;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="\AlbumReview\AlbumReviewBundle\Entity\ReviewEntry", mappedBy="album")
     */
    private $review_entries;


    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
     private $timestamp;

    /**
     * @ Assert/NotBlank('Please upload an image')
     * @ Assert/Image()
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;


    public function __construct()
    {
        $this->review_entries = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param \DateTime $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * Set artist.
     *
     * @param string $artist
     *
     * @return AlbumEntry
     */
    public function setArtist($artist)
    {
        $this->artist = $artist;

        return $this;
    }

    /**
     * Get artist.
     *
     * @return string
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return AlbumEntry
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set trackList.
     *
     * @param array $trackList
     *
     * @return AlbumEntry
     */
    public function setTrackList($trackList)
    {
        $this->trackList = $trackList;

        return $this;
    }

    /**
     * Get trackList.
     *
     * @return array
     */
    public function getTrackList()
    {
        return $this->trackList;
    }

    /**
     * Set reviewer.
     *
     * @param string $reviewer
     *
     * @return AlbumEntry
     */
    public function setReviewer($reviewer)
    {
        $this->reviewer = $reviewer;

        return $this;
    }

    /**
     * Get reviewer.
     *
     * @return string
     */
    public function getReviewer()
    {
        return $this->reviewer;
    }

    /**
     * Set review.
     *
     * @param string $review
     *
     * @return AlbumEntry
     */
    public function setReview($review)
    {
        $this->review = $review;

        return $this;
    }

    /**
     * Get review.
     *
     * @return string
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * Set author.
     *
     * @param \AlbumReview\AlbumReviewBundle\Entity\User $author
     *
     * @return AlbumEntry
     */
    public function setAuthor(\AlbumReview\AlbumReviewBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author.
     *
     * @return \AlbumReview\AlbumReviewBundle\Entity\User|null
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getReviewEntries()
    {
        return $this->review_entries;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $review_entries
     */
    public function setReviewEntries($review_entries)
    {
        $this->review_entries = $review_entries;
    }

    /**
     * Add reviewEntry.
     *
     * @param \AlbumReview\AlbumReviewBundle\Entity\ReviewEntry $reviewEntry
     *
     * @return AlbumEntry
     */
    public function addReviewEntry(\AlbumReview\AlbumReviewBundle\Entity\ReviewEntry $reviewEntry)
    {
        $this->review_entries[] = $reviewEntry;

        return $this;
    }

    /**
     * Remove reviewEntry.
     *
     * @param \AlbumReview\AlbumReviewBundle\Entity\ReviewEntry $reviewEntry
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeReviewEntry(\AlbumReview\AlbumReviewBundle\Entity\ReviewEntry $reviewEntry)
    {
        return $this->review_entries->removeElement($reviewEntry);
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }
}
