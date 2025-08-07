<?php

/**
 * Yii 1.1 stub - CDbCriteria
 * https://www.yiiframework.com/doc/api/1.1/CDbCriteria.
 */
class CDbCriteria
{
    /** @var string */
    public $select = '*';

    /** @var bool */
    public $distinct;

    /** @var string */
    public $condition;

    /** @var array */
    public $params = [];

    /** @var string */
    public $limit;

    /** @var string */
    public $offset;

    /** @var string */
    public $order;

    /** @var string */
    public $group;

    /** @var string */
    public $join;

    /** @var string */
    public $having;

    /** @var mixed */
    public $with;

    /** @var mixed */
    public $alias;

    /** @var bool */
    public $together;

    /** @var array */
    public $scopes;

    /** @var mixed */
    public $index;

    /** @var array */
    public $conditionMap = [];

    /** @var string */
    public $compareOperator;

    /**
     * Merges this criteria with another.
     * @param CDbCriteria $criteria
     */
    public function mergeWith($criteria, $useAnd = true)
    {
    }

    /**
     * Adds a condition to the existing `condition` with "AND".
     * @param string $condition
     * @param string $operator
     */
    public function addCondition($condition, $operator = 'AND')
    {
    }

    /**
     * Adds an IN condition.
     * @param string $column
     * @param array $values
     * @param string $operator
     */
    public function addInCondition($column, $values, $operator = 'AND')
    {
    }

    /**
     * Adds a NOT IN condition.
     * @param string $column
     * @param array $values
     * @param string $operator
     */
    public function addNotInCondition($column, $values, $operator = 'AND')
    {
    }

    /**
     * Adds a search condition (LIKE).
     * @param string $column
     * @param string $keyword
     * @param bool $escape
     * @param string $operator
     */
    public function addSearchCondition($column, $keyword, $escape = true, $operator = 'AND')
    {
    }

    /**
     * Adds a BETWEEN condition.
     * @param string $column
     * @param mixed $valueStart
     * @param mixed $valueEnd
     * @param string $operator
     */
    public function addBetweenCondition($column, $valueStart, $valueEnd, $operator = 'AND')
    {
    }

    /**
     * Adds a column to the select clause.
     * @param string $column
     */
    public function addSelect($column)
    {
    }
}
