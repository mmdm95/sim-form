<?php

namespace Sim\Form\Abstracts;

use Sim\Form\ExpandableElement;
use Sim\Form\FormElements\Wrapper;
use Sim\Form\Interfaces\IFormElement;

abstract class AbstractFormElement implements IFormElement
{
    /**
     * @var string $identifier
     */
    protected $identifier = '';

    /**
     * @var string |null $tag
     */
    protected $tag = null;

    /**
     * @var array $attributes
     */
    protected $attributes = [];

    /**
     * @var ExpandableElement|null $error_element
     */
    protected $error_element = null;

    /**
     * @var AbstractFieldComposite|null $parent_element
     */
    protected $parent_element = null;

    /**
     * @var bool $has_error
     */
    protected $has_error = false;

    /**
     * @var bool $is_csrf_token
     */
    protected $is_csrf_token = false;

    /**
     * @var bool $is_captcha
     */
    protected $is_captcha = false;

    /**
     * AbstractFormElement constructor.
     * @param string|null $tag_name
     * @param string|null $name
     */
    public function __construct(?string $tag_name = null, ?string $name = null)
    {
        $this->setTagName($tag_name);
        $this->setName($name);
        try {
            $this->identifier = $this->createdUniqueIdentifier();
        } catch (\Exception $e) {
            // do nothing for now
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setName(?string $name)
    {
        if (!is_null($name)) {
            $this->attributes['name'] = $name;
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->getFromAttribute('name');
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * {@inheritdoc}
     */
    public function setTagName(?string $tag_name)
    {
        if (!empty($tag_name)) {
            $this->tag = mb_strtolower($tag_name);
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTagName(): string
    {
        return $this->tag;
    }

    /**
     * {@inheritdoc}
     */
    public function setValue(?string $value = '')
    {
        $name = $this->getAttribute('name');
        if (is_null($value)) $value = '';
        if (!empty($name)) {
            $type = $this->getAttribute('type');
            if (!empty($type) && $type != 'password' && !$this->is_csrf_token && !$this->is_captcha) {
                $this->setAttribute('value', $value);
            }
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setParent(?AbstractFieldComposite $parent)
    {
        $this->parent_element = $parent;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return $this->parent_element;
    }

    /**
     * {@inheritdoc}
     */
    public function errorElement(): AbstractFieldComposite
    {
        if (!($this->error_element instanceof AbstractFieldComposite)) {
            $this->error_element = new Wrapper('div');
        }
        return $this->error_element;
    }

    /**
     * {@inheritdoc}
     */
    public function haveError(bool $answer)
    {
        $this->has_error = $answer;
        return $this;
    }

    /**
     * Set a value to an attribute
     *
     * @param string $attribute
     * @param string $value
     * @return static
     */
    public function setAttribute(string $attribute, string $value)
    {
        $attribute = mb_strtolower($attribute);
        $this->attributes[$attribute] = $value;
        return $this;
    }

    /**
     * Get value of an attribute
     *
     * @param string $attribute
     * @return string
     */
    public function getAttribute(string $attribute): string
    {
        return $this->getFromAttribute($attribute);
    }

    /**
     * {@inheritdoc}
     */
    public function setAttributes(array $attributes)
    {
        foreach ($attributes as $name => $val) {
            if (is_string($val)) {
                $this->attributes[mb_strtolower($name)] = $val;
            }
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Return attributes in string format
     *
     * @return string
     */
    protected function attributesToString(): string
    {
        $attributes = $this->getAttributes();
        $newAttr = '';

//        if (is_array($attributes)) {
//            unset($attributes['type']);
        foreach ($attributes as $attr => $value) {
            $newAttr .= "{$attr}=\"{$value}\" ";
        }
//        }
//        else if (is_string($attributes)) {
//            // Remove any kind of type in attribute string
//            $newAttr .= '#';
//            $newAttr = preg_replace('/([^a-z0-9-_]\S*type[^a-z0-9-_]\s*)=*\s*[\"*\'*](.*?)[\"*\'*]\S*/i', '', $newAttr);
//        }
        $newAttr = trim($newAttr);

        return $newAttr;
    }

    /**
     * Get any value from attributes with name of attribute
     *
     * @param $attributeName
     * @return mixed|string
     */
    protected function getFromAttribute($attributeName): string
    {
        if (!is_string($attributeName) || empty($attributeName)) return '';

        $attributes = $this->getAttributes();
//        $value = '';
//        if (is_array($attributes)) {
        $value = $attributes[$attributeName] ?? '';
//        }
//        else if (is_string($attributes)) {
//            // find name value in attribute string
//            preg_match('/[^a-z0-9-_]\S*' . preg_quote($attributeName, '/') . '[^a-z0-9-_]\s*=*[\"\'](.*?)[\"\']/i', $attributes, $matches);
//            $value = $matches[1] ?? '';
//        }
        $value = trim($value);

        return $value;
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function createdUniqueIdentifier(): string
    {
        $identifier = '';
        $token = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_-';
        for ($i = 0; $i < 4; $i++) {
            $identifier .= uniqid(rand(0, strlen($token)), true);
        }
        return base64_encode(str_shuffle($identifier));
    }
}