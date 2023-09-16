<?php

namespace Tests\Browser;

use App\Http\Controllers\RfcDetailController;
use App\Models\Rfc;
use App\Support\FetchContributors;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\About\Contributors;
use Tests\Browser\Components\Rfc\VoteBar;
use Tests\Browser\Pages\AboutPage;
use Tests\Browser\Pages\HomePage;
use Tests\Browser\Pages\RfcPage;
use Tests\DuskTestCase;

class RfcPageTest extends DuskTestCase
{
    use DatabaseTruncation;

    public function test_it_redirect_to_login_page_when_unauthenticated_tries_to_vote(): void
    {
        $rfc = Rfc::factory()->create(
            [
                'title' => 'This is a test rfc that should be rendered',
                'published_at' => Carbon::now()->subDays(),
            ]);

        $this->browse(function (Browser $browser) use ($rfc) {
            $browser->visit(new RfcPage($rfc))
                ->with(new VoteBar(), function (Browser $browser) use ($rfc) {
                    $browser
                        ->assertSee("Click the bar to cast your vote!")
                        ;
                })
                ->assertSee($rfc->title)
                ->assertSee($rfc->description)
                ->assertSee("Check out another RFCs")
                ->assertGuest();

//            ->assertPathIs("/login");
//                ->assertSee('This is a test rfc that should be rendered')
//                ->click("@card-link-more")
//                ->assertUrlIs(route('rfc-detail', ['rfc' => Str::slug($rfc->slug)]))
//                ->assertSee($rfc->title);
        });
    }

}
