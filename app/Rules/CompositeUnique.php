<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class CompositeUnique implements ValidationRule
{
    protected string $table;
    protected array $attributes;
    protected $ignoreId = null;

    /**
     * Create a new rule instance.
     *
     * @param  string  $table
     * @param  array  $attributes
     */
    public function __construct(string $table, array $attributes, $ignoreId = null)
    {
        $this->table = $table;
        $this->attributes = $attributes;
        $this->ignoreId = $ignoreId;

        // dd($this->attributes);
    }

    /**
     * Validate the attribute.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): void  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        // Include the attribute being validated in the attributes array
        $this->attributes[$attribute] = $value;


        // Query to check if a record exists with these attributes (name and academic_year_id)
        $query = DB::table($this->table)->where($this->attributes);

        if ($this->ignoreId) {
            $query->where('id', '<>', $this->ignoreId);
        }
        $exists = $query->exists();

        // dd($this->attributes,$exists);
        if ($exists) {
            $fail('The name Already Taken!!');
        }
    }
}
