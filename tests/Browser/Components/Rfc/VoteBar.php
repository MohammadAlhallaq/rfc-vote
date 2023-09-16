<?php

namespace Tests\Browser\Components\Rfc;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component;

class VoteBar extends Component
{
    public function selector(): string
    {
        return '@vote-bar';
    }

    public function assert(Browser $browser): void
    {
        $browser->assertVisible($this->selector());
    }
}
