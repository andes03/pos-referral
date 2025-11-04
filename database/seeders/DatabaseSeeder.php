<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use App\Models\Pelanggan;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin pegawai
        $admin = Pegawai::create([
            'nama' => 'Administrator',
            'email' => 'admin@pos.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'no_telp' => '081234567890',
            'alamat' => 'Jl. Admin No. 1',
        ]);

        // Create user pegawai
        $user = Pegawai::create([
            'nama' => 'User Pegawai',
            'email' => 'user@pos.test',
            'password' => Hash::make('password'),
            'role' => 'user',
            'no_telp' => '081234567891',
            'alamat' => 'Jl. User No. 2',
        ]);

        // Create pelanggan 1 (with referral)
        $pelanggan1 = Pelanggan::create([
            'nama' => 'John Doe',
            'email' => 'john@customer.test',
            'password' => Hash::make('password'),
            'no_telp' => '081234567892',
            'alamat' => 'Jl. Customer No. 1',
            'kode_referal' => 'REFJOHN123',
        ]);

        // Create pelanggan 2 (without referral)
        $pelanggan2 = Pelanggan::create([
            'nama' => 'Jane Smith',
            'email' => 'jane@customer.test',
            'password' => Hash::make('password'),
            'no_telp' => '081234567893',
            'alamat' => 'Jl. Customer No. 2',
            'kode_referal' => 'REFJANE456',
        ]);

        // Create categories
        $minuman = Kategori::create([
            'nama_kategori' => 'Minuman',
            'deskripsi' => 'Berbagai jenis minuman segar dan hangat',
        ]);

        $makanan = Kategori::create([
            'nama_kategori' => 'Makanan',
            'deskripsi' => 'Menu makanan utama dan ringan',
        ]);

        $dessert = Kategori::create([
            'nama_kategori' => 'Dessert',
            'deskripsi' => 'Aneka hidangan penutup yang manis',
        ]);

        // Create products
        $espresso = Produk::create([
            'id_kategori' => $minuman->id_kategori,
            'nama' => 'Espresso',
            'harga' => 25000,
            'stok' => 50,
            'deskripsi' => 'Kopi espresso klasik dengan crema sempurna',
            'image' => 'produk/0rMO9SYfgqwueovbhJA6Md3OJ9yql77opLD84shR.png',
        ]);

        $latte = Produk::create([
            'id_kategori' => $minuman->id_kategori,
            'nama' => 'Latte',
            'harga' => 35000,
            'stok' => 45,
            'deskripsi' => 'Espresso dengan steamed milk yang lembut',
            'image' => 'produk/DzUyNbC3nMx8E8IcAV8Zy5nY3JOyEsgp731yWtCZ.jpg',
        ]);

        $cappuccino = Produk::create([
            'id_kategori' => $minuman->id_kategori,
            'nama' => 'Cappuccino',
            'harga' => 32000,
            'stok' => 40,
            'deskripsi' => 'Espresso dengan foam milk yang tebal',
            'image' => 'produk/YvCbcTIB2cZFr96IyUzwSUHV81hilTAvBD0EO5q4.png',
        ]);

        $croissant = Produk::create([
            'id_kategori' => $makanan->id_kategori,
            'nama' => 'Croissant',
            'harga' => 28000,
            'stok' => 30,
            'deskripsi' => 'Croissant butter yang renyah dan lembut',
            'image' => 'produk/0rMO9SYfgqwueovbhJA6Md3OJ9yql77opLD84shR.png',
        ]);

        $sandwich = Produk::create([
            'id_kategori' => $makanan->id_kategori,
            'nama' => 'Club Sandwich',
            'harga' => 45000,
            'stok' => 25,
            'deskripsi' => 'Sandwich dengan isian daging, sayuran, dan keju',
            'image' => 'produk/DzUyNbC3nMx8E8IcAV8Zy5nY3JOyEsgp731yWtCZ.jpg',
        ]);

        $tiramisu = Produk::create([
            'id_kategori' => $dessert->id_kategori,
            'nama' => 'Tiramisu',
            'harga' => 38000,
            'stok' => 20,
            'deskripsi' => 'Dessert Italia klasik dengan mascarpone dan kopi',
            'image' => 'produk/YvCbcTIB2cZFr96IyUzwSUHV81hilTAvBD0EO5q4.png',
        ]);

        // Create a successful transaction
        $transaksi = Transaksi::create([
            'id_pelanggan' => $pelanggan1->id_pelanggan,
            'id_pegawai' => $user->id_pegawai,
            'total' => 98000, // 35000 + 28000 + 35000
            'metode_pembayaran' => 'cash',
            'status_pembayaran' => 'pending',
            'tanggal_transaksi' => now(),
        ]);

        // Create transaction details
        DetailTransaksi::create([
            'id_transaksi' => $transaksi->id_transaksi,
            'id_produk' => $latte->id_produk,
            'jumlah' => 1,
            'subtotal' => 35000,
        ]);

        DetailTransaksi::create([
            'id_transaksi' => $transaksi->id_transaksi,
            'id_produk' => $croissant->id_produk,
            'jumlah' => 1,
            'subtotal' => 28000,
        ]);

        DetailTransaksi::create([
            'id_transaksi' => $transaksi->id_transaksi,
            'id_produk' => $tiramisu->id_produk,
            'jumlah' => 1,
            'subtotal' => 38000,
        ]);

        // Update total to correct amount
        $transaksi->update(['total' => 101000]);

        // Mark transaction as paid
        $transaksi->update(['status_pembayaran' => 'paid']);

        // Reduce stock for purchased items
        $latte->reduceStock(1);
        $croissant->reduceStock(1);
        $tiramisu->reduceStock(1);

        // Create additional sample products
        Produk::create([
            'id_kategori' => $minuman->id_kategori,
            'nama' => 'Americano',
            'harga' => 28000,
            'stok' => 35,
            'deskripsi' => 'Espresso dengan air panas',
            'image' => 'produk/0rMO9SYfgqwueovbhJA6Md3OJ9yql77opLD84shR.png',
        ]);

        Produk::create([
            'id_kategori' => $minuman->id_kategori,
            'nama' => 'Iced Tea',
            'harga' => 18000,
            'stok' => 50,
            'deskripsi' => 'Teh dingin segar dengan lemon',
            'image' => 'produk/DzUyNbC3nMx8E8IcAV8Zy5nY3JOyEsgp731yWtCZ.jpg',
        ]);

        Produk::create([
            'id_kategori' => $makanan->id_kategori,
            'nama' => 'Caesar Salad',
            'harga' => 42000,
            'stok' => 20,
            'deskripsi' => 'Salad segar dengan dressing caesar',
            'image' => 'produk/YvCbcTIB2cZFr96IyUzwSUHV81hilTAvBD0EO5q4.png',
        ]);

        Produk::create([
            'id_kategori' => $dessert->id_kategori,
            'nama' => 'Cheesecake',
            'harga' => 35000,
            'stok' => 15,
            'deskripsi' => 'New York style cheesecake',
            'image' => 'produk/0rMO9SYfgqwueovbhJA6Md3OJ9yql77opLD84shR.png',
        ]);
    }
}
