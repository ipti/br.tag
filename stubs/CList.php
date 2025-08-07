<?php

/**
 * Yii 1.1 stub - CList
 * https://www.yiiframework.com/doc/api/1.1/CList.
 */
class CList extends CComponent implements IteratorAggregate, ArrayAccess, Countable
{
    /**
     * @var array the data to be stored in the list
     */
    protected $_d = [];

    /**
     * @var bool whether the list is read-only
     */
    protected $_r = false;

    /**
     * Constructor.
     * @param array $data initial data
     * @param bool $readOnly whether the list is read-only
     */
    public function __construct($data = [], $readOnly = false)
    {
        $this->_d = $data;
        $this->_r = $readOnly;
    }

    /**
     * @return bool whether this list is read-only
     */
    public function getReadOnly()
    {
        return $this->_r;
    }

    /**
     * Returns the number of items in the list.
     * @return int
     */
    public function getCount()
    {
        return count($this->_d);
    }

    /**
     * Returns the iterator for traversing the items.
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->_d);
    }

    /**
     * Returns whether there is an item at the specified offset.
     * @param int $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->_d[$offset]);
    }

    /**
     * Returns the item at the specified offset.
     * @param int $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->_d[$offset];
    }

    /**
     * Sets the item at the specified offset.
     * @param int $offset
     * @param mixed $item
     */
    public function offsetSet($offset, $item)
    {
        if ($this->_r) {
            throw new Exception('The list is read-only.');
        }
        if ($offset === null) {
            $this->_d[] = $item;
        } else {
            $this->_d[$offset] = $item;
        }
    }

    /**
     * Unsets the item at the specified offset.
     * @param int $offset
     */
    public function offsetUnset($offset)
    {
        if ($this->_r) {
            throw new Exception('The list is read-only.');
        }
        unset($this->_d[$offset]);
    }

    /**
     * Returns the number of items in the list (Countable).
     * @return int
     */
    public function count()
    {
        return count($this->_d);
    }

    /**
     * Adds an item into the list.
     * @param mixed $item
     */
    public function add($item)
    {
        $this->offsetSet(null, $item);
    }

    /**
     * Removes an item from the list by index.
     * @param int $index
     * @return mixed the removed item
     */
    public function removeAt($index)
    {
        if (isset($this->_d[$index])) {
            $item = $this->_d[$index];
            $this->offsetUnset($index);

            return $item;
        }

        return null;
    }

    /**
     * Removes all items in the list.
     */
    public function clear()
    {
        if ($this->_r) {
            throw new Exception('The list is read-only.');
        }
        $this->_d = [];
    }

    /**
     * Returns the item at the specified index.
     * @param int $index
     * @return mixed
     */
    public function itemAt($index)
    {
        return $this->_d[$index] ?? null;
    }

    /**
     * @param mixed $item
     * @return int the index of the item, or -1 if not found
     */
    public function indexOf($item)
    {
        return array_search($item, $this->_d, true);
    }

    /**
     * @param mixed $item
     * @return bool whether the list contains the item
     */
    public function contains($item)
    {
        return in_array($item, $this->_d, true);
    }

    /**
     * Copies iterable data into the list, replacing existing items.
     * @param iterable $data
     */
    public function copyFrom($data)
    {
        if ($this->_r) {
            throw new Exception('The list is read-only.');
        }
        $this->_d = is_array($data) ? $data : iterator_to_array($data);
    }

    /**
     * Merges iterable data into the list, keeping existing items.
     * @param iterable $data
     */
    public function mergeWith($data)
    {
        if ($this->_r) {
            throw new Exception('The list is read-only.');
        }
        $this->_d = array_merge($this->_d, is_array($data) ? $data : iterator_to_array($data));
    }

    /**
     * Returns the list data as an array.
     * @return array
     */
    public function toArray()
    {
        return $this->_d;
    }
}
