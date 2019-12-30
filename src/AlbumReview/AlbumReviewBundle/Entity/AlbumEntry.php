<?php

namespace AlbumReview\AlbumReviewBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * AlbumEntry
 * @ORM\Entity
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
     * @var \AlbumReview\AlbumReviewBundle\Entity\ReviewEntry
     * @ORM\ManyToOne(targetEntity="\AlbumReview\AlbumReviewBundle\Entity\ReviewEntry", inversedBy="entries")
     * @ORM\JoinColumn(name="author", referencedColumnName="id")
     */
    private $author;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="\AlbumReview\AlbumReviewBundle\Entity\ReviewEntry", mappedBy="author")
     */
    protected $reviewEntries;

    /**
     * @var \DateTime
     */
    private $timestamp;

    public function __construct()
    {
        parent::__construct();
        $this->reviewEntries = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param \AlbumReview\AlbumReviewBundle\Entity\User|null $author
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
     * Set timestamp.
     *
     * @param \DateTime $timestamp
     *
     * @return AlbumEntry
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp.
     *
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Add entry.
     *
     * @param \AlbumReview\AlbumReviewBundle\Entity\ReviewEntry $entry
     *
     * @return AlbumEntry
     */
    public function addReviewEntry(\AlbumReview\AlbumReviewBundle\Entity\ReviewEntry $entry)
    {
        $this->reviewEntries[] = $entry;

        return $this;
    }

    /**
     * Remove entry.
     *
     * @param \AlbumReview\AlbumReviewBundle\Entity\ReviewEntry $entry
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeReviewEntry(\AlbumReview\AlbumReviewBundle\Entity\ReviewEntry $entry)
    {
        return $this->reviewEntries->removeElement($entry);
    }

    /**
     * Get entries.
     *
     * @return Collection
     */
    public function getReviewEntries()
    {
        return $this->reviewEntries;
    }
}
