<?php

namespace Tests\Browser\Pages;

use Illuminate\Database\Eloquent\Model;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class RfcPage extends Page
{
    public function __construct(
        readonly private Model $rfc
    )
    {
    }

    public function url(): string
    {
        return "/rfc/" . $this->rfc->slug;
    }

    public function assert(Browser $browser): void
    {
        $browser->assertPathIs($this->url());
    }
}
