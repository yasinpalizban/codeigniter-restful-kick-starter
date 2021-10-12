# Codeigniter 4 Modular RestFull Api Kick Starter

To facilitate development and control over project , its use code moules ,with this approach, easily extend
application without changing any structure.

> Attention: To speed up project development , I suggest , to see front-end application with Angular ,It's  fully compatible with this project
> </br> here is link : </br>
> https://github.com/yasinpalizban/

### Instructions

- Run Migrations

```
php spark migrate  -n Modules/Auth 
php spark migrate  -n Modules/Common 
```

- Run seeders

```
php spark db:seed Modules\Auth\Database\Seeds\GroupSeeder
php spark db:seed Modules\Auth\Database\Seeds\defaultUserSeeder
php spark db:seed Modules\Auth\Database\Seeds\UsersGroupsSeeder
php spark db:seed Modules\Auth\Database\Seeds\PermissionSeeder
php spark db:seed Modules\Auth\Database\Seeds\UsersPermissionSeeder
php spark db:seed Modules\Auth\Database\Seeds\GroupsPermissionSeeder

```

- To create random user, run this seeder

```
php spark db:seed Modules\Auth\Database\Seeds\UsersSeeder
```

### Run Application

```
php spark serve

```

### Extra Information

You can sign in via Email Or Phone Or Username </br>

- Default user email admin@admin.com
- Default user phone 0918000
- Default user username admin
- Default user password pass

#### Requirement

- php 7.4, 8.0+

#### Packages

- [codeigniter](https://codeigniter.com/)
- [myth/auth](https://github.com/lonnieezell/myth-auth)
- [faker](https://fakerphp.github.io/)
- [php-jwt](https://github.com/firebase/php-jwt)
- [recaptcha](https://github.com/google/recaptcha)
- [pusher-php-server](https://github.com/pusher/pusher-http-php)
- [codeigniter4/translations](https://github.com/codeigniter4/translations)

#### Last Words

The code has been tested on live and localhost too, you don't worry about it. :-)