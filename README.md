# IMRS Survey - Mehnat Bozori So'rovnomasi Tizimi

IMRS (Integrated Market Research System) Survey - korxonalarning mehnat bozori ehtiyojlarini o'rganish va tahlil qilish uchun mo'ljallangan veb-ilova. Tizim korxonalar tomonidan talab qilinadigan kasbiy ko'nikmalar va kelajakdagi mehnat resurslariga bo'lgan ehtiyojni aniqlash imkonini beradi.

## 📋 Loyiha haqida

Bu loyiha korxonalarning:
- Mavjud kasbiy kadrlar tanqisligi
- Kelajakda kerak bo'ladigan mutaxassisliklar
- Xodimlar sonidagi o'zgarishlar
- Mintaqaviy mehnat bozori talablari

haqida ma'lumot to'plash va tahlil qilish uchun ishlab chiqilgan.

## 🚀 Texnologiyalar

### Backend
- **PHP 8.1+** - Asosiy dasturlash tili
- **Laravel 10** - Web framework
- **MySQL/MariaDB** - Ma'lumotlar bazasi
- **Redis** - Keshlash va session boshqaruvi (ixtiyoriy)

### Frontend
- **Blade Templates** - Laravel shablon tizimi
- **Tailwind CSS** - CSS framework
- **JavaScript (Vanilla)** - Dinamik funksionallik
- **Alpine.js** - Reaktiv komponentlar (ixtiyoriy)

### Qo'shimcha kutubxonalar
- **Laravel Sanctum** - API autentifikatsiya
- **Laravel Socialite** - Google OAuth autentifikatsiya
- **Maatwebsite/Laravel-Excel** - Excel eksport/import
- **Spatie Laravel Permission** - Ruxsatlar boshqaruvi
- **Laravel Telescope** - Debugging va monitoring (dev muhit)
- **Predis** - Redis client

## 📦 Loyiha tuzilmasi

```
IMRS_Survey/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       ├── ImportSkillsCommand.php      # Kadrlarni import qilish
│   │       └── ClearSkillsCache.php         # Keshni tozalash
│   ├── Exports/
│   │   ├── SurveyResponsesExport.php        # So'rovnomalar eksporti
│   │   ├── SkillsStatisticsExport.php       # Kadrlar statistikasi
│   │   └── DetailedSkillsExport.php         # Batafsil statistika
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── SurveyController.php         # So'rovnoma kontrolleri
│   │   │   ├── AdminController.php          # Admin panel
│   │   │   ├── AuthController.php           # Autentifikatsiya
│   │   │   └── ApiController.php            # API endpoints
│   │   └── Middleware/
│   │       └── AdminMiddleware.php          # Admin huquqlarini tekshirish
│   ├── Models/
│   │   ├── SurveyResponse.php               # So'rovnoma javobi
│   │   ├── Skill.php                        # Kasbiy ko'nikmalar
│   │   ├── Region.php                       # Viloyatlar
│   │   ├── District.php                     # Tumanlar
│   │   ├── ActivityType.php                 # Faoliyat turlari
│   │   ├── ResponseMissingSkill.php         # Etishmayotgan kadrlar
│   │   └── ResponseFutureDemandSkill.php    # Kelajakda kerak bo'ladigan kadrlar
│   └── Services/
│       ├── SkillSearchService.php           # Kadr qidirish xizmati
│       └── StatisticsService.php            # Statistika xizmati
├── database/
│   ├── migrations/                          # Ma'lumotlar bazasi migratsiyalari
│   ├── seeders/                             # Boshlang'ich ma'lumotlar
│   └── factories/                           # Test ma'lumotlar generatori
├── resources/
│   └── views/
│       ├── survey/                          # So'rovnoma sahifalari
│       │   ├── step1.blade.php              # 1-qadam: Korxona ma'lumotlari
│       │   ├── step2.blade.php              # 2-qadam: Kadrlar tanlash
│       │   ├── step3.blade.php              # 3-qadam: Qo'shimcha ma'lumotlar
│       │   └── success.blade.php            # Muvaffaqiyatli yuborish
│       ├── admin/                           # Admin panel sahifalari
│       │   ├── dashboard.blade.php          # Asosiy dashboard
│       │   ├── responses.blade.php          # Javoblar ro'yxati
│       │   ├── response-detail.blade.php    # Javob tafsilotlari
│       │   └── skills-statistics.blade.php  # Kadrlar statistikasi
│       └── layouts/                         # Umumiy shablonlar
├── routes/
│   ├── web.php                              # Web marshrutlar
│   └── api.php                              # API marshrutlar
└── public/                                  # Ommaviy fayllar

```

## ⚙️ O'rnatish

### 1. Talablar
- PHP >= 8.1
- Composer
- MySQL/MariaDB >= 5.7
- Node.js & NPM (frontend uchun)
- Redis (ixtiyoriy, lekin tavsiya etiladi)

### 2. Loyihani klonlash

```bash
git clone https://github.com/your-username/IMRS_Survey.git
cd IMRS_Survey
```

### 3. Bog'liqliklarni o'rnatish

```bash
# PHP bog'liqliklari
composer install

# Frontend bog'liqliklari
npm install
```

### 4. Muhit sozlamalari

```bash
# .env faylini yaratish
cp .env.example .env

# Ilovaning kalitini generatsiya qilish
php artisan key:generate
```

### 5. Ma'lumotlar bazasini sozlash

`.env` faylida ma'lumotlar bazasi sozlamalarini o'zgartiring:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=imrs_survey
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 6. Migratsiyalar va seed'larni ishga tushirish

```bash
# Ma'lumotlar bazasi jadvallarini yaratish
php artisan migrate

# Boshlang'ich ma'lumotlarni yuklash
php artisan db:seed
```

### 7. Frontend build qilish

```bash
# Development uchun
npm run dev

# Production uchun
npm run build
```

### 8. Serverni ishga tushirish

```bash
php artisan serve
```

Brauzerda `http://localhost:8000` manzilini oching.

## 🔧 Konfiguratsiya

### Redis (keshlash)

Redis'dan foydalanish uchun `.env` faylida:

```env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Google OAuth (ixtiyoriy)

Google orqali autentifikatsiya uchun:

```env
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

## 📊 Loyiha funksionalligi

### So'rovnoma tizimi (3 bosqichli)

#### 1-bosqich: Korxona ma'lumotlari
- Viloyat va tuman tanlash
- Faoliyat turi
- Korxona nomi va manzili
- Xodimlar soni
- Tashkiliy-huquqiy shakl (davlat/xususiy)
- Xodimlar sonidagi o'zgarishlar (hozirgi va 6 oylik prognoz)

#### 2-bosqich: Kadrlar tanlash
- Hozirda etishmayotgan kadrlar ro'yxati
- Kelajakda kerak bo'ladigan kadrlar ro'yxati
- Qidirish va filtrlash imkoniyati
- Ko'p tanlov rejimi

#### 3-bosqich: Qo'shimcha ma'lumotlar
- Har bir kadr uchun:
  - Talab qilinadigan ta'lim darajasi
  - Kerakli ish tajribasi
  - Jins talabi (erkak/ayol/farqi yo'q)

### Admin panel

#### Dashboard (`/muxabbat/dashboard`)
- Umumiy statistika
- So'rovnomalar soni (davr bo'yicha)
- Viloyatlar bo'yicha taqsimot
- Faoliyat turlari statistikasi
- Grafiklar va diagrammalar

#### Javoblar ro'yxati (`/muxabbat/responses`)
- Barcha so'rovnomalar ro'yxati
- Filtrlar:
  - Davr (yil, chorak)
  - Viloyat
  - Tuman
  - Faoliyat turi
- Qidirish (korxona nomi bo'yicha)
- Pagination
- Batafsil ko'rish

#### Kadrlar statistikasi (`/muxabbat/skills-statistics`)
- Eng talab qilinadigan kadrlar
- Viloyatlar bo'yicha taqsimot
- Faoliyat turlari bo'yicha tahlil
- Ta'lim va tajriba talablari statistikasi
- Excel eksport

#### Eksport (`/muxabbat/export`)
- Barcha so'rovnomalar Excel formatida
- Batafsil kadrlar statistikasi
- Filtrlangan ma'lumotlarni eksport qilish

## 🗄️ Ma'lumotlar bazasi strukturasi

### Asosiy jadvallar

#### `survey_responses`
- Asosiy so'rovnoma ma'lumotlari
- Korxona ma'lumotlari
- Mintaqaviy ma'lumotlar
- Xodimlar soni va o'zgarishlar

#### `response_missing_skills`
- Hozirda etishmayotgan kadrlar
- Ta'lim, tajriba va jins talablari

#### `response_future_demand_skills`
- Kelajakda kerak bo'ladigan kadrlar
- Ta'lim, tajriba va jins talablari

#### `skills`
- Kasbiy ko'nikmalar ro'yxati
- O'zbek va rus tillaridagi nomlar
- Kategoriyalar

#### `regions` va `districts`
- Viloyatlar va tumanlar
- Ierarxik bog'lanish

#### `activity_types`
- Iqtisodiy faoliyat turlari
- OKED kodlari

## 🔐 Xavfsizlik

### CSRF himoyasi
Barcha formalar CSRF token bilan himoyalangan:

```blade
@csrf
```

### Input validatsiya
Barcha kiritilgan ma'lumotlar server tomonida tekshiriladi.

### SQL Injection himoyasi
Laravel Eloquent ORM orqali parametrlangan so'rovlar.

### XSS himoyasi
Blade shablon tizimining avtomatik escape qilish funksiyasi.

## 🚀 Optimizatsiya

### Keshlash strategiyasi
- Viloyatlar va tumanlar - 2 soat
- Kadrlar ro'yxati - 30 daqiqa
- Statistika - 15 daqiqa
- Auto-complete - 1 soat

### Database indekslar
- `survey_responses`: `region_id`, `district_id`, `activity_type_id`, `survey_period_year`, `survey_period_quarter`
- `skills`: `name_uz`, `name_ru`, `category`, `is_active`
- `response_missing_skills`: `survey_response_id`, `skill_id`

### Lazy loading oldini olish
```php
$responses = SurveyResponse::with(['region', 'district', 'activityType'])->get();
```

## 📝 Artisan buyruqlar

```bash
# Kadrlarni import qilish
php artisan skills:import path/to/skills.xlsx

# Keshni tozalash
php artisan cache:clear
php artisan skills:cache-clear

# Migratsiyalar
php artisan migrate
php artisan migrate:fresh --seed

# Optimallashtirish
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🧪 Testing

```bash
# Barcha testlarni ishga tushirish
php artisan test

# Ma'lum bir testni ishga tushirish
php artisan test --filter=SurveyTest

# Coverage bilan
php artisan test --coverage
```

## 📈 Monitoring

### Laravel Telescope
Development muhitda debugging uchun:

```bash
# Telescope o'rnatish (agar o'rnatilmagan bo'lsa)
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

Telescope'ga kirish: `http://localhost:8000/telescope`

### Log'lar
Log fayllar `storage/logs/` katalogida saqlanadi:

```bash
# Log'larni ko'rish
tail -f storage/logs/laravel.log
```

## 🌐 API

### So'rovnoma API

```http
GET /api/skills?search=dasturchi
GET /api/districts?region_id=1
```

### Admin API

```http
GET /api/admin/statistics
GET /api/admin/responses?period=2024-Q1
```

## 🤝 Hissa qo'shish

1. Fork qiling
2. Feature branch yarating (`git checkout -b feature/AmazingFeature`)
3. O'zgarishlarni commit qiling (`git commit -m 'Add some AmazingFeature'`)
4. Branch'ga push qiling (`git push origin feature/AmazingFeature`)
5. Pull Request oching

## 📄 Litsenziya

Bu loyiha MIT litsenziyasi ostida tarqatiladi.

## 👥 Muallif

**IMRS Development Team**

## 📞 Aloqa

Savollar va takliflar uchun:
- Email: support@imrs.uz
- Telegram: @imrs_support

## 🙏 Minnatdorchilik

- Laravel Framework jamoasiga
- Barcha open-source kutubxona mualliflariga
- Loyihada ishtirok etgan dasturchilar jamoasiga

---

**Eslatma:** Bu loyiha ishlab chiqish jarayonida. Yangilanishlar muntazam ravishda qo'shilmoqda.
