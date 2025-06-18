# Nama file: Makefile

DC=docker compose -f compose.dev.yaml
APP=docker compose -f compose.dev.yaml exec app

up:
	@echo "🚀 Menyalakan container Laravel dan service..."
	$(DC) up -d
	@echo "⏳ Menunggu container civika-app aktif..."
	@sleep 5
	$(APP) php artisan serve --host=0.0.0.0 --port=8000
	npm install
	npm run dev

stop:
	@echo "🛑 Menghentikan semua container..."
	$(DC) stop

restart: stop up

install:
	@echo "📦 Menjalankan composer install..."
	$(APP) composer install --no-interaction --optimize-autoloader

key:
	@echo "🔐 Menggenerate app key..."
	$(APP) php artisan key:generate

migrate:
	@echo "🛠️ Menjalankan migrasi database..."
	$(APP) php artisan migrate

seed:
	@echo "🌱 Menjalankan seeder..."
	$(APP) php artisan db:seed

logs:
	@echo "📜 Menampilkan log container app..."
	$(DC) logs -f app

perm:
	@echo "🔧 Menyetel permission..."
	$(APP) sh -c "chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache"

# Shortcut: setup awal
setup: up install key migrate perm
