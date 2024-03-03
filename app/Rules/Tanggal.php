<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class Tanggal implements Rule
{
    protected $tanggalBerangkat, $tanggalPulang;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($tanggalBerangkat, $tanggalPulang)
    {
        $this->tanggalBerangkat = $tanggalBerangkat;
        $this->tanggalPulang = $tanggalPulang;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (Carbon::parse($this->tanggalPulang)->diffInDays(Carbon::parse($this->tanggalBerangkat)) >= 1) {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Tanggal tidak valid';
    }
}
