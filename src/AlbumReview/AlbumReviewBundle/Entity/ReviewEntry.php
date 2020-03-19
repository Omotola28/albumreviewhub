<?php

namespace AlbumReview\AlbumReviewBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;


/**
 * @ORM\Table(name="review_entry")
 * @ORM\Entity(repositoryClass="AlbumReview\AlbumReviewBundle\Repository\ReviewEntryRepository")
 * @JMS\ExclusionPolicy("all")
 */
class ReviewEntry
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @JMS\Expose()
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @JMS\Expose()
     */
    private $albumreviewer;

    /**
     * @var string
     * @ORM\Column(type="text")
     * @JMS\Expose()
     */
    private $review;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     * @JMS\Expose()
     */
    private $timestamp;

    /**
     * @var \AlbumReview\AlbumReviewBundle\Entity\AlbumEntry
     * @ORM\ManyToOne(targetEntity="AlbumReview\AlbumReviewBundle\Entity\AlbumEntry", inversedBy="review_entries")
     * @ORM\JoinColumn(name="album_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @JMS\Exclude()
     */
    private $album;

    /**
     * @var \AlbumReview\AlbumReviewBundle\Entity\User
     * @ORM\ManyToOne(targetEntity="AlbumReview\AlbumReviewBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @JMS\Exclude()
     */
    private $user;

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return AlbumEntry
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * @param AlbumEntry $album
     */
    public function setAlbum($album)
    {
        $this->album = $album;
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
