<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(6),
            'description' => fake()->paragraph(),
            'type' => 'incident',
            'status' => 'open',
            'priority' => fake()->randomElement(['low', 'medium', 'high', 'critical']),
            'requester_id' => User::factory(),
        ];
    }

    public function ofType(string $type): static
    {
        return $this->state(['type' => $type]);
    }

    public function withStatus(string $status): static
    {
        return $this->state(['status' => $status]);
    }
}
