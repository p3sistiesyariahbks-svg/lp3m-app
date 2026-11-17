<?php

namespace Database\Factories;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Payment $payment): void {
            $payment->addMediaFromUrl('https://picsum.photos/200/300')
                ->toMediaCollection('payment-proof', 'local');
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'registration_id' => Registration::factory(),
            'amount' => function (array $attributes) {
                return Registration::find($attributes['registration_id'])->period->fee;
            },
            'status' => fake()->randomElement(PaymentStatus::class),
            'verified_by' => null,
            'verified_at' => null,
            'note' => null,
        ];
    }

    /**
     * Indicate that the payment is pending.
     */
    public function pending(): Factory
    {
        return $this->state(fn (array $attributes): array => [
            'status' => PaymentStatus::Pending,
            'verified_by' => null,
            'verified_at' => null,
            'note' => null,
        ]);
    }

    /**
     * Indicate that the payment is verified.
     */
    public function verified(): Factory
    {
        return $this->state(fn (array $attributes): array => [
            'status' => PaymentStatus::Verified,
            'verified_by' => User::factory(),
            'verified_at' => now(),
            'note' => 'Payment proof has been verified.',
        ]);
    }

    /**
     * Indicate that the payment is rejected.
     */
    public function rejected(): Factory
    {
        return $this->state(fn (array $attributes): array => [
            'status' => PaymentStatus::Rejected,
            'verified_by' => User::factory(),
            'verified_at' => now(),
            'note' => 'Payment proof is unclear.',
        ]);
    }
}
