<?php

namespace Database\Factories;

use App\Enums\RegistrationStatus;
use App\Models\Period;
use App\Models\Placement;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Registration>
 */
class RegistrationFactory extends Factory
{
    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Registration $registration) {
            $registration->placement?->increment('filled');
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
            'user_id' => User::factory(),
            'placement_id' => Placement::factory(),
            'period_id' => function (array $attributes): string {
                return Placement::find($attributes['placement_id'])->period_id;
            },
            'status' => fake()->randomElement(RegistrationStatus::class),
            'amount' => null,
            'submitted_at' => null,
            'note' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
        ];
    }

    /**
     * Indicate that the registration is a draft.
     */
    public function draft(): Factory
    {
        return $this->state(fn (array $attributes): array => [
            'status' => RegistrationStatus::Draft,
            'amount' => null,
            'submitted_at' => null,
            'note' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
        ]);
    }

    /**
     * Indicate that the registration is submitted.
     */
    public function submitted(): Factory
    {
        return $this->state(fn (array $attributes): array => [
            'status' => RegistrationStatus::Submitted,
            'amount' => function (array $attributes): int {
                return Period::find($attributes['period_id'])->fee;
            },
            'submitted_at' => now(),
            'note' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
        ])->afterCreating(function (Registration $registration) {
            $registration->addMediaFromUrl('https://picsum.photos/200/300')
                ->toMediaCollection('computer-certificate', 'local');
            $registration->addMediaFromUrl('https://picsum.photos/200/300')
                ->toMediaCollection('skpi-certificate', 'local');
            $registration->addMediaFromUrl('https://picsum.photos/200/300')
                ->toMediaCollection('parental-consent-letter', 'local');
            $registration->addMediaFromUrl('https://picsum.photos/200/300')
                ->toMediaCollection('id-card', 'local');
            $registration->addMediaFromUrl('https://picsum.photos/200/300')
                ->toMediaCollection('receipt', 'local');
        });
    }

    /**
     * Indicate that the registration is approved.
     */
    public function approved(): Factory
    {
        return $this->state(fn (array $attributes): array => [
            'status' => RegistrationStatus::Approved,
            'amount' => function (array $attributes): int {
                return Period::find($attributes['period_id'])->fee;
            },
            'submitted_at' => now(),
            'note' => null,
            'reviewed_by' => User::factory(),
            'reviewed_at' => now(),
        ])->afterCreating(function (Registration $registration) {
            $registration->addMediaFromUrl('https://picsum.photos/200/300')
                ->toMediaCollection('computer-certificate', 'local');
            $registration->addMediaFromUrl('https://picsum.photos/200/300')
                ->toMediaCollection('skpi-certificate', 'local');
            $registration->addMediaFromUrl('https://picsum.photos/200/300')
                ->toMediaCollection('parental-consent-letter', 'local');
            $registration->addMediaFromUrl('https://picsum.photos/200/300')
                ->toMediaCollection('id-card', 'local');
            $registration->addMediaFromUrl('https://picsum.photos/200/300')
                ->toMediaCollection('receipt', 'local');
        });
    }

    /**
     * Indicate that the registration is rejected.
     */
    public function rejected(): Factory
    {
        return $this->state(fn (array $attributes): array => [
            'status' => RegistrationStatus::Rejected,
            'amount' => function (array $attributes): int {
                return Period::find($attributes['period_id'])->fee;
            },
            'submitted_at' => now(),
            'note' => 'Registration documents are incomplete.',
            'reviewed_by' => User::factory(),
            'reviewed_at' => now(),
        ])->afterCreating(function (Registration $registration) {
            $registration->addMediaFromUrl('https://picsum.photos/200/300')
                ->toMediaCollection('computer-certificate', 'local');
            $registration->addMediaFromUrl('https://picsum.photos/200/300')
                ->toMediaCollection('skpi-certificate', 'local');
            $registration->addMediaFromUrl('https://picsum.photos/200/300')
                ->toMediaCollection('parental-consent-letter', 'local');
            $registration->addMediaFromUrl('https://picsum.photos/200/300')
                ->toMediaCollection('id-card', 'local');
            $registration->addMediaFromUrl('https://picsum.photos/200/300')
                ->toMediaCollection('receipt', 'local');
        });
    }
}
