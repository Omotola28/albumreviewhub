<?php

namespace AlbumReview\AlbumReviewBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ReviewEntry
 * @ORM\Entity
 * @ORM\Table(name="review_entry")
 */
class ReviewEntry
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $albumreviewer;

    /**
     * @var string
     */
    private $review;

    /**
     * @var \DateTime
     */
    private $timestamp;

    /**
     * @var \AlbumReview\AlbumReviewBundle\Entity\AlbumEntry
     * @ORM\ManyToOne(targetEntity="AlbumReview\AlbumReviewBundle\Entity\AlbumEntry",
    inversedBy="reviewEntries")
     * @ORM\JoinColumn(name="author", referencedColumnName="id")
     */
    private $author;

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
     * Set albumreviewer.
     *
     * @param string $albumreviewer
     *
     * @return ReviewEntry
     */
    public function setAlbumReviewer($albumreviewer)
    {
        $this->albumreviewer = $albumreviewer;

        return $this;
    }

    /**
     * Get albumreviewer.
     *
     * @return string
     */
    public function getAlbumReviewer()
    {
        return $this->albumreviewer;
    }

    /**
     * Set review.
     *
     * @param string $review
     *
     * @return ReviewEntry
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
     * Set timestamp.
     *
     * @param \DateTime $timestamp
     *
     * @return ReviewEntry
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

}
