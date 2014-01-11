<?php

namespace Renelems\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Project
 *
 * @Gedmo\Loggable()
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="Renelems\DBBundle\Entity\Repository\ProjectRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Project
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;
    
    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=255)
     */
    private $website;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="introduction", type="string", length=255)
     */
    private $introduction;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;

    /**
     * @var ArrayCollection Tag
     *
     * @ORM\ManyToMany(targetEntity="Tag")
     * @ORM\JoinTable(name="project_has_tag",
     *      joinColumns={@ORM\JoinColumn(name="project_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     * )
     */
    private $tags;
    
    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    private $locale;
    
    /**
     * @var boolean $active
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    protected $active;
    
    /**
     * @ORM\OneToMany(targetEntity="ProjectImage", mappedBy="project", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"sequence" = "asc"})
     */
    private $images;
    
    /**
     * @ORM\OneToMany(targetEntity="ProjectImage", mappedBy="project", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $logo;

    public function __construct()
    {
    	$this->images = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Project
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Project
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set introduction
     *
     * @param string $introduction
     * @return Project
     */
    public function setIntroduction($introduction)
    {
        $this->introduction = $introduction;
    
        return $this;
    }

    /**
     * Get introduction
     *
     * @return string 
     */
    public function getIntroduction()
    {
        return $this->introduction;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Project
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Project
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Project
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    
        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set tags
     *
     * @param string $tags
     * @return Project
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    
        return $this;
    }

    /**
     * Get tags
     *
     * @return string 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Project
     */
    public function setActive($active)
    {
        $this->active = $active;
    
        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set website
     *
     * @param string $website
     * @return Project
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    
        return $this;
    }

    /**
     * Get website
     *
     * @return string 
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Add images
     *
     * @param \Renelems\DBBundle\Entity\ProjectImage $images
     * @return Project
     */
    public function addImage($image)
    {
        $this->images->add($image);
    
        return $this;
    }

    /**
     * Remove images
     *
     * @param \Renelems\DBBundle\Entity\ProjectImage $images
     */
    public function removeImage(\Renelems\DBBundle\Entity\ProjectImage $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImages()
    {
    	$aReturn = array();
        foreach($this->images as $image) {
        	
        	if($image->getType() == 'overview')
        		$aReturn[] = $image;
        }
        return $aReturn;
    }
    
    /**
     * Add images
     *
     * @param \Renelems\DBBundle\Entity\ProjectImage $images
     * @return Project
     */
    public function addLogo(\Renelems\DBBundle\Entity\ProjectImage $image)
    {
    	$this->images->add($image);
    
    	return $this;
    }
    
    /**
     * Remove images
     *
     * @param \Renelems\DBBundle\Entity\ProjectImage $images
     */
    public function removeLogo(\Renelems\DBBundle\Entity\ProjectImage $images)
    {
    	$this->images->removeElement($images);
    }
    
    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLogo()
    {
    	$aReturn = array();
    	foreach ($this->images as $image) {
    		if($image->getType() == 'main')
    			$aReturn[] = $image;
    	}
    	return $aReturn;
    }

    /**
     * Add tags
     *
     * @param \Renelems\DBBundle\Entity\Tag $tags
     * @return Project
     */
    public function addTag(\Renelems\DBBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;
    
        return $this;
    }

    /**
     * Remove tags
     *
     * @param \Renelems\DBBundle\Entity\Tag $tags
     */
    public function removeTag(\Renelems\DBBundle\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Set logo
     *
     * @param string $logo
     * @return Project
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    
        return $this;
    }
    
    public function setTranslatableLocale($locale)
    {
    	$this->locale = $locale;
    }
}