# TODO: Implement Referral Code Discount Feature

## Tasks
- [x] Edit resources/views/pegawai/transaksi/create.blade.php to add kode_referal input field after customer selection
- [x] Update JavaScript in create.blade.php to add logic for checking referral code match and auto-applying 10% discount
- [x] Edit app/Http/Controllers/TransaksiController.php store method to handle kode_referal validation and ensure discount is applied correctly
- [x] Test the functionality to ensure discount is applied only when referral code matches
