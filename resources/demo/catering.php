<?php

return [
    'customerId' => 2,
    'users' => [
        1 => ['id' => 1, 'name' => 'Dewi Anggraini', 'role' => 'admin'],
        2 => ['id' => 2, 'name' => 'Sarah Paramitha', 'role' => 'customer'],
        3 => ['id' => 3, 'name' => 'Rafi Pratama', 'role' => 'customer'],
        4 => ['id' => 4, 'name' => 'Nadia Putri', 'role' => 'customer'],
    ],
    'menus' => [
        1 => ['id' => 1, 'name' => 'Nasi Ayam Sambal Matah', 'description' => 'Ayam berbumbu dengan nasi hangat dan sambal matah segar.', 'price' => 25000, 'is_active' => true],
        2 => ['id' => 2, 'name' => 'Nasi Ayam Bakar', 'description' => 'Ayam bakar berbumbu dengan nasi putih, sambal, dan lalapan.', 'price' => 28000, 'is_active' => true],
        3 => ['id' => 3, 'name' => 'Nasi Rendang', 'description' => 'Rendang daging sapi dengan nasi putih dan pilihan sayur.', 'price' => 32000, 'is_active' => true],
        4 => ['id' => 4, 'name' => 'Nasi Sayur Harian', 'description' => null, 'price' => 22000, 'is_active' => false],
    ],
    'orders' => [
        ['id' => 12, 'user_id' => 2, 'menu_id' => 1, 'quantity' => 5, 'unit_price' => 25000, 'status' => 'pending', 'created_at' => '2026-11-26'],
        ['id' => 11, 'user_id' => 3, 'menu_id' => 2, 'quantity' => 8, 'unit_price' => 28000, 'status' => 'confirmed', 'created_at' => '2026-11-26'],
        ['id' => 10, 'user_id' => 4, 'menu_id' => 3, 'quantity' => 6, 'unit_price' => 32000, 'status' => 'pending', 'created_at' => '2026-11-25'],
        ['id' => 9, 'user_id' => 2, 'menu_id' => 2, 'quantity' => 4, 'unit_price' => 26000, 'status' => 'confirmed', 'created_at' => '2026-11-25'],
        ['id' => 8, 'user_id' => 3, 'menu_id' => 1, 'quantity' => 10, 'unit_price' => 25000, 'status' => 'completed', 'created_at' => '2026-11-25'],
        ['id' => 7, 'user_id' => 4, 'menu_id' => 3, 'quantity' => 6, 'unit_price' => 32000, 'status' => 'completed', 'created_at' => '2026-11-24'],
        ['id' => 6, 'user_id' => 2, 'menu_id' => 1, 'quantity' => 5, 'unit_price' => 25000, 'status' => 'completed', 'created_at' => '2026-11-24'],
        ['id' => 5, 'user_id' => 3, 'menu_id' => 2, 'quantity' => 9, 'unit_price' => 26000, 'status' => 'completed', 'created_at' => '2026-11-23'],
        ['id' => 4, 'user_id' => 4, 'menu_id' => 1, 'quantity' => 5, 'unit_price' => 25000, 'status' => 'completed', 'created_at' => '2026-11-23'],
        ['id' => 3, 'user_id' => 2, 'menu_id' => 3, 'quantity' => 4, 'unit_price' => 32000, 'status' => 'completed', 'created_at' => '2026-11-23'],
        ['id' => 2, 'user_id' => 3, 'menu_id' => 1, 'quantity' => 7, 'unit_price' => 25000, 'status' => 'completed', 'created_at' => '2026-11-21'],
        ['id' => 1, 'user_id' => 4, 'menu_id' => 2, 'quantity' => 5, 'unit_price' => 26000, 'status' => 'completed', 'created_at' => '2026-11-20'],
    ],
    'photos' => [
        1 => 'demo/ayam-matah.webp',
        2 => 'demo/ayam-bakar.webp',
        3 => 'demo/rendang.webp',
    ],
];
