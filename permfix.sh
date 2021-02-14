echo ""

# Permissions
CURRENTUSER=`ps aux | egrep '(apache|httpd|MAMP)' | grep -v ^root | cut -s -d ' ' -f1 | uniq`
CURRENTGROUP=`groups $CURRENTUSER | cut -s -d ' ' -f1`
echo "[*] Current user: $CURRENTUSER"
echo "[*] Current group: $CURRENTGROUP"
echo "[*] Fixing group to files and directories (add to $CURRENTGROUP group possible external daemons)"
sudo chown -R $CURRENTUSER:$CURRENTGROUP .

echo "[*] Setting directories to 750 (RWX for the owner, RX for group, no perms for others)"
find . -type d -exec chmod 750 {} +

echo "[*] Setting files to 644 (RW for the owner, R for group, R for others)"
find . -type f -exec chmod 644 {} +


echo "[*] Setting public directories to 770 (RWX for the owner, RWX for group and no perms for others)"
sudo chmod -R 770 storage
sudo chmod -R 770 vendor
sudo chmod -R 770 public/public

# Cache
echo "[*] Routes, config and views cache..."
php artisan clear-compiled
php artisan route:cache
php artisan config:cache
php artisan view:clear

# Composer
echo "[*] Composer tuning..."
composer clear-cache
composer dump-autoload -o

# OSX files erasing
echo "[*] OSX files erasing..."
rm -rf `find . -name '.DS_Store'`
rm -rf `find . -name '._*'`

echo ""


