<?php

namespace Tests\Feature;

use App\Models\Loan;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;


class LoanControllerTest extends TestCase
{

    /**
     * Index Method Test.
     *
     * @return void
     */
    public function testIndexTest()
    {
        $user = User::first();
        Passport::actingAs(
            $user, ['*']
        );
        $response = $this->get('/api/loans');
        $response->assertStatus(200);
    }

    /**
     * Store Method Test.
     *
     * @return void
     */
    public function testStoreTest()
    {
        $user = User::first();
        Passport::actingAs(
            $user, ['*']
        );
        $loan = factory(Loan::class)->make()->toArray();

        $response = $this->post('/api/loans', $loan);
        $response->assertStatus(200);
    }

    /**
     * Show Method Test.
     *
     * @return void
     */
    public function testShowTest()
    {
        $user = User::first();
        Passport::actingAs(
            $user, ['*']
        );
        $loan = factory(Loan::class)->create();
        $response = $this->get('/api/loans/' . $loan->id);

        $response->assertStatus(200);
    }

    /**
     * Update Method Test.
     *
     * @return void
     */
    public function testUpdateTest()
    {
        $user = User::first();
        Passport::actingAs(
            $user, ['*']
        );
        $loan = factory(Loan::class)->create();

        $response = $this->put('/api/loans/' . $loan->id, $loan->toArray());

        $response->assertStatus(200);
    }

    /**
     * Destroy Method Test.
     *
     * @return void
     */
    public function testDestroyTest()
    {
        $user = User::first();
        Passport::actingAs(
            $user, ['*']
        );
        $loan = factory(Loan::class)->create();

        $response = $this->delete('/api/loans/' . $loan->id);

        $response->assertStatus(200);
    }


}
