<?php 

namespace App\Models;

class UserModel
{
    protected array $users = [
        [
            'id'        => 1,
            'username'  => 'admin',
            'password'  => '$2y$10$vO8FkYt6qXm5gUeZkXvA2OCQxK9WjA6r0BvVzJg1Dq2xVb1eE3M8O', // admin123
            'role'      => 'admin',
            'full_name' => 'Administrator Utama',
            'email'     => 'admin@webgis-microwave.id'
        ],
        [
            'id'        => 2,
            'username'  => 'user',
            'password'  => '$2y$10$r9x5p1QZ2VwK9E6hN4Y.tuq3XqB7zM1d5Y6f7G8h9J0k1L2m3N4oP', // user123
            'role'      => 'user',
            'full_name' => 'Operator Jaringan Radio',
            'email'     => 'operator@webgis-microwave.id'
        ]
    ];

    public function where(string $field, string $value): self
    {
        $this->filtered = array_filter($this->users, function($u) use ($field, $value) {
            return ($u[$field] ?? null) === $value;
        });
        return $this;
    }

    public function first(): ?array
    {
        $res = array_values($this->filtered ?? $this->users);
        return !empty($res) ? $res[0] : null;
    }

    public function getAll(): array
    {
        return $this->users;
    }
}
