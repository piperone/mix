<?php

namespace TorMorten\Mix;

use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\HtmlString;
use TorMorten\Mix\Traits\ResolveCdn;
use TorMorten\Mix\Traits\ResolveHmr;
use TorMorten\Mix\Traits\ResolveLocal;

class Mix
{
    public function __construct($home = null)
    {
        if (!$home) {
            $home = base_path();
        }

        $this->home = $home;
    }

    public function handle($filename, $package)
    {
        return resolve(Pipeline::class)
            ->send(compact('filename', 'package'))
            ->through([
                ResolveHmr::class,
                ResolveLocal::class,
                ResolveCdn::class,
            ])
            ->then(fn($params) => new HtmlString(is_string($params) ? $params : ''));
    }
}