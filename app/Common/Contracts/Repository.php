<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/30
 * Time: 15:35
 */
namespace App\Common\Contracts ;

use Illuminate\Database\Eloquent\Model;

abstract class Repository {


    protected $model;
    /**
     * __construct.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * 数据插入
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function insert($data)
    {
        return $this->newQuery()->insert($data);
    }
    /**
     * Find a model by its primary key.
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function find($id, $columns = ['*'])
    {
        return $this->newQuery()->find($id, $columns);
    }
    /**
     * Find multiple models by their primary keys.
     *
     * @param  \Illuminate\Contracts\Support\Arrayable|array  $ids
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findMany($ids, $columns = ['*'])
    {
        return $this->newQuery()->findMany($ids, $columns);
    }
    /**
     * Find a model by its primary key or throw an exception.
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail($id, $columns = ['*'])
    {
        return $this->newQuery()->findOrFail($id, $columns);
    }
    /**
     * Find a model by its primary key or return fresh model instance.
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOrNew($id, $columns = ['*'])
    {
        return $this->newQuery()->findOrNew($id, $columns);
    }
    /**
     * Get the first record matching the attributes or instantiate it.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function firstOrNew(array $attributes, array $values = [])
    {
        return $this->newQuery()->firstOrNew($attributes, $values);
    }
    /**
     * Get the first record matching the attributes or create it.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function firstOrCreate(array $attributes, array $values = [])
    {
        return $this->newQuery()->firstOrCreate($attributes, $values);
    }
    /**
     * Create or update a record matching the attributes, and fill it with values.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updateOrCreate(array $attributes, array $values = [])
    {
        return $this->newQuery()->updateOrCreate($attributes, $values);
    }
    /**
     * Execute the query and get the first result or throw an exception.
     *
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|static
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function firstOrFail($columns = ['*'])
    {
        return $this->newQuery()->firstOrFail($columns);
    }
    /**
     * Mass 创建用户.
     *
     * @param  array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Throwable
     */
    public function create($attributes)
    {
        return tap($this->newInstance(), function ($instance) use ($attributes) {
            $instance->fill($attributes)->saveOrFail();
        });
    }
    /**
     * Save a new model and return the instance.
     *
     * @param  array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Throwable
     */
    public function forceCreate($attributes)
    {
        return tap($this->newInstance(), function ($instance) use ($attributes) {
            $instance->forceFill($attributes)->saveOrFail();
        });
    }
    /**
     * update.
     *
     * @param  array $attributes
     * @param  mixed $id
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Throwable
     */
    public function update($id, $attributes)
    {
        return tap($this->findOrFail($id), function ($instance) use ($attributes) {
            $instance->fill($attributes)->saveOrFail();
        });
    }
    /**
     * forceCreate.
     *
     * @param  array $attributes
     * @param  mixed $id
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Throwable
     */
    public function forceUpdate($id, $attributes)
    {
        return tap($this->findOrFail($id), function ($instance) use ($attributes) {
            $instance->forceFill($attributes)->saveOrFail();
        });
    }

    /**
     * delete.
     *
     * @param  mixed $id
     * @return bool|null
     */
    public function delete($id)
    {
        return $this->find($id)->delete();
    }
    /**
     * Restore a soft-deleted model instance.
     *
     * @param  mixed $id
     * @return bool|null
     */
    public function restore($id)
    {
        return $this->newQuery()->restore($id);
    }
    /**
     * Force a hard delete on a soft deleted model.
     *
     * This method protects developers from running forceDelete when trait is missing.
     *
     * @param  mixed $id
     * @return bool|null
     */
    public function forceDelete($id)
    {
        return $this->findOrFail($id)->forceDelete();
    }
    /**
     * Create a new model instance that is existing.
     *
     * @param  array  $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function newInstance($attributes = [], $exists = false)
    {
        return $this->getModel()->newInstance($attributes, $exists);
    }



    public function get($criteria = [], $columns = ['*'])
    {
        return $this->matching($criteria)->get($columns);
    }



    public function chunk($criteria, $count, callable $callback)
    {
        return $this->matching($criteria)->chunk($count, $callback);
    }


    public function each($criteria, callable $callback, $count = 1000)
    {
        return $this->matching($criteria)->each($callback, $count);
    }


    public function first($criteria = [], $columns = ['*'])
    {
        return $this->matching($criteria)->first($columns);
    }


    public function paginate($criteria = [], $perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        return $this->matching($criteria)->paginate($perPage, $columns, $pageName, $page);
    }


    public function simplePaginate($criteria = [], $perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        return $this->matching($criteria)->simplePaginate($perPage, $columns, $pageName, $page);
    }


    public function count($criteria = [], $columns = '*')
    {
        return (int) $this->matching($criteria)->count($columns);
    }


    public function min($criteria, $column)
    {
        return $this->matching($criteria)->min($column);
    }


    public function max($criteria, $column)
    {
        return $this->matching($criteria)->max($column);
    }


    public function sum($criteria, $column)
    {
        $result = $this->matching($criteria)->sum($column);
        return $result ?: 0;
    }


    public function avg($criteria, $column)
    {
        return $this->matching($criteria)->avg($column);
    }


    public function average($criteria, $column)
    {
        return $this->avg($criteria, $column);
    }


    public function matching($criteria)
    {
        $criteria = is_array($criteria) === false ? [$criteria] : $criteria;
        return array_reduce($criteria, function ($query, $criteria) {
            $criteria->each(function ($method) use ($query) {
                call_user_func_array([$query, $method->name], $method->parameters);
            });
            return $query;
        }, $this->newQuery());
    }


    public function getQuery($criteria = [])
    {
        return $this->matching($criteria)->getQuery();
    }


    public function getModel()
    {
        return $this->model instanceof Model
            ? clone $this->model
            : $this->model->getModel();
    }


    public function newQuery()
    {
        return $this->model instanceof Model
            ? $this->model->newQuery()
            : clone $this->model;
    }

    public function __toString()
    {
        return get_called_class();
    }


}