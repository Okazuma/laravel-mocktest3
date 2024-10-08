<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Purchase;
use App\Models\Condition;


class UpdateAddressTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\ConditionSeeder::class);
    }

    // 配送先の変更ができるか確認するテスト
    public function test_update_shipping_address()
    {
        $condition = Condition::create(['id' => 1, 'name' => '新品']);
        $user = User::factory()->create();
        $item = Item::create([
            'user_id' => $user->id,
            'name' => 'Test Item',
            'price' => 1000,
            'item_image' => 'path/to/image.jpg',
            'description' => 'Test description',
            'condition_id' => $condition->id,
        ]);

        $this->actingAs($user);

        $updatedAddress = [
            'item_id' => $item->id,
            'postal_code' => '987-6543',
            'address' => 'New Address',
            'building' => 'New Building',
        ];
        $response = $this->post('/update-address', $updatedAddress);

        $purchase = Purchase::where('user_id', $user->id)
                            ->where('item_id', $item->id)
                            ->first();
        $this->assertNotNull($purchase);
        $this->assertEquals($updatedAddress['postal_code'], $purchase->postal_code);
        $this->assertEquals($updatedAddress['address'], $purchase->address);
        $this->assertEquals($updatedAddress['building'], $purchase->building);
        $this->assertEquals($updatedAddress['postal_code'], session('postal_code'));
        $this->assertEquals($updatedAddress['address'], session('address'));
        $this->assertEquals($updatedAddress['building'], session('building'));
    }
}
