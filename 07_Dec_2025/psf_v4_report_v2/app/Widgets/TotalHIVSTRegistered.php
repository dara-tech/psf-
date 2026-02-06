<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

class TotalHIVSTRegistered extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        //

        return view('widgets.total_h_i_v_s_t_registered', [
            'config' => $this->config,
        ]);
    }
}
