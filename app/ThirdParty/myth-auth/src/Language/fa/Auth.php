<?php

return [
    // Exceptions
    'invalidModel' => 'مدل {0} قبل از استفاده باید بارگیری شود.',
    'userNotFound' => 'یافتن کاربری با شناسه = {0 ، شماره} امکان پذیر نیست.',
    'noUserEntity' => 'برای اعتبار سنجی رمز ورود کاربر باید ارائه شود.',
    'tooManyCredentials' => 'شما ممکن است فقط در برابر 1 اعتبارنامه غیر از گذرواژه اعتبار سنجی کنید.',
    'invalidFields' => 'از قسمت "{0}" برای تأیید اعتبار اطلاعات استفاده نمی شود',
    'unsetPasswordLength' => 'شما باید تنظیمات "minimumPasswordLength" را در پرونده پیکربندی Auth تنظیم کنید.',
    'unknownError' => 'متأسفیم ، با مشکلی در ارسال ایمیل به شما روبرو شدیم. لطفاً بعداً دوباره امتحان کنید.',
    'notLoggedIn' => 'برای دسترسی به آن صفحه باید وارد سیستم شوید.',
    'notEnoughPrivilege' => 'شما برای دسترسی به آن صفحه مجوز کافی ندارید.',

    // Registration
    'registerDisabled' => 'متأسفیم ، حسابهای کاربری جدید در حال حاضر مجاز نیستند.',
    'registerSuccess' => 'خوش امدید ',
    'registerCLI' => '"کاربر جدید ایجاد شده است: {0} ، # {1}',

    // Activation
    'activationNoUser' => 'نمی توان کاربر با این لینک فعال سازی پیدا کرد.',
    'activationSubject' => 'فعال سازی حساب کاربری شما',
    'activationSuccess' => 'لطفا با کیک بروی لینک حساب کاربری را تایید نمیاید.',
    'activationResend' => 'ارسال دوباره لینک فعال سازی',
    'notActivated' => 'این حساب هنوز فعال نشده.',
    'errorSendingActivation' => 'ارسال نا موفق ایمیل فعال سازی به : {0}',

    // Login
    'badAttempt' => 'نمی توان شما راوارد کرد لطفا اطلاعات را چک نمایید.',
    'loginSuccess' => 'خوش امدید',
    'invalidPassword' => 'نمی توان وارد شد پسبورد را وارد نمیاید',

    // Forgotten Passwords
    'forgotDisabled' => 'ریست رمز  غیر فعال می باشد.',
    'forgotNoUser' => 'کار بر با این ایمیل وجود ندارد.',
    'forgotSubject' => 'دستوارت ریست رمز',
    'resetSuccess' => 'رمز شما با موفقیت عوض شد',
    'forgotEmailSent' => 'یک توکن امنیتی برای شما رسال شد لطفا برای ادامه بروی کلیک نماید',
    'errorEmailSent' => 'نمی توان دستورات ریست رمز را ارسال کرد : {0}.',
    'errorResetting' => 'نمی توان دستورارت ریست را راسال کرد {0}',

    // Passwords

    'errorPasswordLength' => 'پسبورد حداقل  {0} کارکتر باشد',
    'suggestPasswordLength' => 'بیشترین طول پسبورد 255 کارکتر است .',
    'errorPasswordCommon' => 'نمی توان از رمز عمومی استفاده کرد .',
    'suggestPasswordCommon' => 'رمز برسی شد از نظر اینکه به عنوان سر نخ برای هکر استفاده نشود.',
    'errorPasswordPersonal' => 'رمز نمی تواند شامل هش کد باشد.',
    'suggestPasswordPersonal' => 'نمی توان از ایمیل یا نام کاربری در رمز استفاده کرد ',
    'errorPasswordTooSimilar' => 'رمز شبیه نام کاربری است',
    'suggestPasswordTooSimilar' => 'از نام کاربری در رمز استفاده نشود',
    'errorPasswordPwned' => 'گذرواژه {0} به دلیل نقض داده ها در معرض دید قرار گرفته و {1 ، تعداد} بار در {2} گذرواژه های به خطر افتاده دیده شده است',
    'suggestPasswordPwned' => 'نباید به عنوان رمز استفاده شود : {0}.',
    'errorPasswordEmpty' => 'رمز مورد نیاز است.',
    'passwordChangeSuccess' => 'رمز عوض شد',
    'userDoesNotExist' => 'رمز عوض نشد، کاربر وجود ندارد',
    'resetTokenExpired' => 'زمان توکن به اتمام رسیده .',

    // Groups
    'groupNotFound' => 'نمیتواند گروه را پیدا کند: {0}',

    // Permissions
    'permissionNotFound' => 'نمی تواند  اجاره را پیدا کند: {0}',

    // Banned
    'userIsBanned' => 'کاربر بلاک شده است با ادمین تماس بگیرید',

    // Too many requests
    'tooManyRequests' => 'درخواست بیش از حد لطفن صبر کنید {0} ثانیه',

    // Login views
    'home' => 'خانه',
    'current' => 'الان',
    'forgotPassword' => 'فراموش کردن رمز?',
    'enterEmailForInstructions' => 'مشکلی نیست! ایمیل خود را وارد نماید تا ما دستوراتات ریست رمز  رابرای شما ارسال نمایید',
    'email' => 'ایمیل',
    'emailAddress' => 'ادرس ایمیل',
    'sendInstructions' => 'ارسال دستورات',
    'loginTitle' => 'ورود',
    'loginAction' => 'ورود',
    'rememberMe' => 'منو به یاد بسپار',
    'needAnAccount' => 'حساب جدید?',
    'forgotYourPassword' => 'فراموش کردن رمز?',
    'password' => 'رمز',
    'repeatPassword' => 'تکرار رمز',
    'emailOrUsername' => 'ایمیل یا پسورد',
    'username' => 'نام کاربری',
    'register' => 'ثبت نام',
    'signIn' => 'ورود',
    'alreadyRegistered' => 'قبلا ثبت نام کردید?',
    'weNeverShare' => 'ما ایمیل شما را با هیچ کس به اشتراک نمی گزاریم .',
    'resetYourPassword' => 'ریست رمز شما',
    'enterCodeEmailPassword' => 'کد و رمز ی که از طریق ایمیل ادرس تان دریافت می کنید را وارد نمایید.',
    'token' => 'توکن',
    'newPassword' => 'رمز جدید',
    'newPasswordRepeat' => 'تکرار رمز جدید',
    'resetPassword' => 'ریست رمز',
];
