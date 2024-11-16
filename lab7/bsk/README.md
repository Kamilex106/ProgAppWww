# BSK example site 



## How to run it:
1. install Apache server with MySQL (or use a package like XAMPP)
2. clone repo to `/var/www/html/` (on Linux) or `C:/xampp/htdocs/` (on Windows)
3. add `.env` file inside this project in: `bsk-site/utils/.env` with these fields:
    ```
    DB_HOST=XXX.XXX.XXX.XXX
    DB_USER=your_usrname
    DB_PASS=your_passwd
    DB_NAME=products
    CAPTCHA_KEY=public_key
    CAPTCHA_SECRET=secret_key
    ```