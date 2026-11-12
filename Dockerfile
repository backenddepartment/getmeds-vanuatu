# Getmeds Vanuatu — production image for Coolify (or any Docker host).
#
# PHP 8.3 + Apache, so the site's .htaccess works exactly as on LAMP.
# mbstring, curl and fileinfo are compiled into the official image already.
#
# Persistent state lives in ONE volume mounted at /var/lib/getmeds:
#   orders.log, enquiries.log, uploads/ (prescriptions), cache/
# data/ itself is NOT a volume: it also holds committed catalogue files
# (products.json, medicines.php, ...) that must come from the image.
FROM php:8.3-apache

# Apache: modules the .htaccess uses, and let .htaccess apply.
RUN a2enmod rewrite headers expires deflate remoteip \
 && perl -0pi -e 's#(<Directory /var/www/>.*?AllowOverride) None#$1 All#s' /etc/apache2/apache2.conf \
 && printf '%s\n' \
      'ServerTokens Prod' \
      'ServerSignature Off' \
      'ServerName localhost' \
      '# Real client IP from Coolify/Traefik (private Docker networks only).' \
      'RemoteIPHeader X-Forwarded-For' \
      'RemoteIPTrustedProxy 10.0.0.0/8 172.16.0.0/12 192.168.0.0/16' \
      > /etc/apache2/conf-enabled/getmeds.conf

# PHP: production defaults, room for 5 x 10 MB prescription uploads.
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
 && printf '%s\n' \
      'expose_php = Off' \
      'upload_max_filesize = 10M' \
      'post_max_size = 64M' \
      'max_file_uploads = 5' \
      'memory_limit = 256M' \
      'date.timezone = Pacific/Efate' \
      > "$PHP_INI_DIR/conf.d/getmeds.ini"

WORKDIR /var/www/html
COPY --chown=root:root . .

# Runtime files point into the volume. data/ is writable by Apache so the
# handlers' is_writable() check passes; committed files stay root-owned.
RUN rm -rf data/uploads data/cache data/orders.log data/enquiries.log data/secrets.php \
 && ln -s /var/lib/getmeds/uploads       data/uploads \
 && ln -s /var/lib/getmeds/cache         data/cache \
 && ln -s /var/lib/getmeds/orders.log    data/orders.log \
 && ln -s /var/lib/getmeds/enquiries.log data/enquiries.log \
 && chown www-data:www-data data \
 && mkdir -p /var/lib/getmeds

COPY docker/entrypoint.sh /usr/local/bin/getmeds-entrypoint
RUN chmod 755 /usr/local/bin/getmeds-entrypoint

VOLUME ["/var/lib/getmeds"]
EXPOSE 80

HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
  CMD php -r 'exit(@file_get_contents("http://127.0.0.1/") === false ? 1 : 0);'

ENTRYPOINT ["getmeds-entrypoint"]
CMD ["apache2-foreground"]
