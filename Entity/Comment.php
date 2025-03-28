<?php

namespace Entity;

class Comment
{
    private $_id;
    private $_targetId;
    private $_targetType;
    private $_username;
    private $_content;

    /**
     * @param $id
     * @param $targetId
     * @param $userId
     * @param $content
     */
    public function __construct($id, $targetId, $targetType, $username, $content)
    {
        $this->_id = $id;
        $this->_targetId = $targetId;
        $this->_targetType = $targetType;
        $this->_username = $username;
        $this->_content = $content;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->_id = $id;
    }

    /**
     * @return mixed
     */
    public function getTargetId()
    {
        return $this->_targetId;
    }

    /**
     * @param mixed $targetId
     */
    public function setTargetId($targetId): void
    {
        $this->_targetId = $targetId;
    }

    public function getTargetType()
    {
        return $this->_targetType;
    }

    public function setTargetType($targetType): void
    {
        $this->_targetType = $targetType;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->_username;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId): void
    {
        $this->_username = $userId;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->_content = $content;
    }
}
