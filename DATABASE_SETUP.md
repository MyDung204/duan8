# Hướng dẫn cấu hình MySQL cho dự án

## Yêu cầu
- MySQL 5.7+ hoặc MariaDB 10.2+
- Database: `duan8`

## Cấu hình trong file .env

Mở file `.env` và cập nhật các thông tin sau:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=duan8
DB_USERNAME=root
DB_PASSWORD=
```

**Lưu ý:** Điều chỉnh `DB_PASSWORD` nếu MySQL của bạn có mật khẩu.

## Tạo database

1. Mở MySQL CLI hoặc phpMyAdmin
2. Tạo database:
```sql
CREATE DATABASE duan8 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

## Chạy migrations

```bash
php artisan migrate
```

## Kiểm tra kết nối

```bash
php artisan migrate:status
```

Nếu thấy danh sách migrations, nghĩa là đã kết nối thành công với MySQL.




