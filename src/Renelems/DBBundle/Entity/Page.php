<?php 
namespace Renelems\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Gedmo\Loggable()
 * @ORM\Table(name="page")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class Page
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(name="title", type="string", length=255)
     */
    public $title;
    
    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;
    
    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    private $locale;
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(name="introduction", type="text")
     */
    public $introduction;
    
    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="content", type="text")
     */
    private $content;
    
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
     * @var boolean $isOnHomepage
     *
     * @ORM\Column(name="is_on_homepage", type="boolean", nullable=false)
     */
    protected $isOnHomepage;
    
    /**
     * @var boolean $active
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    protected $active;

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
     * @return Page
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
     * @return Page
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
     * Set content
     *
     * @param string $content
     * @return Page
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Page
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
     * @return Page
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

    public function setTranslatableLocale($locale)
    {
    	$this->locale = $locale;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Page
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
     * Set introduction
     *
     * @param string $introduction
     * @return Page
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
     * Set isOnHomepage
     *
     * @param boolean $isOnHomepage
     * @return Page
     */
    public function setIsOnHomepage($isOnHomepage)
    {
        $this->isOnHomepage = $isOnHomepage;
    
        return $this;
    }

    /**
     * Get isOnHomepage
     *
     * @return boolean 
     */
    public function getIsOnHomepage()
    {
        return $this->isOnHomepage;
    }
}