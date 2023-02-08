serve:
	php -S localhost:8008 -t public

# Generate RSA keys for JWT
generate-rsa-keys:
	mkdir -p var/rsa
	openssl genrsa -out var/rsa/private.pem 2048
	openssl rsa -in var/rsa/private.pem -pubout -out var/rsa/public.pem
